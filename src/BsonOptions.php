<?php


namespace Diskerror\TypedBSON;


use Diskerror\Typed\Options;

class BsonOptions extends Options
{
	/**
	 * Omit empty properties from the array that is output.
	 */
	const OMIT_EMPTY = 1;

	/**
	 * Omit properties that match their default values.
	 */
	const OMIT_DEFAULTS = 2;

	/**
	 * Convert all other objects to string, if possible.
	 */
	const ALL_OBJECTS_TO_STRING = 4;

	/**
	 * Leave MongoDB\BSON instances alone, don't convert to string.
	 */
	const NO_CAST_BSON = 8;

	/**
	 * Cast all DateTimeInterface objects to UTCDateTime.
	 */
	const CAST_DATETIME_TO_UTC = 16;

	/**
	 * Cast member with the name "_id" into MongoDB\BSON\ObjectId when doing bsonSerialize().
	 */
	const CAST_ID_TO_OBJECTID = 32;
}
