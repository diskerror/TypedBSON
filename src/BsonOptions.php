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
	 * Omit properties that match their default values.
	 */
	const DATE_OBJECT_TO_STRING = 4;

	/**
	 * Convert all other objects to string, if possible.
	 */
	const ALL_OBJECTS_TO_STRING = 8;

	/**
	 * Cast member with the name "_id" into MongoDB\BSON\ObjectId.
	 */
	const CAST_ID_TO_OBJECTID = 16;
}

