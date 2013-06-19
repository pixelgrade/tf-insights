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
	
	/**
	 * @return string table
	 */
	static function stats_table()
	{
		return \app\Model_ItemStats::table();
	}
	
	/**
	 * @return string table
	 */
	static function ratings_table()
	{
		return \app\Model_ItemRatings::table();
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
	
	static function update_comments($id, $comments) {
		static::updater($id, ['comments' => $comments], [], [], ['comments'])->run();
		static::clear_entry_cache($id);
	}

	// ------------------------------------------------------------------------
	// Collection

	static function get_items_stats($page=1, $limit=100, $offset = 0, $order = ['sales' => 'DESC'], $constraints = [])
	{		
		return static::stash
			(
				__METHOD__,
				'
					SELECT 
						items.*,
						category.title as category_name,
						category.slug as category_slug,
						stats.sales as sales,
						ratings.rating,
						ratings.votes,
						ratings.votes1stars,
						ratings.votes2stars,
						ratings.votes3stars,
						ratings.votes4stars,
						ratings.votes5stars
						
						FROM :table items
						LEFT OUTER
							JOIN (SELECT * FROM (SELECT *  FROM `'.static::stats_table().'` ORDER BY timestamp DESC) as sl  GROUP BY itemid ORDER BY sales DESC) as stats
							ON stats.itemid = items.id 
						INNER 
							JOIN `'.static::ratings_table().'` ratings
							ON ratings.itemid = items.id
						LEFT OUTER
							JOIN `'.static::category_table().'` category
							ON category.id = items.category
				',
				'mysql'
			)
			->key(__CLASS__.'_'.__FUNCTION__)
			->page($page, $limit, $offset)
			->order($order)
			->constraints($constraints)
			->fetch_all(static::$field_format);
	}

	// -------------------------------------------------------------------------
	// Extended


} # class

