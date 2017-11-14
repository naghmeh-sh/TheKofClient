<?php
use PHPUnit\Framework\TestCase;


/**
 * Test the client basic functionality
 * validating the configuration file
 * 
 * @author Itay Moav
 */
class SurveyMonkeyClient_test extends TestCase {
	static public function setUpBeforeClass(){
		dbgn('Testing configuration values');
	}
	
	/**
	 * Tests the get/set params
	 */
	public function testConfiguremissingAccessToekn(){
		$this->expectException(InvalidArgumentException::class);
		$Client = new Talis\Services\TheKof\SurveyMonkeyClient([]);
	}
	
	public function testConfigureWithAccessToekn(){
		$Client = new Talis\Services\TheKof\SurveyMonkeyClient(Env::$survey_monkey_config);
		$this->assertInstanceOf(\Talis\Services\TheKof\SurveyMonkeyClient::class, $Client,'Client did not initiate with test configuration');
	}
	
	public function testGetRequest(){
		$Client = new Talis\Services\TheKof\SurveyMonkeyClient(Env::$survey_monkey_config);
		$fake_survey_id = 1234;
		
		//default request
		$access_token = Env::$survey_monkey_config['access_token'];
		$ExpectedDryRequest = new \Talis\Services\TheKof\DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->headers();
		$ExpectedDryRequest->url("https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}");
		$ExpectedDryRequest->method(\Talis\Services\TheKof\DryRequest::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->get_dry();
		$this->assertInstanceOf(\Talis\Services\TheKof\DryRequest::class, $ActualDryRequest,'Dry request must return a \Talis\Services\TheKof\DryRequest object');
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'response structure is not same');
	}
}
