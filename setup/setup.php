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
    | Provides a simple CLI interface for configuration of the per-site PhD   |
    | system options. Chosen options are saved in config.php, an              |
    | automagically generated file. An existing config.php is presumed to     |
    | offer defaults for setup. No user authentication is done by this        |
    | script; do not make it accessible to non-administrators.                |
    +-------------------------------------------------------------------------+
*/

$SETUP_REVISION = '$Id$';

/*---------------------------------------------------------------------------*/
require_once 'setup.messages.php';
require_once 'Option_Metadata.inc.php';
require_once 'Configurator.class.php';
require_once 'CLI.class.php';

/*---------------------------------------------------------------------------*/
function PhD_Error( $message ) {
    print "ERROR: " . PhD_Output::paramString( 'Error', $message ) . "\n";
    exit( 1 );
}

function PhD_Warning( $message ) {
    print "WARNING: " . PhD_Output::paramString( 'Warning', $message ) ."\n";
}

/*---------------------------------------------------------------------------*/
$userInterface = NULL;
$configurator = NULL;

/*---------------------------------------------------------------------------*/
if ( php_sapi_name() != 'cli' ) {
    PhD_Error( 'ONLY_CLI_SUPPORTED' );
}
$configurator = new PhD_Configurator;
$userInterface = new PhD_CLI_Interface;

/*---------------------------------------------------------------------------*/
// Fill in defaults.
$OPTIONS = array();
foreach ( $OPTIONS_DATA as $name => $data ) {
    $OPTIONS[ $name ] = $data[ 'default_value' ];
}

/*---------------------------------------------------------------------------*/
// Pull in any existing config.php.
$configurator->readConfig();

/*---------------------------------------------------------------------------*/
// Run the interface. TRUE if user chose values, FALSE if user wanted to break out
if ( $userInterface->run() === TRUE ) {
    // We have a set of values. We trust the interface to have checked the validity.
    $configurator->writeConfig();
    $userInterface->reportSuccess();
} else {
    PhD_Warning( 'UNSAVED_CHANGES' );
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
