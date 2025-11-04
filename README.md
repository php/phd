# PhD - PHP DocBook

## About PhD

PhD is PHP's DocBook rendering system
which is used to convert the PHP Manual into different output formats.

If you would like to contribute to PHP's documentation please refer to the
[contribution guidelines](https://github.com/php/doc-base/blob/master/CONTRIBUTING_DOCS.md).

If you would like to know more about how PHP's documentation is built
and what the different parts of its pipeline are, please refer to the
[documentation overview](https://github.com/php/doc-base/blob/master/OVERVIEW.md).

## Requirements

- PHP 8.1+
- DOM, libXML2, XMLReader and SQLite3.


## Running the test suite

Running the test suite uses the same test runner as the `php-src` repository.

```shell
$ make test
```

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
</details><br>

To check the documentation and combine it into one file,
you need to clone PHP's `doc-base` repository

```shell
$ git clone https://github.com/php/doc-base
```

and run `configure.php`

```shell
$ php doc-base/configure.php
```

which will generate a `.manual.xml` file in the `doc-base` directory.

To render the documentation in `xhtml` format
into the default `./output/` directory:

```shell
$ php phd/render.php -d doc-base/.manual.xml -P PHP -f xhtml
```

`xhtml` files are standalone files that can be opened directly in a browser.
To render the documentation in the same `php` format used on the `php.net` website:

```shell
$ php phd/render.php -d doc-base/.manual.xml -P PHP -f php
```

Please refer to the appropriate section of the
[contribution guidelines](https://github.com/php/doc-base/blob/master/CONTRIBUTING_DOCS.md#more-complex-changes--building-the-php-documentation)
on setting up a local mirror of the PHP documentation.

## PhD's rendering options

The following sections list PhD's the most frequently used options.
To see the list of all options run PhD with the `-h` \ `--help` option.

### Input

`-d` \ `--docbook` `<filename>`    The Docbook file to render

`-p` \ `--partial` `<id[=bool]>`   Partial rendering: the ID to render, optionally skipping its children chunks (default to true; render children)

`-s` \ `--skip` `<id[=bool]>`      Partial rendering: the ID to skip, optionally skipping its children chunks (default to true; skip children)

### Output

`-P` \ `--package` `<packagename>`  The package to use.
If a package is specified without a format the input file is rendered
in every format of the package.
For a list of supported packages, see the list of
[Supported output formats](#supported-output-formats).

`-f` \ `--format` `<formatname>`    The build format to use.
If no package is specified, the appropriate format of the `Generic` package is used.
For a list of supported formats, see the list of
[Supported output formats](#supported-output-formats)

`-o` \ `--output` `<directory>`     The output directory (default: .)

### Indexing

`-I` \ `--noindex`       Do not index before rendering but load from cache. (default: false)

`-M` \ `--memoryindex`   Do not save indexing into a file, store it in memory. (default: false)

`-r` \ `--forceindex`    Force re-indexing. (default: false)

### Information

`-h` \ `--help`          Lists all available options.

`-l` \ `--list`          Lists all supported packages and formats.


## Syntax highlighting

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
you can write a custom syntax highlighter.


### Writing a custom syntax highlighter

A syntax highlighter for PhD is nothing more than a simple PHP class
that has two methods: `factory` and `highlight`.

`factory` is static, takes the format name (i.e. `xhtml`,
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
[try it out](#syntax-highlighting).

## Supported output formats

PhD currently supports the following output formats:

<table>
  <tr>
    <td><b>PACKAGE</b></td>
    <td><b>Generic</b></td>
    <td><b>IDE</b></td>
    <td><b>PEAR</b></td>
    <td><b>PHP</b></td>
  </tr>
  <tr>
    <td rowspan="12"><b>FORMAT</b></td>
    <td>xhtml</td>
    <td>xml</td>
    <td>xhtml</td>
    <td>xhtml</td>
  </tr>
  <tr>
    <td>bigxhtml</td>
    <td>funclist</td>
    <td>bigxhtml</td>
    <td>bigxhtml</td>
  </tr>
  <tr>
    <td>manpage</td>
    <td>json</td>
    <td>php</td>
    <td>php</td>
  </tr>
  <tr>
    <td rowspan="9"></td>
    <td>php</td>
    <td>chm</td>
    <td>chm</td>
  </tr>
  <tr>
    <td>phpstub</td>
    <td>tocfeed</td>
    <td>tocfeed</td>
  </tr>
  <tr>
    <td>sqlite</td>
    <td rowspan="7"></td>
    <td>manpage</td>
  </tr>
  <tr>
    <td rowspan="6"></td>
    <td>howto</td>
  <tr>
    <td>kdevelop</td>
  </tr>
  <tr>
    <td>epub</td>
  </tr>
  <tr>
    <td>enhancedchm</td>
  </tr>
</table>
