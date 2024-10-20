<?php
namespace phpdotnet\phd;

// @php_dir@ gets replaced by pear with the install dir. use __DIR__ when
// running from SVN
define("__INSTALLDIR__", "@php_dir@" == "@"."php_dir@" ? dirname(__DIR__, 2) : "@php_dir@");

// PhD verbose flags
define('VERBOSE_ERRORS',                 (E_USER_DEPRECATED            << 1) - 1);

// Informationals
define('VERBOSE_INDEXING',               E_USER_DEPRECATED             << 1);
define('VERBOSE_FORMAT_RENDERING',       VERBOSE_INDEXING              << 1);
define('VERBOSE_THEME_RENDERING',        VERBOSE_FORMAT_RENDERING      << 1);
define('VERBOSE_RENDER_STYLE',           VERBOSE_THEME_RENDERING       << 1);
define('VERBOSE_PARTIAL_READING',        VERBOSE_RENDER_STYLE          << 1);
define('VERBOSE_PARTIAL_CHILD_READING',  VERBOSE_PARTIAL_READING       << 1);
define('VERBOSE_TOC_WRITING',            VERBOSE_PARTIAL_CHILD_READING << 1);
define('VERBOSE_CHUNK_WRITING',          VERBOSE_TOC_WRITING           << 1);
define('VERBOSE_MESSAGES',               VERBOSE_CHUNK_WRITING         << 1);

define('VERBOSE_INFO',                   ((VERBOSE_MESSAGES            << 1) - 1) & ~VERBOSE_ERRORS);


// Warnings
define('VERBOSE_NOVERSION',              VERBOSE_MESSAGES              << 1);
define('VERBOSE_BROKEN_LINKS',           VERBOSE_NOVERSION             << 1);
define('VERBOSE_MISSING_ATTRIBUTES',     VERBOSE_BROKEN_LINKS          << 1);
define('VERBOSE_OLD_LIBXML',             VERBOSE_MISSING_ATTRIBUTES    << 1);

define('VERBOSE_WARNINGS',               ((VERBOSE_OLD_LIBXML  << 1) - 1) & ~VERBOSE_ERRORS & ~VERBOSE_INFO);


define('VERBOSE_ALL',                    (VERBOSE_OLD_LIBXML   << 1) - 1);
define('VERBOSE_DEFAULT',                (VERBOSE_ALL^(VERBOSE_PARTIAL_CHILD_READING|VERBOSE_CHUNK_WRITING|VERBOSE_WARNINGS|VERBOSE_TOC_WRITING)));
