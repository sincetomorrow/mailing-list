<?php

function debugger( $field, 
                   $name = '', 
                   $style = 'style="border: medium double #234967;"' )
{
    # start capturing the item to be analyzed
    ob_start( );
    
    # in case of ajax responses
    if( isset( $GLOBALS['ajax'] ) && $GLOBALS['ajax'] )
    {
        print( "<pre>" );
    } else {
        print( "<pre $style>" );
    }
    
    # the text to be displayed
    print( "<br/>" );
    print( "&lt;-- $name --&gt;" );
    print( "<br /><br />" );
    print_r( $field );
    print( "<br/>" );
    print( "&lt;-- $name end --&gt;" );
    print( "<br /><br />" );
    print( "</pre>" );
    $result = ob_get_contents( );
    ob_get_clean( );
    ob_get_flush( );
    
    # an xml ajax response
    if( isset( $GLOBALS['xml'] ) && $GLOBALS['xml'] )
    {
        # create the class
        $xml = new XMLWriter( );
        
        # sending the content
        $xml->openURI("php://output");
        
        # xml version 1 with utf-8 charset
        $xml->startDocument( "1.0", "utf-8" );
        
        # the indent for the tags
        $xml->setIndent( 4 );
        
        # start with the response element
        $xml->startElement( "response" );
        
        # store the message
        $xml->startElement( "message" );
        $xml->writeAttribute( "class", "debug" );
        $xml->text( $result );
        $xml->endElement( );
        
        # close the response element
        $xml->endElement( );
        
        # close the xml
        $xml->endDocument( );
        
        # free some memory
        $xml->flush( );
        
        # free some memory - namespaces
        unset( $xml, $result );
        
        # terminate to send the response
        exit;
        
    }
    
    # a json ajax response
    if( isset( $GLOBALS['ajax'] ) && $GLOBALS['ajax'] )
    {
        $json = array( 
            'error'     => true,
            'message'   => array(
                'cls'       => 'debug',
                'text'      => $result
            )
        );
        
        # place the result into the response message
        echo json_encode( $json );
        
        # free some memory - namespaces
        unset( $json, $result );
        
        # terminate to send the response
        exit;
        
    }
    
    # a simple response
    echo $result;
    
    # free some memory - namespaces
    unset( $result );
    
} # end of function debugger( ...

// Zend best programming practices do not close tag
