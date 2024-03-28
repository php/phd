#!@php_bin@
<?php
namespace phpdotnet\phd;

// @php_dir@ gets replaced by pear with the install dir. use __DIR__ when
// running from SVN
if (!defined("__INSTALLDIR__")) {
    define("__INSTALLDIR__", '@php_dir@' == '@'.'php_dir@' ? __DIR__ : '@php_dir@');
}

require_once __INSTALLDIR__ . '/phpdotnet/phd/Autoloader.php';
require_once __INSTALLDIR__ . '/phpdotnet/phd/functions.php';

spl_autoload_register(array(__NAMESPACE__ . "\\Autoloader", "autoload"));


$conf = array();
if (file_exists("phd.config.php")) {
    $conf = include "phd.config.php";
    Config::init($conf);
    v("Loaded config from existing file", VERBOSE_MESSAGES);
} else {
    // need to init regardless so we get package-dirs from the include-path
    Config::init(array());
}

$packageHandlers = array();
foreach (Config::getSupportedPackages() as $package) {
    if ($handler = Format_Factory::createFactory($package)->getOptionsHandler()) {
        $packageHandlers[strtolower($package)] = $handler;
    }
}
$optionsParser = new Options_Parser(
    new Options_Handler(new Config, new Package_Generic_Factory),
    ...$packageHandlers
);
$commandLineOptions = $optionsParser->getopt();

Config::init($commandLineOptions);

/* If no docbook file was passed, die */
if (!is_dir(Config::xml_root()) || !is_file(Config::xml_file())) {
    trigger_error("No Docbook file given. Specify it on the command line with --docbook.", E_USER_ERROR);
}
if (!file_exists(Config::output_dir())) {
    v("Creating output directory..", VERBOSE_MESSAGES);
    if (!mkdir(Config::output_dir(), 0777, True)) {
        v("Can't create output directory : %s", Config::output_dir(), E_USER_ERROR);
    }
} elseif (!is_dir(Config::output_dir())) {
    v("Output directory is not a file?", E_USER_ERROR);
}

// This needs to be moved. Preferably into the PHP package.
if (!$conf) {
    Config::init(array(
        "lang_dir"  => __INSTALLDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR
                        . "phd" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR
                        . "langs" . DIRECTORY_SEPARATOR,
        "phpweb_version_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'version.xml',
        "phpweb_acronym_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
        "phpweb_sources_filename" => Config::xml_root() . DIRECTORY_SEPARATOR . 'sources.xml',
    ));
}

if (Config::saveconfig()) {
    v("Writing the config file", VERBOSE_MESSAGES);
    file_put_contents("phd.config.php", "<?php\nreturn " . var_export(Config::getAllFiltered(), 1) . ";");
}

if (Config::quit()) {
    exit(0);
}

function make_reader() {
    //Partial Rendering
    $idlist = Config::render_ids() + Config::skip_ids();
    if (!empty($idlist)) {
        v("Running partial build", VERBOSE_RENDER_STYLE);

        $parents = [];
        if (file_exists(Config::output_dir() . "index.sqlite")) {
            $sqlite = new \SQLite3(Config::output_dir() . "index.sqlite");
            // Fetch all ancestors of the ids we should render
            foreach(Config::render_ids() as $p => $v) {
                do {
                    $id = $sqlite->escapeString($p);
                    $row = $sqlite->query("SELECT parent_id FROM ids WHERE docbook_id = '$id'")->fetchArray(SQLITE3_ASSOC);
                    if ($row["parent_id"]) {
                        $parents[] = $p = $row["parent_id"];
                        continue;
                    }
                    break;
                } while(1);
            }
        }

        $reader = new Reader_Partial(
            Config::render_ids(),
            Config::skip_ids(),
            $parents
        );
    } else {
        v("Running full build", VERBOSE_RENDER_STYLE);
        $reader = new Reader();
    }
    return $reader;
}

$render = new Render();

// Set reader LIBXML options
$readerOpts = LIBXML_PARSEHUGE;
if (Config::process_xincludes()) {
    $readerOpts |= LIBXML_XINCLUDE;
}

if (file_exists(Config::output_dir() . 'index.sqlite')) {
    $db = new \SQLite3(Config::output_dir() . 'index.sqlite');
} else {
    $db = null;
}

// Indexing
if (requireIndexing(new Config, $db)) {
    v("Indexing...", VERBOSE_INDEXING);
    if (Config::memoryindex()) {
        $db = new \SQLite3(":memory:");
    } else {
        $db = $db ?? new \SQLite3(Config::output_dir() . 'index.sqlite');
    }
    // Create indexer
    $indexRepository = new IndexRepository($db);
    $format = new Index($indexRepository);
    $render->attach($format);

    $reader = make_reader();
    $reader->open(Config::xml_file(), NULL, $readerOpts);
    $render->execute($reader);

    if (Config::memoryindex()) {
        Config::set_indexcache($db);
    }

    $render->detach($format);

    v("Indexing done", VERBOSE_INDEXING);
} else {
    v("Skipping indexing", VERBOSE_INDEXING);
}

foreach((array)Config::package() as $package) {
    $factory = Format_Factory::createFactory($package);

    // Default to all output formats specified by the package
    if (count(Config::output_format()) == 0) {
        Config::set_output_format((array)$factory->getOutputFormats());
    }

    // Register the formats
    foreach (Config::output_format() as $format) {
        $render->attach($factory->createFormat($format));
    }
}

// Render formats
$reader = make_reader();
$reader->open(Config::xml_file(), NULL, $readerOpts);
foreach($render as $format) {
    $format->notify(Render::VERBOSE, true);
}
$render->execute($reader);

v("Finished rendering", VERBOSE_FORMAT_RENDERING);
