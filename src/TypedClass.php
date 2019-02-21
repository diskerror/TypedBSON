<?php

/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           \Diskerror\TypedBSON\TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\TypedBSON;

use Diskerror\Typed\ArrayOptions;
use Diskerror\Typed\TypedClass as TC;

abstract class TypedClass extends TC
{
	use AbstractTrait;

	/**
	 * String representation of PHP object.
	 *
	 * This serialization, as opposed to JSON or BSON, does not unwrap the
	 * structured data. It only omits data that is part of the class definition.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		$toSerialize = [
			'_arrayOptions' => $this->_arrayOptions,
			'_jsonOptions'  => $this->_jsonOptions,
			'_bsonOptions'  => $this->_bsonOptions,
		];
		foreach ($this->_publicNames as $k) {
			$toSerialize[$k] = $this->{$k};
		}

		return serialize($toSerialize);
	}

	/**
	 * Called automatically by MongoDB when a document has a field named
	 * "__pclass".
	 *
	 * Since zero, null, false, or empty strings can be omitted from the
	 * serialized data stored in Mongo this method prevents non-empty defaults
	 * from being written to the restored members.
	 *
	 * @param array $data
	 */
	public function bsonUnserialize(array $data): void
	{
		$this->_initArrayOptions();
		$this->_initMetaData();
		$this->_initProperties();
		foreach ($this->_publicNames as $publicName) {
			$this->_setByName($publicName, array_key_exists($publicName, $data) ? $data[$publicName] : '');
		}
	}

	/**
	 * @param ArrayOptions $arrayOptions
	 *
	 * @return array
	 */
	protected function _toArray(ArrayOptions $arrayOptions): array
	{
		/** @var array $arr */
		$arr = parent::_toArray($arrayOptions);

		if ($arrayOptions->has(ArrayOptions::NO_CAST_BSON)) {
			foreach ($this->_publicNames as $k) {
				if (strpos(get_class($this->{$k}), 'MongoDB\\BSON') === 0) {
					//	MongoDB\BSON were converted so put them back here.
					$arr[$k] = $this->{$k};
				}
			}
		}

		return $arr;
	}
}
