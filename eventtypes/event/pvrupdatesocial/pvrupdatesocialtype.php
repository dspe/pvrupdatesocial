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
		// Test if ini files exists
		if( eZINI::exists( 'pvrupdatestatus.ini', 'extension/pvrupdatesocial/settings' ) && eZINI::exists( 'twittertoken.ini', 'settings/override' ) )
		{
			$parameters = $process->attribute( 'parameter_list' );

			// Get param from object : name & url_alias
			$message = "";
			$processParams 	= $process->attribute( 'parameter_list' );
			$object  		= eZContentObject::fetch( $processParams['object_id'] );
			$message 	   .= $object->attribute( 'name' );
			$url 			= $object->attribute( 'main_node' )->attribute( 'url_alias' );
			eZURI::transformURI( $url, true, 'full' );
			
			if( !empty( $message ) && !empty( $url ) )
			{
				$twitterINI = eZINI::instance( 'twittertoken.ini' );
				$ini 		= eZINI::instance( 'pvrupdatestatus.ini' );

				eZLog::write( "Entering eztwitter workflow" );
       			$twitterConsumerKey		= $ini->variable( 'TwitterSettings', 'ConsumerKey' );
       			$twitterConsumerSecret 	= $ini->variable( 'TwitterSettings', 'ConsumerSecret');
       			$twitterAccessToken 	= $twitterINI->variable( 'TwitterToken', 'Token' );
       			$twitterAccessSecret 	= $twitterINI->variable( 'TwitterToken', 'Secret' );
       			
       			if( !empty( $twitterConsumerKey ) && !empty( $twitterConsumerSecret ) && !empty( $twitterAccessToken ) && !empty( $twitterAccessSecret ) )
       			{
       				
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
		        	
		        	if( $result == FALSE )
		        	{
		        		eZLog::write( "Something wrong when getting shortener url :/ " );
		        		return eZWorkflowType::STATUS_REJECTED;
		        	}
		        	else
		        	{
		        		
						$shortUrl = json_decode( $result );
						// Cut name lenght
						if( strlen( $message. " " . $shortUrl->id ) > 140 )
						{
							$message = mb_strimwidth( $message, 0, 140 - strlen( $shortUrl->id ) - 4 , "..." );
						}
					
						$message .= " " . $shortUrl->id;
					
						/* Send Twitter status */
        				$connection = new TwitterOAuth($twitterConsumerKey, $twitterConsumerSecret, $twitterAccessToken, $twitterAccessSecret );
						$infos = $connection->get( 'account/verify_credentials' );
						$reponse = $connection->post( 'statuses/update', array( 'status' => $message ) );
						
					var_dump( $reponse );
					die();
					
						if( empty( $reponse->error ) )
						{
							eZLog::write( "Can't publish on twitter ?! " . $reponse->error );
							return eZWorkflowType::STATUS_REJECTED;
						}
						else
						{
							return eZWorkflowType::STATUS_ACCEPTED;
						} 
		        	}
		        	curl_close($ch);
       			}
       			else
       			{
       				eZLog::write( "Missed configuration. Please check your ini file." );
       				return eZWorkflowType::STATUS_REJECTED;
       			}
				
			}
			else
			{
				eZLog::write( "Something wrongs append during fetching object" );
				return eZWorkflowType::STATUS_REJECTED;
			}
		}
		else 
		{
			eZlog::write( "ini config files not found" );
			return eZWorkflowType::STATUS_REJECTED;	
		}

	}
}
eZWorkflowEventType::registerEventType( pvrUpdateSocialType::WORKFLOW_TYPE_STRING, 'pvrupdatesocialtype');

?>