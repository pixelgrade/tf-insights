<?php namespace tfinsights\items;

/**
 * @package    tfinsights
 * @category   Items
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_ItemAuthor
{
	use \app\Trait_Model_Factory;
	use \app\Trait_Model_Utilities;
	use \app\Trait_Model_Collection;

	/**
	 * @var string
	 */
	protected static $table = 'pxg_itemauthors';

	protected static $fieldlist = array
		(
			'nums' => array
				(
					'sales', 'followers',
				),
			'strs' => array
				(
					'username', 'level', 'saleslevel', 'country', 'location','image',
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
	static function items_table()
	{
		return \app\Model_Item::table();
	}

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
			->rule(['sales','followers'], 'not_empty');
	}

	static function process(array $fields)
	{
		static::inserter($fields, ['username', 'country', 'location','image'], static::$fieldlist['bools'], static::$fieldlist['nums'])->run();
		static::$last_inserted_id = \app\SQL::last_inserted_id();

		return static::$last_inserted_id;
	}

	/**
	 * Update
	 */
	static function update_process($id, array $fields)
	{
		static::updater($id, $fields, [], [], static::$fieldlist['nums'])->run();
		static::clear_entry_cache($id);
	}

	static function update_saleslevel($id, $level) {
		static::updater($id, ['saleslevel' => $level], ['saleslevel'], [], [])->run();
		static::clear_entry_cache($id);
	}

	static function update_level($id, $level) {
		static::updater($id, ['level' => $level], ['level'], [], [])->run();
		static::clear_entry_cache($id);
	}

	// ------------------------------------------------------------------------
	// Collection

	/**
	 * @param int id
	 * @return array
	 */
	static function get_entry_by_username($username)
	{
		if ($username === null)
		{
			return null;
		}

		$stashkey = __CLASS__.'_USERNAME'.$username;
		$entry = \app\Stash::get($stashkey, null);

		if ( ! $entry)
		{
			$entry = static::statement
				(
					__METHOD__,
					'
						SELECT author.*
						  FROM :table author
						 WHERE author.username = :username
					',
					'mysql'
				)
				->str(':username', $username)
				->run()
				->fetch_entry();

			\app\Stash::store($stashkey, $entry, \app\Stash::tags(\get_called_class(), ['change']));
		}

		return $entry;

	}

	static function get_authors_stats($page=1, $limit=100, $offset = 0, $order = ['sales' => 'DESC'], $constraints = [])
	{
		if (empty($constraints['date'])) {
			return static::statement
				(
					__METHOD__,
					'
						SELECT
							authors.id,
							authors.username,
							authors.country,
							authors.level,
							authors.saleslevel,
							authors.location,
							authors.image,
							authors.followers,
							stats.income as income,
							stats.sales as sales

							FROM :table authors
							LEFT OUTER
								JOIN (
									SELECT
										items.userid as userid,
										SUM(stats2.sales) as sales,
										SUM(items.cost * stats2.sales) as income

										FROM `'.static::items_table().'` items
										LEFT OUTER
											JOIN (SELECT * FROM (SELECT *  FROM `'.static::stats_table().'` ORDER BY timestamp DESC) as sl  GROUP BY itemid ORDER BY sales DESC) stats2
											ON stats2.itemid = items.id
										GROUP BY userid
								) stats
								ON stats.userid = authors.id
					',
					'mysql'
				)
				->run()
				->fetch_all(static::$field_format);
		} else {
			return static::statement
				(
					__METHOD__,
					'
						SELECT
							authors.id,
							authors.username,
							authors.country,
							authors.level,
							authors.saleslevel,
							authors.location,
							authors.image,
							authors.followers,
							stats.income as income,
							stats.sales as sales

							FROM :table authors
							LEFT OUTER
								JOIN (
									SELECT
										items.userid as userid,
										SUM(stats2.sales) as sales,
										SUM(items.cost * stats2.sales) as income

										FROM `'.static::items_table().'` items
										LEFT OUTER
											JOIN (SELECT * FROM (SELECT *  FROM `'.static::stats_table().'` WHERE DATE(timestamp) <= DATE(FROM_UNIXTIME('.$constraints['date'].')) ORDER BY timestamp DESC) as sl  GROUP BY itemid ORDER BY sales DESC) stats2
											ON stats2.itemid = items.id
										GROUP BY userid
								) stats
								ON stats.userid = authors.id
					',
					'mysql'
				)
				->run()
				->fetch_all(static::$field_format);
		}
	}

	static function get_author_stats($id)
	{
		return static::stash
			(
				__METHOD__,
				'
					SELECT
						authors.*,
						stats.income as income

						FROM :table authors
						LEFT OUTER
							JOIN (
							SELECT
								(items.cost * stats2.sales) as income

								FROM `'.static::items_table().'` items
								LEFT OUTER
									JOIN (SELECT * FROM (SELECT *  FROM `'.static::stats_table().'` ORDER BY timestamp DESC) as sl  GROUP BY itemid ORDER BY sales DESC) as stats2
									ON stats2.itemid = items.id
							) as stats
							ON stats.userid = authors.id
				',
				'mysql'
			)
			->key(__CLASS__.'_'.__FUNCTION__)
			->page(1, 1, 0)
			->constraints(['authors.id' => $id])
			->fetch_all(static::$field_format);
	}

	// -------------------------------------------------------------------------
	// Extended


} # class

