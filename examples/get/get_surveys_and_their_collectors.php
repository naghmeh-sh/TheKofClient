<?php
/**
 * Fetching the default response
 * currently is page = 1 and per_page = 50
 */
require_once '../env.php';
$Client = \Talis\Extensions\TheKof\SurveyMonkeyClient::init(Env::$survey_monkey_config,$http_client_wrapper);//this two params are coming from the env.php file
$all_surveys = $Client->surveys()->get();
$i=0;
foreach ($all_surveys as $survey){
	
	echo "\n===================== SURVEY ====================================";
	/* @var $survey Model_Survey */
	var_dump($survey->get_raw_data());
	$survey_collectors = $survey->collectors()->get();
	echo "\n===================== collectors ====================================";
	foreach($survey_collectors as $a_collector){
		var_dump($a_collector->get_raw_data());
	}
	echo "\n\n\n";
	$i++;
	if($i==4) die; // just to not over crowed the page
}
