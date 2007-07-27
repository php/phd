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
    | Provides a simple HTML and CLI interface for configuration of the       |
    | per-site PhD system options. Must be ubiquitous with respect to the     |
    | options provided by each interface. Selects interface automatically     |
    | based on the SAPI in use. Chosen options are saved in config.php, an    |
    | automagically generated file. An existing config.php is presumed to     |
    | offer defaults for setup. No user authentication is done by this        |
    | script; do not make it accessible in your production tree.              |
    +-------------------------------------------------------------------------+
*/

// Used for versioning.
$REVISION = '$Id$';

// Chosen interface for the setup. Determined from SAPI name at main()-time.
$chosenInterface = NULL;

// Configurator instance.
$configurator = NULL;

/*---------------------------------------------------------------------------*/
require_once 'setup.messages.php';
require_once 'Option_Metadata.inc.php';
/*---------------------------------------------------------------------------*/
require_once 'Configurator.class.php';
/*---------------------------------------------------------------------------*/
require_once 'SAPI.interface.php';
/*---------------------------------------------------------------------------*/
require_once 'CLI.class.php';
require_once 'HTTP.class.php';
/*---------------------------------------------------------------------------*/

$configurator = new PhD_Configurator;

switch ( php_sapi_name() ) {

    case 'cli':
        $chosenInterface = new PhD_CLI_Interface;
        break;
    
    case 'embed':
        PhD_Error( PhD_Errors::EMBED_UNSUPPORTED );
        break;

    case 'cgi': // Assume all others are HTTP for now
    default:
        $chosenInterface = new PhD_HTTP_Interface;
        break;
    
}

function PhD_Error( $message ) {
    
    $GLOBALS[ 'chosenInterface' ]->errorMessage( $message );

}

function PhD_Warning( $message ) {
    
    $GLOBALS[ 'chosenInterface' ]->warningMessage( $message );
    
}

// Fill in defaults.
$OPTIONS = array();
foreach ( $OPTIONS_DATA as $name => $data ) {
    if ( strncmp( $name, '__', 2 ) == 0 ) {
        continue;
    }
    $OPTIONS[ $name ] = $data[ 'default_value' ];
}

// Pull in any existing config.php.
$configurator->readConfig();

// Let the interface determine how to run. TRUE if user chose values, FALSE if user wanted to break out
if ( $chosenInterface->run() === TRUE ) {
    
    // We have a set of values. We trust the interface to have checked the validity.
    $configurator->writeConfig();
    $chosenInterface->reportSuccess();

} else {
    PhD_Warning( PhD_Warnings::UNSAVED_CHANGES );
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
