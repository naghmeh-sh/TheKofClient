<?php namespace Talis\Services\TheKof;

/**
 * Class is the "boss" of this entire system.
 * It provides the API to build and execute the queries to Survey monkey
 * 
 * @author Itay Moav
 * @Date 13-11-2017
 *
 */
class SurveyMonkeyClient extends Client_a{
	
	protected function translate_to_model(\stdClass $single_item):Model_a{
		return null; //do nothing. This is the base of the chain TODO potentially this can break the code. But, this is a piece of dead code...
	}
	
	protected function add_url_part():void{
		//do nothing. This is the base of the chain	
	}
	
	/**
	 * Initiate a surveys dry request
	 * 
	 * @param int $survey_id
	 * @return SurveyMonkeyClient
	 */
	public function surveys(int $survey_id = 0):Client_Surveys{
		//survey is a major object -> I reset the requests
		$this->current_dry_request = new Util_DryRequest($this->config['access_token']);
		$this->current_dry_request->url(self::SURVEY_MONKEY_SERVICE_URL);// ($survey_id?"/{$survey_id}":''));
		$SurveyClient = new Client_Surveys($this->config,$this->HttpClientWrapper,$this->current_dry_request);
		$SurveyClient->set_id($survey_id);
		return $SurveyClient;
	}
}
