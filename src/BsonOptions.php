<?php


namespace Diskerror\TypedBSON;


use Diskerror\Typed\Options;

class BsonOptions extends Options
{
	/**
	 * Setting this will instruct the conversion to an array to leave MongoDB\BSON instances alone,
	 * don't convert to string.
	 */
	const NO_CAST_BSON = 1;

	/**
	 * Cast all DateTimeInterface objects to UTCDateTime.
	 */
	const CAST_DATETIME_TO_UTC = 2;

	/**
	 * Cast member with the name "_id" into MongoDB\BSON\ObjectId when doing bsonSerialize().
	 */
	const CAST_ID_TO_OBJECTID = 4;
}
