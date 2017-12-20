<?php namespace Talis\Extensions\TheKof;
class Model_Collector extends Model_a{

	protected function get_client():Client_a{
		return (new SurveyMonkeyClient)->collector($this->item_data->id);
	}
	
	protected function set_if_fully_loaded(){
		$this->is_fully_loaded = isset($this->item_data->date_created);
	}
	
	/**
	 * The url you redirect user to take survey
	 * 
	 * @return string
	 */
	public function url():string{
		$this->fully_load();
		return $this->item_data->url;
	}
}