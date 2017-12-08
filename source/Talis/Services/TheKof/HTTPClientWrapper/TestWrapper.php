<?php namespace Talis\Services\TheKof;
/**
 * @author Itay Moav
 * @Date Dec 8 - 2017
 */
class HTTPClientWrapper_TestWrapper extends HTTPClientWrapper_a{
	
	public function __construct(){
		$this->concrete_http_client = null;
	}
	
	/**
	 * This is where the actual translation from DryRequest info to the actual client
	 * is happening.
	 *
	 * @param Util_DryRequest $DryRequest
	 * @return Util_RawResponse
	 */
	public function execute_dry_request(Util_DryRequest $DryRequest):Util_RawResponse{
		echo "\n==================================================\nDOing " . $DryRequest->url() . "\n\n\n\n";
		$Response = new Util_RawResponse;
		$Response->http_code 			= 200;
		$Response->http_code_message	= 'baba was here - all is good';
		$Response->headers				= [];
		$Response->body					= new stdClass;
		return $Response;
	}
}