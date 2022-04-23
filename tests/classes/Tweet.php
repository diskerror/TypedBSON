<?php

namespace TestClasses;

use Diskerror\TypedBSON\TypedClass;
use TestClasses\Tweet\ReTweet;
use TestClasses\Tweet\TweetTrait;

class Tweet extends TypedClass
{
	protected $_map = [
		'id' => '_id',    //	from Twitter
	];

	protected $_id  = 0;

	use TweetTrait;

	protected $retweeted_status = [ReTweet::class];

}
