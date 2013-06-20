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
		
		//get a single item
		if (isset($req['itemid'])) {
			return \app\Model_Item::get_item_stats($req['itemid']);
		}
		
		//get a totals
		if (isset($req['totals']))
		{
			$constraints = [];
			
			if (isset($req['cost'])) {
				$constraints['cost'] = $req['cost'];
			}
			
			return \app\Model_Item::get_total_stats($constraints);
		}
		
		//get common acceptance day
		if (isset($req['common_acceptance_day'])) {
			return \app\Model_Item::get_common_day_for_acceptance();
		}
		
		//get entries stats for a given period of days
		if (isset($req['period'])) {
			//$conf['constraints']['datetime'] = ['between' => [$start, $end]];
		}
		
		//get items accepted in the last n days
		if (isset($req['accepted'])) {
			$conf['constraints']['items.uploaded_on'] = array('between' => array(date('Y-m-d', strtotime("now -".$req['accepted']." days")), date('Y-m-d', strtotime("now"))));
		}

		//get all the items info, paged
		return \app\Model_Item::get_items_stats($conf['page'], $conf['limit'], $conf['offset'],$conf['order'],$conf['constraints']);
	}

} # class
