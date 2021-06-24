<?php
/**
 * Adds MongoDB\BSON type handling.
 *
 * @name           \Diskerror\TypedBSON\TypedTrait
 * @copyright      Copyright (c) 2019 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\TypedBSON;


/**
 * Trait TypedTrait
 *
 * @package Diskerror\TypedBSON
 */
trait TypedTrait
{
	/**
	 * @var BsonOptions
	 */
	protected $_bsonOptions;

	/**
	 * @var int
	 */
	protected $_bsonOptionDefaults =
		BsonOptions::NO_CAST_BSON | BsonOptions::CAST_DATETIME_TO_UTC | BsonOptions::CAST_ID_TO_OBJECTID;

	/**
	 * Initialize options for when this object is converted to an array.
	 */
	protected function _initArrayOptions()
	{
		parent::_initArrayOptions();
		$this->_bsonOptions = new BsonOptions($this->_bsonOptionDefaults);
	}
}
