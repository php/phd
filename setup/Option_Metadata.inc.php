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
    | The metadata for PhD configuration options.                             |
    +-------------------------------------------------------------------------+
*/

require_once 'PhD_Options.class.php';

function OPTIONS_META_scan_script_dir( $name ) {
    static $lists = NULL;

    if ( is_null( $lists ) ) {
        $lists = array();
    }
    if ( !isset( $lists[ $name ] ) ) {
        $path = dirname( __FILE__ ) . "/../{$name}";
        if ( ( $files = @scandir( $path ) ) === FALSE ) {
            PhD_Error( strtoupper( $name ) . '_UNREADABLE' );
        }
        $fileList = array_map( create_function( '$v', 'return substr( $v, 0, -4 );' ),
            array_filter( $files, create_function( '$v', 'return substr( $v, -4 ) == ".php" && is_file( "'.$path.'/{$v}" );' ) ) );
        if ( count( $fileList ) == 0 ) {
            PhD_Error( strtoupper( $name ) . '_UNAVAILABLE' );
        }
        $lists[ $name ] = $fileList;
    }
    return $lists[ $name ];
}

function OPTIONS_META_get_languages() {
    global $OPTIONS_DATA, $OPTIONS;
    static $languages = NULL;

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

function OPTIONS_META_validate_file_save( $v, $rp = NULL ) {
    if ( ( $rp = realpath( $v ) ) === FALSE ) {
        return FALSE;
    }
    $d = dirname( $rp );
    if ( !is_dir( $d ) || !is_writable( $d ) || !is_executable( $d ) ||
            ( file_exists( $rp ) && ( !is_writable( $rp ) || is_dir( $rp ) ) ) ) {
        return FALSE;
    }
    return TRUE;
}

$OPTIONS_DATA = array(
    'output_format' => array(
        'display_name' => "Output Format",
        'type' => PhD_Options::TYPE_LIST,
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
        'value_list_func' => create_function( '', <<<~FUNCTION
return OPTIONS_META_scan_script_dir( 'formats' );
FUNCTION
        ),
        'prompt' => 'Choose a format',
        'invalid_message' => 'That is not an available format. Please try again.'
    ),
    
    'output_theme' => array(
        'display_name' => 'Output Theme',
        'type' => PhD_Options::TYPE_LIST,
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
        'value_list_func' => create_function( '', <<<~FUNCTION
return OPTIONS_META_scan_script_dir( 'themes' );
FUNCTION
        ),
        'prompt' => 'Choose a theme',
        'invalid_message' => 'That is not an available theme. Please try again.'
    ),
    
    'output_encoding' => array(
        'display_name' => 'Output Encoding',
        'type' => PhD_Options::TYPE_ARBITRARY,
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
        'validity_func' => create_function( '$encoding', <<<~FUNCTION
return iconv( $encoding, $encoding, "Test string" ) === "Test string";
FUNCTION
        ),
        'prompt' => 'Choose an encoding',
        'invalid_message' => 'That encoding does not appear to be supported by your libiconv. Please try another.'
    ),
    
    'xml_root' => array(
        'display_name' => 'XML Root Directory',
        'type' => PhD_Options::TYPE_ARBITRARY,
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
        'validity_func' => create_function( '$root', <<<~FUNCTION
$rv = realpath( $root );
return ( $rv !== FALSE && is_dir( $rv ) && is_readable( $rv ) && is_executable( $rv ) && is_dir( "{$rv}/en" ) );
FUNCTION
        ),
        'prompt' => 'Enter the full path to the XML root',
        'invalid_message' => <<<~MESSAGE
The path you entered does not exist, is not a directory, is not readable, is
not searchable, or does not contain an English directory. Please try again.
MESSAGE
    ),
    
    'language' => array(
        'display_name' => 'Primary Language',
        'type' => PhD_Options::TYPE_LIST,
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
        'value_list_func' => 'OPTIONS_META_get_languages',
        'prompt' => 'Choose a primary language code',
        'invalid_message' => <<<~MESSAGE
The language code you entered does not appear to exist, or is not a directory
in the XML root. Please try again.
MESSAGE
    ),
    
    'fallback_language' => array(
        'display_name' => 'Fallback Language',
        'type' => PhD_Options::TYPE_LIST,
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
        'value_list_func' => 'OPTIONS_META_get_languages',
        'prompt' => 'Choose a fallback language code',
        'invalid_message' => <<<~MESSAGE
The language code you entered does not appear to exist, or is not a directory
in the XML root. Please try again.
MESSAGE
    ),
    
    'enforce_revisions' => array(
        'display_name' => 'Revision Enforce flag',
        'type' => PhD_Options::TYPE_FLAG,
        'default_value' => FALSE,
        'description' => <<<~MESSAGE
PhD is capable of using the <phd:revision/> tag to specify version control
information for files and their translated counterparts. If the revision
control flag is set, PhD will enforce revision matching between translated
files and English files.
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
        'prompt' => 'Type "(Y)es" to enable revision control, or "(N)o" to disable it',
        'invalid_message' => 'Please enter "(Y)es" or "(N)o".'
    ),
    
    'compatibility_mode' => array(
        'display_name' => 'Compatibility mode',
        'type' => PhD_Options::TYPE_FLAG,
        'default_value' => TRUE,
        'description' => <<<~MESSAGE
Turning on this flag causes PhD to modify its processing in several ways to
make it compatible with documentation that was written for a legacy form of
DocBook publishing, specifically XSL or DSSSL.
MESSAGE
        ,
        'details' => <<<~MESSAGE
The specific differences are:
    - XML files without IDs are not rejected; an ID is autogenerated based on
      its path relative to the XML root.
    - XML files containing more than one root element are not rejected; the
      extra elements are treated as separate sections.
    - When revision control is on, <!-- Revision: --> and <!-- EN-Revision: -->
      tags are automatically replaced with an equivelant <phd:revision/> tag.
    - <!-- [name]: [value] --> tags are automatically replaced with equivelant
      <phd:meta/> tags.
    - Entity references and entity delcarations are replaced with equivelant
      <phd:define/>, <phd:constant/> and <phd:include/> tags.
WARNING: This extra processing entails an extra phase during the build, which
will slow it down considerably. Please consider converting old documents to use
PhD elements using convert.php.
MESSAGE
        ,
        'prompt' => 'Type "(Y)es" to enable compatibility mode, or "(N)o" to disable it',
        'invalid_message' => 'Please enter "(Y)es" or "(N)o".'
    ),
    
    'build_log_file' => array(
        'display_name' => 'Build Log',
        'type' => PhD_Options::TYPE_ARBITRARY,
        'default_value' => 'none',
        'description' => <<<~MESSAGE
The log file is where PhD stores the output of the first phase of operation,
the conversion of xml_root's multitude of XML files into a single blog.
MESSAGE
        ,
        'details' => <<<~MESSAGE
Running the many-to-one build process can sometimes produce a copious amount of
output, especially with the debug flag on. This setting is provided to
automatically dump that output to a file for convenience. Multiple runs of the
build will overwrite an existing log file, so be careful.
MESSAGE
        ,
        'validity_func' => create_function( '$v', <<<~FUNCTION
return ( $v === 'none' || OPTIONS_META_validate_file_save( $v ) );
FUNCTION
        ),
        'prompt' => 'Enter the full path to the build log, or "none" to log to stdout',
        'invalid_message' => <<<~MESSAGE
The path you entered is invalid or you don't have write permission to it.
Please try again.
MESSAGE
    ),
    
    'debug' => array(
        'display_name' => 'Debug flag',
        'type' => PhD_Options::TYPE_FLAG,
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
        'prompt' => 'Type "(Y)es" to enable debug output, or "(N)o" to disable it',
        'invalid_message' => 'Please enter "(Y)es" or "(N)o".'
    ),
);

/*
* vim600: sw=4 ts=4 fdm=syntax syntax=php et
* vim<600: sw=4 ts=4
*/
?>
