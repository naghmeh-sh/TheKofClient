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
	 * The original data object from SM
	 * @var \stdClass
	 */
	protected $item_data;
	
	public function __construct(\stdClass $single_item){
		$this->item_data = $single_item;
		$this->set_if_fully_loaded();
	}
	
	/**
	 * Will fully load the item from SM and replace the existing item_data with the 
	 * return result
	 * 
	 * @return Model_a
	 */
	public function fully_load():Model_a{
		if(!$this->is_fully_loaded){
			$this->item_data = $this->get_client()->get_one()->item_data;
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
	 * get method for item id
	 * 
	 * @return integer
	 */
	public function id():int{
		return $this->item_data->id*1;
	}
	
	/**
	 * Sets the $is_fully_loaded flag according to the info found in item_data
	 */
	abstract protected function set_if_fully_loaded();
	
	/**
	 * Returns a client where the current 
	 * item is the top of the drill down.
	 * 
	 * @return Client_a
	 */
	abstract protected function get_client():Client_a;
		
}