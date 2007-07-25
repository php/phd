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
    | The common interface for setup.php to talk to the user.                 |
    +-------------------------------------------------------------------------+
*/

interface PhD_SAPI_Interface {
    
    public function getName();
    
    public function errorMessage( $message );
    public function warningMessage( $message );
    public function reportSuccess();

    public function run();
    
};

?>
