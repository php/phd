# PhD - PHP DocBook

## About PhD

PhD is PHP's very own DocBook 5 rendering system which is used to convert
the PHP Manual into different output formats.


## Requirements

Requirements:
- PHP 8.0+
- DOM, libXML2, XMLReader and SQLite3.


## Using PhD

To use PhD you must clone the git repo

```shell
$ git clone https://github.com/php/phd.git
```

and render your documentation file (`doc-base/.manual.xml` in this example)
in `xhtml` format

```shell
$ php phd/render.php --docbook doc-base/.manual.xml --package PHP --format xhtml
```


# Using PhD to render documentation

## Rendering the PHP Documentation Sources

To render the PHP documentation, you will need to clone the
documentation source files, `doc-base` and PhD.

To get the PHP documentation sources, clone them from the official GitHub
repositories. To clone the English documentation:

```shell
$ git clone https://github.com/php/doc-en en
```

<details>
  <summary>List of languages/repositories</summary>

  - [Brazilian Portugues](https://github.com/php/doc-pt_br) (doc-pt_br)
  - [Chinese(Simplified)](https://github.com/php/doc-zh) (doc-zh)
  - [English](https://github.com/php/doc-en) (doc-en)
  - [French](https://github.com/php/doc-fr) (doc-fr)
  - [German](https://github.com/php/doc-de) (doc-de)
  - [Italian](https://github.com/php/doc-it) (doc-it)
  - [Japanese](https://github.com/php/doc-ja) (doc-ja)
  - [Polish](https://github.com/php/doc-pl) (doc-pl)
  - [Romanian](https://github.com/php/doc-ro) (doc-ro)
  - [Russian](https://github.com/php/doc-ru) (doc-ru)
  - [Spanish](https://github.com/php/doc-es) (doc-es)
  - [Turkish](https://github.com/php/doc-tr) (doc-tr)
  - [Ukrainian](https://github.com/php/doc-uk) (doc-uk)
</details>

To check the documentation and combine it into one file,
you need to clone PHP's `doc-base` repository

```shell
$ git clone https://github.com/php/doc-base
```

and run `configure.php`

```shell
$ php doc-base/configure.php
```

This process will generate a `.manual.xml` file in the `doc-base` directory.

Running PhD to render the docs is
surprisingly simple, so we'll start with that.

```shell
$ phd -d doc-base/.manual.xml -P PHP
```

After running for a few moments, PhD will generate all the output
formats of the PHP Package into the default `./output/` directory.

Let's take a closer look at PhD and see what capabilities are available to us.

```shell
$ phd --help
PhD version: 1.1.12
Copyright(c) 2007-2024 The PHP Documentation Group

    -v
    --verbose <int>            Adjusts the verbosity level
    -f <formatname>
    --format <formatname>      The build format to use
    -P <packagename>
    --package <packagename>    The package to use
    -I
    --noindex                  Do not index before rendering but load from cache
                                (default: false)
    -M
    --memoryindex              Do not save indexing into a file, store it in memory.
                                (default: false)
    -r
    --forceindex               Force re-indexing under all circumstances
                                (default: false)
    -t
    --notoc                    Do not rewrite TOC before rendering but load from
                                cache (default: false)
    -d <filename>
    --docbook <filename>       The Docbook file to render from
    -x
    --xinclude                 Process XML Inclusions (XInclude)
                                (default: false)
    -p <id[=bool]>
    --partial <id[=bool]>      The ID to render, optionally skipping its children
                                chunks (default to true; render children)
    -s <id[=bool]>
    --skip <id[=bool]>         The ID to skip, optionally skipping its children
                                chunks (default to true; skip children)
    -l
    --list                     Print out the supported packages and formats
    -o <directory>
    --output <directory>       The output directory (default: .)
    -F filename
    --outputfilename filename  Filename to use when writing standalone formats
                                (default: <packagename>-<formatname>.<formatext>)
    -L <language>
    --lang <language>          The language of the source file (used by the CHM
                                theme). (default: en)
    -c <bool>
    --color <bool>             Enable color output when output is to a terminal
                                (default: true)
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
    --saveconfig <bool>        Save the generated config (default: false).

    -Q
    --quit                     Don't run the build. Use with --saveconfig to
                                just save the config.
    -k
    --packagedir               Use an external package directory.
```

As you can see, there are plenty of options to look into in PhD. The
most important options are those which allow you to select a format and
package to output your documentation to.

```shell
$ phd --list
Supported packages:
        Generic
                xhtml
                bigxhtml
                manpage
        IDE
                xml
                funclist
                json
                php
                phpstub
        PEAR
                xhtml
                bigxhtml
                php
                chm
                tocfeed
        PHP
                xhtml
                bigxhtml
                php
                howto
                manpage
                pdf
                bigpdf
                kdevelop
                chm
                tocfeed
                epub
                enhancedchm
```

You can tell by the output of the `--list` option that PhD can also be
used to render the docs as a PDF file or as Unix Man Pages.

To select a format and package, you must use the `-f [formatName]` and
`-P [packageName]` options.

```shell
$ phd -f manpage -P PHP -d .manual.xml
```

This command will output the documentation for PHP functions in the Unix
Man page format.


## Customizing the rendering results

PhD lets you specify a number of options to customize the generated
documentation files. The following sections describe some of them.

### Source code highlighter

Part of the documentation of programming languages is source code
examples. PhD is able to colorize the source code of many types of
source code with the help of *highlighters*.

To utilize syntax highlighting, your opening `<programlisting>` tags
need a `role` attribute describing the type of source code. Examples are
`php`, `html` and `python`.

> **_NOTE:_**
> PhD currently only highlights the code if it is embedded in a `CDATA`
> section.

```xml
<programlisting role="php"><![CDATA[
<?php
echo "Hello world!";
?>
]]></programlisting>
```

By default, PhD uses the source code highlighter that is built into PHP
itself which is only able to highlight PHP code.

If your documentation contains other types of source code or markup,
you can [build your own syntax highlighter](#writing-a-custom-syntax-highlighter).


# Extending PhD

Written in PHP, PhD is easy to hack on and easy to extend. It provides
command line parameters to allow using custom code (like source code highlighters)
without changing the internals of PhD.

## Writing a custom syntax highlighter

A syntax highlighter for PhD is nothing more than a simple PHP class
that has two methods: `factory` and `highlight`.

`factory` is static, takes the format name (i.e. `pdf`, `xhtml`,
`troff`) as its only parameter and returns the highlighter instance object
for the given format. The method is called for each output format the
documentation is rendered to.

`highlight` takes three parameters: `text`, `role` and `format`. It is
called whenever a piece of source code needs to be highlighted and
expects the highlighted source code to be returned in the format
of the current rendering format.

Take a look at the provided highlighters, `phpdotnet\phd\Highlighter`,
`phpdotnet\phd\Highlighter_GeSHi` and
`phpdotnet\phd\Highlighter_GeSHi11x`. They will serve as good examples
on how to implement your own highlighter.

Once you wrote your custom source code highlighting class, it's time to
[try it out](#source-code-highlighter).
