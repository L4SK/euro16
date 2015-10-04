
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '882396735180931',
      xfbml      : true,
      version    : 'v2.4'
    });
    FB.getLoginStatus(function(response) {
	  if (response.status === 'connected') {
	    console.log('Logged in.');
	  }
	  else {
	    FB.login();
	  }
	});
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>

<?php

require_once __DIR__ . '/vendor/autoload.php';
$fb = new Facebook\Facebook([
	'app_id' => getenv(FACEBOOK_APP_ID),
	'app_secret' => getenv(FACEBOOK_SECRET),
	'default_graph_version' => 'v2.4',
	]);


$helper = $fb->getJavaScriptHelper();
echo "HELPER = ";
var_dump($helper);
echo "<br><br><br>";
try {
  $accessToken = $helper->getAccessToken();
  echo "accessToken = ";
	var_dump($accessToken);
	echo "<br><br><br>";
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
	echo "IN ISSET ACCESSTOKEN";
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