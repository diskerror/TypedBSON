<?php

namespace Diskerror\TypedBSON;

use Diskerror\Typed\DateTime as DT;
use DateTimeZone;
use InvalidArgumentException;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;

/**
 * This class adds convenience methods to the built-in DateTime.
 *
 * Date and time can be passed in with objects or associative arrays.
 *
 * @copyright     Copyright (c) 2011 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class DateTime extends DT implements Persistable
{
	/**
	 * @return string
	 */
	public function bsonSerialize(): string
	{
		return new UTCDateTime($this);
	}

	public function bsonUnserialize($data)
	{
		parent::__construct($data->toDateTime());
	}
}
