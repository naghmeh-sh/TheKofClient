<?php
use PHPUnit\Framework\TestCase;

/**
 * Test the client basic functionality
 * validating the configuration file
 * 
 * @author Itay Moav
 */
class SurveyMonkeyClient_test extends TestCase {
	
	/**
	 * Tests the get/set params
	 */
	public function testConfiguremissingAccessToekn(){
		$this->expectException(InvalidArgumentException::class);
		$Client = new Talis\Services\TheKof\SurveyMonkeyClient([]);
	}
}