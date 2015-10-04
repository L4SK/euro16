<?php

require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
	'app_id' => getenv(FACEBOOK_APP_ID),
	'app_secret' => getenv(FACEBOOK_SECRET),
	'default_graph_version' => 'v2.4',
	]);

$helper = $fb->getCanvasHelper();
var_dump($helper);
try {
	$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}

if (isset($accessToken)) {
	// OAuth 2.0 client handler
	$oAuth2Client = $fb->getOAuth2Client();

	// Exchanges a short-lived access token for a long-lived one
	$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken('{access-token}');

	// Sets the default fallback access token so we don't have to pass it to each request
	$fb->setDefaultAccessToken('{access-token}');

	try {
		$response = $fb->get('/me');
		$userNode = $response->getGraphUser();
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		// When Graph returns an error
		echo 'Graph returned an error: ' . $e->getMessage();
		exit;
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		// When validation fails or other local issues
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	echo 'Logged in as ' . $userNode->getName();

	try {
		$response = $fb->get('/me');
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		echo 'Facebook SDK returned an error: ' . $e->getMessage();
		exit;
	}

	$plainOldArray = $response->getDecodedBody();
	var_dump($plainOldArray);
}


?>