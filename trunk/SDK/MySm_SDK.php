<?php 

include_once 'OAuth2Exception.php';
include_once 'OAuth2Client.php';

$websrc = "https://www.mysmark.com/";

class MysmSDK extends OAuth2Client {

	function __construct( $client_id, $client_secret ) {
	  global $websrc;
		$config = array(
			"base_uri" => $websrc."api", 
			"client_id" => $client_id,
			"client_secret" => $client_secret, 
			"access_token_uri" => $websrc."oauth2/token" );
		parent::__construct( $config );
	}

	public function getAccessToken() {
		$session = $this->getSession();
		if( isset($session['access_token']) )
		return $session['access_token'];
		$response = json_decode(
		$this->makeRequest(
		$this->conf['access_token_uri'],
				"POST", 
		array(
					"grant_type" => "client_credentials",
					"client_id" => $this->conf['client_id'], 
					"client_secret" => $this->conf['client_secret'] )
		), TRUE );
		if( !is_array($response) || 
			!array_key_exists( "access_token", $response ) ) {
			throw new OAuth2Exception( $response );
		}
		$this->setSession( $this->getSessionObject( $response ), FALSE );
		return $response["access_token"];
	}
}
?>
