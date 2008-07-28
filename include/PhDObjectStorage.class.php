<?php
class PhDObjectStorage extends SplObjectStorage {
	protected static $r = array();

	public function attach($obj, $inf = array()) {
		if (!($obj instanceof PhDFormat)) {
			throw new InvalidArgumentException("Only classess inheriting PhDFormat supported");
		}
		if (empty($inf)) {
			$inf = array(
				XMLReader::ELEMENT => $obj->getElementMap(),
				XMLReader::TEXT    => $obj->getTextMap(),
			);
		}
		parent::attach($obj, $inf);
	}
	final protected static function setReader(PhDReader $r) {
		self::$r[] = $r;
	}
	final protected function getReader() {
		return end(self::$r);
	}
	final protected function popReader() {
		return array_pop(self::$r);
	}
}

