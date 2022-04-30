<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        \Diskerror\TypedBSON\TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\TypedBSON;

use DateTimeInterface;
use Diskerror\Typed\AtomicInterface;
use MongoDB\BSON\Persistable;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray extends \Diskerror\Typed\TypedArray implements Persistable
{
	use TypedTrait;

	/**
	 * Constructor.
	 *
	 * If this class is instantiated directly, ie. "$a = new TypedArray('integer', [1, 2, 3]);",
	 * then $param1 must be the data type as a string, and then $param2 can be the initial data.
	 *
	 * If a derived class is instantiated then the data type must be contained in
	 * the class, ie. "protected $_type = 'integer';", and $param1 can be the initial data.
	 *
	 * @param mixed $param1 OPTIONAL ""
	 * @param array|object|null $param2 OPTIONAL null
	 */
	public function __construct($param1 = '', $param2 = null)
	{
		$this->_initToArrayOptions();

		if (get_called_class() === self::class) {
			$this->_type = (string) $param1;
			parent::__construct($param2);
		}
		else {
			parent::__construct($param1, $param2);
		}
	}

	/**
	 * Called automatically by MongoDB.
	 *
	 * @return array
	 */
	public function bsonSerialize(): array
	{
		$output = [];

		//	At this point all items are some type of object.
		if (is_a($this->_type, AtomicInterface::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->get();
			}
		}
		//	An array of BSON types is not predicted.
		elseif (method_exists($this->_type, 'bsonSerialize')) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->bsonSerialize();
			}
		}
		elseif (method_exists($this->_type, 'toArray')) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->toArray();
			}
		}
		elseif (is_a($this->_type, DateTimeInterface::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->format(DateTimeInterface::ATOM);
			}
		}
		elseif (method_exists($this->_type, '__toString')) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->__toString();
			}
		}
		else {
			//	else this is an array of some generic objects
			foreach ($this->_container as $k => $v) {
				$output[$k] = (array) $v;
			}
		}

		if ($this->toJsonOptions->has(BsonOptions::OMIT_EMPTY)) {
			self::_removeEmpty($output);
		}

		return $output;
	}

	/**
	 * Called automatically by MongoDB when a document has a field named "__pclass".
	 *
	 * @param array $data
	 */
	public function bsonUnserialize(array $data)
	{
		$this->_initToArrayOptions();
		$this->assign($data);
	}
}
