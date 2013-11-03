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
     * 		@description Manager path of files
     *
     *		/Ukraine/Ivano-Frankivsk/
     *																	*
    *///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code    *** ***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
namespace FileSystem
{
    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Manager of path
     *  --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- *
     * @operations
     *  FORWARD
     *  BACK
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
    class Path
    {
        /// *** Constants   *** ///
        const /*string*/SEPARATOR = '/';
        //- Base path -//
        const /*string*/BASE_PATH_SERVER = './';
        const /*string*/BASE_PATH_UNIX = '/';
        const /*string*/BASE_PATH_WIN = '\w:/';


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
        {//TODO: add exceptions
            //- Split path -//
            $this -> nodes = explode(
                self :: SEPARATOR,
                $Path
            );

            //- Set UPPER case -//
            //- Test base path -//
            if(
                !in_array(
                    $this -> nodes[ 0 ] . self :: SEPARATOR,
                    array(
                        self :: BASE_PATH_UNIX,
                        self :: BASE_PATH_SERVER
                    )
                )
                &&
                !preg_match(
                    '|' . self :: BASE_PATH_WIN . '|',
                    $this -> nodes[ 0 ] . self :: SEPARATOR
                )
            )
            {
                //- Exception :: Bad base path -//
                throw new \Exception(
                    'Bad base path',
                    1000//TODO: set correct
                );
            }

            //- Set base path -//
            $this -> basePath = $this -> nodes[ 0 ] . self :: SEPARATOR;

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
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Forward
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param string $Item
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Forward( /*string*/$Item )// : void
        {
            //- Add new item -//
            $this -> nodes[] = $Item;

            //- Update count nodes -//
            $this -> length--;
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Back
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Back()// : void
        {
            //- Remove last element -//
            \array_pop( $this -> nodes );

            //- Update count nodes -//
            $this -> length--;
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Back to base path
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function BackToBase()// : void
        {
            $this -> nodes = array();
        }


        //- SECTION :: GET -//
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Get path
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return string
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function Get()// : string
        {
            return $this -> basePath .
            \implode(
                self :: SEPARATOR,
                $this -> nodes
            );
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Get last node
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return string
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function GetLastNode()// : string
        {
            return $this -> nodes[ $this -> length - 1 ];
        }
    }
}
