<?php namespace Talis\Extensions\TheKof;
/**
 * base abstract for each asset client and for the main client
 * 
 * @author Itay Moav
 * @date 17-11-2017
 */
abstract class Client_a{
	const SURVEY_MONKEY_SERVICE_URL = 'https://api.surveymonkey.net/v3';
	
	/**
	 * Configure values for this group of classes
	 *      access_token string : get it from SurveyMonkey app settings page
	 *
	 * @var array
	 */
	static protected $config = [];
	
	/**
	 * Http client Wrapper to handle actual http request.
	 * Make sure to configure that object ahead of sending it to this class
	 * with the actual http client
	 *
	 * @var HTTPClientWrapper_a
	 */
	static protected $HttpClientWrapper = null;
	
	/**
	 * Inits the client system
	 * The values entered here are gobal and immutable
	 * 
	 * @param array $config
	 * @param HTTPClientWrapper_a $HttpClientWrapper
	 * @throws \InvalidArgumentException
	 */
	static public function megatherion_init(array $config,HTTPClientWrapper_a $HttpClientWrapper){
		self::megatherion_validate_config_attributes($config);
		self::$config = $config;
		self::$HttpClientWrapper = $HttpClientWrapper;
	}
	
	/**
	 * Validates the $config array that has the necessary values
	 *
	 * @param array $config ['access_token']
	 *
	 * @throws \InvalidArgumentException
	 */
	static private function megatherion_validate_config_attributes(array $config):void{
		if(!isset($config['access_token'])){
			throw new \InvalidArgumentException('Missing access_token in $config');
		}
	}
	

	/**
	 * If we have a query, we start each section with this char
	 * Every method that uses it, need to change it to '&' to prevent two ? in
	 * url
	 * @var string
	 */
	protected $query_separator_char = '?';
	
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
	public function __construct(Util_DryRequest $current_dry_request = null){
		$this->current_dry_request = $current_dry_request;
		$this->add_url_part();//for each asset, adds the API point for it
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
			$this->current_dry_request->url_add("{$this->query_separator_char}page={$page}");
			$this->query_separator_char = '&';
			if($per_page > 0){
				$this->current_dry_request->url_add("{$this->query_separator_char}per_page={$per_page}");
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
	 * 
	 * @return Util_Collection
	 */
	public function get(int $page=0,int $per_page=0):Util_Collection{
		$this->get_dry($page,$per_page);
		return $this->build_asset(self::$HttpClientWrapper->execute_dry_request($this->current_dry_request));
	}
	
	/**
	 * If u expect only one item (you set the item id), or u just need the first item in
	 * a collection, use this method, which will return the Model object for the specific item you are looking for.
	 * It will also trigger the full data load on this object (TODO verify it is needed)
	 * 
	 * @return Model_a
	 */
	public function get_one():Model_a{
		return $this->get()->current();
	}
	
	/**
	 * POST dry will generate the request object, but wont post it.
	 * 
	 * @param Model_a $model
	 * @return Util_DryRequest
	 */
	public function post_dry(Model_a $model):Util_DryRequest{
		$this->current_dry_request->method(HTTPClientWrapper_a::METHOD_POST);
		$this->current_dry_request->body($model->get_raw_data());
		return $this->current_dry_request;
		
	}
	
	/**
	 * Takes the input model, creates in SM
	 * and populate the rest of the values in it from the response.
	 * 
	 * @param Model_a $model
	 * @return Model_a
	 */
	public function post(Model_a $model):Model_a{
		$this->post_dry($model);
		$raw_response = self::$HttpClientWrapper->execute_dry_request($this->current_dry_request);
		return $model->change_state($raw_response->body);
	}
	
	/**
	 * Alias for POST
	 * 
	 * @param Model_a $model
	 * @return Model_a
	 */
	public function create(Model_a $model):Model_a{
		return $this->post($model);	
	}
	
	/**
	 * If requesting a specific id, add it to the url
	 * @param integer $asset_id
	 * @return Client_a
	 */
	public function set_id(int $asset_id):Client_a{
		if($asset_id || $this->asset_id_received){
			$this->current_dry_request->url_add("/{$asset_id}");
			$this->asset_id_received = true;
		}
		return $this;
	}
	
	/**
	 * adds query params
	 * 
	 * title=xxxx This also works with partial names, will return all matching
	 * 
	 * @param string $query_string
	 * @return Client_a
	 */
	public function query(string $query_string):Client_a{
		$this->current_dry_request->url_add("{$this->query_separator_char}{$query_string}");
		$this->query_separator_char = '&';
		return $this;
	}
	
	/**
	 * THIS IS ALWAYS TO LOAD THE CURRENT ITEM, ONLY ONE!
	 * 
	 * Overrides any previously entered URL with the entered one.
	 * You can also use that directly, comes handy especially when u use the hrefs 
	 * in the reponse
	 * 
	 * @param string $href
	 * @return Client_a
	 */
	public function set_href(string $href):Client_a{
		$this->current_dry_request->url($href);
		return $this;
	}
	
	/**
	 * Takes the raw response and sends the appropriate translator
	 * to the collection object.
	 * The translation to the right model is done LAZY
	 * 
	 * @param Util_RawResponse $RawResponse
	 * @return Util_Collection
	 */
	protected function build_asset(Util_RawResponse $RawResponse):Util_Collection{
		$that = $this;
		$translation_func = function(\stdClass $single_item) use ($that){
			return $that->translate_to_model($single_item);
		};
		return new Util_Collection($RawResponse,$translation_func);
	}

	abstract protected function add_url_part():void;
	
	/**
	 * For each item/asset type there is one translator from stdClass object to 
	 * a specific Model object 
	 *  
	 * @param \stdClass $single_item
	 * @param Client_a $client used to fully load the model if there is a need. If the model is fully loaded, it will be discarded.
	 * 
	 * @return Model_a
	 */
	abstract protected function translate_to_model(\stdClass $single_item):Model_a;
}
