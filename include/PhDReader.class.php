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

abstract class PhDReader extends XMLReader {

	protected $map = array();

	public function __construct( $file, $encoding = "utf-8", $options = NULL ) {

		if ( !parent::open( $file, $encoding, $options ) ) {
			throw new Exception();
		}

	}
    
    public function __destruct() {
    }
    
    /* Format subclasses must implement these to make them real formats. */
    abstract public function getFormatName();
    abstract protected function transformFromMap( $open, $name );
    
    /* These are new functions, extending XMLReader. */
    
    /* Seek to an ID within the file. */
	public function seek( $id ) {

		while( parent::read() ) {
			if ( $this->nodeType == XMLREADER::ELEMENT && $this->hasAttributes &&
			        $this->moveToAttributeNs( "id", "http://www.w3.org/XML/1998/namespace" ) && $this->value == $id ) {
				return $this->moveToElement();
			}
		}
		return FALSE;

	}
    
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
    			return call_user_func( array( &$this, "format_${name}" ), $type == XMLReader::ELEMENT );
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
