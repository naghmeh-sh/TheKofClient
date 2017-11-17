<?php namespace Talis\Services\TheKof;
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
	 * @param DryRequest $DryRequest
	 */
	public function execute_dry_request(DryRequest $DryRequest){
		$this->concrete_http_client->setMethod($DryRequest->method());
		$this->concrete_http_client->setUri($DryRequest->url());
		$this->concrete_http_client->setHeaders($DryRequest->headers());
		$response = $this->concrete_http_client->send();
		var_dump($response->getBody());
	}
}