<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Cache extends TypedClass
{
	protected $front = [CacheFront::class];
	protected $back  = [CacheBack::class];
}
