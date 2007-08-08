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
    | The text for error, warning, and status messages in setup.php.          |
    +-------------------------------------------------------------------------+
*/

require_once 'PhD_Output.class.php';

class PhD_Messages {
    
    const USAGE = <<<~MESSAGE
PhD per-site configuration setup. Commandline interface.
Usage:
%%% [options]
    -v | --verbose          Give full details on configuration options.
                            This is the default.
    -q | --quiet            Give shorter descriptions of configuration options.
    -s | --silent           Give no descriptions of configuration options.
    -h | --help             Show this message.
MESSAGE;
    
    const CONFIG_BEGIN = <<<~MESSAGE
PhD per-site configuration setup. Commandline interface.
Run began at %%%.
MESSAGE;

    const CURRENT_SETTINGS = <<<~MESSAGE
Current settings:
MESSAGE;

    const NO_VALUES = <<<~MESSAGE
There is no list of possible values available.
MESSAGE;
    
    const BOOLEAN_VALUES = <<<~MESSAGE
This is a yes/no setting.
MESSAGE;
    
    const NUMBYTES_VALUES = <<<~MESSAGE
This is a value given in number of bytes. For convenience you may use any of
the following suffixes to multiply the number by the shown factor.
    K = 1024, M = K*1024, G = M*1024, T = G*1024, P = T*1024
MESSAGE;

    const AVAILABLE_VALUES = <<<~MESSAGE
The available values in this installation are:
%%%
MESSAGE;
    
    const CHOSEN_SETTINGS = <<<~MESSAGE
Chosen settings:
MESSAGE;

    const CONFIG_SAVED = <<<~MESSAGE
Your settings were successfully saved to config.php. PhD is now ready for use.
MESSAGE;

}    

class PhD_Errors {
    
    const ONLY_CLI_SUPPORTED = <<<~ERRMSG
The PhD setup application requires the CLI interface.
ERRMSG;
    
    const CONFIG_UNREADABLE = <<<~ERRMSG
The existing config.php is not readable, or is not a regular file. Please move
it out of the way.
ERRMSG;
    
    const CONFIG_IN_THE_WAY = <<<~ERRMSG
The existing config.php could not be removed. Please move it out of the way.
ERRMSG;
    
    const CONFIG_UNWRITEABLE = <<<~ERRMSG
The new config.php could not be written. This may indicate a permissions error,
or that another process is attempting to access the file.
ERRMSG;
    
    const CONFIG_DIR_INACCESSIBLE = <<<~ERRMSG
The directory for config.php does not exist, is not a directory, or is not
writable.
ERRMSG;
    
    const TEMPLATE_UNREADABLE = <<<~ERRMSG
Could not read the template file. Please ensure that it exists and that the
directory containing it is searchable.
ERRMSG;
    
    const TEMPLATE_MISSING = <<<~ERRMSG
The template file does not exist or is unreadable.
ERRMSG;

    const INPUT_EOF = <<<~ERRMSG
Input ended unexpectedly.
ERRMSG;

    const FORMATS_UNREADABLE = <<<~ERRMSG
The formats directory is missing or unreadable.
ERRMSG;

    const FORMATS_UNAVAILABLE = <<<~ERRMSG
There are no output formats available.
ERRMSG;

    const THEMES_UNREADABLE = <<<~ERRMSG
The themes directory is missing or unreadable.
ERRMSG;

    const THEMES_UNAVAILABLE = <<<~ERRMSG
There are no output themes available.
ERRMSG;
    
    const INTERNAL_ERROR = <<<~ERRMSG
An internal error occurred. Please report this as a bug.
ERRMSG;

}

class PhD_Warnings {
    
    const UNSAVED_CHANGES = <<<~WARNMSG
Your changes were not saved.
WARNMSG;
    
}

class PhD_Prompts {
    
    const INSTRUCTIONS = <<<~PROMPTMSG
Press return without entering any text to leave an option at its current value.
This will be rejected for options which require a value but whose current value
is empty.
PROMPTMSG;

    const OPTION_PROMPT = <<<~PROMPTMSG
%%% [%%%]: 
PROMPTMSG;
    
    const CONFIG_COMPLETE = <<<~PROMPTMSG
To confirm these settings, type "yes" now.
To start over, type "restart".
To quit without saving, type "quit": 
PROMPTMSG;

    const INVALID_CONFIG_COMPLETE = <<<~PROMPTMSG
This is not a valid response. Please try again.
PROMPTMSG;

}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
