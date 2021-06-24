<?php


namespace Diskerror\TypedBSON;


use MongoDB\BSON\UTCDateTimeInterface;

trait DateTrait
{
	/**
	 * Adds the ability to accept a Mongo\BSON\UTCDateTime object.
	 *
	 * @param mixed        $time
	 */
	public function _initCheckBson(&$time)
	{
		if (is_object($time) && $time instanceof UTCDateTimeInterface) {
			$time = $time->toDateTime();
		}
	}
}
