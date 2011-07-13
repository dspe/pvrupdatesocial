<?php

class UpdateSocialObject extends eZPersistentObject
{
    protected function __construct( $row )
    {
        parent::eZPersistentObject( $row );
    }

    public static function definition()
    {
        static $def = array( 'fields' => array(
            'network'   => array(
                'name'      => 'network',
                'datatype'  => 'string',
                'default'   => 0,
                'required'  => true ),
            'consummerKey'  => array(
                'name'      => 'consummerKey',
                'datatype'  => 'string',
                'default'   => 0,
                'required'  => true ),
            'consummerSecret' => array(
                'name'      =>'consummerSecret',
                'datatype'  => 'string',
                'default'   => 0,
                'required'  => true ),
            'token' => array(
                'name'      => 'token',
                'datatype'  => 'string',
                'default'   => 0,
                'required'  => true ),
            'secret'    => array( 
                'name'      => 'secret',
                'datatype'  => 'string',
                'default'   => 0,
                'required'  => true )
            ),
            'keys' => array( 'network' ),
            'class_name' => 'UpdateSocialObject',
            'name'      => 'pvrupdatesocial' );
        return $def;
    }

    public static function create( array $row = array() )
    {
        $object = new self( $row );
        return $object;
    }

}

?>