<?php

/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           \Diskerror\TypedBSON\TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\TypedBSON;

use DateTimeInterface;
use Diskerror\Typed\ArrayOptions;
use Diskerror\Typed\Date;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

abstract class TypedClass extends \Diskerror\Typed\TypedClass
{
	/**
	 * Holds default options for "toArray" customizations when used by json_encode.
	 *
	 * @var int
	 */
	protected $_jsonOptionDefaults = ArrayOptions::SET_ALL_YES;

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
		foreach ($this->_getPublicNames() as $publicName) {
			$toSerialize[$publicName] = $this->{$publicName};
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
		foreach ($this->_getPublicNames() as $publicName) {
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

		if ($arrayOptions->has(ArrayOptions::NO_CAST_BSON | ArrayOptions::CAST_DATETIME_TO_BSON)) {
			foreach ($this->_getPublicNames() as $k) {
				/**
				 * MongoDB\BSON objects were converted above so put them back.
				 */
				if (
					$arrayOptions->has(ArrayOptions::NO_CAST_BSON) &&
					is_object($this->{$k}) &&
					strpos(get_class($this->{$k}), 'MongoDB\\BSON') === 0
				) {
					$arr[$k] = $this->{$k};
				}

				/**
				 * Convert all DateTimeInterface classes (except Typed\Date) into a UTCDateTime class.
				 */
				if (
					$arrayOptions->has(ArrayOptions::CAST_DATETIME_TO_BSON) &&
					$this->{$k} instanceof DateTimeInterface &&
					!($this->{$k} instanceof Date)
				) {
					$arr[$k] = new UTCDateTime($this->{$k});
				}
			}
		}

		/**
		 * Cast "_id" string or number into a MongoDB\BSON\ObjectId.
		 */
		if (
			$arrayOptions->has(ArrayOptions::CAST_ID_TO_OBJECTID) &&
			property_exists($this, '_id') &&
			is_scalar($this->_id)
		) {
			$arr['_id'] = new ObjectId((string)$this->_id);
		}

		return $arr;
	}
}
