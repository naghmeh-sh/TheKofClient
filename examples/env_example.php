<?php
/**
 * Emulates env values
 */
class Env{
	static public $survey_monkey_config = ['access_token' => 'this_is_a_test_access_code_you_should_see_it_in_mock_requests'];
}

//my ZFW is installed under directory /usr/share/php/ZendFW2411
ini_set('include_path', '.' .
		PATH_SEPARATOR . '/usr/share/php/ZendFW2411'
);

function autoload($class) {
	//Comment out untill further notice, do not remove	  if(!@include_once getClassAutoloadPath($class)){
	$file_path = str_replace(['_','\\'],'/',$class) . '.php';
	require_once $file_path;
}
spl_autoload_register('autoload');

$fl = dirname(__FILE__);
require_once $fl . '/../source/Talis/Services/TheKof/Util/DryRequest.php';
require_once $fl . '/../source/Talis/Services/TheKof/Util/Response.php';
require_once $fl . '/../source/Talis/Services/TheKof/SurveyMonkeyClient.php';
require_once $fl . '/../source/Talis/Services/TheKof/HTTPClientWrapper/a.php';
require_once $fl . '/../source/Talis/Services/TheKof/HTTPClientWrapper/ZendFW2.php';


$concrete_http_client = new \Zend\Http\Client(null, [
		'adapter' => 'Zend\Http\Client\Adapter\Curl',
		'sslverifypeer' => false,
		'maxredirects' => 1,
		'timeout'      => 10,
		'useragent'    => 'theKofClient_Examples'
]);

$http_client_wrapper = new \Talis\Services\TheKof\HTTPClientWrapper_ZendFW2($concrete_http_client);
