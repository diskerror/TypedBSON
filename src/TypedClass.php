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
		$objectsToString = $this->toBsonOptions->has(BsonOptions::ALL_OBJECTS_TO_STRING);
		$noCastBson      = $this->toBsonOptions->has(BsonOptions::NO_CAST_BSON);
		$dateToUTC       = $this->toBsonOptions->has(BsonOptions::CAST_DATETIME_TO_UTC);
		$idToObjectId    = $this->toBsonOptions->has(BsonOptions::CAST_ID_TO_OBJECTID);

		$arr = [];
		foreach ($this->getPublicNames() as $pName) {
			$v = $this->_getByName($pName);    //  AtomicInterface objects are returned as scalars.

			if ($omitDefaults && $this->$pName == $this->_defaultValues[$pName]) {
				continue;
			}

			switch (gettype($v)) {
				case 'resource':
					continue 2;

				case 'object':
					switch (true) {
						case $noCastBson && strpos(get_class($v), 'MongoDB\\BSON') === 0:
							break;

						case method_exists($v, 'bsonSerialize'):
							$v = $v->bsonSerialize();
							break;

						case method_exists($v, 'toArray'):
							$v = $v->toArray();
							break;

						case $dateToUTC && $v instanceof DateTimeInterface && !($this->$pName instanceof Date):
							$v = $dateToUTC ?
								new UTCDateTime($v) :
								$v->format('Y-m-d\TH:i:sP'); // this format for JSON, explicit for 7.1 compat
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
		if ($idToObjectId && property_exists($this, '_id') && is_scalar($this->_id)) {
			if ($this->_id == 0) {
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
		foreach ($this->getPublicNames() as $publicName) {
			$this->_setByName($publicName, array_key_exists($publicName, $data) ? $data[$publicName] : '');
		}
	}
}
