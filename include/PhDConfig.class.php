<?php
define('VERBOSE_INDEXING',               0x01);
define('VERBOSE_FORMAT_RENDERING',       0x02);
define('VERBOSE_THEME_RENDERING',        0x04);
define('VERBOSE_RENDER_STYLE',           0x08);
define('VERBOSE_PARTIAL_READING',        0x10);
define('VERBOSE_PARTIAL_CHILD_READING',  0x20);
define('VERBOSE_TOC_WRITING',            0x40);
define('VERBOSE_CHUNK_WRITING',          0x80);

define('VERBOSE_ALL',                    0xFF);


class PhDConfig {
	private static $opts = array(
		"output_format"       => array(
		),
		"output_theme"        => array(
		),
		"chunk_extra"         => array(
		),
		"index"               => true,
		"xml_root"            => __DIR__,
		"xml_file"            => false,
		"language"            => "en",
		"fallback_language"   => "en",
		"build_log_file"      => false,
		"verbose"             => VERBOSE_ALL,
		"date_format"         => "H:i:s",
		"render_ids"          => array(
		),
		"skip_ids"            => array(
		),
		"output_dir"          => __DIR__,
		"lang_dir"            => __DIR__,
	);

	public static function init(array $a) {
		self::$opts = array_merge(self::$opts, (array)$a);
	}
	public static function get($opt) {
		if (!is_string($opt)) {
			throw new UnexpectedValueException("Excpecting a string");
		}
		if (!isset(self::$opts[$opt])) {
			throw new UnexpectedValueException("Unknown option: $opt");
		}
		return self::$opts[$opt];
	}
}


