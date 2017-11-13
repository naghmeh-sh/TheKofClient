<?php
/**
 * Show console output.
 *
 * @param  string  $text
 *
 * @return void;
 */
function dbgn($text)
{
	fwrite(STDERR, $text."\n");
}

class Env{
	static public $survey_monkey_config = ['access_token' => 'this_is_a_test_access_code_you_should_see_it_in_mock_requests'];
}


require_once '../source/Talis/Services/TheKof/SurveyMonkeyClient.php';
