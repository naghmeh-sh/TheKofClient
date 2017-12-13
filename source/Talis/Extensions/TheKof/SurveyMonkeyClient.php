<?php namespace Talis\Extensions\TheKof;

/**
 * Class is the "boss" of this entire system.
 * It provides the API to build and execute the queries to Survey monkey
 * 
 * @author Itay Moav
 * @Date 13-11-2017
 *
 */
class SurveyMonkeyClient extends Client_a{
	
	/**
	 * Init system and return a ready survey monkey client
	 * 
	 * @param array $config
	 * @param HTTPClientWrapper_a $HttpClientWrapper
	 * @return SurveyMonkeyClient
	 */
	static public function init(array $config,HTTPClientWrapper_a $HttpClientWrapper):SurveyMonkeyClient{
		self::megatherion_init($config, $HttpClientWrapper);
		return new SurveyMonkeyClient;
	}
	
	/**
	 * Shutdown, as this client has no model attached.
	 * 
	 * {@inheritDoc}
	 * @see \Talis\Extensions\TheKof\Client_a::translate_to_model()
	 */
	protected function translate_to_model(\stdClass $single_item):Model_a{
		return null; //do nothing. This is the base of the chain TODO potentially this can break the code. But, this is a piece of dead code...
	}
	
	/**
	 * shutdown as this client has no direct queries
	 * 
	 * {@inheritDoc}
	 * @see \Talis\Extensions\TheKof\Client_a::add_url_part()
	 */
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
		$this->current_dry_request = new Util_DryRequest(self::$config['access_token']);
		$this->current_dry_request->url(self::SURVEY_MONKEY_SERVICE_URL);// ($survey_id?"/{$survey_id}":''));
		$SurveyClient = new Client_Surveys($this->current_dry_request);
		$SurveyClient->set_id($survey_id);
		return $SurveyClient;
	}
	
	/**
	 * this is not a drill down, this is to get 
	 * a client for a known collector.
	 * This is the top method
	 * 
	 * @param int $collector_id
	 */
	public function collector(int $collector_id):Client_Collectors{
		$this->current_dry_request = new Util_DryRequest(self::$config['access_token']);
		$this->current_dry_request->url(self::SURVEY_MONKEY_SERVICE_URL);// ($survey_id?"/{$survey_id}":''));
		$CollectorClient = new Client_Collectors($this->current_dry_request);
		$CollectorClient->set_id($collector_id);
		return $CollectorClient;
	}
}
