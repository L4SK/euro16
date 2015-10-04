<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      //appId		: $.getenv("FACEBOOK_APP_ID"),
      appId		: '882396735180931',
      xfbml		: true,
      cookie	: true,
      version	: 'v2.4'
    });
  };

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
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