# PhD - PHP DocBook

## About PhD

PhD is PHP's very own DocBook 5 rendering system. It is used to convert
the PHP Manual and PEAR Documentation into different output formats like
XHTML, PDF, Man pages and CHM.

The goal of PhD is to become a fast, general DocBook rendering system.
At the moment of writing, PhD is already very fast: It can create the
chunked version of PEAR's manual (some 3000 files) in less than a minute
on a 2GHz system. It also renders the PHP and PEAR manual flawlessly. It
does not support every DocBook 5 tag yet, and using it to render own
DocBook files may need some tweaks.

## Requirements

Requirements:
- PHP 8.0+
- DOM, libXML2, XMLReader and SQLite3.

## Quickstart

Get PhD from the source repository

```shell
$ git clone https://github.com/php/phd.git
```

and render your documentation file (`doc-base/.manual.xml` in this example)
in `php` format

```shell
$ php phd/render.php --docbook doc-base/.manual.xml --package PHP --format php
```


## Installation

PhD is distributed via an own PEAR channel, [doc.php.net](http://doc.php.net).
Using it is also the easiest way to get it.

### Installation via PEAR

> **_NOTE:_**
> You need a working [PEAR
> installation](https://pear.php.net/manual/en/installation.php).

To install the latest version of PhD:

```shell
$ pear install doc.php.net/phd
```

Verify the installation:
```shell
$ phd --version
PhD Version: 1.0.0-stable
PHP Version: 5.3.3
Copyright(c) 2007-2024 The PHP Documentation Group
```

To see list all available PhD packages:

```shell
$ pear remote-list -c doc.php.net
```

Installing the PhD Packages:

```shell
$ pear install doc.php.net/phd_php
Starting to download PhD_PHP-1.0.0.tgz (18,948 bytes)
[...]
install ok: channel://doc.php.net/PhD_PHP-1.0.0

$ pear install doc.php.net/phd_pear
downloading PhD_PEAR-1.0.0.tgz ...
[...]
install ok: channel://doc.php.net/PhD_PEAR-1.0.0
```

That's it!

### Installation from Git

To get the latest and greatest features that have not been released yet,
you can use PhD from Git.

```shell
$ git clone https://github.com/php/phd.git
... output

$ pear install package.xml package_generic.xml package_php.xml package_pear.xml
[...]
install ok: channel://doc.php.net/PhD-1.0.1
install ok: channel://doc.php.net/PhD_Generic-1.0.1
install ok: channel://doc.php.net/PhD_PHP-1.0.1
install ok: channel://doc.php.net/PhD_PEAR-1.0.1
```

To install the standalone Packages:
```shell
for i in package_*.xml; do pear install $i; done
```

```shell
$ phd --version
PhD Version: phd-from-svn
PHP Version: 5.3.3-dev
Copyright(c) 2007-2010 The PHP Documentation Group
```

And now you're done.

### Using PhD without installation

You can use PhD without installing it or PEAR by simply cloning the git repository:

```shell
$ git clone https://github.com/php/phd.git
```
and running it with

```shell
$ php phd/render.php
```

You can now either use PhD using the above line
or you can also create an alias for it.


# Using PhD to render documentation

## Rendering the PHP Documentation Sources

To get the PHP documentation sources, simply clone them from the official GitHub
repositories.

To clone the English documentation:
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

To prepare the documentation, `cd` to the phpdoc directory, and run
configure.php.

```shell
$ php doc-base/configure.php
```

This process will generate a .manual.xml file in the current directory,
which is what we need for building the docs. Now we're ready to proceed
with running PhD to generate the PHP docs.

To quickly become familiar with using PhD, you can download the PHP
documentation sources and render those.
Running PhD to render the docs is
surprisingly simple, so we'll start with that.

```shell
$ phd -d doc-base/.manual.xml -P PHP
```


After a running for a few moments, PhD will generate all the output
formats of the PHP Package into the default `./output/` directory.

So now that you've seen the fruits of your labor, let's take a closer
look at PhD and see what capabilities are available to us.

```shell
$ phd --help
PhD version: 1.1.6
Copyright(c) 2007-2013 The PHP Documentation Group

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

Most options can be passed multiple times for greater effect.

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


> **_NOTE:_**
> The format packages are provided by separate PEAR packages
> (doc.php.net/PhD_Generic, doc.php.net/PhD_IDE, doc.php.net/PhD_PEAR
> and doc.php.net/PhD_PHP) where only the Generic is installed by
> default.

You can tell by the output of the `--list` option that PhD can also be
used to render the docs as a PDF file, or as Unix Man Pages.

To select a format and package, you must use the `-f [formatName]` and
`-P [packageName]` options.

```shell
$ phd -f manpage -P PHP -d .manual.xml
```

This command will output the documentation for PHP functions in the Unix
Man page format.

## Compiling the PhD guide

The PhD guide is this manual you are reading currently. It lives in
PhD's Git repository under `docs/phd-guide/phd-guide.xml`. If you
installed PhD from Git, you already have it. Otherwise, get it:

```shell
$ svn checkout http://svn.php.net:/repository/phd/trunk/docs/phd-guide
U phd/docs/phd-guide/phd-guide.xml
```

Now you have everything you need. Just type

```shell
$ cd phd/docs/phd-guide/
$ phd -f bigxhtml -d phd-guide.xml
```

There should be an .html file in the directory now. View it with a
browser!

That's all to say. This way you can render your own docbook files, too.

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
itself. It is only able to highlight PHP code and nothing else.

If your documentation contains other types of source code or markup,
like XML, HTML, Javascript or any other language, you should try the
[GeSHi](http://qbnz.com/highlighter/) highlighter that is shipped with
PhD:

1.  Install GeSHi from the MediaWiki PEAR channel:

```shell
$ pear channel-discover mediawiki.googlecode.com/svn
$ pear install mediawiki/geshi
```

2.  Use the GeSHi syntax highlighting class when rendering your
    documentation:

```shell
$ phd -g 'phpdotnet\phd\Highlighter_GeSHi' -d phd-guide.xml
```

If you have GeSHi version 1.1.x installed, you should use the
`phpdotnet\phd\Highlighter_GeSHi11x` highlighter, which is adapted to
GeSHi's new API.

Apart from using the highlighter shipped with PhD, you can [build your
own highlighters](#phd-extension-highlighter).

# DocBook extensions

PhD has been tailored for PHP and PEAR manuals. To make writing
documentation as easy as possible, some own tags have been added to the
DTD.

All extensions live in their own XML namespace "`phd:`" which resolves
to <http://www.php.net/ns/phd>. When using one of the attributes or tags
described here, remember to set the namespace:

```xml
xmlns:phd="http://www.php.net/ns/phd"
```

## General DocBook extensions

The extensions listed here are available in all PhD themes and formats.

### Manual chunking with "phd:chunk" (Attribute)

PhD automatically chooses which sections, chapters or other tags get
their own file (chunk) when using a chunked theme. Sometimes the result
of this automatism is not optimal and you want to fine-tune it. The
attribute "phd:chunk" is offered as solution by PhD.

#### Allowed values

`phd:chunk` may have values `true` and `false`. They force the element
to be chunked or not.

#### Allowed in

`phd:chunk` may be used in every tag that accepts
[db.common.attributes](https://www.docbook.org/tdg5/en/html/ref-elements.html#common.attributes).

#### Example

```xml
<?xml version="1.0" encoding="utf-8"?>
<preface xmlns="http://docbook.org/ns/docbook"
    xmlns:phd="http://www.php.net/ns/phd"
    xml:id="preface"
    phd:chunk="false"
>
    <info>
    <title>Preface</title>
    ..
    </info>
    ..
</preface>
```


### Generating Table Of Contents: \<phd:toc\> (Tag)

To manually insert a Table Of Contents (TOC) that creates a list of
links to children elements of a specified tag.

phd:toc-depth

#### Allowed in

`<phd:toc>` is can be used everywhere `<para>` is allowed.

#### Children

You can add a title with `<title>`.

#### Attributes

| Attribute name | Description | Default value |
|----|----|----|
| phd:element | ID of the element whose children shall be linked | *none* |
| phd:toc-depth | Depth of the TOC/Number of levels | `1` |

Attributes for \<phd:toc\>

## PEAR specific DocBook extensions

The DocBook extensions listed here are only available when using a PEAR
theme.

### Linking to PEAR API documentation: \<phd:pearapi\> (Tag)

A large part of the PEAR manual is about packages and how to use them.
Package authors often find they need to link to the API documentation of
a specific method, variable or class of their package. To ease the
linking process, the `<phd:pearapi>` tag was introduced.

You can let PhD automatically create the link text by just closing the
tag, or specify the tag text via the tag's content.

#### Package links

`phd:package` name is put into the attribute, any text:

```xml
<phd:pearapi phd:package="HTML_QuickForm"/>
<phd:pearapi phd:package="HTML_QuickForm">some text</phd:pearapi>
```

    HTML_QuickForm
    some text

#### Class links

Class name as `phd:linkend` attribute value.

```xml
<phd:pearapi phd:package="HTML_QuickForm" phd:linkend="HTML_QuickForm_element"/>
<phd:pearapi phd:package="HTML_QuickForm" phd:linkend="HTML_QuickForm_element">some text</phd:pearapi>
```

    HTML_QuickForm_element
    some text

#### Class method links

Class and method name as `phd:linkend` text, separated by a double
colon.

```xml
<phd:pearapi phd:package="HTML_QuickForm" phd:linkend="HTML_QuickForm_element::setName"/>
<phd:pearapi phd:package="HTML_QuickForm" phd:linkend="HTML_QuickForm_element::setName">some text</phd:pearapi>
```


    HTML_QuickForm_element::setName()
    some text

#### Class variable links

Class and variable name as `phd:linkend` text, separated by a double
colon and a dollar sign before the variable name.

```xml
<phd:pearapi phd:package="Net_Geo" phd:linkend="Net_Geo::$cache_ttl"/>
<phd:pearapi phd:package="Net_Geo" phd:linkend="Net_Geo::$cache_ttl">some text</phd:pearapi>
```

    Net_Geo::$cache_ttl
    some text

# Extending PhD

Written in PHP, PhD is easy to hack on and easy to extend. It provides
command line parameters to use custom code without changing PhD
internals, like source code highlighters.

## Writing an own syntax highlighter

A syntax highlighter for PhD is nothing more than a simple PHP class
that has two methods, a `factory` and `highlight`.

`factory` is static and takes the format name (i.e. `pdf`, `xhtml`,
`troff`) as only parameter. It returns the highlighter instance object
for the given format. The method is called for each output format the
documentation is rendered to.

`highlight` takes three parameters, `text`, `role` and `format`. It is
called whenever a piece of source code needs to be highlighted and
expects the highlighted source code to be returned in whatever format
the current rendering format is expected to be.

Take a look at the provided highlighters, `phpdotnet\phd\Highlighter`,
`phpdotnet\phd\Highlighter_GeSHi` and
`phpdotnet\phd\Highlighter_GeSHi11x`. They will serve as good examples
how to implement your own highlighter.

Once you wrote your custom source code highlighting class, it's time to
[try it out](#render-custom-highlighter).

# Links

Some other articles for further reading. Latest are on top.

-   [PhD 0.1RC1
    released](https://bjori.blogspot.com/2007/10/phd-php-based-docbook-renderer-rc1.html)
    by Hannes Magnusson (PhD 0.1RC1)

Copyright(c) 2007-2024 The PHP Documentation Team
