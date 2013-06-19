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
class Task_Grab_Sales extends \app\Task_Base
{
	function grab_sales($target)
	{		
		//begin
		if ($this->get('verbose', null) == 'on') {
			$this->writer->printf('status','Info', 'Firing up the sales crawler...Vrrruuuummm vrrrummm')->eol();
		}
		
		$entries = \app\Model_Item::entries(1,1000000);
		$counter = 0;
		
		foreach ($entries as $entry)
		{			
			//get the json info
			$item_data = $this->fetch_json_data('http://marketplace.envato.com/api/v3/item:'.$entry['id'].'.json');

			if (!empty($item_data["item"]))
			{
				//add to database
				\app\Model_ItemStats::process(['itemid'=>$entry['id'], 'sales' => $item_data['item']['sales']]);

				if ($this->get('verbose', null) == 'on') {
					$this->writer->printf('status','+', 'Added new item sales .... '.$item_data["item"]["item"])->eol();
				}
			}
			$counter++;
			
			//wait every 50 items
			if ($counter % 50 == 0)
			{
				if ($this->get('verbose', null) == 'on') {
					$this->writer->printf('status','#', 'Waiting 3 seconds .... ')->eol();
				}
				\sleep(3);
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
		if ($this->get('verbose', null) == 'on') {
			\app\Task::consolewriter($this->writer);
		}
		
		$target = \app\SQLDatabase::instance(); // default database
		
		$this->grab_sales($target);
	}

} # class
