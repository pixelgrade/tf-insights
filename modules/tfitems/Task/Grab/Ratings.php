<?php namespace tfinsights\items;

/* This file is property of PixelGrade. You may NOT copy, or redistribute it.
 * Please see the license that came with your copy for more information.
 */
// Include the library
include \app\CFS::dir('vendor/simple_html_dom').'simple_html_dom'.EXT;

/**
 * @package    tfinsights
 * @category   Task
 * @author     PixelGrade
 * @copyright  (c) 2013, PixelGrade Team
 */
class Task_Grab_Ratings extends \app\Task_Base
{
	function grab_ratings($target)
	{		
		//begin
		//$this->writer->printf('status','Info', 'Firing up the sales crawler...Vrrruuuummm vrrrummm')->eol();
		
		$entries = \app\Model_Item::entries(1,1000000);
		$counter = 0;
		
		foreach ($entries as $entry)
		{
			$entryratings = \app\Model_ItemRatings::entry($entry['id']);
			//$this->writer->printf('status','+', 'Working on .... '.$entry['url'])->eol();
			// Retrieve the DOM from the current URL
			$html = \file_get_html($entry['url']);
			
			$det = $html->find('meta[itemprop="ratingValue"]',0);
			if (!empty($det)) {

				//get and construct the info needed
				$item_data = [];
				$content = $det->getAttribute('content');
				if (!empty($content)) {
					$item_data['rating'] = $content;
				}
				unset($det);
				
				$det = $html->find('meta[itemprop="ratingCount"]',0);
				$content = $det->getAttribute('content');
				if (!empty($content)) {
					$item_data['votes'] = $content;
				}
				unset($det);
				$individualratings = $html->find('ul.rating-breakdown',0);
				
				foreach ($individualratings->find('li') as $li) {
					$count = $li->find('small.rating-breakdown__count',0)->innertext;
					$stars = $li->find('small.rating-breakdown__key',0)->innertext;
					
					if (!empty($stars)) {
						if (strpos($stars,'5') !== false) {
							$item_data['votes5stars'] = $count;
						}
						if (strpos($stars,'4') !== false) {
							$item_data['votes4stars'] = $count;
						}
						if (strpos($stars,'3') !== false) {
							$item_data['votes3stars'] = $count;
						}
						if (strpos($stars,'2') !== false) {
							$item_data['votes2stars'] = $count;
						}
						if (strpos($stars,'1') !== false) {
							$item_data['votes1stars'] = $count;
						}
					}
				}

				if (!empty($item_data))
				{
					if (!empty($entryratings)) {
						//add to database
						\app\Model_ItemRatings::update($entry['id'], $item_data);
						
						//$this->writer->printf('status','+', 'Updated ratings for .... '.$entry['item'])->eol();
					} else {
						$item_data['itemid'] = $entry['id'];
						//add to database
						\app\Model_ItemRatings::process($item_data);
						
						//$this->writer->printf('status','+', 'Added ratings for .... '.$entry['item'])->eol();
					}
				}
				
			}
			
			//if we are here already, let's update the comments count
			$det = $html->find('.sidebar-stats__box--comments span',0);
			if (!empty($det)) {
				$comments = $det->innertext;
				if (!empty($comments)) {
					\app\Model_Item::update_comments($entry['id'],$comments);
				}
			}
			
			//also let's update the authors saleslevel
			$det = $html->find('li[class*="badge-sold_between"]',0);
			if (!empty($det)) {
				$saleslevel = $det->innertext;
				if (!empty($saleslevel)) {
					\app\Model_ItemAuthor::update_saleslevel($entry['userid'],$saleslevel);
				}
			}
			
			//also let's update the authors level - Elite, Power Elite, etc
			$det = $html->find('li[class*="badge-power_elite_author"]',0);
			if (!empty($det)) {
				$level = $det->innertext;
				if (!empty($level)) {
					\app\Model_ItemAuthor::update_level($entry['userid'], str_replace(' Author','',$level));
				}
			} else {
				$det = $html->find('li[class*="badge-elite_author"]',0);
				if (!empty($det)) {
					$level = $det->innertext;
					if (!empty($level)) {
						\app\Model_ItemAuthor::update_level($entry['userid'],str_replace(' Author','',$level));
					}
				}
			}
			
			$counter++;
			
			//wait every 30 items
			if ($counter % 30 == 0)
			{
				//$this->writer->printf('status','#', 'Waiting 5 seconds .... ')->eol();
				\sleep(5);
			}
		}
	}
	
	function fetch_json_data($url)
	{
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);  
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		$ch_data = curl_exec($ch);  
		curl_close($ch);  

		if(!empty($ch_data))  
		{  
			return json_decode($ch_data, true);  
			
		}  
		else   
		{  
			return false;
		}  
	}
	
	function run()
	{
		//\app\Task::consolewriter($this->writer);
		
		$target = \app\SQLDatabase::instance(); // default database
		
		$this->grab_ratings($target);
	}

} # class
