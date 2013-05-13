<?php namespace tfinsights\core;

/**
 * @package    demo
 * @category   Controller
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Controller_Dashboard extends \app\Puppet implements \mjolnir\types\Controller
{
	use \app\Trait_Controller;

	use \app\Trait_Controller_DemoBase;

	protected static $grammar = [ 'dashboard' ];

} # class
