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
    | Provides the common code for managing the config.php file in setup.php. |
    +-------------------------------------------------------------------------+
*/

$OPTIONS_DATA_COMMON_FUNCS = array(

    'get_languages_func' => create_function( '', <<<~FUNCTION
global $OPTIONS_DATA, $OPTIONS;
static $languages = NULL;

if ( $OPTIONS_DATA[ 'xml_root' ][ 'validity_check_function' ]( $OPTIONS[ 'xml_root' ] ) ) {
    if ( is_null( $languages ) ) {
        $d = $OPTIONS[ 'xml_root' ];
        if ( ( $languageList = @scandir( $d ) ) === FALSE ) {
            PhD_Error( "Unable to scan XML root." );
        }
        $c = 'return is_dir( "'.$d.'/{$v}" ) && !in_array( $v, array( ".", "..", "CVS", ".svn" ) );';
        $languages = array_filter( $languageList, create_function( '$v', $c ) );
    }
    return $languages;
}
return NULL;
FUNCTION
    ),
    
    'numbytes_value_list_func' => create_function( '', <<<~FUNCTION
return 0;
FUNCTION
    ),
    
    'numbytes_validity_func' => create_function( '$v', <<<~FUNCTION
return preg_match( '/^(\d+)(?:([KMGTP])B?)?$/iu', $v, $matches ) ? TRUE : FALSE;
FUNCTION
    ),
    
    'numbytes_final_value_func' => create_function( '$v', <<<~FUNCTION
preg_match( '/^(\d+)(?:([KMGTP])B?)?$/iu', $v, $matches );
$multipliers = array( '' => 1, 'K' => 1024, 'M' => 1048576, 'G' => 1073741824, 'T' => 1099511627776, 'P' => 1125899906842620 );
return ( intval( $matches[ 1 ] ) * $multipliers[ strval( $matches[ 2 ] ) ] );
FUNCTION
    ),

    'boolean_value_list_func' => create_function( '', <<<~FUNCTION
return FALSE;
FUNCTION
    ),

    'boolean_validity_func' => create_function( '$v', <<<~FUNCTION
return in_array( substr( strtolower( $v ), 0, 1 ), array( 1, 0, 'y', 'n' ) ) || $v === TRUE || $v === FALSE;
FUNCTION
    ),
    
    'boolean_final_value_func' => create_function( '$v', <<<~FUNCTION
return ( substr( strtolower( $v ), 0, 1 ) == 'y' ) ? TRUE : FALSE;
FUNCTION
    ),
    
    'verbatim_final_value_func' => create_function( '$v', <<<~FUNCTION
return $v;
FUNCTION
    ),
    
    'unknown_value_list_func' => create_function( '', <<<~FUNCTION
return NULL;
FUNCTION
    ),

);

$OPTIONS_DATA = array(
    'output_format' => array(
        'default_value' => 'xhtml',
        'description' => <<<~MESSAGE
The output format for PhD determines the final form of any output it produces.
Generally, this will be directly related to the medium in which it is used.
MESSAGE
        ,
        'details' => <<<~MESSAGE
Some possible options are HTML4, XHTML, XML (the identity transformation), CHM,
WML, PDF, and plaintext. For a full list, see the formats/ folder. The default
is the builtin XHTML. The selected theme will determine the version and
conformance of any XML-based format.
MESSAGE
        ,
        'value_list_function' => create_function( '', <<<~FUNCTION
static $formatList = NULL;

if ( is_null( $formatList ) ) {
    $path = dirname( __FILE__ ) . "/../formats";
    if ( ( $formats = @scandir( $path ) ) === FALSE ) {
        PhD_Error( "The formats directory is missing or unreadable at \"{$path}\"." );
    }
    $formatList = array_map( create_function( '$v', 'return substr( $v, 0, -4 );' ),
        array_filter( $formats, create_function( '$v', 'return substr( $v, -4 ) == ".php" && is_file( "'.$path.'/{$v}" );' ) ) );
    if ( count( $formatList ) == 0 ) {
        PhD_Error( "No output formats are available." );
    }
}
return $formatList;
FUNCTION
        ),
        'validity_check_function' => create_function( '$format', <<<~FUNCTION
return in_array( $format, $GLOBALS[ 'OPTIONS_DATA' ][ 'output_format' ][ 'value_list_function' ]() );
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Choose a format',
        'invalid_message' => 'That is not an available format. Please try again.'
    ),
    
    'output_theme' => array(
        'default_value' => 'default',
        'description' => <<<~MESSAGE
The output theme for PhD determines site-specific alterations to the selected
output format.
MESSAGE
        ,
        'details' => <<<~MESSAGE
The default theme is, simply enough, the builtin "default", which will display
an error message for all cases. A valid theme must be selected by the
administrator for the site to function.
MESSAGE
        ,
        'value_list_function' => create_function( '', <<<~FUNCTION
static $themeList = NULL;

if ( is_null( $themeList ) ) {
    $path = dirname( __FILE__ ) . "/../themes";
    if ( ( $themes = @scandir( $path ) ) === FALSE ) {
        PhD_Error( "The themes directory is missing or unreadable." );
    }
    $themeList = array_filter( $themes,
        create_function( '$v', 'return is_dir( "'.$path.'/{$v}" ) && !in_array( $v, array( ".", "..", "CVS" ) );' ) );
    if ( count( $themeList ) == 0 ) {
        PhD_Error( "No themes are available." );
    }
}
return $themeList;
FUNCTION
        ),
        'validity_check_function' => create_function( '$theme', <<<~FUNCTION
return in_array( $theme, $GLOBALS[ 'OPTIONS_DATA' ][ 'output_theme' ][ 'value_list_function' ]() );
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Choose a theme',
        'invalid_message' => 'That is not an available theme. Please try again.'
    ),
    
    'output_encoding' => array(
        'default_value' => 'utf-8',
        'description' => <<<~MESSAGE
The output encoding for PDP-E determines the encoding that will be used for all
output. Input encodings are determined by the XML parser. The output encoding
can be any encoding supported by libiconv on this system.
MESSAGE
        ,
        'details' => <<<~MESSAGE
The default is UTF-8. For HTTP-based outputs, an appropriate header() call will
be made. For all SGML-based outputs, it is up to the output method to output a
proper encoding declaration. For reasons of safety and consistency, themes and
output formats can not override this value; if either needs to force an
encoding or set of encodings, it must document that requirement and allow the
system administrator to choose the proper one, and raise an error at runtime
for an unsupported encoding.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'unknown_value_list_func' ],
        'validity_check_function' => create_function( '$encoding', <<<~FUNCTION
return iconv( $encoding, $encoding, "Test string" ) === "Test string";
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Choose an encoding',
        'invalid_message' => 'That encoding does not appear to be supported by your libiconv. Please try another.'
    ),
    
    'xml_root' => array(
        'default_value' => '/INVALID/PATH',
        'description' => <<<~MESSAGE
The location of the language trees.
MESSAGE
        ,
        'details' => <<<~MESSAGE
The XML root tells PhD where to find the XML files it wil be displaying in
this installation. The expected structure is the same as that used by
phpdoc-all, e.g. a set of directories named by language code containing the XML
files and translations laid out by section structure. Tilde expansion is NOT
done. Symbolic links are resolved at run time.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'unknown_value_list_func' ],
        'validity_check_function' => create_function( '$root', <<<~FUNCTION
$rv = realpath( $root );
return ( $rv !== FALSE && is_dir( $rv ) && is_readable( $rv ) && is_executable( $rv ) && is_dir( "{$rv}/en" ) );
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Enter the full path to the XML root',
        'invalid_message' => <<<~MESSAGE
The path you entered does not exist, is not a directory, is not readable, is
not searchable, or does not contain an English directory. Please try again.
MESSAGE
    ),
    
    'language' => array(
        'default_value' => 'en',
        'description' => <<<~MESSAGE
The language tells PhD which set of translations to use for display. The
language is a two or four letter language code. It is assumed that
subdirectories of the XML root are named according to the language code of the
translations they contain. The language specified here must exist in the XML
root.
MESSAGE
        ,
        'details' => <<<~MESSAGE
The default is English. English files are always the final fallback if a
translated version of a page is not found.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'get_languages_func' ],
        'validity_check_function' => create_function( '$v', <<<~FUNCTION
return in_array( $v, $GLOBALS[ 'OPTIONS_DATA' ][ 'language' ][ 'value_list_function' ]() );
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Choose a primary language code',
        'invalid_message' => <<<~MESSAGE
The language code you entered does not appear to exist, or is not a directory
in the XML root. Please try again.
MESSAGE
    ),
    
    'fallback_language' => array(
        'default_value' => 'en',
        'description' => <<<~MESSAGE
The fallback language is used before English when PhD can not find a translated
file in the primary language. If it is set to English, PhD immediately falls
back to English files without any extra overhead. The language specified here
must exist in the XML root.
MESSAGE
        ,
        'details' => <<<~MESSAGE
This provides an optional fallback before English if another fallback would
make more sense for a site. The default is English, causing immediate fallback.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'get_languages_func' ],
        'validity_check_function' => create_function( '$v', <<<~FUNCTION
return in_array( $v, $GLOBALS[ 'OPTIONS_DATA' ][ 'language' ][ 'value_list_function' ]() );
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Choose a fallback language code',
        'invalid_message' => <<<~MESSAGE
The language code you entered does not appear to exist, or is not a directory
in the XML root. Please try again.
MESSAGE
    ),
    
    'enforce_revisions' => array(
        'default_value' => FALSE,
        'description' => <<<~MESSAGE
PhD is capable of using either traditional <!-- Revision: --> and
<!-- EN-Revision: --> tags or the <phd:revision/> tag to specify version
control information for files and their translated counterparts. If the
revision control flag is set, PhD will enforce revision matching between
translated files and English files.
MESSAGE
        ,
        'details' => <<<~MESSAGE
The processor relies on whatever version control system is in use to provide
the revision information in its proper place for the tag style in use. Use of
the <phd:revision/> tag is strongly preferred to the use of XML comments. If a
reivison mismatch is found, the active theme is given an opportunity to present
an error or warning to users.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'boolean_value_list_func' ],
        'validity_check_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'boolean_validity_func' ],
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'boolean_final_value_func' ],
        'prompt' => 'Type "(Y)es" to enable revision control, or "(N)o" to disable it',
        'invalid_message' => 'Please enter "(Y)es" or "(N)o".'
    ),
    
    'database_path' => array(
        'default_value' => '/INVALID/PATH/phd-data.sqlite',
        'description' => <<<~MESSAGE
The database path tells PhD where to store the SQLite 3 database file, used for
indexing and cache data. Most installations will want to place the database
file in the same directory as PhD itself.
MESSAGE
        ,
        'details' => <<<~MESSAGE
This setup will attempt to create a database file in the given location and
remove it again. The user running PhD must have write and execute permissions
to the enclosing directory. The file name can be whatever best suits the needs
of the host system.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'unknown_value_list_func' ],
        'validity_check_function' => create_function( '$path', <<<~FUNCTION
try {
    $d = dirname( realpath( $path ) );
    if ( !is_dir( $d ) || !is_writable( $d ) || !is_executable( $d ) ) {
        return FALSE;
    }
    $p = new PDO( "sqlite:{$path}", NULL, NULL, array( PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ) );
    $p = NULL;
    unset( $p );
    return @unlink( $path );
} catch ( PDOException $e ) {
    return FALSE;
}
FUNCTION
        ),
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'verbatim_final_value_func' ],
        'prompt' => 'Enter the full path to the database file',
        'invalid_message' => <<<~MESSAGE
The path is invalid, you don't have write permission to it, an existing
database file is in the way, or loading SQLite failed. Please try again.
MESSAGE
    ),
    
    'chunking_memory_limit' => array(
        'default_value' => ( 100 * 1048576 ),  // 100MB
        'description' => <<<~MESSAGE
The chunking memory limit controls how big a single chunk of output data must
grow for PhD to begin spooling it to disk to avoid memory exhaustion.
MESSAGE
        ,
        'details' => <<<~MESSAGE
While performing transformations, PhD stores a growing chunk in memory for
efficiency. However, efficiency will quickly degrade if the chunk is too large.
This is particularly problematic for output formats and themes which don't do
any chunking at all. Setting this limit to zero will force PhD to spool for all
chunks, no matter how small. This is the recommended setting for non-chunking
outputs; the default is fine for most other installations.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'numbytes_value_list_func' ],
        'validity_check_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'numbytes_validity_func' ],
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'numbytes_final_value_func' ],
        'prompt' => 'Enter the chunking limit',
        'invalid_message' => 'You must enter a valid numeric value.'
    ),
    
    'debug' => array(
        'default_value' => FALSE,
        'description' => <<<~MESSAGE
The debug flag controls whether PhD runs in debug mode. This should NEVER be
set in a production environment; its intended use is for PhD developers and
experienced site administrators.
MESSAGE
        ,
        'details' => <<<~MESSAGE
If set, many extra assertions are enabled and verbose error messages and
progress information are provided. Please note that debug output is under the
control of both the output format and the output theme, as a matter of
consistency. This flag is only provided in the setup interface for ease of
administration.
MESSAGE
        ,
        'value_list_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'boolean_value_list_func' ],
        'validity_check_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'boolean_validity_func' ],
        'final_value_function' => $OPTIONS_DATA_COMMON_FUNCS[ 'boolean_final_value_func' ],
        'prompt' => 'Type "(Y)es" to enable debug output, or "(N)o" to disable it',
        'invalid_message' => 'Please enter "(Y)es" or "(N)o".'
    ),

);

function getOptionNames() {
    return array_filter( array_keys( $GLOBALS[ 'OPTIONS_DATA' ] ),
    create_function( '$v', 'return strncmp( "__", $v, 2 );' ) );
}

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
