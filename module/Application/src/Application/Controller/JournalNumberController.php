<?php
/// *** Application :: JournalNumberController  *** *** *** *** *** *** *** ///

    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     *                                                              *
     * @copyright (c) 2013, Vitaliy Tsutsman
     *
     * @author coffeine-009
     * 
     * @date 2013-11-09 09:55:21 :: ....-..-.. ..:..:..
     * 
     * @address /Ukraine/Ivano-Frankivsk/Chornovola/115/3
     *                                                              *
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *

/// *** Code    *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ///
namespace Application\Controller
{
    //- Dependencies -//
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;

    /** *** *** *** *** *** *** *** *** *** *** *** *** *
     * JournalNumber controller
     * ---  --- --- --- --- --- --- --- --- --- --- --- *
     * Description
     * ACTIONS:
     *  LIST
     *  CREATE
     *  READ
     *  UPDATE
     *  DELETE
    *///*** *** *** *** *** *** *** *** *** *** *** *** *
    class JournalNumberController
        extends
            AbstractActionController
    {
        /// *** Constants   *** ///
        
        /// *** Properties  *** ///
        
        /// *** Methods     *** ///
        /** *** *** *** *** *** *** *** *** *** *
         * List of Journal numbers
         * ---  --- --- --- --- --- --- --- --- *
         * @description {Your description}
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function listAction()// : void
        {            
            return new ViewModel(
                array(

                )
            );
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
        public function readAction()// : void
        {
            return new ViewModel(
                array(

                )
            );
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

