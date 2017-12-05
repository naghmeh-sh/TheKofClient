<?php namespace Talis\Services\TheKof;
class Model_Collector extends Model_a{

	protected function set_if_fully_loaded(){
		$this->is_fully_loaded = isset($this->item_data->date_created);
	}
}