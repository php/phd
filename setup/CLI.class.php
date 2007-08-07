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

class PhD_CLI_Interface {
    
    protected $quietMode = 0;
    
    public function __construct() {

        // If we someday have a version of PHP with the nice long getopt() on all systems patch...
        if ( in_array( '-h', $_SERVER[ 'argv' ] ) || in_array( '--help', $_SERVER[ 'argv' ] ) ) {
            print PhD_Output::paramString( 'Message', 'USAGE', $_SERVER[ 'argv' ][ 0 ] );
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
    
    public function run() {
        global $configurator, $OPTIONS, $OPTIONS_DATA;
        
        $now = date( DATE_RFC2822 );
        print PhD_Output::paramString( 'Message', 'CONFIG_BEGIN', $now ) . "\n\n";
        
        do {
        
            $this->displaySettings( 'CURRENT_SETTINGS' );
    
            print PhD_Output::paramString( 'Prompt', 'INSTRUCTIONS' ) . "\n\n";
            
            foreach ( $OPTIONS_DATA as $optionName => $optionData ) {
                
                printf( "%-40s\n", "{$optionData[ 'display_name' ]}:" );
                if ( $this->quietMode < 2 ) {
                    print "{$optionData[ 'description' ]}\n";
                }
                if ( $this->quietMode < 1 ) {
                    print "{$optionData[ 'details' ]}\n";
                }
                
                switch ( PhD_Options::getType( $optionName ) ) {
                    case PhD_Options::TYPE_ARBITRARY:
                        print PhD_Output::paramString( 'Message', 'NO_VALUES' );
                        break;
                    case PhD_Options::TYPE_LIST:
                        print PhD_Output::paramString( 'Message', 'AVAILABLE_VALUES',
                            wordwrap( "\t" . implode( ' ', PhD_Options::getValueList( $optionName ) ), 71, "\n\t", FALSE ) );
                        break;
                    case PhD_Options::TYPE_NUMERIC:
                        print PhD_Output::paramString( 'Message', 'NUMERIC_VALUE',
                            $optionData[ 'min_value' ], $optionData[ 'max_value' ] );
                        break;
                    case PhD_Options::TYPE_FILESIZE:
                        print PhD_Output::paramString( 'Message', 'NUMBYTES_VALUES' );
                        break;
                    case PhD_Options::TYPE_FLAG:
                        print PhD_Output::paramString( 'Message', 'BOOLEAN_VALUES' );
                        break;
                    default:
                        PhD_Error( 'INTERNAL_ERROR' );
                }
                
                print "\n\n";
                
                do {
                    $response = $this->getLine( PhD_Output::paramString( 'Prompt', 'OPTION_PROMPT', $optionData[ 'prompt' ],
                        $configurator->$optionName ) );
                    if ( $response === '' ) {
                        $response = $configurator->$optionName;
                    }
                    if ( PhD_Options::checkValidity( $optionName, $response ) === TRUE ) {
                        break;
                    }
                    print "{$optionData[ 'invalid_message' ]}\n";
                } while ( TRUE );
                
                $configurator->$optionName = PhD_Options::getFinalValue( $optionName, $response );
                
                print "\n";
            
            }

            $this->displaySettings( 'CHOSEN_SETTINGS' );
            
            do {
                $response = $this->getLine( PhD_Output::paramString( 'Prompt', 'CONFIG_COMPLETE' ) );

                switch ( strval( $response ) ) {
                    case 'yes':
                        return TRUE;
                    case 'restart':
                        continue 3;
                    case 'quit':
                        return FALSE;
                    default:
                        print PhD_Output::paramString( 'Prompt', 'INVALID_CONFIG_COMPLETE' );
                        continue 2;
                }
            } while ( TRUE );
            
        } while ( TRUE );
    }
    
    public function reportSuccess() {
        print PhD_Output::paramString( 'Message', 'CONFIG_SAVED' );
    }
    
    protected function getLine( $prompt = NULL ) {
        if ( !is_null( $prompt ) ) {
            print $prompt;
        }
        
        if ( ( $result = fgets( STDIN ) ) === FALSE ) {
            PhD_Error( 'INPUT_EOF' );
        }
        return trim( $result );
    }
    
    protected function displaySettings( $headerConst ) {
        global $configurator, $OPTIONS_DATA;
        
        print PhD_Output::paramString( 'Message', $headerConst ) . "\n";
        foreach ( $OPTIONS_DATA as $name => $data ) {
            printf( "\t%-25s: %s\n", $name,
                is_bool( $configurator->$name ) ? ( $configurator->$name ? "On" : "Off" ) : $configurator->$name );
        }
        print "\n";
    }

}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
