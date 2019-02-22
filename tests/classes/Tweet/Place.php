<?php

namespace Tweet;

//  Leave this mixed, Typed and TypedBSON
class Place extends \Diskerror\Typed\TypedClass
{
	protected $id           = 0;

	protected $url          = '';

	protected $place_type   = '';

	protected $name         = '';

	protected $full_name    = '';

	protected $country_code = '';

	protected $country      = '';
}
