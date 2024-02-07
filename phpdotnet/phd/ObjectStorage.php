<?php
namespace phpdotnet\phd;

class ObjectStorage extends \SplObjectStorage
{
	public function attach($obj, $inf = array()): void {
		if (!($obj instanceof Format)) {
			throw new \InvalidArgumentException(
                'Only classess inheriting ' . __NAMESPACE__ . '\\Format supported'
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



