<?php namespace tfinsights\items;

/**
 * @package    tfinsights
 * @category   Items
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_Item
{
	use \app\Trait_Model_Factory;
	use \app\Trait_Model_Utilities;
	use \app\Trait_Model_Collection;

	/**
	 * @var string
	 */
	protected static $table = 'pxg_items';
	
	protected static $fieldlist = array
		(
			'nums' => array
				(
					'id', 'userid', 'category', 'cost', 'rating',
				),
			'strs' => array
				(
					'item', 'url',  'live_preview_url', 'thumbnail', 'tags', 'uploaded_on', 'last_update',
				),
			'bools' => array
				(
				
				),
		);

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
	
	/**
	 * @return string table
	 */
	static function stats_table()
	{
		return \app\Model_ItemStats::table();
	}

	// -------------------------------------------------------------------------
	// factory interface
	
	/**
	 * @return \app\Validator
	 */
	static function check(array $fields, $context = null)
	{
		return \app\Validator::instance($fields)
			->rule(['cost','rating','item', 'url', 'live_preview_url', 'thumbnail', 'tags', 'uploaded_on', 'last_update'], 'not_empty');
	}
	
	static function process(array $fields)
	{
		static::inserter($fields, static::$fieldlist['strs'], static::$fieldlist['bools'], static::$fieldlist['nums'])->run();
		static::$last_inserted_id = \app\SQL::last_inserted_id();
		
		return static::$last_inserted_id;
	}
	
	/**
	 * Update 
	 */
	static function update_process($id, array $fields)
	{
		static::updater($id, $fields, static::$fieldlist['strs'], static::$fieldlist['bools'], ['cost', 'rating'])->run();
		static::clear_entry_cache($id);
	}

	// ------------------------------------------------------------------------
	// Collection

	

	// -------------------------------------------------------------------------
	// Extended


} # class

