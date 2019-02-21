<?php

namespace Diskerror\TypedBSON;

use Diskerror\Typed\Date as Dt;
use MongoDB\BSON\Persistable;

/**
 * This class adds convenience methods for date-only to Diskerror\DateTime.
 *
 * Date and time can be passed in with objects or associative arrays.
 *
 * THIS HAS NOT BEEN EXHAUSTIVELY TESTED. Particularly "add()" and "sub()".
 *
 * @copyright     Copyright (c) 2011 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class Date extends Dt implements Persistable
{
	public function bsonSerialize()
	{
		return [$this->format('Y-m-d')];
	}

	public function bsonUnserialize(array $data)
	{
		parent::__construct($data[0]);
	}
}
