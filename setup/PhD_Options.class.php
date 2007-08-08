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
    | A facility for easily managing option metadata.                         |
    +-------------------------------------------------------------------------+
*/

class Phd_Options {
    
    const TYPE_ARBITRARY = 0;       // Any user-entered value
    const TYPE_LIST = 1;            // One of a list of enumerated values
    const TYPE_NUMERIC = 2;         // Any integer
    const TYPE_FILESIZE = 3;        // A positive integer including the [KMGTP[B]] suffix
    const TYPE_FLAG = 4;            // Boolean flag
    
    public static function getType( $optName ) {
        global $OPTIONS_DATA;
        
        if ( empty( $OPTIONS_DATA[ $optName ] ) ) {
            return NULL;
        }
        return $OPTIONS_DATA[ $optName ][ 'type' ];
    }
    
    public static function getValueList( $optName ) {   // TYPE_LIST options only
        global $OPTIONS_DATA;
        
        if ( PhD_Options::getType( $optName ) != PhD_Options::TYPE_LIST ) {
            return NULL;
        }
        return call_user_func( $OPTIONS_DATA[ $optName ][ 'value_list_func' ] );
    }
    
    public static function checkValidity( $optName, $value ) {
        global $OPTIONS_DATA;
        
        if ( ( $type = PhD_Options::getType( $optName ) ) === NULL ) {
            return NULL;
        }

        switch ( $type ) {
            case PhD_Options::TYPE_ARBITRARY:
                return call_user_func( $OPTIONS_DATA[ $optName ][ 'validity_func' ], $value );

            case PhD_Options::TYPE_LIST:
                return in_array( $value, PhD_Options::getValueList( $optName ) );

            case PhD_Options::TYPE_NUMERIC:
                if ( ctype_digit( $value ) ) {
                    return ( $value >= $OPTIONS_DATA[ $optName ][ 'min_value' ] &&
                             $value <= $OPTIONS_DATA[ $optName ][ 'max_value' ] );
                }
                return FALSE;

            case PhD_Options::TYPE_FILESIZE:
                return preg_match( '/^(\d+)(?:([KMGTP])B?)?$/iu', $value ) ? TRUE : FALSE;

            case PhD_Options::TYPE_FLAG:
                return in_array( substr( strtolower( $value ), 0, 1 ), array( 1, 0, 'y', 'n' ) ) ||
                    $value === TRUE || $value === FALSE;

            default:
                return NULL;
        }
    }
    
    public static function getFinalValue( $optName, $value ) {
        global $OPTIONS_DATA;
        
        if ( ( $type = PhD_Options::getType( $optName ) ) === NULL ) {
            return NULL;
        }

        switch ( $type ) {
            case PhD_Options::TYPE_ARBITRARY:
                return isset( $OPTIONS_DATA[ $optName ][ 'final_value_func' ] ) ?
                    call_user_func( $OPTIONS_DATA[ $optName ][ 'final_value_func' ], $value ) : $value;

            case PhD_Options::TYPE_LIST:

            case PhD_Options::TYPE_NUMERIC:
                return $value;

            case PhD_Options::TYPE_FILESIZE:
                preg_match( '/^(\d+)(?:([KMGTP])B?)?$/iu', $value, $matches );
                $multipliers = array(
                    '' => 1,
                    'K' => 1024,
                    'M' => 1048576,
                    'G' => 1073741824,
                    'T' => 1099511627776,
                    'P' => 1125899906842620
                );
                return ( intval( $matches[ 1 ] ) * $multipliers[ strval( $matches[ 2 ] ) ] );

            case PhD_Options::TYPE_FLAG:
                return is_bool( $value ) ? $value : ( substr( strtolower( $value ), 0, 1 ) == 'y' ? TRUE : FALSE );

            default:
                return NULL;
        }
    }
    
}    

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
