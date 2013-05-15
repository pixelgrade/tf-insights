<?php namespace app;

// This is an IDE honeypot. It tells IDEs the class hirarchy, but otherwise has
// no effect on your application. :)

// HowTo: order honeypot -n 'tfinsights\items'


class Model_Crawler extends \tfinsights\items\Model_Crawler
{
	/** @return \app\Validator */
	static function update_check($id, array $fields) { return parent::update_check($id, $fields); }
	/** @return \app\SQLStatement */
	static function statement($identifier, $sql, $lang = null) { return parent::statement($identifier, $sql, $lang); }
}

class Model_Item extends \tfinsights\items\Model_Item
{
	/** @return \app\Validator */
	static function update_check($id, array $fields) { return parent::update_check($id, $fields); }
	/** @return \app\SQLStatement */
	static function statement($identifier, $sql, $lang = null) { return parent::statement($identifier, $sql, $lang); }
}

class Model_ItemAuthor extends \tfinsights\items\Model_ItemAuthor
{
	/** @return \app\Validator */
	static function update_check($id, array $fields) { return parent::update_check($id, $fields); }
	/** @return \app\SQLStatement */
	static function statement($identifier, $sql, $lang = null) { return parent::statement($identifier, $sql, $lang); }
}

class Model_ItemCategory extends \tfinsights\items\Model_ItemCategory
{
	/** @return \app\Validator */
	static function update_check($id, array $fields) { return parent::update_check($id, $fields); }
	/** @return \app\SQLStatement */
	static function statement($identifier, $sql, $lang = null) { return parent::statement($identifier, $sql, $lang); }
}

class Model_ItemStats extends \tfinsights\items\Model_ItemStats
{
	/** @return \app\Validator */
	static function update_check($id, array $fields) { return parent::update_check($id, $fields); }
	/** @return \app\SQLStatement */
	static function statement($identifier, $sql, $lang = null) { return parent::statement($identifier, $sql, $lang); }
}

class Schematic_Tfinsights_Items_Base extends \tfinsights\items\Schematic_Tfinsights_Items_Base
{
	/** @return \app\Schematic_Tfinsights_Items_Base */
	static function instance() { return parent::instance(); }
}

/**
 * @method \app\Task_Grab_Items set($name, $value)
 * @method \app\Task_Grab_Items add($name, $value)
 * @method \app\Task_Grab_Items metadata_is(array $metadata = null)
 * @method \app\Task_Grab_Items writer_is($writer)
 * @method \app\Writer writer()
 */
class Task_Grab_Items extends \tfinsights\items\Task_Grab_Items
{
	/** @return \app\Task_Grab_Items */
	static function instance() { return parent::instance(); }
}

class Task_Grab_Sales extends \tfinsights\items\Task_Grab_Sales
{
	/** @return \app\Task_Grab_Sales */
	static function invoke($encoded_task) { return parent::invoke($encoded_task); }
}
