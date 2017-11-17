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
	fwrite(STDERR,"\n==== {$text}\n");
}

/**
 * Emulates env values
 */
class Env{
	static public $survey_monkey_config = ['access_token' => 'this_is_a_test_access_code_you_should_see_it_in_mock_requests'];
}

/**
 * Emulates ZFW http client methods
 */
class MockZendFWHttpClient{
	
	public 	$uri='',
			$method = \Talis\Services\TheKof\HTTPClientWrapper_a::METHOD_GET,
			$body=null,
			$response_body = null
	;
	
	public function setUri($url){
		$this->uri = $url;
	}
	
	public function setMethod(string $method){
		$this->method = $method;
	}
	
	public function setRawBody($body){
		$this->body = $body;
	}
	
	public function send(){
		return $this->response_body;
	}
	
	/**
	 * according to the url+method 
	 * this is where the "magic" happens and different 
	 * responses are returned.
	 */
	public function getRawRequestData(){
			
	}
	
	public function setHeaders(array $headers){
		//boohooo
	}
	
	public function resetParameters($something){
		//naahhhhh
	}
}


require_once '../source/Talis/Services/TheKof/Util/DryRequest.php';
require_once '../source/Talis/Services/TheKof/SurveyMonkeyClient.php';
require_once '../source/Talis/Services/TheKof/HTTPClientWrapper/a.php';
require_once '../source/Talis/Services/TheKof/HTTPClientWrapper/ZendFW2.php';
require_once '../source/Talis/Services/TheKof/Client/a.php';


