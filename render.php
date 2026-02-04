#!@php_bin@
<?php
namespace phpdotnet\phd;

require_once __DIR__ . '/phpdotnet/phd/constants.php';
require_once __INSTALLDIR__ . '/phpdotnet/phd/Autoloader.php';
Autoloader::setPackageDirs([__INSTALLDIR__]);

spl_autoload_register(array(__NAMESPACE__ . "\\Autoloader", "autoload"));

$config = new Config;

$outputHandler = new OutputHandler($config);

$errorHandler = new ErrorHandler($outputHandler);
$olderrrep = error_reporting();
error_reporting($olderrrep | VERBOSE_DEFAULT);
set_error_handler($errorHandler->handleError(...));

$conf = array();
if (file_exists("phd.config.php")) {
    $conf = include "phd.config.php";
    $config->init($conf);
    $outputHandler->v("Loaded config from existing file", VERBOSE_MESSAGES);
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
    new Options_Handler($config, new Package_Generic_Factory, $outputHandler),
    ...$packageHandlers
);
$commandLineOptions = $optionsParser->getopt();

$config->init($commandLineOptions);

if (isset($commandLineOptions["packageDirs"])) {
    Autoloader::setPackageDirs($config->packageDirs);
}

/* If no docbook file was passed, die */
if (!is_dir($config->xmlRoot) || !is_file($config->xmlFile)) {
    throw new \Error('No Docbook file given. Specify it on the command line with --docbook.');
}
if (!file_exists($config->outputDir)) {
    $outputHandler->v("Creating output directory..", VERBOSE_MESSAGES);
    if (!mkdir($config->outputDir, 0777, True)) {
        throw new \Error(vsprintf("Can't create output directory : %s", [$config->outputDir]));
    }
    $outputHandler->v("Output directory created", VERBOSE_MESSAGES);
} elseif (!is_dir($config->outputDir)) {
    throw new \Error('Output directory is not a file?');
}

// This needs to be moved. Preferably into the PHP package.
if (!$conf) {
    $config->init(array(
        "langDir"  => __INSTALLDIR__ . DIRECTORY_SEPARATOR . "phpdotnet" . DIRECTORY_SEPARATOR
                        . "phd" . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR
                        . "langs" . DIRECTORY_SEPARATOR,
        "phpwebVersionFilename" => $config->xmlRoot . DIRECTORY_SEPARATOR . 'version.xml',
        "phpwebAcronymFilename" => $config->xmlRoot . DIRECTORY_SEPARATOR . 'entities' . DIRECTORY_SEPARATOR . 'acronyms.xml',
        "phpwebSourcesFilename" => $config->xmlRoot . DIRECTORY_SEPARATOR . 'sources.xml',
        "phpwebHistoryFilename" => $config->xmlRoot . DIRECTORY_SEPARATOR . 'fileModHistory.php',
    ));
}

if ($config->saveConfig) {
    $outputHandler->v("Writing the config file", VERBOSE_MESSAGES);
    file_put_contents("phd.config.php", "<?php\nreturn " . var_export($config->getAllFiltered(), 1) . ";");
}

if ($config->quit) {
    exit(0);
}

$render = new Render();

// Set reader LIBXML options
$readerOpts = LIBXML_PARSEHUGE;
if ($config->processXincludes) {
    $readerOpts |= LIBXML_XINCLUDE;
}

// Setup indexing database
if ($config->memoryIndex) {
    $db = new \SQLite3(":memory:");
    $initializeDb = true;
} else {
    $initializeDb = !file_exists($config->outputDir . 'index.sqlite');
    $db = new \SQLite3($config->outputDir . 'index.sqlite');
}
$indexRepository = new IndexRepository($db);
if ($initializeDb) {
    $indexRepository->init();
}
$config->indexCache = $indexRepository;

// Indexing
if ($config->requiresIndexing()) {
    $outputHandler->v("Indexing...", VERBOSE_INDEXING);
    // Create indexer
    $format = new Index($config->indexCache, $config, $outputHandler);
    
    $render->attach($format);

    $outputHandler->v("Running full build", VERBOSE_RENDER_STYLE);
    $reader = new Reader($outputHandler);
    $reader->open($config->xmlFile, NULL, $readerOpts);
    $render->execute($reader);

    $render->offsetUnset($format);

    $outputHandler->v("Indexing done", VERBOSE_INDEXING);
} else {
    $outputHandler->v("Skipping indexing", VERBOSE_INDEXING);
}

foreach($config->package as $package) {
    $factory = Format_Factory::createFactory($package);

    // Default to all output formats specified by the package
    if (count($config->outputFormat) == 0) {
        $config->outputFormat = $factory->getOutputFormats();
    }

    // Register the formats
    foreach ($config->outputFormat as $format) {
        $render->attach($factory->createFormat($format, $config, $outputHandler));
    }
}

//Partial Rendering
$idlist = $config->renderIds + $config->skipIds;
if (!empty($idlist)) {
    $outputHandler->v("Running partial build", VERBOSE_RENDER_STYLE);

    $parents = [];
    if ($config->indexCache) {
        $parents = $config->indexCache->getParents($config->renderIds);
    }

    $reader = new Reader_Partial(
        $outputHandler,
        $config->renderIds,
        $config->skipIds,
        $parents,
    );
} else {
    $outputHandler->v("Running full build", VERBOSE_RENDER_STYLE);
    $reader = new Reader($outputHandler);
}

// Render formats
$reader->open($config->xmlFile, NULL, $readerOpts);
foreach($render as $format) {
    $format->notify(Render::VERBOSE, true);
}
$render->execute($reader);

$outputHandler->v("Finished rendering", VERBOSE_FORMAT_RENDERING);
