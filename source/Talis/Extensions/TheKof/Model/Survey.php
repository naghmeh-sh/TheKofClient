<?php namespace Talis\Extensions\TheKof;
class Model_Survey extends Model_a{

	protected function get_client():Client_a{
		return (new SurveyMonkeyClient)->surveys($this->item_data->id);
	}
	
	protected function set_if_fully_loaded(){
		$this->is_fully_loaded = isset($this->item_data->id) && isset($this->item_data->response_count);
	}
	
	/**
	 * Get the collectors client for the current survey
	 * collectors
	 * 
	 * @param int $collector_id
	 * @return Client_Collectors
	 */
	public function collectors(int $collector_id=0):Client_Collectors{
		return $this->get_client()->collectors($collector_id);
	}
}