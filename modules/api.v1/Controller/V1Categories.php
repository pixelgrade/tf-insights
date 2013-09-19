<?php namespace tfinsights\api\v1;

/* This file is property of Pixel Grade Media. You may NOT copy, or redistribute
 * it. Please see the license that came with your copy for more information.
 */

/**
 * @package    tfinsights
 * @category   Controller
 * @author     Pixel Grade Team
 * @copyright  (c) 2013, Pixel Grade Media
 */
class Controller_V1Categories extends \app\Controller_Base_V1Api
{
	/**
	 * @return array
	 */
	function get($req)
	{
		if (isset($req['all']))
		{
			$page = null;
			$limit = null;
			$offset = 0;
		}
		else # limited
		{
			$page = isset($req['page']) ? $req['page'] : 1;
			$limit = isset($req['limit']) ? $req['limit'] : 100;
			$offset = isset($req['offset']) ? $req['offset'] : 0;
		}

		return \app\Model_ItemCategory::entries($page, $limit, $offset);
	}

} # class
