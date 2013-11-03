<?php
///	***	FileSystem :: File  *** ***	***	***	***	***	***	***	***	***	***	***	///

    /**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
     * 																	*
     * 		@copyright 2013
     * 			by
     * 		@author Vitaliy Tsutsman
     *
     * 		@date 2013-10-11 23:53:53 - 2013-11-03 14:58:33
     *
     * 		@description Object for manipulation with file
     *
     *		/Ukraine/Ivano-Frankivsk/
     *																	*
    *///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code    *** ***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
namespace FileSystem
{
    //- Using other namespace -//
    require_once 'Coffeine/FileSystem/Path.php';//TODO: write autoloader
    use FileSystem\Path as Path;

    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     * File
     *  --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- *
     * @operations
     *  CHANGE_MODE
     *  COPY
     *  CREATE
     *  DELETE
     *  GET_CONTENT
     *  MOVE
     *  SET_CONTENT
     *  //TODO: getInfoAboutFile
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
    class File
    {
        ///	***	Constants	***	///
        //- TYPE -//
        const TYPE_DIR     = 'DIR';     //- Folder(Directory)               -//
        const TYPE_BACK    = '..';      //- Special file for return to back -//
        const TYPE_BASE    = '.';       //- Speial file for back to base    -//
        const TYPE_NONE    = 'NONE';    //- Undefined type of file          -//

        //- Size format -//
        const SIZE_FORMAT_BYTE  = 'byte';   //- Byte        -//
        const SIZE_FORMAT_KBYTE = 'KB';     //- Kilobyte    -//
        const SIZE_FORMAT_MBYTE = 'MB';     //- Megabyte    -//
        const SIZE_FORMAT_GBYTE = 'GB';     //- Gigabyte    -//


        ///	***	Properties	***	///
        protected /*timestamp*/     $created;   //- Date and time of create -//
        protected /*int*/           $mode;      //- Mode of access to file  -//
        protected /*Path*/          $path;      //- Path to this file       -//
        protected /*uint*/          $size;      //- Size in bytes -//
        protected /*const string*/  $type;      //- Type of file(Ex. txt)   -//

        protected /*handle*/        $handle;    //- Handle of file -//


        /// *** Methods     *** ///
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Construct
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param [string] $Path
         * @param [const string] $Type = self :: TYPE_DIR
         * @param [int] $Mode = 0777
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function __construct(
            /*string*/      $Path = '.',
            /*const string*/$Type = self :: TYPE_DIR, 
            /*int*/         $Mode = 0777
        )// : void
        {
            //- Initialization -//
            $this -> path = new Path( $Path );
            $this -> type = $Type;
            $this -> mode = $Mode;

            //- Determinate type -//
            if( $this -> path -> GetLastNode() === self :: TYPE_BACK )
            {
                //- Special file for return to back -//
                $this -> type = self :: TYPE_BACK;
                return;
            }

            if( $this -> path -> GetLastNode() === self :: TYPE_BASE )
            {
                //- Special file for return to base path -//
                $this -> type = self :: TYPE_BASE;
                return;
            }

            //- Determinate extension for exist file -//
            if( file_exists( $this -> path -> Get() ) )
            {
                //- Get data about file -//
                $this -> size = filesize( $this -> path -> Get() );
                $this -> created = filemtime( $this -> path -> Get() );

                //- Determinate ext -//
                if( filetype( $this -> path -> Get() ) === 'file' )
                {
                    //- Undefined type -//
                    $this -> type = self :: TYPE_NONE;

                    $extension = split( '\.', $this -> path -> GetLastNode() );
                    $count = count( $extension );
                    if( $count > 0 )
                    {
                        $len = strlen( $extension[ $count - 1 ] );
                        if( $len < 8 )
                        {
                            $this -> type = $extension[ $count - 1 ];
                        }
                    }
                }
            }
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Destruct
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function __destruct()
        {
            //- Free memory -//
            $this -> created = null;
            $this -> mode = null;
            $this -> path = null;
            $this -> type = null;
            
            if( $this -> handle )
            {
                fclose( $this -> handle );
            }
        }


        //- SECTION :: MAIN -//
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Change mode
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param uint $Mode Octail or decimal mode
         * @return bool
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function ChangeMode( /*uint*/$Mode )// : bool
        {
            //- Update mode for access to file -//
            return chmod( 
                $this -> path -> Get(), 
                $Mode 
            );
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Copy
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param Path $PathDestination
         * @return bool
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Copy( /*Path*/$PathDestination )// : bool
        {
            //- Get params -//
            $pathSource = $this -> path -> Get();
            $pathDestination = new Path( $PathDestination );
                $pathDestination -> Forward( $this -> path -> GetLastNode() );

            //- Copy process -//
            if( !is_dir( $pathSource ) )
            {
                //- File -//
                return copy( 
                    $pathSource, 
                    $pathDestination -> Get() 
                );
            }else
                {
                    //- Folder -//
                    //- Create new folder -//
                    $dir = new \FileSystem\File( $pathDestination -> Get() );
                    if( !$dir -> Create() )
                    {
                        //- ERROR :: Can not create directory -//
                        return false;
                    }

                    //- Copy content from this dir -//
                    $content = $this -> GetContent();
                    foreach( $content as $item )
                    {
                        //- Skip special files -//
                        if(
                            in_array(
                                $item,
                                array(
                                    self :: TYPE_BACK,
                                    self :: TYPE_BASE
                                )
                            )
                        )
                        {
                            //- Next -//
                            continue;
                        }

                        //- Create new file in path destination -//
                        $file = new \FileSystem\File(
                            $this -> path -> Get() .
                            Path :: SEPARATOR .
                            $item
                        );
                        if( !$file -> Copy( $pathDestination -> Get() ) )
                        {
                            return false;
                        }
                    }
                }

            return true;
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Create
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param [const string] $Mode
         * @param [bool] $Recursive = false
         * @return bool
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Create( 
            /*const string*/$Mode = 'w', 
            /*bool*/        $Recursive = false 
        )// : bool
        {
            if( $this -> type === self :: TYPE_DIR )
            {
                //- Create directory -//
                return mkdir( $this -> path -> Get(), $this -> mode, $Recursive );
            }

            //- Create file -//
            return (bool)$this -> handle = fopen( 
                $this -> path -> Get(), 
                $Mode 
            );
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Delete
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return bool
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Delete()// : bool
        {
            //- Special files for return to back -//
            if(
                in_array(
                    $this -> type,
                    array(
                        self :: TYPE_BASE,
                        self :: TYPE_BACK
                    )
                )
            )
            {
                //- File do not need delete -//
                return true;
            }

            //- Real file or directory -//
            if( $this -> type === self :: TYPE_DIR )
            {
                //- Directory -//
                $content = $this -> GetContent();
                foreach( $content as $file )
                {
                    $file = new \FileSystem\File(
                        $this -> path -> Get() .
                        Path::SEPARATOR .
                        $file
                    );
                    if( !$file -> Delete() )
                    {
                        return false;
                    }
                }

                rmdir( $this -> path -> Get() );
            }else
                {
                    return unlink( $this -> path -> Get() );
                }

            return true;
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Move
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param Path $PathDestination
         * @return bool
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Move( /*Path*/$PathDestination )// : bool
        {
            //- Copy and delete files and directories -//
            return $this -> Copy( $PathDestination ) && $this -> Delete();
        }

        //- SECTION :: GET -//
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Get content from this path
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return array.index|string|false
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function GetContent()// : array.index|string|false
        {
            if( $this -> type === self :: TYPE_DIR )
            {
                //- List of content in directory -//
                return scandir( $this -> path -> Get() );
            }

            //- Content -//
            return fread( 
                $this -> handle, 
                filesize( 
                    $this -> path -> Get() 
                ) 
            );
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Get size
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param [const string] $Format = BYTE
         * @return uint
         * @throws \Exception
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function GetSize( /*const string*/$Format = self :: SIZE_FORMAT_BYTE )// : uint
        {
            switch( $Format )
            {
                //- Byte -//
                case self :: SIZE_FORMAT_BYTE:
                {
                    return $this -> size;
                }break;

                //- KB -//
                case self :: SIZE_FORMAT_KBYTE:
                {
                    return $this -> size / 1024;
                }break;

                //- MB -//
                case self :: SIZE_FORMAT_MBYTE:
                {
                    return $this -> size / (1024 * 2);
                }break;

                //- GB -//
                case self :: SIZE_FORMAT_GBYTE:
                {
                    return $this -> size / (1024 * 3);
                }break;

                default:
                {
                    throw new \Exception( 'Unsuorted size format' );
                }break;
            }
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Get creation
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param [const string] $Format = TIMESTAMP
         * @return uint|string
         * @throws \Exception
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function GetCreation( /*const sting*/$Format = 'Y-m-d H:i:s' )// : uint|string
        {
            return date(
                $Format, 
                $this -> created
            );
        }


        //- SECTION :: SET -//
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Set content
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param string $Content
         * @return bool
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function SetContent( /*string*/$Content )// : bool
        {
            //- Directory -//
            if( $this -> type === self :: TYPE_DIR )
            {
                //- EXCEPTION :: Can not set content to folder -//
                throw new \Exception( 'Can not set content to folder' );//TODO: create new Exception
            }

            //- Content -//
            return fwrite( 
                $this -> handle, 
                $Content 
            );
        }
    }
}
