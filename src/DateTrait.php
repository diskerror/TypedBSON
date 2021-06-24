<?php


namespace Diskerror\TypedBSON;


use DateTime;
use DateTimeZone;
use MongoDB\BSON\UTCDateTimeInterface;
use function in_array;
use function substr;
use const STDERR;

trait DateTrait
{
	protected static $_days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

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
//
//		if (is_string($time) && in_array(substr($time, 0, 3), self::$_days)) {
////					Twitter:	Thu Jun 10 05:24:16 + 0000 2021
//			$time = DateTime::createFromFormat('D m d H:m:s + Z Y', $time, $timezone);
//		}
	}
}
