<?php

/*  +-------------------------------------------------------------------------+
    | $Id$                                                                    |
    +-------------------------------------------------------------------------+
    | Copyright(c) 2007                                                       |
    | Authors:                                                                |
    |    Gwynne Raskind <gwynne@php.net>                                      |
    | This source file is subject to the license that is bundled with this    |
    | package in the file LICENSE, and is available through the               |
    | world-wide-web at the following url:                                    |
    | http://phd.php.net/LICENSE                                              |
    +-------------------------------------------------------------------------+
    | Provides a flexible interface for handling simple template files.       |
    +-------------------------------------------------------------------------+
*/

class Template_File {
    
    const DIR_UNREADABLE = 1;
    const DIR_UNWRITEABLE = 2;
    const TEMPLATE_MISSING = 3;
    const TEMPLATE_UNREADABLE = 4;
    const FILE_IN_THE_WAY = 5;
    const FILE_UNWRITEABLE = 6;
    const NO_OUTPUT_PATH = 7;
    
    protected $_TF_templatePath = NULL;
    protected $_TF_outputPath = NULL;
    protected $_TF_substitutions = array();
    
    public $_TF_allowOverwrite = FALSE;

    public function __construct( $templatePath, $outputPath = NULL, $allowOverwrite = FALSE ) {
        
        if ( !is_null( $outputPath ) ) {
            if ( !$allowOverwrite && file_exists( $outputPath ) ) {
                throw new Exception( '', Template_File::FILE_IN_THE_WAY );
            }
            if ( !file_exists( dirname( $outputPath ) ) || !is_dir( dirname( $outputPath ) ) ) {
                throw new Exception( '', Template_File::DIR_UNREADABLE );
            }
            if ( !is_writeable( dirname( $outputPath ) ) ) {
                throw new Exception( '', Template_File::DIR_UNWRITEABLE );
            }
        }
        
        $this->_TF_templatePath = $templatePath;
        $this->_TF_outputPath = $outputPath;
        $this->_TF_allowOverwrite = $allowOverwrite;
        
    }
    
    public function __destruct() {
    }
    
    public function subst( $name, $value ) {

        $this->_TF_substitutions[ $name ] = $value;

    }
    
    public function unsubst( $name ) {
        
        unset( $this->_TF_substitutions[ $name ] );
    
    }
    
    public function __get( $name ) {

        if ( $name == 'allowOverwrite' ) {
            return $this->_TF_allowOverwrite;
        } else {
            return $this->_TF_substitutions[ $name ];
        }

    }
    
    public function __set( $name, $value ) {
        
        if ( $name == 'allowOverwrite' ) {
            $this->_TF_allowOverwrite = $value;
        } else {
            $this->subst( $name, $value );
        }

    }
    
    public function __unset( $name ) {
        
        $this->unsubst( $name );
    
    }
    
    public function writeTemplate( $outputPath = NULL, $allowOverwrite = NULL ) {
        
        if ( is_null( $outputPath ) && is_null( $this->_TF_outputPath ) ) {
            throw new Exception( '', Template_File::NO_OUTPUT_PATH );
        } else {
            $this->_TF_outputPath = is_null( $outputPath ) ? $this->_TF_outputPath : $outputPath;
        }
        
        if ( !is_null( $allowOverwrite ) ) {
            $this->_TF_allowOverwrite = $allowOverwrite;
        }
        
        $template = $this->readTemplate( $this->_TF_templatePath );
        $template = $this->doSubstitutions( $this->_TF_substitutions, $template );
        $this->writeTemplateInternal( $this->_TF_outputPath, $template, $this->_TF_allowOverwrite );
        
    }
    
    protected function readTemplate( $templatePath ) {
        
        if ( !file_exists( $templatePath ) ) {
            throw new Exception( '', Template_File::TEMPLATE_MISSING );
        }
        if ( !is_readable( $templatePath ) ) {
            throw new Exception( '', Template_File::TEMPLATE_UNREADABLE );
        }
        
        return file_get_contents( $templatePath );
    
    }
    
    protected function doSubstitutions( $substitutions, $template ) {
        
        return str_replace(
            array_map( create_function( '$v', 'return "@${v}@";' ), array_keys( $substitutions ) ),
            array_values( $substitutions ),
            $template );

    }
    
    protected function writeTemplateInternal( $outputPath, $template, $allowOverwrite ) {
        
        if ( file_exists( $outputPath ) ) {
            if ( !$allowOverwrite || @unlink( $outputPath ) === FALSE ) {
                throw new Exception( '', Template_File::FILE_IN_THE_WAY );
            }
        }
        
        if ( @file_put_contents( $outputPath, $template ) === FALSE ) {
            throw new Exception( '', Template_File::FILE_UNWRITEABLE );
        }
    
    }

}

?>
