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

$Client = \Talis\Services\TheKof\SurveyMonkeyClient::init(Env::$survey_monkey_config,$http_client_wrapper);//this two params are coming from the env.php file
$all_surveys = $Client->surveys(Env::$survey_id_to_query)->get();
foreach ($all_surveys as $survey){
	/* @var $survey Model_Survey */
	var_dump($survey);
}