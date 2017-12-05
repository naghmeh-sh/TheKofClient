<?php namespace Talis\Services\TheKof;
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