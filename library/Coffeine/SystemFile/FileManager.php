<?php
///	***	FileSystem :: FileManager	***	***	***	***	***	***	***	***	***	***	///

    /**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
     * 																	*
     * 		@copyright 2012
     * 			by
     * 		@author Vitaliy Tsutsman
     *
     * 		@date 2013-10-07 20:45:58 - ..../../..
     *
     * 		@description Manager of files
     *
     *		/Ukraine/Ivano-Frankivsk/
     *																	*
    *///	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code    *** ***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
namespace SystemFile
{
    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Manager of files and folders
     *  --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- *
     * @operations
     *  CREATE
     *  COPY
     *  DELETE
     *  MOVE
     *  SCAN
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
    class FileManager
    {
        ///	***	Constants	***	///
        //- FILE_TYPE -//
        const FILE_TYPE_DIR     = 'DIR';//- Folder(Directory)               -//
        const FILE_TYPE_BACK    = '..'; //- Special file for back to step   -//
        const FILE_TYPE_BASE    = '.';  //- Speial file for back to home    -//
        const FILE_TYPE_NONE    = 'NONE';//- Undefined type of file -//

        ///	***	Properties	***	///
        private /*const string*/$type;  //- Type of file(Ex. txt, jpg) -//

        //- Path -//
        private $path;
        private $content;

        ///	***	Methods	***	///
        public function __construct(
            /*string*/  $Path = ''
        )
        {
            $this -> path = '';
            $this -> content = null;
            //TODO
        }

        public function __destruct()
        {
            //TODO
        }

        //- SET.ADRESS -//
        public function setPath( /*string*/$Path )// : void
        {
            if( is_dir( $this -> path = $Path ) ){ $this -> type = $this -> FILE_TYPE_DIR; }
        }

        //- GET.CONTENT -//
        public function getContent()// : array.index.asotiation :: $array[ 1 ][ 'name' ]
        {
            $content = array(); $pos = 0;

            foreach( $this -> content AS $directory )
            {
                if( $directory == $this -> FILE_TYPE_BACK || $directory == $this -> FILE_TYPE_BEG )
                {
                    //continue;
                }

                if( is_dir( $this -> path . '/' . $directory ) )
                {
                    $type = $this -> FILE_TYPE_DIR;

                    if( $directory == $this -> FILE_TYPE_BACK ){ $type = $this -> FILE_TYPE_BACK; }
                    if( $directory == $this -> FILE_TYPE_BEG ){ $type = $this -> FILE_TYPE_BEG; }

                }else
                {
                    $type = $this -> FILE_TYPE_NONE;
                    preg_match_all( '/\.(\w{1,9})$/U', $directory, $result, PREG_PATTERN_ORDER );

                    if( isset($result[ 1 ][ 0 ]) )
                    {
                        $type = $result[ 1 ][ 0 ];
                    }
                }

                $content[ $pos++ ] = array(
                    'name'	=> $directory, 
                    'type'	=> strtolower( $type ),  
                    'size'	=> filesize( $this -> path . '/' . $directory ), 
                    'date'	=> date( 'Y/m/d H:i:s', filemtime( $this -> path.'/'.$directory ) )
                );
            }

            return $content;
        }

        //- GET.PATH -//
        public function getPath()// : string.path
        {
            //- Return active path -//
            return $this -> path;
        }

        //- SCAN.CONTENT -//
        public function scanContent()// : bool
        {
            if( !is_dir( $this -> path ) ){ return false; }

            $content = scandir( $this -> path );

            if( !$content ){ return false; }

            $this -> content = $content;

            return true;
        }

        //- FILE.COPY -//
        public function copy( /*string*/$PathSource, /*string*/$PathDestination )// : bool
        {Zend_Debug::dump(array($PathSource, $PathDestination));
            //- Get file name -//
            $path_parts = split( '/', $PathSource );

            $file_name = $path_parts[ count( $path_parts) - 1 ];

            //- IF DIRECTORY THEN COPY -//
            if( is_dir( $PathSource ) )
            {
                //- Create directory -//
                if( !@mkdir( $PathDestination . '/' . $file_name ) ){ return false; }

                //- DIRECTORY.SCAN -//
                $content = scandir( $PathSource );

                if( count( $content ) > 0 )
                {
                    //- RUN -//
                    foreach( $content as $file )
                    {
                        //- CPECIAL SYMBOLS -//
                        if( $file == $this -> FILE_TYPE_BACK ||  $file == $this -> FILE_TYPE_BEG ){ continue; }

                        //- COPY FILES AND DIRECTORIES -//
                        $path_new = $PathSource . '/' . $file;

                        if( is_dir( $path_new ) )
                        {
                            //- RECURSION COPY -//
                            return $this -> copy(
                            $path_new,	  						//- source 		-//
                            $PathDestination . '/' . $file_name	//- destination -//
                            );
                        }else
                        {
                            //- COPY FILES -//
                            if( !copy( $path_new, $PathDestination . '/' . $file_name . '/' . $file ) ){ return false; }
                        }
                    }
                }

            }else
            {
                //- Copy File -//
                if( !@copy( $PathSource, $PathDestination . '/' . $file_name ) ){ return false; }
            }

            return true;
        }

        //- FILE.MOVE -//
        public function move( /*string*/$PathSource, /*string*/$PathDestination )// : bool
        {
            //- Copy and delete -//
            if( $this -> copy( $PathSource, $PathDestination ) )
            {
                if( $this -> delete( $PathSource ) ){ return true; }
            }

            return false;
        }

        //- FILE.DELETE -//
        public function delete( /*string*/$Path )// : bool
        {
            if( is_dir( $Path ) )
            {
                $content = scandir( $Path );

                foreach( $content as $file )
                {
                    if( $file != $this -> FILE_TYPE_BACK && $file != $this -> FILE_TYPE_BEG )
                    {
                        $this -> delete( $Path . '/' . $file );
                    }
                }

                rmdir( $Path );
            }elseif( file_exists( $Path ) ){ unlink( $Path ); }

            return true;
        }

        //- DIRECTORY.CREATE -//
        public function createDirectory( /*string.path*/$Path, /*string*/$DirName )// : bool
        {
            if( !@mkdir( $Path . '/' . $DirName, 0777, true ) ){ return false; }

            return true;
        }

        //- UPLOAD -//
        public function upload( /*string.path*/$PathSource, /*string.path*/$PathDestination )// : bool
        {
            if( @move_uploaded_file( $PathSource, $PathDestination ) )
            {
                return true;
            }

            return false;
        }

        //- RENAME -//
        public function rename( /*string.path*/$PathSrc, /*string*/$PathDest )// : bool
        {
            if( !@rename( $PathSrc, $PathDest ) ){ return false; }

            return true;
        }

        //- FILES.PACK.ZIP -//
        public function packZip( /*array.files*/$Files, /*string.path*/$Path )// : bool
        {
            $zip = new Zip();

            $tmp_file_name =  FILE_MANAGER :: DIR_TMP . '/download.zip';
            if( file_exists( $tmp_file_name ) ){ unlink( $tmp_file_name ); }

            $zip -> open( $tmp_file_name );

            foreach( $Files as $file )
            {
                if( !$zip -> pack( $Path . '/' . $file -> name, '' ) ){ return false; }
            }

            $zip -> close();

            return true;
        }
    }
}