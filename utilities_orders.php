<?php


//require_once('FirePHPCore/lib/FirePHPCore/FirePHP.class.php');
//$firephp = FirePHP::getInstance(true);
//ob_start(); // Starts FirePHP output buffering


require_once('inc/database.php');
require_once('local_config/config.php');
require_once ('utilities.php');


/**
 * 
 * custom interface for order listing which converts most common query params into 
 * calls to stored procedures. 
 * @param str $time_period today | all | prevMonth
 * @param int $limit sql limit 
 */
function get_orders_in_range($time_period='today', $limit=117111451111)
{
	
	//TODO server - client difference in time/date?!
	$today = date('Y-m-d', strtotime("Today"));
	$tomorrow = date('Y-m-d', strtotime("Tomorrow"));
	$prevMonth = date('Y-m-d', strtotime('Today - 1 month'));
	$very_distant_future = '9999-12-30';
	$very_distant_past	= '1980-01-01';
	
	switch ($time_period) {
		// all orders where date_for_order = today
		case 'ordersForToday':
			printXML(stored_query_XML_fields('get_orders_listing', $today, $today, $limit));
			break;
		
		//all orders 
		case 'all':
			printXML(stored_query_XML_fields('get_orders_listing', $very_distant_past, $very_distant_future, $limit));
			break;

		//last month
		case 'prevMonth':
			printXML(stored_query_XML_fields('get_orders_listing', $prevMonth, $very_distant_future, $limit));
			break;
		
		//all orders that have been send off, but have no shop date assigned
		case 'limboOrders':
			echo '';
			break;
		
		case 'futureOrders':
			printXML(stored_query_XML_fields('get_orders_listing', $tomorrow, $very_distant_future, $limit));
			break;
		
		
		default:
			throw new Exception("get_orders_in_range: param={$time_period} not supported");  
			break;
	}

	
}

	
?>