<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'tfinsights\core'


/**
 * @method \app\Controller_Dashboard add_preprocessor($name, $processor)
 * @method \app\Controller_Dashboard add_postprocessor($name, $processor)
 * @method \app\Controller_Dashboard preprocess()
 * @method \app\Controller_Dashboard postprocess()
 * @method \app\Controller_Dashboard channel_is($channel)
 * @method \app\Channel channel()
 * @method \app\Renderable public_index()
 * @method \app\Lang lang()
 * @method \app\Theme theme()
 */
class Controller_Dashboard extends \tfinsights\core\Controller_Dashboard
{
	/** @return \app\Controller_Dashboard */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Controller_Home add_preprocessor($name, $processor)
 * @method \app\Controller_Home add_postprocessor($name, $processor)
 * @method \app\Controller_Home preprocess()
 * @method \app\Controller_Home postprocess()
 * @method \app\Controller_Home channel_is($channel)
 * @method \app\Channel channel()
 * @method \app\Renderable public_index()
 * @method \app\Lang lang()
 * @method \app\Theme theme()
 */
class Controller_Home extends \tfinsights\core\Controller_Home
{
	/** @return \app\Controller_Home */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Controller_Login add_preprocessor($name, $processor)
 * @method \app\Controller_Login add_postprocessor($name, $processor)
 * @method \app\Controller_Login preprocess()
 * @method \app\Controller_Login postprocess()
 * @method \app\Controller_Login channel_is($channel)
 * @method \app\Channel channel()
 * @method \app\Renderable action_signin()
 * @method \app\Renderable public_signin()
 * @method \app\Renderable public_signout()
 * @method \app\Renderable signin_view($errors = null)
 * @method \app\Renderable public_index()
 * @method \app\Lang lang()
 * @method \app\Theme theme()
 */
class Controller_Login extends \tfinsights\core\Controller_Login
{
	/** @return \app\Controller_Login */
	static function instance() { return parent::instance(); }
}

class Schematic_Mjolnir_Access_Demo extends \tfinsights\core\Schematic_Mjolnir_Access_Demo
{
	/** @return \app\Schematic_Mjolnir_Access_Demo */
	static function instance() { return parent::instance(); }
}
trait Trait_Controller_DemoBase { use \tfinsights\core\Trait_Controller_DemoBase; }
