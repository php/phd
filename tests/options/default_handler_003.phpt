--TEST--
Default options handler 003 - Show help - short option
--ARGS--
-h
--SKIPIF--
<?php
if (file_exists(__DIR__ . "/../../phd.config.php")) {
    die("Skipped: existing phd.config.php file will overwrite command line options.");
}
?>
--FILE--
<?php
namespace phpdotnet\phd;

require_once __DIR__ . "/../../render.php";
?>
--EXPECTF--
PhD version: %s
Copyright(c) %d-%d The PHP Documentation Group

  -v
  --verbose <int>            Adjusts the verbosity level
  -f <formatname>
  --format <formatname>      The build format to use
  -P <packagename>
  --package <packagename>    The package to use
  -I
  --noindex                  Do not index before rendering but load from cache
                             (default: %s)
  -M
  --memoryindex              Do not save indexing into a file, store it in memory.
                             (default: %s)
  -r
  --forceindex               Force re-indexing under all circumstances
                             (default: %s)
  -t
  --notoc                    Do not rewrite TOC before rendering but load from
                             cache (default: %s)
  -d <filename>
  --docbook <filename>       The Docbook file to render from
  -x
  --xinclude                 Process XML Inclusions (XInclude)
                             (default: %s)
  -p <id[=bool]>
  --partial <id[=bool]>      The ID to render, optionally skipping its children
                             chunks (default to %s; render children)
  -s <id[=bool]>
  --skip <id[=bool]>         The ID to skip, optionally skipping its children
                             chunks (default to %s; skip children)
  -l
  --list                     Print out the supported packages and formats
  -o <directory>
  --output <directory>       The output directory (default: %s)
  -F filename
  --outputfilename filename  Filename to use when writing standalone formats
                             (default: <packagename>-<formatname>.<formatext>)
  -L <language>
  --lang <language>          The language of the source file (used by the CHM
                             theme). (default: %s)
  -c <bool>
  --color <bool>             Enable color output when output is to a terminal
                             (default: %s)
  -C <filename>
  --css <filename>           Link for an external CSS file.
  -g <classname>
  --highlighter <classname>  Use custom source code highlighting php class
  -V
  --version                  Print the PhD version information
  -h
  --help                     This help
  -e <extension>
  --ext <extension>          The alternative filename extension to use,
                             including the dot. Use 'false' for no extension.
  -S <bool>
  --saveconfig <bool>        Save the generated config (default: %s).

  -Q
  --quit                     Don't run the build. Use with --saveconfig to
                             just save the config (default: %s).
  -k
  --packagedir               Use an external package directory.


Most options can be passed multiple times for greater effect.
