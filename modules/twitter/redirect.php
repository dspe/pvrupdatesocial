<?php

$http	= eZHTTPTool::instance();
$ini 	= eZINI::instance( 'pvrupdatestatus.ini' );

$twitteroauth = new TwitterOAuth( 
						$ini->variable( 'TwitterSettings', ConsumerKey ),
						$ini->variable( 'TwitterSettings', ConsumerSecret )
				);

$requestToken = $twitteroauth->getRequestToken(
										eZSys::serverURL() . eZSys::indexDir() . '/twitter/callback' 
									);

$http->setSessionVariable( 'oauth_token', $requestToken['oauth_token'] );
$http->setSessionVariable( 'oauth_token_secret', $requestToken['oauth_token_secret'] );

if( $twitteroauth->http_code == 200 )
{
	$url = $twitteroauth->getAuthorizeURL( $requestToken['oauth_token'] );
	header( 'Location: ' . $url );
	eZExecution::cleanExit();
}
else
{
	eZDebug::writeError( 'Error : OH MY GOD !' );
}