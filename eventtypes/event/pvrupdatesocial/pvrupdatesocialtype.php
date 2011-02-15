<?php

class pvrUpdateSocialType extends eZWorkflowEventType
{
	const WORKFLOW_TYPE_STRING = "pvrupdatesocial";
	
	public function __construct()
	{
		parent::__construct( pvrUpdateSocialType::WORKFLOW_TYPE_STRING, 'Update Social Status');
	}
	
	public function execute( $process, $event )
	{
		$parameters = $process->attribute( 'parameter_list' );
		
		$twitterINI = eZINI::instance( 'pvrupdatestatus.ini' );
       	$twitterDebugOutput = $twitterINI->variable( 'GeneralSettings', 'DebugOutput' );
 
       	eZLog::write( "Entering eztwitter workflow" );
       	$twitterConsumerKey		= $twitterINI->variable( 'TwitterSettings', 'ConsumerKey' );
       	$twitterConsumerSecret 	= $twitterINI->variable( 'TwitterSettings', 'ConsumerSecret');
       	$twitterAccessToken 	= $twitterINI->variable( 'TwitterSettings', 'AccessToken' );
       	$twitterAccessSecret 	= $twitterINI->variable( 'TwitterSettings', 'AccessSecret' );
 
       	if( empty( $twitterConsumerKey ) || 
        	empty( $twitterConsumerSecret ) || 
           	empty( $twitterAccessToken ) || 
           	empty( $twitterAccessSecret ) ) 
       	{
        	if( $twitterDebugOutput == 'enabled' ) 
            	eZLog::write( "Please configure pvrupdatestatus.ini" );    
       	}
       	if( $twitterDebugOutput == 'enabled' )
           	eZLog::write( "Credentials found in pvrupdatestatus.ini" );

        /*$twitter = new Arc90_Service_Twitter();
       	$twitter->useOAuth( $twitterConsumerKey, 
                           $twitterConsumerSecret, 
                           $twitterAccessToken, 
                           $twitterAccessSecret );*/
		
		return eZWorkflowType::STATUS_ACCEPTED;
	}
}
eZWorkflowEventType::registerEventType( pvrUpdateSocialType::WORKFLOW_TYPE_STRING, 'pvrupdatesocialtype');

?>