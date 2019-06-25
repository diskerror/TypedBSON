<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        \Diskerror\TypedBSON\TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\TypedBSON;

use MongoDB\BSON\Persistable;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray extends \Diskerror\Typed\TypedArray implements Persistable
{
	/**
	 * Constructor.
	 *
	 * This allows for initial values to be passed in the first parameter.
	 * This is only works for children of this class where the type has already
	 * been set.
	 *
	 * @param mixed             $type   OPTIONAL ''
	 * @param array|object|null $values OPTIONAL null
	 */
	public function __construct($type = '', $values = null)
	{
		if (get_called_class() !== self::class) {
			if (!isset($this->_type)) {
				throw new InvalidArgumentException('$this->_type must be set in child class.');
			}

			if (null !== $values) {
				throw new InvalidArgumentException('Only the first parameter can be set when using an inherited class.');
			}

			// Assume the first parameter holds the values and the second is unset.
			$values = $type;
		}
		else {
			$this->_type = (string)$type;
		}

		$this->_initArrayOptions();
		$this->_initMetaData();

		$this->assign($values);
	}

	use AbstractTrait;

	/**
	 * String representation of object.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		return serialize([
			'_type'         => $this->_type,
			'_arrayOptions' => $this->_arrayOptions,
			'_jsonOptions'  => $this->_jsonOptions,
			'_bsonOptions'  => $this->_bsonOptions,
			'_container'    => $this->_container,
		]);
	}

	/**
	 * Constructs the object
	 *
	 * @link  https://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized The string representation of the object.
	 *
	 * @return void
	 */
	public function unserialize($serialized): void
	{
		$data = unserialize($serialized);

		$this->_type         = $data['_type'];
		$this->_arrayOptions = $data['_arrayOptions'];
		$this->_jsonOptions  = $data['_jsonOptions'];
		$this->_bsonOptions  = $data['_bsonOptions'];
		$this->_container    = $data['_container'];
	}

	/**
	 * Called automatically by MongoDB when a document has a field named "__pclass".
	 *
	 * @param array $data
	 */
	public function bsonUnserialize(array $data)
	{
		$this->_initArrayOptions();
		$this->assign($data);
	}
}
