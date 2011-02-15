<?php 
//
// Definition of pvrUptateSocialtype class
//

class pvrUpdateSocialtype extends eZDataType
{
	const DATA_TYPE_STRING = "pvrUpdateSocial";
	const CONTENT_VALUE = 'data_int1';
    const PREFIX_ATTRIBUTE = 'ContentObjectAttribute';
    const CONTENT_CLASS_VALUE = 'data_int1';

    function pvrUpdateSocialtype()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Update Social", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }


    /*!
     Initializes the object attribute with the datetime data.
    */
    function initializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
    }

    function validateDateTimeHTTPInput( $day, $month, $year, $hour, $minute, $second, $contentObjectAttribute )
    {
         $state = eZDateTimeValidator::validateDateTime( $day, $month, $year, $hour, $minute, $second );
         if ( $state == eZInputValidator::STATE_INVALID )
         {
             $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                  'Date is not valid.' ) );
             return eZInputValidator::STATE_INVALID;
         }
         return $state;
    }
    

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $fieldName = $base . "_pvrUS_twitter_" . $contentObjectAttribute->attribute( 'id' );
        $returnState = eZInputValidator::STATE_ACCEPTED;
    	
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        
        if ( $http->hasPostVariable( $base . '_pvrUS_twitter_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_day_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_minute_' . $contentObjectAttribute->attribute( 'id' ) )
             )
        {
            $year   = $http->postVariable( $base . '_pvrUS_twitter_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month  = $http->postVariable( $base . '_pvrUS_twitter_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day    = $http->postVariable( $base . '_pvrUS_twitter_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $hour   = $http->postVariable( $base . '_pvrUS_twitter_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_pvrUS_twitter_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = 0;
           
            if ( $year == '' or $month == '' or $day == '' or $hour == '' or $minute == '' )
            {
                if ( !( $year == '' and $month == '' and $day == '' and $hour == '' and $minute == '' ) or ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() ) )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Missing datetime input.' ) );
                    $returnState = eZInputValidator::STATE_INVALID;
                }
                else 
                {
                    $returnState = eZInputValidator::STATE_ACCEPTED;
                }
            }
            else
            {
                return $this->validateDateTimeHTTPInput( $day, $month, $year, $hour, $minute, $second, $contentObjectAttribute );
            }
        }
            $returnState = eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Returns the content data for the given content object attribute.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
    	$stamp = $contentObjectAttribute->attribute( 'data_int' );
    	
        if ( !is_null( $stamp ) )
        {
            $time = new eZDateTime( $stamp );

        }
        else
        {
        	$time = array( 'timestamp' => '',
                           'datetime' => '',
                           'year' => '',
                           'month' => '',
        				   'day' => '',
        				   'hour' => '',
        				   'minute' => '',
                           'is_valid' => false );
        }
        return $time;
    }

    /*!
     Fetches the HTTP input for the content object attribute.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {     
		if ( $http->hasPostVariable( $base . '_pvrUS_twitter_year_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_month_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_day_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_hour_' . $contentObjectAttribute->attribute( 'id' ) ) and
             $http->hasPostVariable( $base . '_pvrUS_twitter_minute_' . $contentObjectAttribute->attribute( 'id' ) )
             )
        {
            $year   = $http->postVariable( $base . '_pvrUS_twitter_year_' . $contentObjectAttribute->attribute( 'id' ) );
            $month  = $http->postVariable( $base . '_pvrUS_twitter_month_' . $contentObjectAttribute->attribute( 'id' ) );
            $day    = $http->postVariable( $base . '_pvrUS_twitter_day_' . $contentObjectAttribute->attribute( 'id' ) );
            $hour   = $http->postVariable( $base . '_pvrUS_twitter_hour_' . $contentObjectAttribute->attribute( 'id' ) );
            $minute = $http->postVariable( $base . '_pvrUS_twitter_minute_' . $contentObjectAttribute->attribute( 'id' ) );
            $second = 0;
            if ( $year != '' and $month != '' and $day !='' and $hour != '' and $minute != '')
            {
            	$datetime = new eZDateTime();
            	$datetime->setMDYHMS( $month, $day, $year, $hour, $minute, $second );
                $contentObjectAttribute->setAttribute( 'data_int', $datetime->timestamp() );
            }
            else
            {
                $contentObjectAttribute->setAttribute( 'data_int', null );
            }
            return true;
        }
        return false;
    }
    
    


    /*!
     Executes a custom action for an object attribute which was defined on the web page.
    */
    function customObjectAttributeHTTPAction( $http, $action, $objectAttribute, $parameters )
    {
        /*$surveyID = $objectAttribute->attribute( self::CONTENT_VALUE );
        $survey = $this->fetchSurveyByID( $surveyID );
        if ( is_object( $survey ) )
        {
           $status = $survey->handleAttributeHTTPAction( $http, $action, $objectAttribute, $parameters );
           if ( $status == true )
           {
               $survey->sync();
           }
        }*/
    }

    /*!
     Clean up stored object attribute
     \note Default implementation does nothing.
    */
    function deleteStoredObjectAttribute( $objectAttribute, $version = null )
    {
       /* $surveyID = $objectAttribute->attribute( self::CONTENT_VALUE );
        $survey = $this->fetchSurveyByID( $surveyID );
        if ( is_object( $survey ) )
        {
            $survey->remove();
        }*/
    }

    /*!
      Set the survey itself to published.
     \return True if the value was stored correctly.
    */
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $retValue = false;
       /* $surveyID = $contentObjectAttribute->attribute( self::CONTENT_VALUE );
        $survey = $this->fetchSurveyByID( $surveyID );
        if ( is_object( $survey ) )
        {
            $survey->setAttribute( 'published', 1 );
            $survey->store();
            $retValue = true;
        }
        else
        {
            eZDebug::writeError( "Survey ID $surveyID did not exist.", 'eZSurveyType::onPublish' );
        }*/
        return $retValue;
    }

    /*!
     Returns the meta data used for storing search indices.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_int1" );
    }

    /*!
     Returns the text.
    */
    function title( $contentObjectAttribute, $name = null )
    {
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $value = $classAttribute->attribute( self::CONTENT_CLASS_VALUE );

        $dom = $attributeParametersNode->ownerDocument;
        $defaultValueNode = $dom->createElement( 'value', $value );
        $attributeParametersNode->appendChild( $defaultValueNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attrjeppibuteNode, $attributeParametersNode )
    {
        $value = $attributeParametersNode->getElementsByTagName( 'value' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::CONTENT_CLASS_VALUE, $value );
    }
    
}

eZDataType::register( pvrUpdateSocialtype::DATA_TYPE_STRING, "pvrUpdateSocialtype" );

?> 
