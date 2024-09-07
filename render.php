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
Autoloader::setPackageDirs([__INSTALLDIR__]);

spl_autoload_register(array(__NAMESPACE__ . "\\Autoloader", "autoload"));

$config = new Config;

$conf = array();
if (file_exists("phd.config.php")) {
    $conf = include "phd.config.php";
    $config->init($conf);
    v("Loaded config from existing file", VERBOSE_MESSAGES);
} else {
    // need to init regardless so we get package-dirs from the include-path
    $config->init(array());
}

$packageHandlers = array();
foreach ($config->getSupportedPackages() as $package) {
    if ($handler = Format_Factory::createFactory($package)->getOptionsHandler()) {
        $packageHandlers[strtolower($package)] = $handler;
    }
}
$optionsParser = new Options_Parser(
    new Options_Handler($config, new Package_Generic_Factory),
    ...$packageHandlers
);
$commandLineOptions = $optionsParser->getopt();

$config->init($commandLineOptions);

if (isset($commandLineOptions["package_dirs"])) {
    Autoloader::setPackageDirs($config->package_dirs());
}

/* If no docbook file was passed, die */
if (!is_dir($config->xml_root()) || !is_file($config->xml_file())) {
    trigger_error("No Docbook file given. Specify it on the command line with --docbook.", E_USER_ERROR);
}
if (!file_exists($config->output_dir())) {
    v("Creating output directory..", VERBOSE_MESSAGES);
    if (!mkdir($config->output_dir(), 0777, True)) {
        v("Can't create output directory : %s", $config->output_dir(), E_USER_ERROR);
    }
    v("Output directory created", VERBOSE_MESSAGES);
} elseif (!is_dir($config->output_dir())) {
    v("Output directory is not a file?", E_USER_ERROR);
}

// This needs to be moved. Preferably into the PHP package.
if (!$conf) {
    $config->init(array(
        "lang_dir"  => __INSTALLDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR
                        . "phd" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR
                        . "langs" . DIRECTORY_SEPARATOR,
        "phpweb_version_filename" => $config->xml_root() . DIRECTORY_SEPARATOR . 'version.xml',
        "phpweb_acronym_filename" => $config->xml_root() . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
        "phpweb_sources_filename" => $config->xml_root() . DIRECTORY_SEPARATOR . 'sources.xml',
        "phpweb_history_filename" => $config->xml_root() . DIRECTORY_SEPARATOR . 'fileModHistory.php',
    ));
}

if ($config->saveconfig()) {
    v("Writing the config file", VERBOSE_MESSAGES);
    file_put_contents("phd.config.php", "<?php\nreturn " . var_export($config->getAllFiltered(), 1) . ";");
}

if ($config->quit()) {
    exit(0);
}

function make_reader(Config $config) {
    //Partial Rendering
    $idlist = $config->render_ids() + $config->skip_ids();
    if (!empty($idlist)) {
        v("Running partial build", VERBOSE_RENDER_STYLE);

        $parents = [];
        if ($config->indexcache()) {
            $parents = $config->indexcache()->getParents($config->render_ids());
        }

        $reader = new Reader_Partial(
            $config->render_ids(),
            $config->skip_ids(),
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
if ($config->process_xincludes()) {
    $readerOpts |= LIBXML_XINCLUDE;
}

// Setup indexing database
if ($config->memoryindex()) {
    $db = new \SQLite3(":memory:");
    $initializeDb = true;
} else {
    $initializeDb = !file_exists($config->output_dir() . 'index.sqlite');
    $db = new \SQLite3($config->output_dir() . 'index.sqlite');
}
$indexRepository = new IndexRepository($db);
if ($initializeDb) {
    $indexRepository->init();
}
$config->set_indexcache($indexRepository);

// Indexing
if ($config->requiresIndexing()) {
    v("Indexing...", VERBOSE_INDEXING);
    // Create indexer
    $format = new Index($config->indexcache(), $config);
    $render->attach($format);

    $reader = make_reader($config);
    $reader->open($config->xml_file(), NULL, $readerOpts);
    $render->execute($reader);

    $render->detach($format);

    v("Indexing done", VERBOSE_INDEXING);
} else {
    v("Skipping indexing", VERBOSE_INDEXING);
}

foreach((array)$config->package() as $package) {
    $factory = Format_Factory::createFactory($package);

    // Default to all output formats specified by the package
    if (count($config->output_format()) == 0) {
        $config->set_output_format((array)$factory->getOutputFormats());
    }

    // Register the formats
    foreach ($config->output_format() as $format) {
        $render->attach($factory->createFormat($format, $config));
    }
}

// Render formats
$reader = make_reader($config);
$reader->open($config->xml_file(), NULL, $readerOpts);
foreach($render as $format) {
    $format->notify(Render::VERBOSE, true);
}
$render->execute($reader);

v("Finished rendering", VERBOSE_FORMAT_RENDERING);
