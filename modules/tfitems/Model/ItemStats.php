<?php namespace tfinsights\items;

/**
 * @package    tfinsights
 * @category   Items
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_ItemStats
{
	use \app\Trait_Model_Factory;
	use \app\Trait_Model_Utilities;
	use \app\Trait_Model_Collection;

	/**
	 * @var string
	 */
	protected static $table = 'pxg_itemstats';

	/**
	 * @return string table
	 */
	static function category_table()
	{
		return \app\Model_ItemCategory::table();
	}
	
	/**
	 * @return string table
	 */
	static function author_table()
	{
		return \app\Model_ItemAuthor::table();
	}

	// -------------------------------------------------------------------------
	// factory interface
	
	

	// ------------------------------------------------------------------------
	// Collection

	

	// -------------------------------------------------------------------------
	// Extended


} # class

