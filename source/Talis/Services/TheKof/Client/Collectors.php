<?php namespace Talis\Services\TheKof;
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
}
