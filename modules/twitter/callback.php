<?php

$http   = eZHTTPTool::instance();
$ini    = eZINI::instance( 'pvrupdatestatus.ini' );
$tpl    = eZTemplate::factory();

if( $http->hasGetVariable( 'oauth_verifier' ) &&
    ( $http->getVariable( 'oauth_token' ) !== $http->sessionVariable( 'oauth_token' ) ) )
{
    // Nothing to do yet :)
}
else
{
    $twitteroauth = new TwitterOAuth(
                            $ini->variable( 'TwitterSettings', 'ConsumerKey' ),
                            $ini->variable( 'TwitterSettings', 'ConsumerSecret' ),
                            $http->sessionVariable( 'oauth_token' ),
                            $http->sessionVariable( 'oauth_token_secret' )
                        );

    $access_token = $twitteroauth->getAccessToken( $http->getVariable( 'oauth_verifier' ) );
    $http->removeSessionVariable( 'oauth_token' );
    $http->removeSessionVariable( 'oauth_token_secret' );

    if( $twitteroauth->http_code == 200 )
    {
        /* Save to database */
        $data = UpdateSocialObject::create(
            array(
                'network'        => 'twitter',
                'consummerKey'   => $ini->variable( 'TwitterSettings', 'ConsumerKey' ),
                'consummerSecret' => $ini->variable( 'TwitterSettings', 'ConsumerSecret' ),
                'token'          => $access_token['oauth_token'],
                'secret'         => $access_token['oauth_token_secret']
            ) );
        $data->store();
    }
    $tpl->setVariable( 'haveToken', 1);
}

$Result = array();
$Result['content'] = $tpl->fetch( 'design:twitter/confirm.tpl' );
$Result['path'] = array(
        array(
            'text'  => ezi18n( 'extension/pvrupdatesocial', 'Twitter settings' ),
            'url'   => false
        )
    );
?>