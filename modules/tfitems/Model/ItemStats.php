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
	 * @var array
	 */
	protected static $field_format = [];

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

	static function get_item_sales($id, $constraints = [])
	{
		$order = ['timestamp'=>'ASC'];
		return static::stash
			(
				__METHOD__,
				'
					SELECT
						itemid,
						sales,
						timestamp
						
						FROM :table
				',
				'mysql'
			)
			->key(__CLASS__.'_'.__FUNCTION__)
			->page(1, 99999, 0)
			->order($order)
			->constraints($constraints)
			->fetch_all(static::$field_format);
	}
	
	static function get_items_sales($date)
	{		
		if (!date) {
			//we need to grab all
		} else {
			return static::stash
				(
					__METHOD__,
					'
						SELECT
							itemid,
							sales,
							timestamp

							FROM :table
							
							WHERE DATE(timestamp) <= DATE(FROM_UNIXTIME('.$date.')
							
							GROUP BY itemid ORDER BY sales DESC
					',
					'mysql'
				)
				->key(__CLASS__.'_'.__FUNCTION__)
				->page(1, 99999, 0)
				->fetch_all(static::$field_format);
		}
	}

	// -------------------------------------------------------------------------
	// Extended


} # class

