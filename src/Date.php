<?php

namespace Diskerror\TypedBSON;

use MongoDB\BSON\Serializable;
use MongoDB\BSON\Unserializable;

class Date extends \Diskerror\Typed\Date implements Serializable, Unserializable
{
	use DateTrait;

	public function bsonSerialize()
	{
		return [$this->format('Y-m-d')];
	}

	public function bsonUnserialize(array $data)
	{
		list($year, $month, $day) = explode('-', $data[0]);
		$this->setDate($year, $month, $day);
	}
}
