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

if(!is_numeric(Env::$collector_id_to_query)){
	echo "\nERROR!  You must put a numeric collector id in the env at Env::\$collector_id_to_query in env.php\n";
	exit;
}

$Client = new \Talis\Services\TheKof\SurveyMonkeyClient(Env::$survey_monkey_config,$http_client_wrapper);//this two params are coming from the env.php file
$all_surveys = $Client->surveys(Env::$survey_id_to_query)->collectors(Env::$collector_id_to_query)->get();