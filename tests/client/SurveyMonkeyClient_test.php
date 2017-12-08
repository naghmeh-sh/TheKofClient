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
	
	static private function get_proper_client(){
		return Talis\Services\TheKof\SurveyMonkeyClient::init(Env::$survey_monkey_config,new Talis\Services\TheKof\HTTPClientWrapper_TestWrapper);
	}
	
	/**
	 * Tests the get/set params
	 */
	public function testConfiguremissingAccessToekn(){
		$this->expectException(InvalidArgumentException::class);
		$Client = Talis\Services\TheKof\SurveyMonkeyClient::init([],new Talis\Services\TheKof\HTTPClientWrapper_TestWrapper);
	}
	
	public function testConfigureWithAccessToekn(){
		$Client = self::get_proper_client();
		$this->assertInstanceOf(\Talis\Services\TheKof\SurveyMonkeyClient::class, $Client,'Client did not initiate with test configuration');
	}
	
	public function testGetDryRequest(){
		dbgn('Testing GET requests');
		// init
		$access_token = Env::$survey_monkey_config['access_token'];
		$Client = self::get_proper_client();
		$fake_survey_id = 1234;
		$headers = [
				'Authorization' => "bearer {$access_token}",
				'Content-type'  => 'application/json'
		];
		
		// TESTS 
		
		//1. default request
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}";
		$ExpectedDryRequest = new \Talis\Services\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Services\TheKof\HTTPClientWrapper_a::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->get_dry();
		$this->assertInstanceOf(\Talis\Services\TheKof\Util_DryRequest::class, $ActualDryRequest,'Dry request must return a \Talis\Services\TheKof\DryRequest object');
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'response structure is not same');
		
		$this->assertEquals($expected_url, $ActualDryRequest->url(),'url does not match');
		$this->assertEquals('GET', $ActualDryRequest->method(),'METHOD does not match');
		$this->assertEquals($headers, $ActualDryRequest->headers(),'headers do not match');
		
		//2. page one, default size
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}?page=1";
		$ExpectedDryRequest = new \Talis\Services\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Services\TheKof\HTTPClientWrapper_a::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->get_dry(1);
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'(page 1) response structure is not same');

		$this->assertEquals($expected_url, $ActualDryRequest->url(),'url does not match');
		$this->assertEquals('GET', $ActualDryRequest->method(),'METHOD does not match');
		$this->assertEquals($headers, $ActualDryRequest->headers(),'headers do not match');
		
		
		//3. page 2 size 10
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}?page=2&per_page=10";
		$ExpectedDryRequest = new \Talis\Services\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Services\TheKof\HTTPClientWrapper_a::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->get_dry(2,10);
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'(page 2,10) response structure is not same');
		
		$this->assertEquals($expected_url, $ActualDryRequest->url(),'url does not match');
		$this->assertEquals('GET', $ActualDryRequest->method(),'METHOD does not match');
		$this->assertEquals($headers, $ActualDryRequest->headers(),'headers do not match');
	}
	
	/**
	 * I am using a mock HTTP client that emulates Zend Http Client (ZFW2)
	 * methods and returns a mock JSON same as what SM would return.
	 * Over time, more tests needs to be added for error handling.
	 */
	public function testGetMockLiveRequest(){
		$http_client = new MockZendFWHttpClient;
		
	}
}
