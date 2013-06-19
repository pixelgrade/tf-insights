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
class Task_Grab_Authors extends \app\Task_Base
{
	function grab_authors($target)
	{		
		//begin
		if ($this->get('verbose', null) == 'on') {
			$this->writer->printf('status','Info', 'Firing up the sales crawler...Vrrruuuummm vrrrummm')->eol();
		}
		
		$entries = \app\Model_ItemAuthor::entries(1,1000000);
		$counter = 0;
		if ($this->get('verbose', null) == 'on') {
			$this->writer->printf('status','Info', 'Firing up the crawler...Vrrruuuummm vrrrummm')->eol();
		}
		foreach ($entries as $entry)
		{
			if ($this->get('verbose', null) == 'on') {
				$this->writer->printf('status','-', 'Updating author .... '.$entry['username'])->eol();
			}
			
			$user_id = $entry['id'];
			
			//get the info about the user
			$user_data = $this->fetch_json_data('http://marketplace.envato.com/api/v3/user:'.$entry['username'].'.json');
			if (!empty($user_data['user'])) {
				\app\Model_ItemAuthor::update($user_id, $user_data['user']);
			} else {
				if ($this->get('verbose', null) == 'on') {
					$this->writer->printf('status','X', 'Author not found .... '.$entry['username'])->eol();
				}
			}
			
			$counter++;
			
			//wait every 50 authors
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
		
		$this->grab_authors($target);
	}

} # class
