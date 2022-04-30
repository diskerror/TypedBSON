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
	public $toBsonOptions;

	/**
	 * Initialize options for when this object is converted to an array.
	 */
	protected function _initToArrayOptions()
	{
		$this->_optionList[] = 'toBsonOptions';
		parent::_initToArrayOptions();
		$this->toBsonOptions = new BsonOptions(
			BsonOptions::OMIT_EMPTY | BsonOptions::CAST_ID_TO_OBJECTID
		);
	}
}
