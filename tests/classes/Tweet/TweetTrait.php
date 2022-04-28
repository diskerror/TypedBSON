<?php /** @noinspection PhpUndefinedMethodInspection */

namespace TestClasses\Tweet;

use Diskerror\TypedBSON\DateTime;
use Diskerror\Typed\Scalar\TStringNormalize;

trait TweetTrait {

	protected $created_at                = [DateTime::class, '2018-07-18 17:10:28'];

	protected $contributors              = null;

	protected $entities                  = [Entity::class];

	protected $extended_entities         = [ExtendedEntities::class];

	protected $favorite_count            = 0;

	protected $filter_level              = 'low';

	protected $in_reply_to_screen_name   = '';

	protected $in_reply_to_status_id_str = '';

	protected $in_reply_to_user_id_str   = '';

	protected $is_quote_status           = false;

	protected $lang                      = 'en';

	protected $place                     = [Place::class];

	protected $possibly_sensitive        = false;

	protected $retweet_count             = 0;

	protected $source                    = '';	//	address html tag

	protected $text                      = [TStringNormalize::class];

	protected $truncated                 = false;

	protected $user                      = [User::class];

}
