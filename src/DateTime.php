<?php


namespace Diskerror\TypedBSON;

use DateTimeZone;
use MongoDB\BSON\Serializable;
use MongoDB\BSON\Unserializable;
use MongoDB\BSON\UTCDateTime;

class DateTime extends \Diskerror\Typed\DateTime implements Serializable, Unserializable
{
	use DateTrait;

	/**
	 * @return UTCDateTime
	 */
	public function bsonSerialize(): object
	{
		if ($this->getTimezone() == new DateTimeZone('UTC')) {
			return new UTCDateTime($this);
		}
		$d = clone $this;
		$d->setTimezone('UTC');
		return new UTCDateTime($d);
	}

	/**
	 * @param array $data
	 * @return : void
	 */
	public function bsonUnserialize(array $data): void
	{
		$tz = $this->getTimezone();
		$this->setTimezone(new DateTimeZone('UTC'));
		$this->setTime(0, 0, 0, $data['milliseconds'] * 1000);
		$this->setTimezone($tz);
	}
}
