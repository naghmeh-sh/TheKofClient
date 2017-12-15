<?php namespace Talis\Extensions\TheKof;
class Util_CommunicationIssuesExceptions extends \Exception{
	public function __construct($message,$code){
		parent::__construct("Failed communication with Survey Monkey. Code [{$code}] message [{$message}]",$code);
	}
}
