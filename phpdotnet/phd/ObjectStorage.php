<?php
namespace phpdotnet\phd;

class ObjectStorage extends \SplObjectStorage
{
	public function attach(object $object, mixed $info = array()): void {
		if (!($object instanceof Format)) {
			throw new \InvalidArgumentException(
                'Only classess inheriting ' . __NAMESPACE__ . '\\Format supported'
            );
		}
		if (empty($info)) {
			$info = array(
				\XMLReader::ELEMENT => $object->getElementMap(),
				\XMLReader::TEXT    => $object->getTextMap(),
			);
		}
		parent::attach($object, $info);

        if (PHP_VERSION_ID < 80500) {
            parent::attach($object, $info);
        }

        $this->offsetSet($object, $info);
	}
}



