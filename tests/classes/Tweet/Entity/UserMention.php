<?php

namespace Tweet\Entity;

use Diskerror\TypedBSON\{TypedClass, TypedArray};

class UserMention extends TypedClass
{
	protected $id          = '';

	protected $screen_name = '';

	protected $name        = '';

	protected $indices     = [TypedArray::class, 'int'];

}
