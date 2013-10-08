<?php
///	***	FileSystem :: PathManager	***	***	***	***	***	***	***	***	***	***	///

    /**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
     * 																	*
     * 		@copyright 2012
     * 			by
     * 		@author Vitaliy Tsutsman
     *
     * 		@date 2013-10-07 22:38:03 - ..../../..
     *
     * 		@description Manager of path of filesstem
     *
     *		/Ukraine/Ivano-Frankivsk/
     *																	*
    *///	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code    *** ***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
namespace SystemFile
{
    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Manager of path
     *  --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- *
     * @operations
     *  CREATE
     *  COPY
     *  DELETE
     *  MOVE
     *  SCAN
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
    class PathManager
    {
        /// *** Constants   *** ///
        const /*string*/SEPARATOR = '/';
        //- Base path -//
        const /*string*/BASE_PATH_SERVER = './';
        const /*string*/BASE_PATH_UNIX = '/';
        const /*string*/BASE_PATH_WIN = 'C:/';


        /// *** Properties  *** ///
        protected /*const string*/$basePath;
        protected /*string.array*/$nodes;
        protected /*uint*/$length;


        /// *** Methods     *** ///
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Construct
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param string $Path
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function __construct( /*string*/$Path = '.' )// : void
        {
            //- Split path -//
            $this -> nodes = explode(
                self :: SEPARATOR, 
                $Path
            );

            //- Set UPPER case -//
            $this -> nodes[ 0 ] = \strtoupper( $this -> nodes[ 0 ] );

            //- Set base path -//
            if( 
                in_array( 
                $this -> nodes[ 0 ] . self :: SEPARATOR, 
                    array(
                        self :: BASE_PATH_UNIX, 
                        self :: BASE_PATH_WIN, 
                        self :: BASE_PATH_SERVER
                    )
                )
            )
            {
                $this -> basePath = $this -> nodes[ 0 ] . self :: SEPARATOR;
            }

            //- Delete first element -//
            array_shift( $this -> nodes );

            //- Culc count elements in array -//
            $this -> length = count( $this -> nodes );

            //- Delete empty elements in end -//
            if( empty( $this -> nodes[ $this -> length - 1 ] ) )
            {
                array_pop( $this -> nodes );
                $this -> length--;
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
            $this -> nodes = null;
        }


        //- SECTION :: MAIN -//
        //TODO: write
    }
}
