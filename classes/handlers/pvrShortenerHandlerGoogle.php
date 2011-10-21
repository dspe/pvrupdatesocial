<?php

class pvrShortenerHandlerGoogle extends urlShortener
{
    static private $googleApiUri = 'https://www.googleapis.com/urlshortener/v1/url';

    static public function checkUrl( $url )
    {
        $parse_url = parse_url( $url );
        if( !empty( $parse_url['host'] ) )
            return true;

        return false;
    }

    static private function accountConnect( $accountParams )
    {
        return true;
    }

     static public function shrink( $uri )
     {
         if( self::checkUrl( $uri ) )
         {

            /* If curl is enabled */
            if( extension_loaded( 'curl' ) )
            {
                $requestData = array( 'longUrl' => $uri );

                /* Fetch short uri */
                //$ch = curl_init( sprintf( $googleApiUri . '?key=%s', $googleKey ) );
                $ch = curl_init( sprintf( self::$googleApiUri ) );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
                $result = curl_exec($ch);
            }

            if( $result == FALSE )
            {
                eZLog::write( "Error during fetching short uri : " . curl_error( $ch ) );
                return null;
            }
            else
            {
                $json = json_decode( $result );
                return $json->id;
            }

         }
         return null;
     }
}

?>