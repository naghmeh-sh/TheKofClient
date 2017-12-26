<?php
use PHPUnit\Framework\TestCase;


/**
 * Testing the DryRequest generating functionality for
 * collectors
 * @author Itay Moav
 */
class DryRequestsCollectors_test extends TestCase {
	static public function setUpBeforeClass(){
		dbgn('======== DRY REQUESTS FOR COLLECTORS');
	}
	
	public function testGETDryRequest(){
		dbgn('Testing GET');
		// init
		$access_token = Env::$survey_monkey_config['access_token'];
		$Client = tests_get_proper_client();
		$fake_survey_id = 1234;
		$fake_collector_id = 4321;
		
		$headers = [
				'Authorization' => "bearer {$access_token}",
				'Content-type'  => 'application/json'
		];
		
		// TESTS 
		
		//1. default request
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}/collectors";
		$ExpectedDryRequest = new \Talis\Extensions\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Extensions\TheKof\HTTPClientWrapper_a::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->collectors()->get_dry();
		$this->assertInstanceOf(\Talis\Extensions\TheKof\Util_DryRequest::class, $ActualDryRequest,'Dry request must return a \Talis\Extensions\TheKof\DryRequest object');
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'response structure is not same');
		
		$this->assertEquals($expected_url, $ActualDryRequest->url(),'url does not match');
		$this->assertEquals('GET', $ActualDryRequest->method(),'METHOD does not match');
		$this->assertEquals($headers, $ActualDryRequest->headers(),'headers do not match');
		
		
		//2. page one, default size
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}/collectors?page=1";
		$ExpectedDryRequest = new \Talis\Extensions\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Extensions\TheKof\HTTPClientWrapper_a::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->collectors()->get_dry(1);
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'(page 1) response structure is not same');

		$this->assertEquals($expected_url, $ActualDryRequest->url(),'url does not match');
		$this->assertEquals('GET', $ActualDryRequest->method(),'METHOD does not match');
		$this->assertEquals($headers, $ActualDryRequest->headers(),'headers do not match');
		
		
		//3. page 2 size 10
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}/collectors?page=2&per_page=10";
		$ExpectedDryRequest = new \Talis\Extensions\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Extensions\TheKof\HTTPClientWrapper_a::METHOD_GET);
		
		$ActualDryRequest = $Client->surveys($fake_survey_id)->collectors()->get_dry(2,10);
		$this->assertEquals($ExpectedDryRequest,$ActualDryRequest,'(page 2,10) response structure is not same');
		
		$this->assertEquals($expected_url, $ActualDryRequest->url(),'url does not match');
		$this->assertEquals('GET', $ActualDryRequest->method(),'METHOD does not match');
		$this->assertEquals($headers, $ActualDryRequest->headers(),'headers do not match');
	}

	public function testPOSTDryRequest(){
		dbgn('Testing POST');
		// init
		$access_token = Env::$survey_monkey_config['access_token'];
		$Client = tests_get_proper_client();
		$fake_survey_id = 1234;
		
		$headers = [
				'Authorization' => "bearer {$access_token}",
				'Content-type'  => 'application/json'
		];
		
		// TESTS

		// create new web collector, minimal mandatory input
		$raw_data = new \stdClass;
		$raw_data->type = 'weblink';
		
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}/collectors";
		$ExpectedDryRequest = new \Talis\Extensions\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Extensions\TheKof\HTTPClientWrapper_a::METHOD_POST);
		$ExpectedDryRequest->body($raw_data);

		$CollectorModel = new \Talis\Extensions\TheKof\Model_Collector($raw_data);
		$ActualDryRequest = $Client->surveys($fake_survey_id)->collectors()->post_dry($CollectorModel);
		
		$this->assertInstanceOf(\Talis\Extensions\TheKof\Util_DryRequest::class, $ActualDryRequest,'post_dry method must return a \Talis\Extensions\TheKof\DryRequest object');
		$this->assertEquals($ExpectedDryRequest,    $ActualDryRequest,            'response structure is not same');
		$this->assertEquals($expected_url,          $ActualDryRequest->url(),     'url does not match');
		$this->assertEquals('POST',                 $ActualDryRequest->method(),  'METHOD does not match');
		$this->assertEquals($headers,               $ActualDryRequest->headers(), 'headers do not match');
		$this->assertEquals($raw_data,              $ActualDryRequest->body(),    'Body does not match');
		
		// create new web collector, all possible input field supplied TODO upgrade this test to use proper set methods with proper ENUM values from the MODEL class
		// next versions?
		$raw_data = new \stdClass;
		$raw_data->type = 'weblink';
		$raw_data->name = 'Test Collector PHPUnit';
		$raw_data->thank_you_message = ' Thankee Sai';
		$raw_data->disqualification_message = 'boohoo';
		$raw_data->close_date = '2038-01-01T00:00:00+00:00';
		$raw_data->closed_page_message = 'This survey is currently closed.';
		$raw_data->redirect_url = 'https://www.surveymonkey.com';
		$raw_data->display_survey_results = false;
		$raw_data->edit_response_type = 'until_complete';
		$raw_data->anonymous_type = 'not_anonymous';
		$raw_data->allow_multiple_responses = true;
		$raw_data->date_modified = '2015-10-06T12:56:55+00:00';
		$raw_data->date_created = '2015-10-06T12:56:55+00:00';
		$raw_data->password = 'babaganush';
		$raw_data->response_limit = 3;
		$raw_data->redirect_type = 'url';
		
		
		
		$expected_url = "https://api.surveymonkey.net/v3/surveys/{$fake_survey_id}/collectors";
		$ExpectedDryRequest = new \Talis\Extensions\TheKof\Util_DryRequest(Env::$survey_monkey_config['access_token']);
		$ExpectedDryRequest->url($expected_url);
		$ExpectedDryRequest->method(\Talis\Extensions\TheKof\HTTPClientWrapper_a::METHOD_POST);
		$ExpectedDryRequest->body($raw_data);
		
		$CollectorModel = new \Talis\Extensions\TheKof\Model_Collector($raw_data);
		$ActualDryRequest = $Client->surveys($fake_survey_id)->collectors()->post_dry($CollectorModel);
		
		$this->assertInstanceOf(\Talis\Extensions\TheKof\Util_DryRequest::class, $ActualDryRequest,'post_dry method must return a \Talis\Extensions\TheKof\DryRequest object');
		$this->assertEquals($ExpectedDryRequest,    $ActualDryRequest,            'response structure is not same');
		$this->assertEquals($expected_url,          $ActualDryRequest->url(),     'url does not match');
		$this->assertEquals('POST',                 $ActualDryRequest->method(),  'METHOD does not match');
		$this->assertEquals($headers,               $ActualDryRequest->headers(), 'headers do not match');
		$this->assertEquals($raw_data,              $ActualDryRequest->body(),    'Body does not match');
		
	}
}
