<?php

$http	= eZHTTPTool::instance();
$ini	= eZINI::instance( 'pvrupdatestatus.ini' );
$tpl	= eZTemplate::factory();

if( $http->hasGetVariable( 'oauth_verifier' ) && 
		( $http->getVariable( 'oauth_token' ) !== $http->sessionVariable( 'oauth_token' ) ) )
{
	// Nothing to do yet :)
}
else
{
	$twitteroauth = new TwitterOAuth(
							$ini->variable( 'TwitterSettings', 'ConsumerKey' ), 
							$ini->variable( 'TwitterSettings', 'ConsummerSecret' ),
							$http->sessionVariable( 'oauth_token' ),
							$http->sessionVariable( 'oauth_token_secret' )
						);
	
	$access_token = $twitteroauth->getAccessToken( $http->getVariable( 'oauth_verifier' ) );
	$http->removeSessionVariable( 'oauth_token' );
	$http->removeSessionVariable( 'oauth_token_secret' );
	
	if( $twitteroauth->http_code == 200 )
	{
		$ini = eZINI::instance( 'twittertoken.ini' );
		$ini->setVariable( 'TwitterToken', 'UserID', $access_token['user_id'] );
		$ini->setVariable( 'TwitterToken', 'Token', $access_token['oauth_token'] );
		$ini->setVariable( 'TwitterToken', 'Secret', $access_token['oauth_token_secret'] );
		$result = $ini->save(
					'twittertoken.ini.append.php',
					false,
					false,
					false,
					'settings/override',
					false,
					true 
					);
		if( $result === false )
		{
			eZDebug::writeError( 'Token IYA ! ', 'pvrupdatesocial Twitter' );
		}
	}
	
	$tpl->setVariable( 'haveToken', 1);
}

$Result = array();
$Result['content'] = $tpl->fetch( 'design:twitter/confirm.tpl' );
$Result['path'] = array(
		array(
			'text'	=> ezi18n( 'extension/pvrupdatesocial', 'Twitter settings' ),
			'url' 	=> false
		)
	);
?>