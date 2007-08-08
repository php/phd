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
    | A simple interface to configuration of the per-site PhD system options. |
    | Chosen options are saved in config.php, an automatically generated      |
    | file. An existing config.php is presumed to offer defaults for setup.   |
    | No user authentication is done by this script; do not make it           |
    | accessible to non-administrators.                                       |
    +-------------------------------------------------------------------------+
*/

$SETUP_REVISION = '$Id$';

/*---------------------------------------------------------------------------*/
require_once 'setup.messages.php';
require_once 'Option_Metadata.inc.php';
require_once 'Configurator.class.php';
require_once 'PhD_Interface.class.php';

/*---------------------------------------------------------------------------*/
$interface = NULL;
$configurator = NULL;

/*---------------------------------------------------------------------------*/
$configurator = new PhD_Configurator;
$interface = new PhD_Interface;

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
if ( $interface->run() === TRUE ) {
    // We have a set of values. We trust the interface to have checked the validity.
    $configurator->writeConfig();
    $interface->reportSuccess();
} else {
    PhD_Warning( 'UNSAVED_CHANGES' );
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
