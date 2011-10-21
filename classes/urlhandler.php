<?php

abstract class shortenerHandler
{
    /*
     * Check if url is correctly construct
     */
    abstract static public function checkUrl( $url )
    {
    
    }

    /*
     * Connect via an account
     *
     */
    static private function accountConnect( $accountParams )
    {
        return true;
    }

    /*
     * Shrink uri
     *
     */
     abstract static public function skrink( $uri )
     {
        return null;
     }
}
?>