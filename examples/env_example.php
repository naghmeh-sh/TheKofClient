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
 * Default auto loader
 * @param unknown $class
 */
function autoload($class) {
	//Comment out untill further notice, do not remove	  if(!@include_once getClassAutoloadPath($class)){
	$file_path = str_replace(['_','\\'],'/',$class) . '.php';
	require_once $file_path;
}
spl_autoload_register('autoload');


//including TheKof
$fl = dirname(__FILE__);
require_once $fl . '/../source/Talis/Services/TheKof/Client/a.php';
require_once $fl . '/../source/Talis/Services/TheKof/HTTPClientWrapper/a.php';
require_once $fl . '/../source/Talis/Services/TheKof/Model/a.php';

require_once $fl . '/../source/Talis/Services/TheKof/Util/DryRequest.php';
require_once $fl . '/../source/Talis/Services/TheKof/Util/RawResponse.php';
require_once $fl . '/../source/Talis/Services/TheKof/Util/Collection.php';
require_once $fl . '/../source/Talis/Services/TheKof/SurveyMonkeyClient.php';
require_once $fl . '/../source/Talis/Services/TheKof/HTTPClientWrapper/ZendFW2.php';
require_once $fl . '/../source/Talis/Services/TheKof/Client/Surveys.php';
require_once $fl . '/../source/Talis/Services/TheKof/Client/Collectors.php';
require_once $fl . '/../source/Talis/Services/TheKof/Model/Survey.php';

$concrete_http_client = new \Zend\Http\Client(null, [
		'adapter' => 'Zend\Http\Client\Adapter\Curl',
		'sslverifypeer' => false,
		'maxredirects' => 1,
		'timeout'      => 10,
		'useragent'    => 'theKofClient_Examples'
]);

$http_client_wrapper = new \Talis\Services\TheKof\HTTPClientWrapper_ZendFW2($concrete_http_client);
