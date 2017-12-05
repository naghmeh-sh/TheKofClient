<?php
/**
 * Fetching the default response
 * currently is page = 1 and per_page = 50
 */
require_once '../env.php';

if(!is_numeric(Env::$survey_id_to_query)){
	echo "\nERROR!  You must put a numeric survey id in the env at Env::\$survey_monkey_config in env.php\n";
	exit;
}

$Client = new \Talis\Services\TheKof\SurveyMonkeyClient(Env::$survey_monkey_config,$http_client_wrapper);//this two params are coming from the env.php file
$survey = $Client->surveys(Env::$survey_id_to_query)->get_one();
var_dump($survey);