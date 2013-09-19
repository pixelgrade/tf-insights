<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'tfinsights\api\v1'


/**
 * @method \app\Controller_Base_V1Api add_preprocessor($name, $processor)
 * @method \app\Controller_Base_V1Api add_postprocessor($name, $processor)
 * @method \app\Controller_Base_V1Api preprocess()
 * @method \app\Controller_Base_V1Api postprocess()
 * @method \app\Controller_Base_V1Api channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Controller_Base_V1Api extends \tfinsights\api\v1\Controller_Base_V1Api
{
	/** @return \app\Controller_Base_V1Api */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Controller_V1Authors add_preprocessor($name, $processor)
 * @method \app\Controller_V1Authors add_postprocessor($name, $processor)
 * @method \app\Controller_V1Authors preprocess()
 * @method \app\Controller_V1Authors postprocess()
 * @method \app\Controller_V1Authors channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Controller_V1Authors extends \tfinsights\api\v1\Controller_V1Authors
{
	/** @return \app\Controller_V1Authors */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Controller_V1Categories add_preprocessor($name, $processor)
 * @method \app\Controller_V1Categories add_postprocessor($name, $processor)
 * @method \app\Controller_V1Categories preprocess()
 * @method \app\Controller_V1Categories postprocess()
 * @method \app\Controller_V1Categories channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Controller_V1Categories extends \tfinsights\api\v1\Controller_V1Categories
{
	/** @return \app\Controller_V1Categories */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Controller_V1Items add_preprocessor($name, $processor)
 * @method \app\Controller_V1Items add_postprocessor($name, $processor)
 * @method \app\Controller_V1Items preprocess()
 * @method \app\Controller_V1Items postprocess()
 * @method \app\Controller_V1Items channel_is($channel = null)
 * @method \app\Channel channel()
 */
class Controller_V1Items extends \tfinsights\api\v1\Controller_V1Items
{
	/** @return \app\Controller_V1Items */
	static function instance() { return parent::instance(); }
}
