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
	
}