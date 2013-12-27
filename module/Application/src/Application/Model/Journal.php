<?php
/// *** Application :: Journal  *** *** *** *** *** *** *** *** *** *** *** ///

    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     *                                                              *
     * @copyright (c) 2013, Vitaliy Tsutsman
     *
     * @author coffeine-009
     * 
     * @date 2013-11-10 4:25:28 :: ....-..-.. ..:..:..
     * 
     * @address /Ukraine/Ivano-Frankivsk/Chornovola/115/3
     *                                                              *
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *

/// *** Code    *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ///
namespace Application\Model
{
    //- Dependencies -//


    /** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Journal
     * ---  --- --- --- --- --- --- --- --- --- --- --- *
     * Description
     * ACTIONS:
     *  LIST
     *  CREATE
     *  READ
     *  UPDATE
     *  DELETE
    *///*** *** *** *** *** *** *** *** *** *** *** *** *
    class Journal
    {
        /// *** Constants   *** ///
        
        /// *** Properties  *** ///
        protected $entityManager;
        
        /// *** Methods     *** ///
        public function __construct( $EntityManager )
        {
            $this -> entityManager = $EntityManager;
        }

        /** *** *** *** *** *** *** *** *** *** *
         * List of Expression Name is undefined on line 44, column 22 in Templates/Scripting/Zend/Controller. action
         * ---  --- --- --- --- --- --- --- --- *
         * @description {Your description}
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function getList(
            /*string*/  $OrderField = 'id', 
            /*string*/  $OrderType = 'asc', 
            /*int*/     $Page = 1, 
            /*int*/     $Count = 10
        )// : array
        {
            $journals = (object)array(
                'count' => 2, 
                'data'  => array()
            );

            $journalList = $this -> entityManager -> getRepository(
                'Application\Model\Entity\Journal'
            )
                -> findBy(
                    array(), 
                    array( 
                        $OrderField => $OrderType 
                    ), 
                    $Count, 
                    --$Page * $Count
                );

            //- Formating response -//
            foreach( $journalList as $journal )
            {
                $journals -> data[] = (object)array(
                    'id'            => $journal -> getId(), 
                    'isbn'          => $journal -> getIsbn(), 
                    'countNumbers'  => 1, 
                    'creation'      => $journal -> getCreation() -> format('Y-m-d H:i:s')
                );
            }

            return $journals;
        }

        /** *** *** *** *** *** *** *** *** *** *
         * Create a new Expression Name is undefined on line 60, column 27 in Templates/Scripting/Zend/Controller. action
         * ---  --- --- --- --- --- --- --- --- *
         * @description {Your description}
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function createAction()// : void
        {
            
        }

        /** *** *** *** *** *** *** *** *** *** *
         * Read info about Expression Name is undefined on line 72, column 30 in Templates/Scripting/Zend/Controller. action
         * ---  --- --- --- --- --- --- --- --- *
         * @description {Your description}
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function get( /*int*/$Id )// : void
        {
            $journal = $this -> entityManager -> getRepository(
                'Application\Model\Entity\Journal'
            )
                -> find( $Id );

            //- Formating response -//
            $journalData = (object)array(
                'id'        => $journal -> getId(), 
                'isbn'      => $journal -> getIsbn(), 
                'creation'  => $journal -> getCreation() -> format('Y-m-d H:i:s')
            );

            return $journalData;
        }

        /** *** *** *** *** *** *** *** *** *** *
         * Update a Expression Name is undefined on line 88, column 23 in Templates/Scripting/Zend/Controller. action
         * ---  --- --- --- --- --- --- --- --- *
         * @description {Your description}
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function updateAction()// : void
        {
            
        }

        /** *** *** *** *** *** *** *** *** *** *
         * Delete a Expression Name is undefined on line 100, column 23 in Templates/Scripting/Zend/Controller. action
         * ---  --- --- --- --- --- --- --- --- *
         * @description {Your description}
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function deleteAction()// : void
        {
            
        }
    }
}

