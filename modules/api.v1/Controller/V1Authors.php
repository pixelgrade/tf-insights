<?php namespace tfinsights\api\v1;

/* This file is property of Pixel Grade Media. You may NOT copy, or redistribute
 * it. Please see the license that came with your copy for more information.
 */

/**
 * @package    tfinsights
 * @category   Controller
 * @author     Pixel Grade Team
 * @copyright  (c) 2013, Pixel Grade
 */
class Controller_V1Authors extends \app\Controller_Base_V1Api
{
	/**
	 * @return array
	 */
	function get($req)
	{
		$conf = [];
		$conf['constraints'] = [];
		$conf['order'] = [];
		
		if (! isset($req['page']))
		{
			$conf['page'] = 1;
		}
		else
		{
			$conf['page'] = $req['page'];
		}
		
		if (! isset($req['limit']))
		{
			$conf['limit'] = 100;
		}
		else
		{
			$conf['limit'] = $req['limit'];
		}
		
		if (! isset($req['offset']))
		{
			$conf['offset'] = 0;
		}
		else
		{
			$conf['offset'] = $req['offset'];
		}
		
		if (isset($req['all']))
		{
			$conf['page'] = 1;
			$conf['offset'] = 0;
			$conf['limit'] = 9999999;
		}
		
		$date = false;
			
		if (isset($req['date'])) {
			$conf['constraints']['date'] = $req['date'];
		}
		
		//get a single author
		if (isset($req['userid'])) {
			return \app\Model_ItemAuthor::get_author_stats($req['userid']);
		}

		//get all the items info, paged
		return \app\Model_ItemAuthor::get_authors_stats($conf['page'], $conf['limit'], $conf['offset'],$conf['order'],$conf['constraints']);
	}

} # class
