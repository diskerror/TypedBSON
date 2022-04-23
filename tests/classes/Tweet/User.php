<?php

namespace TestClasses\Tweet;

use Diskerror\TypedBSON\DateTime;
use Diskerror\TypedBSON\TypedClass;
use Diskerror\Typed\Scalar\TStringNormalize;

class User extends TypedClass
{
	protected $id                   = 0;

	protected $name                 = '';

	protected $screen_name          = '';

	protected $location             = '';

	protected $contributors_enabled = false;

	protected $created_at           = [DateTime::class, '2018-07-18 17:10:28'];

	protected $description          = [TStringNormalize::class];

	protected $favourites_count     = 0;

	protected $followers_count      = 0;

	protected $friends_count        = 0;

	protected $geo_enabled          = false;

	protected $is_translator        = false;

	protected $lang                 = 'en';

	protected $listed_count         = 0;

	protected $protected            = false;

	protected $statuses_count       = 0;

	protected $time_zone            = '';

	protected $url                  = '';

	protected $verified             = false;


	/**
	 * Optional method for filtering description property.
	 *
	 * protected $description = '';
	 *
	 * @param $v
	 */
//	protected function _set_description($v)
//	{
//		$this->description = preg_replace('/\s+/', ' ', \Normalizer::normalize((string)$v, \Normalizer::FORM_D));
//	}

}
