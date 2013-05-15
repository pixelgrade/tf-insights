<?php namespace tfinsights\items;

/**
 * @package    tfinsights
 * @category   Items
 * @author     Ibidem Team
 * @copyright  (c) 2012, Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Model_ItemCategory
{
	use \app\Trait_Model_Factory;
	use \app\Trait_Model_Utilities;
	use \app\Trait_Model_Collection;

	/**
	 * @var string
	 */
	protected static $table = 'pxg_itemcategories';
	
	protected static $fieldlist = array
		(
			'nums' => array
				(
					'id', 'parent_id',
				),
			'strs' => array
				(
					'title', 'slug',
				),
			'bools' => array
				(
				
				),
		);
	

	// -------------------------------------------------------------------------
	// factory interface
	
	static function process(array $fields)
	{
		static::inserter($fields, static::$fieldlist['strs'], static::$fieldlist['bools'], static::$fieldlist['nums'])->run();
		static::$last_inserted_id = \app\SQL::last_inserted_id();
		
		return static::$last_inserted_id;
	}
	
	/**
	 * Insert new category
	 */
	static function insert_by_slug($slug)
	{
		if ($slug === null)
		{
			return null;
		}
		
		$cats = \explode('/',$slug);
		if (!empty($cats))
		{
			$parent_id = null;
			$current_slug = '';
			foreach($cats as $cat)
			{
				if (empty($current_slug))
				{
					$current_slug = $cat;
				}
				else
				{
					$current_slug .= '/'.$cat;
				}
				$entry = static::get_entry_by_slug($current_slug);
				if (empty($entry))
				{
					$title = \ucwords(\str_replace('-', ' ', $cat));
					static::statement
						(
							__METHOD__,
							'
								INSERT INTO :table (`parent_id`, `title`, `slug`)
								VALUES (:parent_id, :title, :slug)
							'
						)
						->num(':parent_id', $parent_id)
						->str(':title', $title)
						->str(':slug', $current_slug)
						->run();
					
					$parent_id = \app\SQL::last_inserted_id();
				}
				else
				{				
					$parent_id = $entry['id'];
				}
			}
		}
		static::clear_cache();
		
		return $parent_id;
	}

	// ------------------------------------------------------------------------
	// Collection
	
	/**
	 * @param int id
	 * @return array 
	 */
	static function get_entry_by_slug($slug)
	{
		if ($slug === null)
		{
			return null;
		}
		
		$stashkey = __CLASS__.'_SLUG'.$slug;
		$entry = \app\Stash::get($stashkey, null);
		
		if ( ! $entry)
		{
			$entry = static::statement
				(
					__METHOD__,
					'
						SELECT category.*
						  FROM :table category
						 WHERE category.slug = :slug
					',
					'mysql'
				)
				->str(':slug', $slug)
				->run()
				->fetch_entry();
			
			\app\Stash::store($stashkey, $entry, \app\Stash::tags(\get_called_class(), ['change']));
		}
		
		return $entry;
	
	}
	// -------------------------------------------------------------------------
	// Extended


} # class