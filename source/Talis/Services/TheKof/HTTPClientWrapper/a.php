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
	abstract public function execute_dry_request(Util_DryRequest $DryRequest):Util_Response;
}

