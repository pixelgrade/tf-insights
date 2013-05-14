<?php namespace tfinsights\items;

/* This file is property of PixelGrade. You may NOT copy, or redistribute it.
 * Please see the license that came with your copy for more information.
 */

/**
 * @package    tfinsights
 * @category   Task
 * @author     PixelGrade
 * @copyright  (c) 2013, PixelGrade Team
 */
class Task_Crawl_Items extends \app\Task
{
	function grab_items($target)
	{
		//get the parameters
		$category = $this->config['category'];
		$series = $this->config['series'];
		
		$file = $this->config['file'];
		$userid = $this->config['userid'];
		
		if (empty($category))
		{
			echo 'We need a category id!!!';
			return ;
		}
		
		if (empty($series))
		{
			echo 'We need a series id!!!';
			return ;
		}
		
		if (empty($file))
		{
			echo 'We need a file with urls to scrape!!!';
			return ;
		}
		
		if (empty($userid))
		{
			echo 'We need a user id to assign the videos to!!!';
			return ;
		}
		
		//we have the config; proceed
		
		//get the file with urls
		$urls = \file(\dirname(__FILE__).'/config/'.$file, \FILE_IGNORE_NEW_LINES);
		
		if (empty($urls))
		{
			echo 'No urls found in the given file';
			return;
		}
		
		//get series details
		$seriesdet = \app\Model_VideoSeries::entry($series);
		$categorydet = \app\Model_VideoCategory::entry($category);
		
		//begin
		$this->writer->status('Info', 'Starting to process the urls')->eol();
		foreach ($urls as $url)
		{
			$this->writer->status('-', 'Processing .... '.$url)->eol();
			
			$fields['category'] = $category;
			$fields['series'] = $series;
			$fields['timestamp'] = \date_create('now')->format('Y-m-d H:i:s');
			$fields['userid'] = $userid;
			
			// Retrieve the DOM from a given URL
			$html = \file_get_html($url);
			
			//get the title and episode number
			$det = $html->find('div#descriere_episod div.informatii',0);
			$epnum = array();
			\preg_match_all('!\d+!', $det->find('h2',0)->innertext, $epnum);
			
			$fields['episode'] = $epnum[0][0];
			//detect multiple episodes
                        
			if (!empty($epnum[0][1]) && $epnum[0][1] > $epnum[0][0])
			{
				//we have multiple episodes
				$fields['multiple_ep'] = $epnum[0][1] - $epnum[0][0] + 1;
			}
			
			$title = $det->find('h2',1);
			$fields['title'] = \substr($title->innertext, 7);
			
			//parse the slug
			$slug = \str_replace(' ', '-', $fields['title']);
			$slug = \str_replace('--', '-', $slug);
			
			//remove the accents
			$slug = $this->removeAccents($slug);
			$slug = \preg_replace('/[^a-zA-Z0-9\-]/','',$slug);
			$slug = \str_replace('---', '-', $slug);
			$slug = \str_replace('--', '-', $slug);

			//remove start and ends that are wrong
			$slug = \preg_replace('/^[\-]+/','',$slug);
			$slug = \preg_replace('/[\-]+$/','',$slug);
			
			$fields['slug'] = $slug;
			
			//get the movie details source1
			$code = $html->find('div#sursa1',0);
			
			if (!empty($code->find('param[name=movie]',0)->value) && $this->startsWith($code->find('param[name=movie]',0)->value,'http://www.peteava.ro/static/swf/player.swf'))
			{
				//we have a peteava movie
				$fields['content_type'] = 'peteava';
				$fields['width'] = 728;
				$fields['height'] = 441;
				$fields['duration'] = 0;
				
				$vars = $code->find('param[name=flashvars]',0)->value;
				//get the movie id
				\preg_match('!\d+!', $vars, $matches);
				
				$fields['html_contents'] = $matches[0];
				
//				$fields['imgurl'] = 'http://storage2.peteava.ro/serve/thumbnail/'.$fields['html_contents'].'/playerstandard';
				
//				// we test to see if the image really exists
//				if (!$this->url_exists($fields['imgurl']))
//				{
//					$fields['imgurl'] = 'http://animekage.com'.$html->find('div#descriere_episod div.thumb img',0)->src;
//				}
			}
			else
			{
				//we have a iframe or embed movie
				$fields['content_type'] = 'html';
				if (!empty($code->find('iframe',0)->width))
				{
					$fields['width'] = $code->find('iframe',0)->width;
					$fields['height'] = $code->find('iframe',0)->height;
				}
				else if (!empty($code->find('embed',0)->width))
				{
					$fields['width'] = $code->find('embed',0)->width;
					$fields['height'] = $code->find('embed',0)->height;
				}
				$fields['duration'] = 0;
				$fields['html_contents'] = $code->innertext;
				
			}
			
			$fields['imgurl'] = $html->find('div#descriere_episod div.thumb img',0)->src;
			if ($this->startsWith($fields['imgurl'],'/'))
			{
				$fields['imgurl'] = 'http://animekage.com'.$fields['imgurl'];
			}
			
			//get the movie details source2
			$code = '';
			$code = $html->find('div#sursa2',0);
			if (!empty($code))
			{
				if (!empty($code->find('param[name=movie]',0)->value) && $this->startsWith($code->find('param[name=movie]',0)->value,'http://www.peteava.ro/static/swf/player.swf'))
				{
					//we have a peteava movie
					$fields['content_type2'] = 'peteava';
					$width = 728;
					if (isset($fields['width']))
					{
						$fields['width'] = ($fields['width'] < $width) ? $width : $fields['width'];
					}
					else
					{
						$fields['width'] = $width;
					}
					$height = 441;
					if (isset($fields['height']))
					{
						$fields['height'] = ($fields['height'] < $height) ? $height : $fields['height'];
					}
					else
					{
						$fields['height'] = $height;
					}

					$vars = $code->find('param[name=flashvars]',0)->value;
					//get the movie id
					\preg_match('!\d+!', $vars, $matches);

					$fields['html_contents2'] = $matches[0];

//					$fields['imgurl'] = 'http://storage2.peteava.ro/serve/thumbnail/'.$fields['html_contents2'].'/playerstandard';
//					
//					// we test to see if the image really exists
//					if (!$this->url_exists($fields['imgurl']))
//					{
//						$fields['imgurl'] = 'http://animekage.com'.$html->find('div#descriere_episod div.thumb img',0)->src;
//					}

				}
				elseif ($this->startsWith($code->innertext,'<iframe'))
				{
					//we have a iframe movie
					$fields['content_type2'] = 'html';
					if (!empty($code->find('iframe',0)->width))
					{
						$width = $code->find('iframe',0)->width;
						$fields['width'] = ($fields['width'] < $width) ? $width : $fields['width'];
						$height = $code->find('iframe',0)->height;
						$fields['height'] = ($fields['height'] < $height) ? $height : $fields['height'];
					}
					else if (!empty($code->find('embed',0)->width))
					{
						$width = $code->find('embed',0)->width;
						$fields['width'] = ($fields['width'] < $width) ? $width : $fields['width'];
						$height = $code->find('embed',0)->height;
						$fields['height'] = ($fields['height'] < $height) ? $height : $fields['height'];
					}
					$fields['duration'] = 0;
					$fields['html_contents2'] = $code->innertext;

				}
			}
			
			//get the movie details source3
			$code = '';
			$code = $html->find('div#sursa3',0);
			if (!empty($code))
			{
				if (!empty($code->find('param[name=movie]',0)->value) && $this->startsWith($code->find('param[name=movie]',0)->value,'http://www.peteava.ro/static/swf/player.swf'))
				{
					//we have a peteava movie
					$fields['content_type3'] = 'peteava';
					
					$width = 728;
					if (isset($fields['width']))
					{
						$fields['width'] = ($fields['width'] < $width) ? $width : $fields['width'];
					}
					else
					{
						$fields['width'] = $width;
					}
					$height = 441;
					if (isset($fields['height']))
					{
						$fields['height'] = ($fields['height'] < $height) ? $height : $fields['height'];
					}
					else
					{
						$fields['height'] = $height;
					}

					$vars = $code->find('param[name=flashvars]',0)->value;
					//get the movie id
					\preg_match('!\d+!', $vars, $matches);

					$fields['html_contents3'] = $matches[0];

//					$fields['imgurl'] = 'http://storage2.peteava.ro/serve/thumbnail/'.$fields['html_contents3'].'/playerstandard';
//					
//					// we test to see if the image really exists
//					if (!$this->url_exists($fields['imgurl']))
//					{
//						$fields['imgurl'] = 'http://animekage.com'.$html->find('div#descriere_episod div.thumb img',0)->src;
//					}

				}
				elseif ($this->startsWith($code->innertext,'<iframe'))
				{
					//we have a iframe movie
					$fields['content_type3'] = 'html';
					if (!empty($code->find('iframe',0)->width))
					{
						$width = $code->find('iframe',0)->width;
						$fields['width'] = ($fields['width'] < $width) ? $width : $fields['width'];
						$height = $code->find('iframe',0)->height;
						$fields['height'] = ($fields['height'] < $height) ? $height : $fields['height'];
					}
					else if (!empty($code->find('embed',0)->width))
					{
						$width = $code->find('embed',0)->width;
						$fields['width'] = ($fields['width'] < $width) ? $width : $fields['width'];
						$height = $code->find('embed',0)->height;
						$fields['height'] = ($fields['height'] < $height) ? $height : $fields['height'];
					}
					$fields['duration'] = 0;
					$fields['html_contents3'] = $code->innertext;

				}
			}
			
			if (isset($fields['multiple_ep']))
			{
				$fields['description'] = $seriesdet['title'].' - episoadele '.$fields['episode'].'-'.($fields['episode']+$fields['multiple_ep'] - 1).': '.$fields['title'].' Urmărește acum serialul anime online '.$seriesdet['title'].'! Vizionare plăcută (cu subtitrare în limba română - RoSub)!';
			}
			else
			{
				$fields['description'] = $seriesdet['title'].' - episodul '.$fields['episode'].': '.$fields['title'].' Urmărește acum serialul anime online '.$seriesdet['title'].'! Vizionare plăcută (cu subtitrare în limba română - RoSub)!';
			}
//			\var_dump($fields);die;
			//save the video
			\app\Model_Video::process($fields);
			
			$this->writer->status('-', 'Finished -> '.$seriesdet['title'].' - episodul '.$fields['episode'].': '.$fields['title'])->eol();
			unset($fields);
			\sleep(5);
			
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
	
	function execute()
	{	
		$target = \app\SQLDatabase::instance(); // default database
		
		// define the public files directory
		if ( ! \defined('PUBDIR'))
		{
			\define('PUBDIR', \trim(\file_get_contents(DOCROOT.'pubdir')));
		}
		
		$this->grab_items($target);
	}

} # class
