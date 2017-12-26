<?php
/**
 * Creating a collector for the input survey
 */
require_once '../env.php';
if(!is_numeric(Env::$survey_id_to_query)){
	echo "\nERROR!  You must put a numeric survey id in the env at Env::\$survey_monkey_config in env.php\n";
	exit;
}
if(!isset($argv[1]) || !$argv[1]){
	echo "\nYou must supply a collector name as a parameter.\n";
	exit;
} else {
	$collector_name = $argv[1];
	$s_id = Env::$survey_id_to_query;
	echo "\nYou are about to create a collector named [{$collector_name}] for survey [{$s_id}]\n";
}


$raw_data = new \stdClass;
$raw_data->type				= 'weblink';
$raw_data->name				= $collector_name;
$raw_data->redirect_url 	= 'http://www.somewebsite.com';
$raw_data->redirect_type 	= 'url';

$Client = \Talis\Extensions\TheKof\SurveyMonkeyClient::init(Env::$survey_monkey_config,$http_client_wrapper);//this two params are coming from the env.php file
$collector = $Client->surveys(Env::$survey_id_to_query)->collectors()->post(new \Talis\Extensions\TheKof\Model_Collector($raw_data));

var_dump($collector);
