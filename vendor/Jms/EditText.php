<?php

/// *** Edit text   *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ///

    /*  *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     *                                                                          *
     *      Copyright 2011 by Vitaliy Tsutsman                                  *
     *                                                                          *
     *      @   Date                                                            *
     *          *   Begin   -   2011/11/13 20:24                                *
     *          *   Finish  -   2011/../.. ..:..                                *
     *                                                                          *
     *     Ukraine/Ivano-Frankivsk/Krivonosa/11                                 *
     *                                                                          *
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *

class TextEdit
{
    /// *** Vars    *** ///
    private $Text;
    private $Result;
    
    /// *** Methods *** ///
    public function __construct( $TEXT )
    {
        $this -> Text = $TEXT;
    }
    
    public function __destruct()
    {
        /// *** *** ///
    }
    
    public function SetText( $Text )
    {
        $this -> Text = $Text;
    }
    
    public function GetResult()
    {
        return $this -> Result[ 0 ];
    }
    
    public function GetResults()
    {
        return $this -> Result;
    }
    
    
    public function Find( $TypeRE, $ReqularExpression )
    {
        switch( $TypeRE )
        {
            case 'POSIX':
            {
                /// *** Working POSIX RE    *** ///
                eregi( $ReqularExpression, $this -> Text, $this -> Result );
            }break;
        }
    }    
    
    public function GetClassTex( $Mask, $CountSub )/// className{}{}{}
    {
        $length = strlen( $Mask );
        
        $t = strstr( $this -> Text, $Mask );
        
        $temp = strstr( $t, '{' );
        
        $pos = 0;
        $this -> Result[ 0 ] = '';
        $count = 1;$i = 1;
        while( $i < strlen( $temp ) )
        {
            if( $pos > ($CountSub - 1) ){ return true; }
            
            if( $temp[ $i ] == '{' ){   $count++;   }
            
            if( $temp[ $i ] == '}' )
            {   
                $count--;
                
                if( $count == 0 )
                {
                    /// *** *** ///
                    $this -> Result[ ++$pos ] = '';
                                        
                    for( $j = $i + 1; $j < strlen( $temp ); $j++ )
                    {
                        if( $temp[ $j ] == '{' )
                        {
                            $i = $j + 1;
                            $count = 1;
                            break;
                        }
                    }
                    
                    continue;
                }   
            }
            
            $this -> Result[ $pos ] .= $temp[ $i++ ];            
        }
        
        return true;
    }
    
    public function Replace( $TypeRE, $ReqularExpression, $StringReplacement )
    {
        switch( $TypeRE )
        {
            case 'POSIX':
            {
                /// *** Working POSIX RE    *** ///
                $this -> Result = eregi_replace( $ReqularExpression, $StringReplacement, $this -> Text );
            }break;
        }
    }
}

?>