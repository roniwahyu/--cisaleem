<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* 
*/

	if(!function_exists('loadingtime')){

		function loadingtime(){
			//place this before any script you want to calculate time
			$time_start = microtime(true); 

			//sample script
			for($i=0; $i<1000; $i++){
			 //do anything
			}

			$time_end = microtime(true);

			//dividing with 60 will give the execution time in minutes other wise seconds
			$execution_time = ($time_end - $time_start)/60;

			//execution time of the script
			echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';
		}
	}
	if(!function_exists('start')){

		function start(){
			// At start of script
			$time_start = microtime(true); 
			return $time_start;
		}
	}
	if(!function_exists('showtime')){

		function showtime($time_start){

			// Anywhere else in the script
			echo 'Total execution time in seconds: ' .$this->endtime($time_start);
		}
	}
	if(!function_exists('endtime')){

		function endtime($time_start){

			// Anywhere else in the script
			return (microtime(true) - $time_start);
		}
	}
	



	if(!function_exists('microtime_float')){

		function microtime_float()
		{
		    list($usec, $sec) = explode(" ", microtime());
		    return ((float)$usec + (float)$sec);
		}
	}

	/*
	$time_start = microtime_float();

	// Sleep for a while
	usleep(100);

	$time_end = microtime_float();
	$time = $time_end - $time_start;

	echo "Did nothing in $time seconds\n";
	*/
	/*--------*/

	/*$time_start = microtime(true);

	// Sleep for a while
	usleep(100);

	$time_end = microtime(true);
	$time = $time_end - $time_start;

	echo "Did nothing in $time seconds\n";
	*/
	/*----------*/
	/*
	// Randomize sleeping time
	usleep(mt_rand(100, 10000));

	// As of PHP 5.4.0, REQUEST_TIME_FLOAT is available in the $_SERVER superglobal array.
	// It contains the timestamp of the start of the request with microsecond precision.
	$time = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];

	echo "Did nothing in $time seconds\n";
	*/


/* End of file logger_helper.php */
/* Location: ./application/helpers/logger_helper.php */
 ?>