<?php
use PHPUnit\Framework\TestCase;


/**
 * Testing the DryRequest generating functionality for
 * Surveys
 * 
 * @author Itay Moav
 */
class Configure_test extends TestCase {
	static public function setUpBeforeClass(){
		dbgn('======== CONFIGURATION');
	}

	/**
	 * Tests the get/set params
	 */
	public function testConfiguremissingAccessToekn(){
		$this->expectException(InvalidArgumentException::class);
		$Client = Talis\Extensions\TheKof\SurveyMonkeyClient::init([],new TestHTTPClientWrapper);
	}
	
	public function testConfigureWithAccessToekn(){
		$Client = tests_get_proper_client();
		$this->assertInstanceOf(\Talis\Extensions\TheKof\SurveyMonkeyClient::class, $Client,'Client did not initiate with test configuration');
	}
}
