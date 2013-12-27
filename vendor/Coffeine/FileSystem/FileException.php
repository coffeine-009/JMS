<?php
///	***	FileSystem :: FileException ***	***	***	***	***	***	***	***	***	***	///

    /**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
     * 																	*
     * 		@copyright 2013
     * 			by
     * 		@author Vitaliy Tsutsman
     *
     * 		@date 2013-11-03 17:40:00 - 2013-11-03 17:47:57
     *
     * 		@description Exceptions for file
     *
     *		/Ukraine/Ivano-Frankivsk/Petranka/Gryshevskiy/234
     *																	*
    *///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Code    *** ***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
namespace FileSystem
{
    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     * File exception
     *  --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- --- *
     * Exceptions for file
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
    class FileException
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
        public function __construct( /*string*/$Message = null, /*int*/$Code = 0 )
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
