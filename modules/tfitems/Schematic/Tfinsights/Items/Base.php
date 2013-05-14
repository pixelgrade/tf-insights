<?php namespace tfinsights\items;

/* This file is property of PixelGrade. You may NOT copy, or redistribute it.
 * Please see the license that came with your copy for more information.
 */

/**
 * @package    pixelgrade
 * @category   Schematic
 * @author     PixelGrade
 * @copyright  (c) 2012, PixelGrade Team
 */
class Schematic_Tfinsights_Items_Base extends \app\Instantiatable implements \mjolnir\types\Schematic
{
	use \app\Trait_Schematic;
	
	function down()
	{
		\app\Schematic::destroy
			(
				\app\Model_Item::table(),
				\app\Model_ItemAuthor::table(),
				\app\Model_ItemCategory::table(),
				\app\Model_ItemStats::table()
			);
	}
	
	function up()
	{
		\app\Schematic::table
			(
				\app\Model_Item::table(), 
				'
					`id`           :key_primary,
					`userid`	   :key_foreign,
					`category`     :key_foreign,
					`title`        varchar(1000) DEFAULT \'\',
					`url`          varchar(1000) DEFAULT \'\',
					`cost`		   :counter,
					`rating`       :counter DEFAULT \'0\',
					`thumbnail`    varchar(1000) DEFAULT \'\',
					`tags`         varchar(1500) DEFAULT \'\',
					`uploaded_on`  :datetime_optional,
					`last_update`  :datetime_optional,
					
					PRIMARY KEY (`id`)
				'
			);
		
		\app\Schematic::table
			(
				\app\Model_ItemAuthor::table(), 
				'
					`id`           :key_primary,
					`username`     varchar(100) DEFAULT \'\',
					`country`      varchar(100) DEFAULT \'\',
					`sales`        :counter,
					`location`     varchar(500) DEFAULT \'\',
					`image`		   varchar(1000) DEFAULT \'\',
					`followers`    :counter,
					
					PRIMARY KEY (`id`)
				'
			);
		
		\app\Schematic::table
			(
				\app\Model_ItemCategory::table(), 
				'
					`id`          :key_primary,
					`title`       :title,
					`slug`        varchar(200) DEFAULT \'\',
					`url`         varchar(1000) DEFAULT \'\',
					
					PRIMARY KEY (`id`)
				'
			);
		
		\app\Schematic::table
			(
				\app\Model_ItemStats::table(), 
				'
					`itemid`		:key_foreign,
					`sales`			int(6) DEFAULT 0,
					`timestamp`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
				'
			);
		
		\app\Schematic::table
			(
				\app\Model_Crawler::table(), 
				'
					`id`			:key_primary,
					`option`		:key_foreign,
					`value`			varchar(1000) DEFAULT \'\',
					`timestamp`		TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					
					PRIMARY KEY (`id`)
				'
			);
		
	}

	function bind()
	{
		\app\Schematic::constraints
			(
				[
					\app\Model_Item::table() => array
						(
							'userid' => [\app\Model_ItemAuthor::table(), 'CASCADE', 'CASCADE'],
							'category' => [\app\Model_ItemCategory::table(), 'CASCADE', 'CASCADE'],
						),
					\app\Model_ItemStats::table() => array
						(
							'itemid' => [\app\Model_Item::table(), 'CASCADE', 'CASCADE'],
						),
				]
			);
	}
	
} # class
