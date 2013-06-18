<?php namespace tfinsights\items;

/**
 * @package    tfinsights
 * @category   Items
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_ItemRatings
{
	use \app\Trait_Model_Factory;
	use \app\Trait_Model_Utilities;
	use \app\Trait_Model_Collection;

	/**
	 * @var string
	 */
	protected static $table = 'pxg_itemratings';
	
	protected static $fieldlist = array
		(
			'nums' => array
				(
					'itemid', 'rating', 'votes', 'votes1stars','votes2stars','votes3stars','votes4stars','votes5stars',
				),
			'strs' => array
				(
					
				),
			'bools' => array
				(
				
				),
		);
	
	protected static $unique_key = 'itemid';

	// -------------------------------------------------------------------------
	// factory interface
	
	/**
	 * @return \app\Validator
	 */
	static function check(array $fields, $context = null)
	{
		return \app\Validator::instance($fields)
			->rule(['rating','votes'], 'not_empty');
	}
	
	static function process(array $fields)
	{
		static::inserter($fields, static::$fieldlist['strs'], static::$fieldlist['bools'], static::$fieldlist['nums'])->run();
	}
	
	/**
	 * Update 
	 */
	static function update_process($id, array $fields)
	{
		static::updater($id, $fields, [], [], ['rating', 'votes', 'votes1stars','votes2stars','votes3stars','votes4stars','votes5stars'])->run();
		static::clear_entry_cache($id);
	}

	// ------------------------------------------------------------------------
	// Collection

	

	// -------------------------------------------------------------------------
	// Extended


} # class

