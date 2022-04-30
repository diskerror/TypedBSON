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
use Diskerror\Typed\Date;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;

abstract class TypedClass extends \Diskerror\Typed\TypedClass implements Persistable
{
	use TypedTrait;

	/**
	 * Called automatically by MongoDB.
	 *
	 * @return array
	 */
	public function bsonSerialize(): array
	{
		$omitEmpty       = $this->toBsonOptions->has(BsonOptions::OMIT_EMPTY);
		$omitDefaults    = $this->toBsonOptions->has(BsonOptions::OMIT_DEFAULTS);
		$dateToString    = $this->toBsonOptions->has(BsonOptions::DATE_OBJECT_TO_STRING);
		$objectsToString = $this->toBsonOptions->has(BsonOptions::ALL_OBJECTS_TO_STRING);

		$arr = [];
		foreach ($this->getPublicNames() as $pName) {
			if ($omitDefaults && $this->$pName == $this->_defaultValues[$pName]) {
				continue;
			}

			$v = $this->_getByName($pName);    //  AtomicInterface objects are returned as scalars.

			switch (gettype($v)) {
				case 'resource':
					continue 2;

				case 'object':
					switch (true) {
						case strpos(get_class($v), 'MongoDB\\BSON') === 0:
							break;

						case method_exists($v, 'bsonSerialize'):
							$v = $v->bsonSerialize();
							break;

						case $dateToString && is_a($v, \Diskerror\Typed\DateTime::class, true):
							$v = $v->jsonSerialize();
							break;

						case method_exists($v, 'jsonSerialize'):
							$v = $v->jsonSerialize();
							break;

						case method_exists($v, 'toArray'):
							$v = $v->toArray();
							break;

						case $objectsToString && method_exists($v, '__toString'):
							$v = $v->__toString();
							break;
					}
					break;

				//	nulls, bools, ints, floats, strings, and arrays
				default:
			}

			if ($omitEmpty && self::_isEmpty($v)) {
				continue;
			}

			$arr[$pName] = $v;
		}

		/**
		 * Cast "_id" string or number into a MongoDB\BSON\ObjectId.
		 */
		if (
			$this->toBsonOptions->has(BsonOptions::CAST_ID_TO_OBJECTID) &&
			array_key_exists('_id', $arr) && is_scalar($arr['_id'])
		) {
			if ($arr['_id'] == 0) {
				$arr['_id'] = new ObjectId();
			}
			else {
				$arr['_id'] = new ObjectId((string) $arr['_id']);
			}
		}

		return $arr;
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
		$this->_initToArrayOptions();
		$this->_initMetaData();
		$this->_initProperties();
		$this->replace($data);
	}
}
