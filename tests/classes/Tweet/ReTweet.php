<?php

namespace Tweet;

use Diskerror\TypedBSON\TypedClass;

class ReTweet extends TypedClass
{
	protected $id = 0;

	use TweetTrait;
}
