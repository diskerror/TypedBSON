<?php
/**
 * Adds MongoDB\BSON type handling.
 *
 * @name           \Diskerror\TypedBSON\AbstractTrait
 * @copyright      Copyright (c) 2019 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\TypedBSON;

use Diskerror\Typed\ArrayOptions;

/**
 * Trait AbstractTrait
 *
 * @package Diskerror\TypedBSON
 */
trait AbstractTrait
{
	/**
	 * Holds options for "toArray" customizations when used by MongoDB.
	 *
	 * @var ArrayOptions
	 */
	protected $_bsonOptions;

	/**
	 * Holds default options for "toArray" customizations when used by MongoDB/bsonSerialize.
	 *
	 * @var int
	 */
	protected $_bsonOptionDefaults =
		ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCE | ArrayOptions::NO_CAST_BSON | ArrayOptions::CAST_DATETIME_TO_BSON;

	protected function _initArrayOptions()
	{
		$this->_arrayOptions = new ArrayOptions($this->_arrayOptionDefaults);
		$this->_jsonOptions  = new ArrayOptions($this->_jsonOptionDefaults);
		$this->_bsonOptions  = new ArrayOptions($this->_bsonOptionDefaults);
	}

	/**
	 * Called automatically by MongoDB.
	 *
	 * @return array
	 */
	public function bsonSerialize(): array
	{
		return $this->_toArray($this->_bsonOptions);
	}
}
