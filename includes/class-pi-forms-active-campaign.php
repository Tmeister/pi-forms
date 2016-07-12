<?php

/**
 * The Active Campaign SDK
 */
require_once plugin_dir_path( __FILE__ ) . '/vendors/activecampaign/includes/ActiveCampaign.class.php';

/**
 * Class ActiveCampaignProxy
 */
class ActiveCampaignProxy {

	/**
	 * @var $api_url
	 * The API URL
	 */
	protected $api_url;

	/**
	 * @var $api_key
	 * The API KEY
	 */
	protected $api_key;

	protected $app;

	public function __construct( $api_url, $api_key ) {
		$this->api_key = $api_key;
		$this->api_url = $api_url;
	}

	public function testCredentials() {
		if ( $this->api_key && $this->api_url ) {
			try {
				$this->app = new ActiveCampaign( $this->api_url, $this->api_key );
				if ( ! $this->app->credentials_test() ) {

					return - 1;
				}

				return true;
			} catch ( Exception $e ) {
				return false;
			}
		} else {
			return false;
		}
	}
}