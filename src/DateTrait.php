<?php


namespace Diskerror\TypedBSON;


use DateTime;
use MongoDB\BSON\UTCDateTimeInterface;

trait DateTrait
{
	/**
	 * Adds the ability to accept a Mongo\BSON\UTCDateTime object.
	 *
	 * @param mixed $time
	 */
	public function _initCheckBson(&$time)
	{
		if (is_object($time) && $time instanceof UTCDateTimeInterface) {
			$time = (float) $time->__toString() / 1000;
		}

		if (is_string($time) && $time[0] !== '@') {
//					Twitter:	Thu Jun 10 05:24:16 + 0000 2021
			$time = DateTime::createFromFormat('D m d H:m:s + Z Y', $time);
		}
	}
}
