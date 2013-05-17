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
class Controller_V1Items extends \app\Controller_Base_V1Api
{
	/**
	 * @return array
	 */
	function get($req)
	{
		$conf = [];
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
		
		return \app\Model_Item::get_items_stats($conf['page'], $conf['limit'], $conf['offset']);
	}

} # class
