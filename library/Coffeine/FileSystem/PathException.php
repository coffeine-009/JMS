<?php
///	***	FileSystem :: PathException	***	***	***	***	***	***	***	***	***	***	///

    /**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
     * 																	*
     * 		@copyright 2012
     * 			by
     * 		@author Vitaliy Tsutsman
     *
     * 		@date 2013-11-03 17:24:21 - 2013-11-03 17:39:28
     *
     * 		@description Path exception
     *
     *		/Ukraine/Ivano-Frankivsk/Petranka/Gryshevskiy/234
     *																	*
    *///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code    *** ***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
namespace FileSystem
{
    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Path exception
     *  --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- *
     * Exception for path
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
    class PathException
        extends
            \Exception
    {
        /// *** Methods     *** ///
        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Construct
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param string $Message Message of exception
         * @param int $Code Code of exception
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function __construct( /*string*/$Message = null, /*int*/$Code = 0 )// : void
        {
            if( !$Message )
            {
                throw new $this( 'Unknown' . get_class( $this ) );
            }

            parent :: __construct(
                $Message, 
                $Code
            );
        }

        /** *** *** *** *** *** *** *** *** *** *** *** *** *** *
         * Destruct
         * ---  --- --- --- --- --- --- --- --- --- --- --- --- *
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *** *** *** *** *
        public function __destruct()
        {
            parent :: __destruct();
        }
    }
}
