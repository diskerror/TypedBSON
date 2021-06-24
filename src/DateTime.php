<?php


namespace Diskerror\TypedBSON;


class DateTime extends \Diskerror\Typed\DateTime
{
	public function __construct($time = 'now', $timezone = null)
	{
		$this->_initCheckBson($time);
		parent::__construct($time, $timezone);
	}

	use DateTrait;
}
