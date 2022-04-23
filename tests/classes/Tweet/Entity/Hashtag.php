<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\TypedBSON\{TypedArray, TypedClass};

class Hashtag extends TypedClass
{
	protected $text    = '';

	protected $indices = [TypedArray::class, 'int'];
}
