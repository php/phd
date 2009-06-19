<?php
namespace phpdotnet\phd;

class ObjectStorage extends \SplObjectStorage
{
	protected static $r = array();

	public function attach($obj, $inf = array()) {
		if (!($obj instanceof Format_Enterprise)) {
			throw new InvalidArgumentException(
                'Only classess inheriting ' . __NAMESPACE__ . '\\Format_Enterprise supported'
            );
		}
		if (empty($inf)) {
			$inf = array(
				XMLReader::ELEMENT => $obj->getElementMap(),
				XMLReader::TEXT    => $obj->getTextMap(),
			);
		}
		parent::attach($obj, $inf);
	}
	final protected static function setReader(Reader_Enterprise $r) {
		self::$r[] = $r;
	}
	final protected function getReader() {
		return end(self::$r);
	}
	final protected function popReader() {
		return array_pop(self::$r);
	}
}

