<?php

class twitterOperators
{
    /*!
     * Constructor
     */
    function __construct()
    {
        $this->Operators = array(
            'twitterInfo', 'ezini_exists'
        );
    }

    /*!
     * Returns the operators in this class.
     */
    function &operatorList()
    {
        return $this->Operators;
    }

    /*!
     * Return true to tell the template engine that the paramter list
     * exists per operator type, this is needed for operator classes
     * that have multiple operators.
     */
    function namedParameterPerOperator()
    {
        return true;
    }

    /*!
     * The first operator has two parameters, the other has none.
     * See eZTemplateOperator::namedParameterList()
     */
    function namedParameterList()
    {
        return array(
            'twitterInfo' => array( 
                                    'Token' => array( 'type' => 'string',
                                                        'required' => true,
                                                        'default' => false ),
                                    'Secret' => array( 'type' => 'string',
                                                        'required' => true,
                                                        'default' => false )
                            ),
            'ezini_exists' => array(
                                    'IniFile' => array( 'type' => 'string',
                                                        'required' => true,
                                                        'default' => false ),
                                    'Folder' => array( 'type' => 'string',
                                                        'required' => false,
                                                        'default' => 'settings' )
                            )
        );
    }

    /*!
     * Executes the needed operator(s).
     * Checks operator names, and calls the appropriate functions.
     */
    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch( $operatorName )
        {
            case 'twitterInfo':
                {
                    $ini    = eZINI::instance( 'pvrupdatestatus.ini' );

                    $consumer_key       = $ini->variable( 'TwitterSettings', 'ConsumerKey' );
                    $consumer_secret    = $ini->variable( 'TwitterSettings', 'ConsumerSecret' );
                    $oauth_token        = $namedParameters['Token'];
                    $oauth_token_secret = $namedParameters['Secret'];

                    $connection = new TwitterOAuth($consumer_key, $consumer_secret, $oauth_token, $oauth_token_secret );
                    $infos = $connection->get( 'account/verify_credentials' );

                    return $operatorValue = (array) $infos;
                }break;

            case 'ezini_exists':
                {
                    $ini_file   = $namedParameters['IniFile'];
                    $folder     = $namedParameters['Folder'];

                    return $operatorValue = eZINI::exists( $ini_file, $folder );
                }break;
        }
    }

}