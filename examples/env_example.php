<?php
ini_set('error_reporting', E_ALL|E_STRICT);
ini_set('log_errors', true);
ini_set('display_errors', true);
//my ZFW is installed under directory /usr/share/php/ZendFW2411
ini_set('include_path', '.' .
		PATH_SEPARATOR . '/usr/share/php/ZendFW2411'
);

/**
 * Emulates env values
 */
class Env{
	static public $survey_monkey_config = ['access_token' => 'this_is_a_test_access_code_you_should_see_it_in_mock_requests'];
	
	static public $survey_id_to_query   = 'PUT AN INTEGER HERE. You can figure out survey ids by calling the get_all_your_surveys.php example and check the output';
	
	static public $collector_id_to_query   = 'PUT AN INTEGER HERE. You can figure out collector ids by calling the get_one_survey_all_collectors.php example and check the output';
}

/**
 * For the purpose of using ZFW
 * @param unknown $class
 */
function autoload($class) {
	//Comment out untill further notice, do not remove	  if(!@include_once getClassAutoloadPath($class)){
	$file_path = str_replace(['_','\\'],'/',$class) . '.php';
	require_once $file_path;
}
spl_autoload_register('autoload');

require_once dirname(__FILE__). '/../bundle/thekofclient.php';

$concrete_http_client = new \Zend\Http\Client(null, [
		'adapter' => 'Zend\Http\Client\Adapter\Curl',
		'sslverifypeer' => false,
		'maxredirects' => 1,
		'timeout'      => 10,
		'useragent'    => 'theKofClient_Examples'
]);

$http_client_wrapper = new \Talis\Services\TheKof\HTTPClientWrapper_ZendFW2($concrete_http_client);
