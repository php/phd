<?php
namespace phpdotnet\phd;

class ObjectStorage extends \SplObjectStorage
{
	public function attach($obj, $inf = array()): void {
		if (!($obj instanceof Format)) {
			throw new \InvalidArgumentException(
                'Only classess inheriting ' . Format::class . ' supported'
            );
		}
		if (empty($inf)) {
			$inf = array(
				\XMLReader::ELEMENT => $obj->getElementMap(),
				\XMLReader::TEXT    => $obj->getTextMap(),
			);
		}
		parent::attach($obj, $inf);
	}
}


/*
* vim600: sw=4 ts=4 syntax=php et
* vim<600: sw=4 ts=4
*/
