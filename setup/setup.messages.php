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
    | Provides the text for error, warning, and status messages in setup.php. |
    +-------------------------------------------------------------------------+
*/

class PhD_Errors {
    
    const EMBED_UNSUPPORTED = <<<~ERRMSG
Embedded SAPI is not supported by PhD.
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

    const CLI_WRONG_SAPI = <<<~ERRMSG
The CLI interface requires the CLI SAPI!
ERRMSG;

    const CLI_INPUT_EOF = <<<~ERRMSG
Input ended unexpectedly.
ERRMSG;
    
    const INTERNAL_ERROR = <<<~ERRMSG
An internal error occurred. Please report this as a bug.
ERRMSG;

};

class PhD_Warnings {
    
    const UNSAVED_CHANGES = <<<~WARNMSG
Your changes were not saved.
WARNMSG;
    
};

class PhD_Prompts {
    
    public static function paramPrompt( $prompt/*, ... */ ) {
        
        $args = func_get_args();
$c = '
static $a = NULL;
if ( is_null( $a ) ) {
    $a = unserialize(\''.serialize($args).'\');
}
$v = next( $a );
return ( is_bool( $v ) ? ( $v ? "Yes" : "No" ) : $v );
';

        return count( $args ) > 1 ? preg_replace_callback( '/%%%/',
            create_function( '$v', $c ),
            $prompt ) : $prompt;
    
    }
    
    const CLI_USAGE = <<<~PROMPTMSG
PhD per-site configuration setup. Commandline interface.
Usage:
%%% [options]
    -v | --verbose          Give full details on configuration options.
                            This is the default.
    -q | --quiet            Give shorter descriptions of configuration options.
    -s | --silent           Give no descriptions of configuration options.
    -h | --help             Show this message.

PROMPTMSG;
    
    const CLI_CONFIG_BEGIN = <<<~PROMPTMSG
PhD per-site configuration setup. Commandline interface.
Run began at %%%.

PROMPTMSG;

    const CLI_CURRENT_SETTINGS = <<<~PROMPTMSG
Current settings:

PROMPTMSG;

    const CLI_INSTRUCTIONS = <<<~PROMPTMSG
Press return without entering any text to leave an option at its current value.
This will be rejected for options which require a value but whose current value
is empty.


PROMPTMSG;

    const CLI_BOOLEAN_VALUE = "\n";/*<<<~PROMPTMSG
This is a flag setting. Type "y" or "yes" to turn it on, or "n" or "no" to turn
it off.


PROMPTMSG;*/
    
    const CLI_AVAILABLE_VALUES = <<<~PROMPTMSG
The available values in this installation are:
%%%


PROMPTMSG;

    const CLI_OPTION_PROMPT = <<<~PROMPTMSG
%%% [%%%]: 
PROMPTMSG;
    
    const CLI_CHOSEN_SETTINGS = <<<~PROMPTMSG
Chosen settings:

PROMPTMSG;

    const CLI_CONFIG_COMPLETE = <<<~PROMPTMSG
To confirm these settings, type "yes" now.
To start over, type "restart".
To quit without saving, type "quit": 
PROMPTMSG;

    const CLI_INVALID_CONFIG_COMPLETE = <<<~PROMPTMSG
This is not a valid response. Please try again.

PROMPTMSG;
    
    const CLI_CONFIG_SAVED = <<<~PROMPTMSG
The settings were successfully saved to config.php. You may now start using
PhD.

PROMPTMSG;

};

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
