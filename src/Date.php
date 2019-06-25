<?php


namespace Diskerror\TypedBSON;


class Date extends \Diskerror\Typed\Date
{
	use DateTrait;

	public function __construct($time = 'now', $timezone = null)
	{
		$this->_initCheckBson($time);
		parent::__construct($time, $timezone);
	}


}
