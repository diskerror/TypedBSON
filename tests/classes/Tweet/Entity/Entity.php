<?php

namespace Tweet\Entity;

use Tweet\Entity\Hashtag;
use Tweet\Entity\Url;
use Tweet\Entity\UserMention;
use Diskerror\Typed\{TypedClass, TypedArray};

class Entity extends TypedClass
{
	protected $hashtags      = [TypedArray::class, Hashtag::class];
	protected $urls          = [TypedArray::class, \Tweet\Entity\Url::class];
	protected $user_mentions = [TypedArray::class, UserMention::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
