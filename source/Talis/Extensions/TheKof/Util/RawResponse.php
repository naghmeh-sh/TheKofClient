<?php namespace Talis\Extensions\TheKof;
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
