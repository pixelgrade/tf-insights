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
	
	protected static $fieldlist = array
		(
			'nums' => array
				(
					'itemid', 'sales', 
				),
			'strs' => array
				(
					
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

	// -------------------------------------------------------------------------
	// factory interface
	
	static function process(array $fields)
	{
		static::inserter($fields, static::$fieldlist['strs'], static::$fieldlist['bools'], static::$fieldlist['nums'])->run();
		static::$last_inserted_id = \app\SQL::last_inserted_id();
		
		return static::$last_inserted_id;
	}

	// ------------------------------------------------------------------------
	// Collection

	static function get_item_sales($id, $constraints)
	{		
		return static::stash
			(
				__METHOD__,
				'
					SELECT
						sales,
						timestamp
						
						FROM :table
				',
				'mysql'
			)
			->key(__CLASS__.'_'.__FUNCTION__)
			->page(1, 9999, 0)
			->constraints($constraints)
			->fetch_all(static::$field_format);
	}

	// -------------------------------------------------------------------------
	// Extended


} # class

