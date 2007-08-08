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
    | The class for formatting PhD setup messages (use this more globally?).  |
    +-------------------------------------------------------------------------+
*/

class PhD_Output {
    
    protected static $argList = NULL;
    protected static function paramStringCallback( $v ) {
        $v = next( self::$argList );
        return ( is_bool( $v ) ? ( $v ? "Yes" : "No" ) : $v );
    }
    public static function paramString( $whichClass, $constName /*, ... */ ) {
        self::$argList = func_get_args(); next( self::$argList );
        $str = constant( "PhD_{$whichClass}s::{$constName}" );
        return count( self::$argList ) > 2 ?
            preg_replace_callback( '/%%%/', array( __CLASS__, 'paramStringCallback' ), $str ) :
            $str;
    }

}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
