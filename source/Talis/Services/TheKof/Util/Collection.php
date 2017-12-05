<?php namespace Talis\Services\TheKof;
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
