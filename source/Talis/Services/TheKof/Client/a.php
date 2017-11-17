<?php namespace Talis\Services\TheKof;
/**
 * base abstract for each asset client and for the main client
 * 
 * @author Itay Moav
 * @date 17-11-2017
 */
abstract class Client_a{
	const SURVEY_MONKEY_SERVICE_URL = 'https://api.surveymonkey.net/v3';
	
	/**
	 * Configure values for this class
	 *      access_token string : get it from SurveyMonkey app settings page
	 *
	 * @var array
	 */
	protected $config = [];
	
	/**
	 * Http client Wrapper to handle actual http request.
	 * Make sure to configure that object ahead of sending it to this class
	 * with the actual http client
	 *
	 * @var HTTPClientWrapper_a
	 */
	protected $HttpClientWrapper = null;
	
	/**
	 * Client builds requests, according to called methods and params.
	 * That request is then sent to method or method_dry to be executed.
	 * This variable is where I store the current request the client is
	 * working on.
	 *
	 * @var Util_DryRequest
	 */
	protected $current_dry_request = null;
	
	/**
	 * Some drill down elements (like collectors)
	 * requires the existance of the parent elemend id.
	 * This var is tracking this.
	 * It is being set in the set_id() method
	 * 
	 * @var bool
	 */
	protected $asset_id_received   = false;
	
	/**
	 * @param array $config
	 * @param HTTPClientWrapper_a $HttpClientWrapper
	 * @param Util_DryRequest $current_dry_request bubbled from the previous client
	 * 
	 * @throws \InvalidArgumentException
	 */
	public function __construct(array $config,HTTPClientWrapper_a $HttpClientWrapper=null,Util_DryRequest $current_dry_request = null){
		$this->validate_config_attributes($config);
		$this->config     = $config;
		$this->HttpClientWrapper = $HttpClientWrapper;
		$this->current_dry_request = $current_dry_request;
		$this->add_url_part();//for each asset, adds the API point for it
	}
	
	/**
	 * Validates the $config array that has the necessary values
	 *
	 * @param array $config ['access_token']
	 *
	 * @throws \InvalidArgumentException
	 */
	protected function validate_config_attributes(array $config):void{
		if(!isset($config['access_token'])){
			throw new \InvalidArgumentException('Missing access_token in $config');
		}
	}
	
	/**
	 * Adds paging (if values entered and return a Dry reques
	 * with the needed info to construct an http request
	 * (You could technically create a wrapper on top of this method
	 *  to generate curl, Zend CLient or any other way to do the actual http).
	 *
	 * @param int $page if zero, parameter will be ommited and SM defaults will be used
	 * @param int $per_page if zero, parameter will be ommited and SM defaults will be used
	 *
	 * @return Util_Util_DryRequest
	 */
	public function get_dry(int $page=0,int $per_page=0):Util_DryRequest{
		$this->current_dry_request->method(HTTPClientWrapper_a::METHOD_GET);
		if($page > 0){
			$this->current_dry_request->url_add("?page={$page}");
			if($per_page > 0){
				$this->current_dry_request->url_add("&per_page={$per_page}");
			}
		}
		return $this->current_dry_request;
	}
	
	/**
	 * Performs the actual GET http, uses dry request to generate the values
	 * for the http request
	 *
	 * @param int $page
	 * @param int $per_page
	 */
	public function get(int $page=0,int $per_page=0){
		$this->get_dry($page,$per_page);
		$response = $this->HttpClientWrapper->execute_dry_request($this->current_dry_request);
		var_dump($response);
	}
	
	/**
	 * If requesting a specific id, add it to the url
	 * @param integer $asset_id
	 */
	public function set_id(int $asset_id):void{
		if($asset_id){
			$this->current_dry_request->url_add("/{$asset_id}");
			$this->asset_id_received = true;
		}
	}
	
	abstract protected function add_url_part():void;
	
}
