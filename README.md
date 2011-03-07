# README

OAuth is a relatively basic wrapper around OAuthSimple and cURL that focuses on reducing
the amount of boilerplate code when working with OAuthSimple and cURL. This library
also focuses on a consumer and has no support for running OAuth powered servers, while
this may be added in the future this could take a long time so you're better off either
implementing a server yourself or using a different library for it.

## Requirements

* PHP 5.3 or newer
* cURL
* OAuthSimple (ships with this package)

When running any of the test files the following is required:

* An application registered with the Google API
* A consumer secret and key, these can be retrieved from Google
* Apache, Nginx or any other webserver of which the webroot includes the script test/callback.php
* A webbrowser such as Google Chrome or Mozilla Firefox
* A terminal with the "open" command installed (this is used to open a browser)

## Installation

Just drop lib/ somewhere and add the following line to your PHP files:

    require_once('path/to/oauth/lib/oauth.php');

From this point on all required classes will be loaded automatically.

## Usage

In order to create a new consumer application you have to create a new instance of
`OAuth\Core\Consumer` as following:

    $oauth = new OAuth\Core\Consumer( consumer key, consumer secret );

Requests can be executed using the request() method:

    $response = $oauth->request( api url, request method, options, curl options );

If you only want to build the request without sending it this can be done by calling sign().
This method takes exactly the same arguments as request() but returns an array instead of
directly executing the request. This can be useful if you want to redirect a user to the
OAuth authorization URL.

## Configuration

If you need to run any of the tests there are 3 things that need to be set:

* The consumer key, can be retrieved from Google
* The consumer secret, can also be retrieved from Google
* The callback URL, this URL should point to test/callback.php

## License

This package is licensed under the MIT license, a copy of this license can be found in
the file "license.txt".

## Contact & Support

If you happen to encounter a bug or would like to request a feature you can use the
bugtracker provided by GitHub or send an Email to github@isset.nl.

