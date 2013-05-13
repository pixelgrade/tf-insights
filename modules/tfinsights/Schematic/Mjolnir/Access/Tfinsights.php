<?php namespace tfinsights\core;

/**
 * @package    demo
 * @category   Schematic
 * @author     Ibidem Team
 * @copyright  (c) 2013, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Schematic_Mjolnir_Access_Demo extends \app\Instantiatable implements \mjolnir\types\Schematic
{
	use \app\Trait_Schematic;

	function move()
	{
		#
		# Basic example of adding an extra field phonenumber to the library
		# defined user table in the access module.
		#

		\app\Schematic::alter
			(
				\app\Model_User::table(),
				'
					ADD COLUMN `phonenumber` :phonenumber AFTER `nickname`
				'
			);
	}

} # class
