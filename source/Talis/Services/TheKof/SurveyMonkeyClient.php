<?php namespace Talis\Services\TheKof;

/**
 * Class is the "boss" of this entire system.
 * It provides the API to build and execute the queries to Survey monkey
 * 
 * @author Itay Moav
 * @Date Nov 13th 2017
 *
 */
class SurveyMonkeyClient{
	const SURVEY_MONKEY_SERVICE_URL = 'https://api.surveymonkey.net/v3';
	
	/**
	 * Configure values for this class
	 *      access_token string : get it from SurveyMonkey app settings page
	 *       
	 * @var array
	 */
	private $config = [];
	
	/**
	 * Http client to handle actual http request.
	 * Make sure to configure that object ahead of sending it to this class
	 * 
	 * @var Zend\Http\Client
	 */	
	private $HttpClient = null;
	
	/**
	 * Client builds requests, according to called methods and params.
	 * That request is then sent to method or method_dry to be executed.
	 * This variable is where I store the current request the client is 
	 * working on.
	 * 
	 * @var DryRequest
	 */
	private $current_dry_request = null;
	
	/**
	 * @param array $config
	 * @param Zend\Http\Client $HttpClient
	 * @throws \InvalidArgumentException
	 */
	public function __construct(array $config,Zend\Http\Client $HttpClient=null){
		$this->validate_config_attributes($config);
		$this->config = $config;
	}
	
	/**
	 * Validates the $config array that has the necessary values
	 * 
	 * @param array $config ['access_token']
	 * 
	 * @throws \InvalidArgumentException
	 */
	private function validate_config_attributes(array $config):void{
		if(!isset($config['access_token'])){
			throw new \InvalidArgumentException('Missing access_token in $config');
		}
	}
	
	/**
	 * Initiate a surveys dry request
	 * 
	 * @param int $survey_id
	 * @return SurveyMonkeyClient
	 */
	public function surveys(int $survey_id = 0):SurveyMonkeyClient{
		//survey is a major object -> I reset the requests
		$this->current_dry_request = new DryRequest($this->config['access_token']);
		$this->current_dry_request->url(self::SURVEY_MONKEY_SERVICE_URL . '/surveys' . ($survey_id?"/{$survey_id}":''));
		
		return $this;
	}
	
	/**
	 * Adds paging to url and return;
	 * 
	 * @return \Talis\Services\TheKof\DryRequest
	 */
	public function get_dry(){
		$this->current_dry_request->method(DryRequest::METHOD_GET);
		return $this->current_dry_request;
	}
}
