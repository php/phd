<?php

/*  $Id$
    +-------------------------------------------------------------------------+
    | Copyright(c) 2007                                                       |
    | Authors:                                                                |
    |    Gwynne Raskind <gwynne@php.net>                                      |
    |    Hannes Magnusson <bjori@php.net>                                     |
    | This source file is subject to the license that is bundled with this    |
    | package in the file LICENSE, and is available through the               |
    | world-wide-web at the following url:                                    |
    | http://phd.php.net/LICENSE                                              |
    +-------------------------------------------------------------------------+
    | The base class for reading the giant XML blob. This is intended for     |
    | extension by output formats, and then for further extension by output   |
    | themes. This class should not be instantiated directly.                 |
    +-------------------------------------------------------------------------+
*/

// All XML namespaces used by PhD must be defined as constants here. NEVER hardcode namespace URLs.
define( 'XMLNS_XML',    'http://www.w3.org/XML/1998/namespace' );
define( 'XMLNS_XLINK',  'http://www.w3.org/1999/xlink' );
define( 'XMLNS_PHD',    'http://phd.php.net/namespace' );

// Special flag for non-iterative attributes
define( 'PHD_WANT_INLINE_ATTRIBUTES', 99 );

abstract class PhDReader extends XMLReader {

    // ***
    // Properties
    
    // What END_ELEMENT node name, namespace, and depth to stop at, NULL if none.
    protected $lastElementName = NULL;
    protected $lastElementNS = NULL;
    protected $lastElementDepth = NULL;
    
    // The current list of attributes for inline attribute access.
    protected $attrList = NULL;
    
    // The current list of name->value replacements from <define/> elements.
    protected $replacementList = array();
    
    // The current input stream. This isn't so unlike the flex idea of multiple input streams.
    //  We use this to implement includes without doing all kinds of crazy things.
    protected $inputSource = NULL;

	protected $map = array();

	public function __construct( $file, $encoding = "utf-8", $options = NULL ) {

		if ( !parent::open( $file, $encoding, $options ) ) {
			throw new Exception();
		}
		$this->inputSource = $this;

	}
    
    public function __destruct() {
    }
    
    // ***
    // Format subclasses must implement these to make them real formats.
    //  THIS IS THE OFFICIAL OUTPUT FORMAT INTERFACE.

    //  proto string getFormatName( void )
    //      Return the name of the format.
    abstract public function getFormatName();

    //  proto array getNodeTypeList( void )
    //      Return an array of node types that the format cares about
    //      processing. Nodes with types not in this array will be SKIPPED. In
    //      the special case of a NULL value being returned, PhD will pass all
    //      nodes through to the output file unmodified; the identity
    //      transformation format would do this. A format that returns an empty
    //      array will result in an empty output document!
    abstract protected function getNodeTypeList();
    
    //  proto string transformNode( string name, int type )
    //      Transform a given node, returning the binary string output. Binary
    //      strings ARE handled safely. This function will be called for all
    //      types of nodes returned by getNodeTypeList(). In the special case
    //      of a NULL (not empty!) value being returned, parsing will halt
    //      immediately WITHOUT an error. Use the usual means to trigger a
    //      processing error. It is always valid for this method to make the
    //      parser move around in the file.
    abstract protected function transformNode( $name, $type );
    
    //  proto bool isChunkBoundary( void )
    //      Return TRUE if it's time to chunk the output, FALSE otherwise.
    //      Always return FALSE to avoid chunking. A chunk boundary always
    //      includes the current node. This will often be modified by themes.
    abstract protected function isChunkBoundary();
    
    // ***
    // Public methods
    
    // Seek to an ID. This is used to start the parser somewhere that isn't at
    //  the beginning (duh). Be careful; this does not cause the parser to halt
    //  at the closing element of a successful seek. Use setRoot() for that.
    //  Don't forget to check the return value.
	public function seek( $id ) {

		while ( $this->inputSource->read() ) {
			if ( $this->inputSource->nodeType == XMLReader::ELEMENT && $this->inputSource->hasAttributes &&
			        $id == $this->inputSource->getAttributeNs( "id", XMLNS_XML ) ) {
				return TRUE;
			}
		}
		return FALSE;

	}
	
	// Seek to an ID AND set it as the root element for the parser. The node
	//  must be an element for this to succeed.
	public function setRoot( $id ) {
	    
	    if ( $this->inputSource->seek( $id ) ) {
            if ( $this->inputSource->nodeType != XMLReader::ELEMENT ) {
                return FALSE;
            }
            $this->lastElementName = $this->inputSource->name;
            $this->lastElementNS = $this->inputSource->namespaceURI;
            $this->lastElementDepth = $this->inputSource->depth;
            return TRUE;
        }
        return FALSE;

    }
    
    // Run a transformation starting from the current node until the next chunk
    //  boundary. This is recommended for small to medium-sized chunks. If the
    //  chunk buffer grows beyond a reasonable limit (adjusted in config.php),
    //  the parser will start spooling to disk.
    public function transformChunk() {
        global $OPTIONS;
        
        if ( ( $chunkData = fopen( "php://temp/maxmemory:{$OPTIONS[ 'chunking_memory_limit' ]}", 'r+' ) ) === FALSE ) {
            PhD_Error( "Couldn't create the chunk spooling stream. Why?" );
        }
        $nodeTypeList = $this->getNodeTypeList();
        $attributeMode = ( in_array( PHD_WANT_INLINE_ATTRIBUTES, $nodeTypeList ) ? 1 :
            in_array( XMLReader::ATTRIBUTE, $nodeTypeList ) ? 2 : 0 );
        
        do {
            $type = $this->inputSource->nodeType;
            
            // First, handle elements in the PhD namespace specially.
            if ( $type == XMLReader::ELEMENT && $this->inputSource->namespaceURI == XMLNS_PHD ) {
                if ( $this->handlePhDElement() ) {  // this will probably recurse!
                    continue;
                }
            }
            
            // Next, check for a chunking boundary.
            if ( $this->isChunkingBoundary() ) {
                break;
            }
            
            // If the format wanted inline attributes and this is an element node, gobble up the attrs for the format to use.
            if ( $attributeMode == 1 && $type == XMLReader::ELEMENT && $this->inputSource->hasAttributes ) {
                $this->gobbleAttributes();
            }
            
            // Next, pass the node to the format, if it wants it.
            if ( in_array( $type, $nodeTypeList ) ) {
                fwrite( $chunkData, $this->transformNode( $this->inputSource->name, $type ) );
            }
            
            // Next, if this is an element node AND the format wants attribute nodes iteratively, iterate them as a subset
            if ( $type == XMLReader::ELEMENT && $this->inputSource->hasAttributes && $attributeMode == 2 ) {
                fwrite( $chunkData, $this->iterateAttributes() );
            }
            
            // Next, clear out the last set of attributes if any.
            $this->attrList = NULL;
            
            // And finally, advance to the next node.
        } while ( $this->inputSource->read() );
        
        rewind( $chunkData );
        $finalData = stream_get_contents( $chunkData );
        fclose( $chunkData );
        return $finalData;

    }
    
    // This is probably the most problematic issue in all the engine:
    //  How to handle all our custom elements gracefully with a pull model
    protected function handlePhDElement() {
        
        switch ( $this->name ) {
            case 'include': {
                $this->includeFile( strlen( $t = $this->inputSource->getAttribute( 'target' ) ) ? $t :
                    $this->inputSource->getAttributeNs( 'href', XMLNS_XLINK ) );
                return TRUE;
            }
            case 'define': {
                $this->replacementList[ $this->inputSource->getAttribute( 'name' ) ] = $this->inputSource->readInnerXML();
                return TRUE;
            }
            case 'constant': {
                $this->substituteConstant( 
    
    /* Go to the next useful node in the file. */
	public function nextNode() {

		while( $this->read() ) {
			switch( $this->nodeType ) {

    			case XMLReader::ELEMENT:
    				if ( $this->isEmptyElement ) {
    				    continue;
    				}

    			case XMLReader::TEXT:
    			case XMLReader::CDATA:
    			case XMLReader::END_ELEMENT:
    				return TRUE;
			}
		}
		return FALSE;

	}
    
    /* Read a node with the right name? */
	public function readNode( $nodeName ) {

		return $this->read() && !( $this->nodeType == XMLReader::END_ELEMENT && $this->name == $nodeName );

	}

    /* Get the content of a named node, or the current node. */
	public function readContent( $node = NULL ) {

		$retval = "";
		if ( !$node ) {
			$node = $this->name;
		}
		if ( $this->readNode( $node ) ) {
			$retval = $this->value;
			$this->read(); // Jump over END_ELEMENT too
		}
		return $retval;

	}
    
    /* Get the attribute value by name, if exists. */
	public function readAttribute( $attr ) {

		return $this->moveToAttribute( $attr ) ? $this->value : "";

	}

    /* Handle unmapped nodes. */
	public function __call( $func, $args ) {

		if ( $this->nodeType == XMLReader::END_ELEMENT ) {
		    /* ignore */ return;
		}
		trigger_error( "No mapper for $func", E_USER_WARNING );

		/* NOTE:
		 *  The _content_ of the element will get processed even though we dont 
		 *  know how to handle the elment itself
		*/
		return "";

	}

    /* Perform a transformation. */
	public function transform() {

		$type = $this->nodeType;
		$name = $this->name;

		switch( $type ) {

    		case XMLReader::ELEMENT:
    		case XMLReader::END_ELEMENT:
    			if( isset( $this->map[ $name ] ) ) {
    				return $this->transformFromMap( $type == XMLReader::ELEMENT, $name );
    			}
    			return call_user_func( array( $this, "format_{$name}" ), $type == XMLReader::ELEMENT );
    			break;

    		case XMLReader::TEXT:
    			return $this->value;
    			break;

    		case XMLReader::CDATA:
    			return $this->highlight_php_code( $this->value );
    			break;

    		case XMLReader::COMMENT:
    		case XMLReader::WHITESPACE:
    		case XMLReader::SIGNIFICANT_WHITESPACE:
    			/* swallow it */
    			/* XXX This could lead to a recursion overflow if a lot of comment nodes get strung together. */
    			$this->read();
    			return $this->transform();

    		default:
    			trigger_error( "Dunno what to do with {$this->name} {$this->nodeType}", E_USER_ERROR );
    			return "";
		}

    }

}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
