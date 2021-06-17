<?php

require 'Tweet/TweetTrait.php';

use Diskerror\Typed\ArrayOptions;
use Diskerror\TypedBSON\TypedClass;
use Tweet\ReTweet;
use Tweet\TweetTrait;

class Tweet extends TypedClass
{
	protected $_map = [
		'id' => '_id',    //	from Twitter
	];

	protected $_id  = 0;

	/**
	 * Tweet constructor.
	 */
	public function __construct($in = null)
	{
		$this->_jsonOptionDefaults = ArrayOptions::SET_ALL_YES ^ ArrayOptions::CAST_DATETIME_TO_BSON;
		\Diskerror\Typed\TypedClass::__construct($in);
	}

	use TweetTrait;

	protected $retweeted_status = [ReTweet::class];

}
