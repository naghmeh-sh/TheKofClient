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
}
