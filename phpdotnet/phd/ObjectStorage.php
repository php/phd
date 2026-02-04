<?php
namespace phpdotnet\phd;

class ObjectStorage extends \SplObjectStorage
{
	public function attach(object $object, mixed $info = []): void {
		if (!($object instanceof Format)) {
			throw new \InvalidArgumentException(
                'Only classess inheriting ' . __NAMESPACE__ . '\\Format supported'
            );
		}
		if (empty($info)) {
			$info = [
				\XMLReader::ELEMENT => $object->getElementMap(),
				\XMLReader::TEXT    => $object->getTextMap(),
            ];
		}

        $this->offsetSet($object, $info);
	}
}



