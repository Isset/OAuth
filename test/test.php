<?php

require_once(__DIR__ . '/helper.php');

use Waffles\Test;
use OAuth\Core\Consumer;

Test::group("Request an authorization token", function()
{
	Test::add("Get a request token from Google", function($test)
	{
		$oauth    = new Consumer(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
		$response = $oauth->request(
			'https://www.google.com/accounts/OAuthGetRequestToken', 
			'GET',
			array(
				'scope' => 'https://www.google.com/webmasters/tools/feeds/sites/',
			)
		);

		parse_str($response, $response);

		$test->expects($response['oauth_token'])->to_not()->be_empty();
		$test->expects($response['oauth_token_secret'])->to_not()->be_empty();
	});

	Test::add("Get an access token from Google", function($test)
	{
		$oauth    = new Consumer(OAUTH_CONSUMER_KEY, OAUTH_CONSUMER_SECRET);
		$response = $oauth->request(
			'https://www.google.com/accounts/OAuthGetRequestToken', 
			'GET',
			array(
				'scope'          => 'https://www.google.com/webmasters/tools/feeds/sites/',
				'oauth_callback' => OAUTH_CALLBACK
			)
		);

		parse_str($response, $response);

		$test->expects($response['oauth_token'])->to_not()->be_empty();
		$test->expects($response['oauth_token_secret'])->to_not()->be_empty();

		$token  = $response['oauth_token'];
		$secret = $response['oauth_token_secret'];

		// Create a new request
		$response = $oauth->sign(
			'https://www.google.com/accounts/OAuthAuthorizeToken', 
			'GET',
			array(
				'oauth_token' => $token
			)
		);

		// Authorize the user
		$response_file = __DIR__ . '/response.txt';

		if ( file_exists($response_file) )
		{
			unlink($response_file);
		}

		shell_exec("open '{$response['signed_url']}'");

		// Wait for the response
		while ( !file_exists($response_file) )
		{
			sleep(0.5);
		}

		// Assume we have the file, read the contents and validate it
		$response_file = unserialize(file_get_contents($response_file));

		$test->expects($response_file['oauth_verifier'])->to_not()->be_empty();
		$test->expects($response_file['oauth_token'])->to_not()->be_empty();

		// Get the access token from Google
		$oauth->oauth_config['oauth_secret'] = $secret;
		$oauth->oauth_config['oauth_token']  = $response_file['oauth_token'];

		// Reset the request as things will get messed up otherwise
		$oauth->reset();

		$response = $oauth->request(
			'https://www.google.com/accounts/OAuthGetAccessToken',
			'GET',
			array(
				'oauth_verifier' => $response_file['oauth_verifier'],
				'oauth_token'    => $response_file['oauth_token']
			)
		);

		parse_str($response, $response);

		// Validate and store the tokens
		$test->expects($response['oauth_token'])->to_not()->be_empty();
		$test->expects($response['oauth_token_secret'])->to_not()->be_empty();
	});
});

Test::run_all();
