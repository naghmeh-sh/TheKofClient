<?php namespace Talis\Services\TheKof;
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
class DryRequest{
	const METHOD_GET	= 'GET';
	
	private $url,$method,$body,$headers;
	
	public function __construct($access_token){
		$this->headers([
				'Authorization' => "bearer {$access_token}",
				'Content-type'  => 'application/json'
		]);
	}
	
	public function url(string $url=''):string{
		return $this->url = $url?:$this->url;
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
