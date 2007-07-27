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
    | The commandline interface for setup.php to talk to the user.            |
    +-------------------------------------------------------------------------+
*/

class PhD_CLI_Interface implements PhD_SAPI_Interface {
    
    protected $quietMode = 0;
    
    public function __construct() {
        
        if ( php_sapi_name() !== 'cli' ) {
            PhD_error( PhD_Errors::CLI_WRONG_SAPI );
        }
        
        if ( in_array( '-h', $_SERVER[ 'argv' ] ) || in_array( '--help', $_SERVER[ 'argv' ] ) ) {
            print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_USAGE, $_SERVER[ 'argv' ][ 0 ] );
            exit( 1 );
        }
        
        if ( in_array( '-q', $_SERVER[ 'argv' ] ) || in_array( '--quiet', $_SERVER[ 'argv' ] ) ) {
            $this->quietMode = 1;
        } else if ( in_array( '-s', $_SERVER[ 'argv' ] ) || in_array( '--silent', $_SERVER[ 'argv' ] ) ) {
            $this->quietMode = 2;
        } else if ( in_array( '-v', $_SERVER[ 'argv' ] ) || in_array( '--verbose', $_SERVER[ 'argv' ] ) ) {
            $this->quietMode = 0;
        }
        
    }
    
    public function __destruct() {
    }
    
    public function getName() {

        return 'Commandline';
    
    }
    
    public function errorMessage( $message ) {
        
        print "ERROR: ${message}\n";
        exit( 1 );
    
    }
    
    public function warningMessage( $message ) {
        
        print "WARNING: ${message}\n";
    
    }
    
    public function run() {
        global $configurator, $OPTIONS, $OPTIONS_DATA;
        
        $now = date( DATE_RFC2822 );
        print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_CONFIG_BEGIN, $now );
        
        do {
        
            print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_CURRENT_SETTINGS );
            $this->displaySettings();
    
            print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_INSTRUCTIONS );
            
            foreach ( $OPTIONS_DATA as $optionName => $optionData ) {

                if ( !strncmp( $optionName, '__', 2 ) )
                    continue;
                
                if ( $this->quietMode < 2 ) {
                    print "${optionData[ 'description' ]}\n";
                }
                if ( $this->quietMode < 1 ) {
                    print "${optionData[ 'details' ]}\n";
                }
                
                if ( ( $valueList = $optionData[ 'value_list_function' ]() ) !== NULL ) {
                    if ( $valueList !== FALSE ) {
                        print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_AVAILABLE_VALUES,
                            wordwrap( "\t" . implode( ' ', $valueList ) , 71, "\n\t", FALSE ) );
                    }
                } else if ( $this->quietMode < 2 ) {
                    print "\n";
                }
                
                do {
                    $response = $this->getLine( PhD_Prompts::paramPrompt(
                        PhD_Prompts::CLI_OPTION_PROMPT, $optionData[ 'prompt' ], $configurator->$optionName ) );
                    if ( $response === '' ) {
                        $response = $configurator->$optionName;
                    }
                    if ( $optionData[ 'validity_check_function' ]( $response ) === TRUE ) {
                        break;
                    }
                    print $optionData[ 'invalid_message' ]."\n";
                } while( TRUE );
                
                $configurator->$optionName = $valueList === FALSE ? ( substr( strtolower( $response ), 0, 1 ) == 'y' ) : $response;
                print "\n";
                
            }
            
            print "\n";
            print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_CHOSEN_SETTINGS );
            $this->displaySettings();
            
            do {
                $response = $this->getLine( PhD_Prompts::paramPrompt( PhD_Prompts::CLI_CONFIG_COMPLETE ) );

                switch ( strval( $response ) ) {
                    case 'yes':
                        return TRUE;
                    
                    case 'restart':
                        continue 3;
                    
                    case 'quit':
                        return FALSE;
                    
                    default:
                        print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_INVALID_CONFIG_COMPLETE );
                        continue 2;
                }
            } while ( TRUE );
            
        } while ( TRUE );
        
    }
    
    public function reportSuccess() {
        
        print PhD_Prompts::paramPrompt( PhD_Prompts::CLI_CONFIG_SAVED );
    
    }
    
    protected function getLine( $prompt = NULL ) {
        
        if ( !is_null( $prompt ) ) {
            print $prompt;
        }
        
        if ( ( $result = fgets( STDIN ) ) === FALSE ) {
            PhD_Error( PhD_Errors::CLI_INPUT_EOF );
        }
        return trim( $result );
    
    }
    
    protected function displaySettings() {
        global $configurator;

        foreach ( getOptionNames() as $name ) {
            printf( "\t%-25s: %s\n", $name,
                is_bool( $configurator->$name ) ? ( $configurator->$name ? "On" : "Off" ) : $configurator->$name );
        }
        print "\n";
        
    }

};

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
