<?php

/*  $Id$
    +-------------------------------------------------------------------------+
    | Copyright(c) 2007                                                       |
    | Authors:                                                                |
    |    Gwynne Raskind <gwynne@php.net>                                      |
    | This source file is subject to the license that is bundled with this    |
    | package in the file LICENSE, and is available through the               |
    | world-wide-web at the following url:                                    |
    | http://phd.php.net/LICENSE                                              |
    +-------------------------------------------------------------------------+
    | Provides the common code for managing the config.php file in setup.php. |
    +-------------------------------------------------------------------------+
*/

require_once 'Template_file.class.php';

class PhD_Configurator {
    
    protected $config_php_path = '';
    
    public function __construct() {
        
        $this->config_php_path = dirname( __FILE__ ) . '/../config.php';
        
    }
    
    public function __destruct() {
    }
    
    public function __get( $name ) {
        
        return $GLOBALS[ 'OPTIONS' ][ $name ];
    
    }
    
    public function __isset( $name ) {
        
        return isset( $GLOBALS[ 'OPTIONS' ][ $name ] );
    
    }
    
    public function __set( $name, $value ) {
        
        $GLOBALS[ 'OPTIONS' ][ $name ] = $value;
        
    }

    public function __unset( $name ) {
        
        unset( $GLOBALS[ 'OPTIONS' ][ $name ] );
        
    }
    
    public function readConfig() {
        
        if ( file_exists( $this->config_php_path ) && is_readable( $this->config_php_path ) && is_file( $this->config_php_path ) ) {
            require $this->config_php_path;
            $GLOBALS[ 'OPTIONS' ] = $OPTIONS;
        } else if ( file_exists( $this->config_php_path ) ) {
            PhD_Error( PhD_Errors::CONFIG_UNREADABLE );
        }
        
    }
    
    public function isValidOptionValue( $name, $value ) {
        
        $vf = $GLOBALS[ 'OPTIONS_DATA' ][ $name ][ 'validity_check_function' ];
        return is_callable( $vf ) ? $vf( $value ) : FALSE;
    
    }
    
    public function writeConfig() {
        
        try {
            $file = new Template_File( dirname( __FILE__ ) . '/config.in.php', $this->config_php_path, TRUE );
            $file->SETUP_REV = $GLOBALS[ 'REVISION' ];
            $file->SETUP_DATE = date( DATE_RFC2822 );
            $file->SAPI = $GLOBALS[ 'chosenInterface' ]->getName();
            $file->OPTION_ARRAY = var_export( $GLOBALS[ 'OPTIONS' ], 1 );
            $file->writeTemplate();
            unset( $file );
        } catch ( Exception $e ) {
            $codeMap = array(
                Template_File::DIR_UNREADABLE => PhD_Errors::TEMPLATE_UNREADABLE,
                Template_File::DIR_UNWRITEABLE => PhD_Errors::CONFIG_DIR_INACCESSIBLE,
                Template_File::TEMPLATE_MISSING => PhD_Errors::TEMPLATE_MISSING,
                Template_File::TEMPLATE_UNREADABLE => PhD_Errors::TEMPLATE_UNREADABLE,
                Template_File::FILE_IN_THE_WAY => PhD_Errors::CONFIG_IN_THE_WAY,
                Template_File::FILE_UNWRITEABLE => PhD_Errors::CONFIG_UNWRITEABLE,
                Template_File::NO_OUTPUT_PATH => PhD_Errors::INTERNAL_ERROR,
            );
            PhD_Error( isset( $codeMap[ $e->getCode() ] ) ? $codeMap[ $e->getCode() ] : PhD_Errors::INTERNAL_ERROR );
        }

    }

}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
