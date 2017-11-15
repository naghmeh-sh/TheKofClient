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
