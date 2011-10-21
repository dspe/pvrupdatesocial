<?php

class pvrUpdateSocialType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = "pvrupdatesocial";

    public function __construct()
    {
        parent::__construct( pvrUpdateSocialType::WORKFLOW_TYPE_STRING, 'Update Social Status');
    }

    /*
     * @TODO: change test fonction... Not really great as i wish.
     *        and export some code to a class...
     */
    public function execute( $process, $event )
    {
        // Test if ini files exists
        if( eZINI::exists( 'pvrupdatestatus.ini', 'extension/pvrupdatesocial/settings' ) )
        {
            $ini            = eZINI::instance( 'pvrupdatestatus.ini' );
            $parameters     = $process->attribute( 'parameter_list' );
            $processParams  = $process->attribute( 'parameter_list' );
            $object         = eZContentObject::fetch( $processParams['object_id'] );


            $currentSiteAccess = eZSiteAccess::current();
            $uriAccess  = ( $currentSiteAccess['type'] == eZSiteAccess::TYPE_URI );
            $hostAccess = ( $currentSiteAccess['type'] == eZSiteAccess::TYPE_HTTP_HOST );

            $alterUrl   = ( "pheelit" == -1 or "pheelit" == $currentSiteAccess['name'] ) ? false : true;

            if( $alterUrl and $uriAccess )
            {
                // store access path
                $previousAccessPath = eZSys::instance()->AccessPath;
                // clear access path
                eZSys::clearAccessPath();
                // set new access path with siteaccess name
                eZSys::addAccessPath( "pheelit" );
            }

            $node = $object->attribute('main_node');
            eZSiteAccess::load(
                        array( 'name'     => "pheelit",
                               'type'     => eZSiteAccess::TYPE_STATIC,
                               'uri_part' => array() 
                        ));
            $url = $node->attribute('url_alias');
            eZSiteAccess::load( $currentSiteAccess );
            eZURI::transformURI( $url, false, 'full' );

            // Get param from object : name & url_alias
            $message        = "";
            $message       .= $object->attribute( 'name' );
            //$url          = $node->attribute( 'url_alias' );

            if( $alterUrl and $hostAccess )
            {
                // retrieve domain name associated to the request siteaccess
                $Ini = eZINI::instance();
                $matchMapItems = $Ini->variableArray( 'SiteAccessSettings', 'HostMatchMapItems' );
                foreach ( $matchMapItems as $matchMapItem )
                {
                    if ( $matchMapItem[1] == "pheelit" )
                    {
                        $host = $matchMapItem[0];
                        break;
                    }
                }
                if ( isset( $host ) )
                {
                    $uriParts = explode( eZSys::hostname(), $url );
                    $url = implode( $host, $uriParts );
                }
            }

            if( !empty( $message ) && !empty( $url ) )
            {
                eZLog::write( "Enterring twitter workflow" );

                $cond = array( 'network' => 'twitter' );
                $object = eZPersistentObject::fetchObject( UpdateSocialObject::definition(), null, $cond );

                $twitterConsumerKey     = $object->consummerKey;
                $twitterConsumerSecret  = $object->consummerSecret;
                $twitterAccessToken     = $object->token;
                $twitterAccessSecret    = $object->secret;

                if( !empty( $twitterConsumerKey ) && !empty( $twitterConsumerSecret ) && !empty( $twitterAccessToken ) && !empty( $twitterAccessSecret ) )
                {

                    /* Get url shortener */
                    //$googleKey = $ini->variable( 'GoogleURLShortener', 'GoogleKey');
                    //$ch = curl_init( sprintf( 'https://www.googleapis.com/urlshortener/v1/url?key=%s', $googleKey ) );

                    $service = urlShortener::create( $ini->variable( 'TwitterSettings', 'UseService' ) );
                    $uri = $service->shrink( $url );

                    if( $uri == null )
                    {
                        return eZWorkflowType::STATUS_REJECTED;
                    }
                    else
                    {
                        // Cut name lenght
                        if( strlen( $message. " " . $uri ) > 140 )
                        {
                            $message = mb_strimwidth( $message, 0, 140 - strlen( $uri ) - 4 , "..." );
                        }
                        $message .= " " . $uri;

                   /*     print "<pre>";
                    var_dump( $message );
                    print "</pre>";
                    die();
                    */
                        /* Send Twitter status */
                        $connection = new TwitterOAuth($twitterConsumerKey, $twitterConsumerSecret, $twitterAccessToken, $twitterAccessSecret );
                        $infos = $connection->get( 'account/verify_credentials' );
                        $reponse = $connection->post( 'statuses/update', array( 'status' => $message ) );

                        if( isset( $reponse->error ) )
                        {
                            eZLog::write( "Can't publish on twitter ?! " . $reponse->error );
                            return eZWorkflowType::STATUS_REJECTED;
                        }
                        else
                        {
                            eZLog::write( "Publication on twitter is ok :)" );
                            return eZWorkflowType::STATUS_ACCEPTED;
                        }
                    }
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