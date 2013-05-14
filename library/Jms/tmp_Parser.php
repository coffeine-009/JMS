private function ParceTex( $StreamIn )
    {
        /// *** Get language    *** ///
        $search = new TextEdit( $StreamIn );        
            $search -> Find( 'POSIX', '\\documentclass([[:space:]]*)\[([[:space:]]|[[:alnum:]]|,)*]' );
        
            $search_ = new TextEdit( $search -> GetResult() );
                $search_ -> Find( 'POSIX', '[[:space:]]*,[[:space:]]*(ua|en|ru)' );
        
            $result = $search_ -> GetResults();
        
        $this -> DataFromTex[ 'language' ] = $result[ 1 ];//echo $result[1];
        
        /// *** Get Author & abstract  *** ///
        $search = new TextEdit( $StreamIn );  
            
            /// *** English *** ///      
            //$search -> Find( 'POSIX', 'enabstract([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|\.)*)}([[:space:]]|%)*({([[:space:]]|[[:alnum:]]|\[|\]|\$|_|\.|[\{(w)*\}])*})([[:space:]]|%)*({([[:space:]]|[[:alnum:]]|\[|\]|\$|_|\.|[\{(w)*\}])*})' );
            $search -> GetClassTex( 'enabstract', 3 );
            
            $ress = $search -> GetResults();
            $possition = 0;
            for( $i = 0;  $i < count( $ress ); $i++ )
            {
                if( strlen( $ress[ $i ] ) >= 3 )
                {
                    $this -> DataFromTex[ 'abstract' ][ 'en' ][ $possition++ ] = $ress[ $i ];//echo '<br> >>> ' .$ress[ $i ]; 
                }
            }
            
        $search = new TextEdit( $StreamIn );
        
            /// *** Ukraine *** ///
            //$search -> Find( 'POSIX', '\uaabstract([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}' );
            $search -> GetClassTex( 'uaabstract', 3 );
            
            $ress = $search -> GetResults();
            $possition = 0;
            for( $i = 0;  $i < count( $ress ); $i++ )
            {
                if( strlen( $ress[ $i ] ) >= 3 )
                {
                    $this -> DataFromTex[ 'abstract' ][ 'ua' ][ $possition++ ] = $ress[ $i ];//echo '<br> >>> ' .$ress[ $i ];                    
                }
            }
        
        $search = new TextEdit( $StreamIn );
        
            /// *** Russian *** ///
            //$search -> Find( 'POSIX', '\ruabstract([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}' );
            $search -> GetClassTex( 'ruabstract', 3 );
            
            $ress = $search -> GetResults();
            $possition = 0;
            for( $i = 0;  $i < count( $ress ); $i++ )
            {
                if( strlen( $ress[ $i ] ) >= 3 )
                {
                    $this -> DataFromTex[ 'abstract' ][ 'ru' ][ $possition++ ] = $ress[ $i ];//echo '<br> >>> ' .$ress[ $i ];                    
                }
            }
            
        $search = new TextEdit( $StreamIn );
        
            /// *** Subjclass *** ///
            $search -> Find( 'POSIX', '\subjclass([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\')*)}' );
            
            $res = $search -> GetResults();
                        
            $this -> DataFromTex[ 'subjclass' ] = $res[ 2 ];
            
        
        $search = new TextEdit( $StreamIn );
        
            /// *** Key Words *** ///
            //$search -> Find( 'POSIX', '\keywords([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|_|\.|\$)*)}' );
            $search -> GetClassTex( 'keywords', 1 );
            
            $res = $search -> GetResults();
            
            $this -> DataFromTex[ 'keywords' ] = $res[ 0 ];
            
        
        $search = new TextEdit( $StreamIn );
        
            /// *** UDC *** ///
            $search -> Find( 'POSIX', '\UDC([[:space:]]|%)*{(([[:space:]]|[[:alnum:]]|,|-|~|\.|\'|\+)*)}' );
            
            $res = $search -> GetResults();
            
            $this -> DataFromTex[ 'udc' ] = $res[ 2 ];
            
            
        $search = new TextEdit( $StreamIn );
        
            /// *** Page Number *** ///
            $search -> Find( 'POSIX', '\pageno([[:space:]]|%)*{(([[:space:]]|[[:digit:]]|,|-|\.)*)}' );
            
            $res = $search -> GetResults();
            
            $this -> DataFromTex[ 'pageno' ] = $res[ 2 ];
            
        return true;
    }
