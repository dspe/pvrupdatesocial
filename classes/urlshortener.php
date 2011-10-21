<?php

class urlShortener
{

    /* List of supported shortener service implemented
     *
     * The list is an array with the form 'shortenerservice' => 'handlerClassName'
     *
     */
    static private $implementation = array( 'google' => 'pvrShortenerHandlerGoogle',
                                          );

    /**
     * Adds a shortener Service implementation to the list of know implementations
     *
     */
    static public function addImplementation( $implementationName, $className )
    {
        self::$implementation[$implementationName] = $className;
    }

    /**
     * Returns a list width shortener Service implementation.
     *
     */
    static public function getImplementations()
    {
        $list = array();
        foreach( self::$implementations as $name => $className )
        {
            $list[] = $name;
        }
        return $list;
    }

    /**
     * Create and return a specific instance of the specified urlshortener implementation
     *
     */
    static public function create( $service )
    {
        if( !is_string( $service ) )
        {
            eZDebug::writeError( 'Error when creating a specified urlShortener implementation : $service was empty. ', __METHOD__ );
        }
        $impName = $service;

        $className = self::$implementation[$impName];
        $instance = new $className();

        return $instance;
    }

}
?>