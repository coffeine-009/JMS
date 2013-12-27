<?php
/// *** Application :: Journal  *** *** *** *** *** *** *** *** *** *** *** ///

    /** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *
     *                                                              *
     * @copyright (c) 2013, Vitaliy Tsutsman
     *
     * @author coffeine-009
     * 
     * @date 2013-11-08 22:00:00 :: 2013-..-.. ..:..:..
     * 
     * @address /Ukraine/Ivano-Frankivsk/Chornovola/115/3
     *                                                              *
    *///*** *** *** *** *** *** *** *** *** *** *** *** *** *** *** *

/// *** Code    *** *** *** *** *** *** *** *** *** *** *** *** *** *** *** ///
namespace Application\Controller
{
    //- Dependencies -//
    use \Application\Model\Journal as Journal;
    use \Zend\Mvc\Controller\AbstractActionController;
    use \Zend\View\Model\ViewModel;
    use \Zend\Filter;
    use \Zend\InputFilter\Input;
    use \Zend\InputFilter\InputFilter;
    use \Zend\InputFilter\FileInput;
    use \Zend\Validator;

    /** *** *** *** *** *** *** *** *** *** *** *** *** *
     * Journal controller
     * ---  --- --- --- --- --- --- --- --- --- --- --- *
     * Release main actions for work with journal
     * ACTIONS:
     *  LIST
     *  CREATE
     *  READ
     *  UPDATE
     *  DELETE
    *///*** *** *** *** *** *** *** *** *** *** *** *** *
    class JournalController
        extends
            AbstractActionController
    {
        /// *** Constants   *** ///
        
        /// *** Properties  *** ///


        /// *** Methods     *** ///
        /** *** *** *** *** *** *** *** *** *** *
         * List of journals action
         * ---  --- --- --- --- --- --- --- --- *
         * @description Default action for controller
         * @param void
         * @return void
        *///*** *** *** *** *** *** *** *** *** *
        public function listAction()// : void
        {
            //- Get params -//
            $orderField =       $this -> params() -> fromRoute( 'orderField' );
            $orderType  =       $this -> params() -> fromRoute( 'orderType' );
            $page       = (int) $this -> params() -> fromRoute( 'page' );
            $count      = (int) $this -> params() -> fromRoute( 'count' );

            //- Get data about journals -//
            $model = new Journal(
                $this -> getServiceLocator() -> get(
                    'Doctrine\ORM\EntityManager'
                )
            );
            $journals = $model -> getList(
                $orderField, 
                $orderType, 
                $page, 
                $count
            );

            //- Return params to view -//
            return new ViewModel(
                array(
                    'journals'  => $journals -> data, 
                    'pages'     => (object)array(
                        'count'             => $journals -> count, 
                        'active'            => $page, 
                        'countDisplay'      => $count,  
                        'countPagesLinks'   => 9//TODO: get from config
                    )
                )
            );
        }

        public function createAction()// : void
        {
            //- Init -//
            $result = array(
                'value' => array(
                    'issn'          => '', 
                    'title'         => '', 
                    'description'   => ''
                ), 
                'error' => array(
                    'issn'          => array(), 
                    'title'         => array(), 
                    'description'   => array()
                )
            );

            //- Validation requests -//
            if( $this -> getRequest() -> isPost() )
            {
                //- Logotip -//
                $logotip = new FileInput( 'logotip' );
                    $logotip -> getValidatorChain()
                        -> attachByName( 'filesize', array( 'max' => '204800' ) )
                        -> attachByName( 'filemimetype', array( 'mimeType' => 'image/jpeg' ) );

                //- ISSN -//
                $issn = new Input( 'issn' );
                    $issn -> getValidatorChain()
                    -> addValidator(
                        new Validator\NotEmpty()
                    )
                    -> addValidator(
                        new Validator\Regex(
                            array(
                                'pattern'   => '/^[[:alnum:]\-]{2,15}$/uix'
                            )
                        )
                    );

                //- Title -//
                $title = new Input( 'title' );
                    $title -> getValidatorChain()
                    -> addValidator(
                        new Validator\NotEmpty()
                    )
                    -> addValidator(
                        new Validator\Regex(
                            array(
                                'pattern'   => '/^[[:alnum:]\-\ ]+$/uix'
                            )
                        )
                    );

                //- Description -//
                $description = new Input( 'description' );
                    $description -> getValidatorChain()
                    -> addValidator(
                        new Validator\Regex(
                            array(
                                'pattern'   => '/^[^&\<\>\/\\#]+/'
                            )
                        )
                    );
                    
                $inputFilter = new InputFilter();
                    $inputFilter 
                        -> add( $logotip )
                        -> add( $issn )
                        -> add( $title )
                        -> add( $description )
                        -> setData( 
                            array_merge_recursive( 
                                $_POST, 
                                $this -> getRequest() -> getFiles() -> toArray()
                            ) 
                        );

                if( $inputFilter -> isValid() )
                {
                    $data = $inputFilter -> getRawValues();
                    
                    //- Save file -//
                    $adapter =  new \Zend\File\Transfer\Adapter\Http();
                        $adapter -> setDestination( './' );
                    
                    if( !$adapter -> receive( $data[ 'logotip' ][ 'name' ] ) )
                    {
                        throw new \Exception( 'Upload error' );
                    }
                    
                    \Zend\Debug\Debug::dump( $data );
                   
                    //TODO: Create model & receive file & redirect
                    
                }else
                    {
                        //- Transport errors to view -//
                        $result[ 'error' ] = array_merge(
                            $result[ 'error' ], 
                            $inputFilter -> getMessages()
                        );

                        //- Populate form -//
                        $result[ 'value' ] = array_merge( 
                            $result[ 'value' ], 
                            $inputFilter -> getValues()
                        );\Zend\Debug\Debug::dump($result);
                    }
            }
            
            //- Return params to view -//
            return new ViewModel( $result );
        }

        public function readAction()// : void
        {
            //- Get params -//
            $id = (int)$this -> params() -> fromRoute( 'id' );
            
            $journal = new \Application\Model\Journal(
                $this -> getServiceLocator() -> get(
                    'Doctrine\ORM\EntityManager'
                )
            );
            
            $journalData = $journal ->get( $id );

            return new ViewModel(
                array(
                    'journal' => array(
                        'id'        => $journalData -> id, 
                        'isbn'      => $journalData -> isbn, 
                        'creation'  => $journalData -> creation, 
                        'JournalLanguage'   => array(
                            'title'         => 'CMP', 
                            'description'   => 'CMP_'
                        ), 
                        'JournalNumber' => array(
                            array(
                                'id'    => 1, 
                                'volume'=> '1', 
                                'issue' => 1
                            )
                        )
                    )
                )
            );
        }

        public function updateAction()// : void
        {
            
        }

        public function deleteAction()// : void
        {
            
        }
    }
}
