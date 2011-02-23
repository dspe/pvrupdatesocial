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
		
		$twitterINI = eZINI::instance( 'twittertoken.ini' );
		$ini = eZINI::instance( 'pvrupdatestatus.ini' );

		eZLog::write( "Entering eztwitter workflow" );
       	$twitterConsumerKey		= $ini->variable( 'TwitterSettings', 'ConsumerKey' );
       	$twitterConsumerSecret 	= $ini->variable( 'TwitterSettings', 'ConsumerSecret');
       	$twitterAccessToken 	= $twitterINI->variable( 'TwitterToken', 'Token' );
       	$twitterAccessSecret 	= $twitterINI->variable( 'TwitterToken', 'Secret' );
 
     /*  	if( empty( $twitterConsumerKey ) || 
        	empty( $twitterConsumerSecret ) || 
           	empty( $twitterAccessToken ) || 
           	empty( $twitterAccessSecret ) ) 
       	{*/
	       	eZLog::write( "Credentials found in twittertoken.ini" );
	       	/* @TODO: test à faire avant envoie */
			$message = "";
			
			$processParams = $process->attribute( 'parameter_list' );

			$object  = eZContentObject::fetch( $processParams['object_id'] );
			$message .= $object->attribute( 'name' );
			
			$url = $object->attribute( 'main_node' )->attribute( 'url_alias' );
			eZURI::transformURI( $url, true, 'full' );
	
			/* Get url shortener */
			$googleKey = $ini->variable( 'GoogleURLShortener', 'GoogleKey');
			$requestData = array(
            	'longUrl' => $url
        	);
			
			$ch = curl_init( sprintf( 'https://www.googleapis.com/urlshortener/v1/url?key=%s', $googleKey ) );
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
			
	        $result = curl_exec($ch);
        	
			$shortUrl = json_decode( $result );
			curl_close($ch);
			
			$message .= " " . $shortUrl->id;
			//$dataMap = $object->attribute( 'data_map' );
			
			/* Send Twitter status */
        	$connection = new TwitterOAuth($twitterConsumerKey, $twitterConsumerSecret, $twitterAccessToken, $twitterAccessSecret );
			$infos = $connection->get( 'account/verify_credentials' );
			$reponse = $connection->post( 'statuses/update', array( 'status' => $message ) );
			if( isset( $reponse->error ) )
				var_dump( $reponse->error );
		//die();	
		
			return eZWorkflowType::STATUS_ACCEPTED;
       /*	}
       	else
       	{
       		return eZWorkflowType::STATUS_REJECTED;
       	}*/
	}
}
eZWorkflowEventType::registerEventType( pvrUpdateSocialType::WORKFLOW_TYPE_STRING, 'pvrupdatesocialtype');

?>