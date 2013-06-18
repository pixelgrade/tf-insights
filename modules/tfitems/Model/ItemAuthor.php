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
					'username', 'country', 'location','image',
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
	static function stats_table()
	{
		return \app\Model_ItemStats::table();
	}

	// -------------------------------------------------------------------------
	// factory interface
	
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
		static::updater($id, $fields, [], [], static::$fieldlist['nums'])->run();
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

	// -------------------------------------------------------------------------
	// Extended


} # class

