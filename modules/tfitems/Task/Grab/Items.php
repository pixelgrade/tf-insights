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
class Task_Grab_Items extends \app\Task_Base
{
	function grab_items($target)
	{
		//get the parameters
		$url = $this->get('url', null);
		$class = $this->get('class', null);
		
		if (empty($url))
		{
			echo 'We need a url to start from!!!';
			return ;
		}
		
		if (empty($class))
		{
			echo 'We need a div class that holds the ids (i.e. <li class="wordpress-template" data-item-id="4720197">)!!!';
			return ;
		}
		
		//we have the config; proceed
		
		//begin
		if ($this->get('verbose', null) == 'on') {
			$this->writer->printf('status','Info', 'Firing up the crawler...Vrrruuuummm vrrrummm')->eol();
		}
		$reachedtheend = false;
		while (!$reachedtheend)
		{
			if ($this->get('verbose', null) == 'on') {
				$this->writer->printf('status','-', 'Processing .... '.$url)->eol();
			}			
			
			// Retrieve the DOM from the current URL
			$html = \file_get_html($url);
			
			if (!empty($html) && $html !== false) {
			
				$det = $html->find('ul.item-list',0);
				$tempresults = $det->find('li.'.$class);

				//get the item ids from this page
				$item_ids = array();
				foreach ($tempresults as $key => $item)
				{
					$item_ids[] = $item->attr["data-item-id"];
				}

				//lets add these found items to the database
				foreach ($item_ids as $itemid)
				{
					$fields = array();

					//get the json info
					$item_data = $this->fetch_json_data('http://marketplace.envato.com/api/v3/item:'.$itemid.'.json');

					if ($item_data["item"])
					{
						$entry = \app\Model_Item::entry($item_data["item"]["id"]);

						if (empty($entry))
						{
							//let's do some preprocessing
							//we need to get the category id
							$cat_id = \app\Model_ItemCategory::get_entry_by_slug($item_data["item"]["category"])['id'];
							if (empty($cat_id))
							{
								$cat_id = \app\Model_ItemCategory::insert_by_slug($item_data["item"]["category"]);
							}

							//we need to get the user id
							$user_id = \app\Model_ItemAuthor::get_entry_by_username($item_data["item"]["user"])['id'];
							if (empty($user_id))
							{
								//get the info about the user
								$user_data = $this->fetch_json_data('http://marketplace.envato.com/api/v3/user:'.$item_data["item"]["user"].'.json');

								$user_id = \app\Model_ItemAuthor::process($user_data['user']);
							}

							//add the ids
							$item_data['item']['userid'] = $user_id;
							$item_data['item']['category'] = $cat_id;

							//fix the dates
							$item_data['item']['uploaded_on'] = date('Y-m-d H:i:s', strtotime($item_data['item']['uploaded_on']));
							$item_data['item']['last_update'] = date('Y-m-d H:i:s', strtotime($item_data['item']['last_update']));


							//add to database
							\app\Model_Item::process($item_data['item']);
							if ($this->get('verbose', null) == 'on') {
								$this->writer->printf('status','+', 'Added new item .... '.$item_data["item"]["item"])->eol();
							}
						}
						else
						{
							//fix the dates
							$item_data['item']['uploaded_on'] = date('Y-m-d H:i:s', strtotime($item_data['item']['uploaded_on']));
							$item_data['item']['last_update'] = date('Y-m-d H:i:s', strtotime($item_data['item']['last_update']));

							//we update the entry
							\app\Model_Item::update($entry['id'],$item_data['item']);
							if ($this->get('verbose', null) == 'on') {
								$this->writer->printf('status','|', 'Updated item .... '.$item_data["item"]["item"])->eol();
							}
						}
					}
				}


				// get the url to the next page
				$det = $html->find('div.pagination',0);
				$next_url = $det->find('a[rel="next"]',0);
				if (!empty($next_url))
				{
					$url = 'http://themeforest.net'.$next_url->attr["href"];
				}
				else
				{
					//we have reached the end
					$reachedtheend = true;
				}
			
			}
			
			\sleep(1);
			
		}
	}
	
	function startsWith($haystack, $needle)
	{
		return !strncmp($haystack, $needle, strlen($needle));
	}

	function endsWith($haystack, $needle)
	{
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}

		return (substr($haystack, -$length) === $needle);
	}
	
	function removeAccents($str) {
		
		$convMap = array(
			' '=>'-', 'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj', 'Ž'=>'Z', 'ž'=>'z', 'C'=>'C', 'c'=>'c', 'C'=>'C', 'c'=>'c',
			'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
			'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
			'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
			'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', "ă" => "a", 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
			'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
			'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
			'ÿ'=>'y', 'R'=>'R', 'r'=>'r', "'"=>'-', '"'=>'-', 'ț' => 't', 'ș' => 's', 'Ț' => 'T', 'Ș' => 'S', 'Ă' => 'A', 'Î' => 'I', 'â' => 'a', 'Â' => 'A'
		);
		return strtr($str, $convMap);
	}
	
	function url_exists($url){
		$ch = curl_init($url);    
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if($code == 200){
		   $status = true;
		}else{
		  $status = false;
		}
		curl_close($ch);
	   return $status;
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
		if ($this->get('verbose', null) == 'on') {
			\app\Task::consolewriter($this->writer);
		}
		
		$target = \app\SQLDatabase::instance(); // default database
		
		$this->grab_items($target);
	}

} # class
