<?php
/**
 * Fetching the default response
 * currently is page = 1 and per_page = 50
 */
require_once '../env.php';
$Client = new \Talis\Services\TheKof\SurveyMonkeyClient(Env::$survey_monkey_config,$http_client_wrapper);//this two params are coming from the env.php file
$all_surveys = $Client->surveys()->get();
foreach ($all_surveys as $survey){
	/* @var $survey Model_Survey */
	var_dump($survey);
}
