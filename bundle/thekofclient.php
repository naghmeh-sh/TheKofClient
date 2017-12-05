<?php namespace Talis\Services\TheKof;


/**
 * @author Itay Moav
 * @Date Nov 17 - 2017
 */
abstract class HTTPClientWrapper_a{
	/**
	 * HTTP method types
	 * 
	 * @var string $METHOD_GET
	 * @var string $METHOD_POST
	 * @var string $METHOD_PUT
	 * @var string $METHOD_DELETE
	 * @var string $METHOD_OPTIONS
	 * @var string $METHOD_HEAD
	 */
	const METHOD_GET	 = 'GET',
		  METHOD_POST	 = 'POST',
		  METHOD_PUT	 = 'PUT',
		  METHOD_DELETE  = 'DELETE',
		  METHOD_OPTIONS = 'OPTIONS',
		  METHOD_HEAD	 = 'HEAD'
	;
	
	/**
	 * @var mixed the actual http client
	 */
	protected $concrete_http_client = null;

	/**
	 * Just send in the instantiated http client
	 * 
	 * @param mixed $concrete_http_client
	 */
	public function __construct($concrete_http_client){
		$this->concrete_http_client = $concrete_http_client;
	}
	
	/**
	 * This is where the actual translation from DryRequest info to the actual client
	 * is happening.
	 * 
	 * @param Util_DryRequest $DryRequest
	 * TODO what do I return here? a dry response?
	 */
	abstract public function execute_dry_request(Util_DryRequest $DryRequest):Util_RawResponse;
}



/**
 * @author Itay Moav
 * @Date Nov 17 - 2017
 */
class HTTPClientWrapper_ZendFW2 extends HTTPClientWrapper_a{
	
	/**
	 * I have it here for sake of documentation
	 * 
	 * @var \Zend\Http\Client
	 */
	protected $concrete_http_client = null;
	
	/**
	 * This is where the actual translation from DryRequest info to the actual client
	 * is happening.
	 *
	 * @param Util_DryRequest $DryRequest
	 * @return Util_RawResponse
	 */
	public function execute_dry_request(Util_DryRequest $DryRequest):Util_RawResponse{
		echo "
==================================================
DOing " . $DryRequest->url() . "



";
		$this->concrete_http_client->setMethod($DryRequest->method());
		$this->concrete_http_client->setUri($DryRequest->url());
		$this->concrete_http_client->setHeaders($DryRequest->headers());
		$res = $this->concrete_http_client->send();
		$Response = new Util_RawResponse;
		$Response->http_code 			= $res->getStatusCode();
		$Response->http_code_message	= $res->getReasonPhrase();
		$Response->headers				= $res->getHeaders()->toArray();
		$Response->body					= json_decode($res->getBody());
		return $Response;
	}
}

class Model_Survey extends Model_a{

	protected function set_if_fully_loaded(){
		$this->is_fully_loaded = isset($this->item_data->response_count);
	}
}

class Model_Collector extends Model_a{

	protected function set_if_fully_loaded(){
		$this->is_fully_loaded = isset($this->item_data->date_created);
	}
}

abstract class Model_a{
	/**
	 * When querying a collection (as opposed to one item by id) the result returns
	 * the minimum needed fields.
	 * To get the full item info, another request has to be done (with the full info url).
	 * @var bool
	 */
	protected $is_fully_loaded = false;
	
	/**
	 * If there is a need to fully load the data from SM,
	 * This is the client we should use.
	 * 
	 * @var Client_a
	 */
	protected $client_to_fully_load;
	
	/**
	 * The original data object from SM
	 * @var \stdClass
	 */
	protected $item_data;
	
	public function __construct(\stdClass $single_item,Client_a $client_to_fully_load){
		$this->item_data = $single_item;
		$this->set_if_fully_loaded();
		if(!$this->is_fully_loaded){
			$this->client_to_fully_load = $client_to_fully_load;
		}
	}
	
	/**
	 * Will fully load the item from SM and replace the existing item_data with the 
	 * return result
	 * 
	 * @return Model_a
	 */
	public function fully_load():Model_a{
		if(!$this->is_fully_loaded){
			$this->item_data = $this->client_to_fully_load->set_href($this->item_data->href)->get_one()->item_data;
			$this->client_to_fully_load = null;
		}
		return $this;
	}
	
	/**
	 * CAREFULL - THIS IS read/write access, objects are transfered by REF.
	 * 
	 * @return \stdClass
	 */
	public function get_raw_data():\stdClass{
		return $this->item_data;
	}
	
	/**
	 * Sets the $is_fully_loaded flag according to the info found in item_data
	 */
	abstract protected function set_if_fully_loaded();
		
}

/**
 * Takes a raw resonse with a translatore and translates 
 * each element in raw response to it's model. 
 * Provide utilities to iterate over the collection of item
 * and to fetch the next/previous page
 * 
 * @author Itay Moav
 * @date   20-11-2017
 */
class Util_Collection implements \Iterator{
	/**
	 * Used to take the raw response and translate to the appropriate 
	 * model object
	 * 
	 * @var callable
	 */
	private $translation_func;
	
	/**
	 * Array of the data items fetched by the request
	 * 
	 * @var array
	 */
	private $data_collection = [];
	
	private $page = 1;
	
	private $page_size = 50;
	
	/**
	 * Total entries/items 
	 * 
	 * @var integer
	 */
	private $total_entries_in_query = 1;
	
	/**
	 * Url for the next page for this set
	 * @var string
	 */
	private $link_next = '';
	
	/**
	 * Url for the previous page, before this page
	 * @var string
	 */
	private $link_previous = '';
	
	public function __construct(Util_RawResponse $RawResponse,callable $translation_func){
		$this->parse_raw_response($RawResponse);
		$this->translation_func = $translation_func;
	}
	
	/**
	 * Disects the response into the relevant 
	 * members.
	 * 
	 * @param Util_RawResponse $RawResponse
	 */
	private function parse_raw_response(Util_RawResponse $RawResponse):void{
		//NOTICE! if the query fetches only one result, then the response wont have [data].
		//It will have ONLY the one, fully loaded, object
		if(isset($RawResponse->body->id) && $RawResponse->body->id){//one full object was returned
			$this->data_collection 			= [$RawResponse->body];
			$this->total_entries_in_query 	= 1;
			$this->page_size 				= 1;
			$this->page 					= 1;
			$this->link_previous			= null;
			$this->link_next				= null;
		} else { //a real collection
			$this->data_collection 			= $RawResponse->body->data;
			$this->total_entries_in_query 	= $RawResponse->body->total;
			$this->page_size 				= $RawResponse->body->per_page;
			$this->page 					= $RawResponse->body->page;
			$this->link_previous			= $RawResponse->body->prev??null;//at the edges u can still get null here 
			$this->link_next				= $RawResponse->body->next??null;//at the edges u can still get null here
		}
	}
	
	
	public function current(){
		$func = $this->translation_func;
		return $func(current($this->data_collection));
	}
	
	public function next(){
		return next($this->data_collection);
	}
	
	public function key(){
		return key($this->data_collection);
	}
	
	public function valid(){
		return current($this->data_collection);
	}
	
	public function rewind(){
		reset($this->data_collection);
	}
}


/**
 * Data structure for holding a unified response object
 * from which ever http client u wish to use.
 * No fancy stuff, plain and simple.
 * 
 * @author Itay Moav
 * @date   17/11/2017
 *
 */
class Util_RawResponse{
	public $http_code,
		   $http_code_message,
		   $headers,
		   $body
	;
}


/**
 * Data structure for holding a request details.
 * Usefull for mocking up tests, overriding the default use
 * of Zend FW for the HTTP request.
 * Very similar to the TalisMS Request object, but is to be used here internaly, no depndencies.
 * 
 * @author Itay Moav
 * @date   14/11/2017
 *
 */
class Util_DryRequest{
	
	private $url,$method,$body,$headers;
	
	public function __construct($access_token){
		$this->headers([
				'Authorization' => "bearer {$access_token}",
				'Content-type'  => 'application/json'
		]);
	}
	
	public function __toString(){
		$res = new \stdClass;
		$res->url     = $this->url;
		$res->method  = $this->method;
		$res->headers = $this->headers;
		$res->body    = $this->body;
		return json_encode($res);
	}
	
	/**
	 * Sets the url to input value
	 * 
	 * @param string $url
	 * @return string url
	 */
	public function url(string $url=''):string{
		return $this->url = $url?:$this->url;
	}
	
	/**
	 * Concats to the current url
	 * 
	 * @param string $concate_url
	 * @return string the modified url
	 */
	public function url_add(string $concate_url):string{
		return $this->url .= $concate_url;
	}
	
	
	public function method(string $method=''):string{
		return $this->method= $method?:$this->method;
	}
	
	public function body($body=''){
		return $this->body = $body?:$this->body;
	}
	
	public function headers(array $headers=[]):array{
		return $this->headers = $headers?:$this->headers;
	}
}


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
	 * 
	 * @return Util_Collection
	 */
	public function get(int $page=0,int $per_page=0):Util_Collection{
		$this->get_dry($page,$per_page);
		return $this->build_asset($this->HttpClientWrapper->execute_dry_request($this->current_dry_request));
	}
	
	/**
	 * If u expect only one item (you set the item id), or u just need the first item in
	 * a collection, use this method, which will return the Model object for the specific item you are looking for.
	 * It will also trigger the full data load on this object (TODO verify it is needed)
	 * 
	 * @return Model_a
	 */
	public function get_one():Model_a{
		$collection = $this->get();
		return $collection->current();
	}
	
	
	
	/**
	 * If requesting a specific id, add it to the url
	 * @param integer $asset_id
	 * @return Client_a
	 */
	public function set_id(int $asset_id):Client_a{
		if($asset_id){
			$this->current_dry_request->url_add("/{$asset_id}");
			$this->asset_id_received = true;
		}
		return $this;
	}
	
	/**
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
			return $that->translate_to_model($single_item,$that);
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
	abstract protected function translate_to_model(\stdClass $single_item,Client_a $client):Model_a;
}


/**
 * Surveys client
 * 
 * @author Itay Moav
 * @date 17-11-2017
 */
class Client_Surveys extends Client_a{
	protected function add_url_part():void{
		$this->current_dry_request->url_add('/surveys');
	}
	
	/**
	 * Drills into the current survey(s) collectors
	 * Calling the collector client REQUIRES you to send a survey id
	 * 
	 * @param int $collector_id
	 * @return Client_Collectors
	 */
	public function collectors(int $collector_id=0):Client_Collectors{
		if(!$this->asset_id_received){
			throw new \LogicException('Missing survey id when drilldown into collectors');
		}
		//survey is a major object -> I reset the requests
		$CollectorsClient = new Client_Collectors($this->config,$this->HttpClientWrapper,$this->current_dry_request);
		$CollectorsClient->set_id($collector_id);
		return $CollectorsClient;
	}

	/**
	 * Sends the data of a single item to the right model class
	 * 
	 * {@inheritDoc}
	 * @see \Talis\Services\TheKof\Client_a::translate_to_model()
	 */
	protected function translate_to_model(\stdClass $single_item,Client_a $client):Model_a{
		return new Model_Survey($single_item,$client);
	}
}


/**
 * Collectors client
 * 
 * @author Itay Moav
 * @date 17-11-2017
 */
class Client_Collectors extends Client_a{
	protected function add_url_part():void{
		$this->current_dry_request->url_add('/collectors');
	}
	
	protected function translate_to_model(\stdClass $single_item,Client_a $client):Model_a{
		return new Model_Collector($single_item,$client);
	}
}



/**
 * Class is the "boss" of this entire system.
 * It provides the API to build and execute the queries to Survey monkey
 * 
 * @author Itay Moav
 * @Date 13-11-2017
 *
 */
class SurveyMonkeyClient extends Client_a{
	
	protected function translate_to_model(\stdClass $single_item,Client_a $client):Model_a{
		return null; //do nothing. This is the base of the chain TODO potentially this can break the code. But, this is a piece of dead code...
	}
	
	protected function add_url_part():void{
		//do nothing. This is the base of the chain	
	}
	
	/**
	 * Initiate a surveys dry request
	 * 
	 * @param int $survey_id
	 * @return SurveyMonkeyClient
	 */
	public function surveys(int $survey_id = 0):Client_Surveys{
		//survey is a major object -> I reset the requests
		$this->current_dry_request = new Util_DryRequest($this->config['access_token']);
		$this->current_dry_request->url(self::SURVEY_MONKEY_SERVICE_URL);// ($survey_id?"/{$survey_id}":''));
		$SurveyClient = new Client_Surveys($this->config,$this->HttpClientWrapper,$this->current_dry_request);
		$SurveyClient->set_id($survey_id);
		return $SurveyClient;
	}
}
