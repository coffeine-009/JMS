<?php
///	***	Class :: Controller :: Article	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-13 18:16:30 :: 2013-..-.. ..:..:..
	 * 
	 * @address Poland/Krakow/Bydruka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class ArticleController 
	extends 
		//Zend_Controller_Action
		BaseController
{

    public function init()
    {
		//- Init -//
		parent :: init();
    }

    public function indexAction()
    {
        // action body
    }

    
    //- Add new article(Upload) -//
    public function addAction()
    {
		//- Init view -//
		
		//- Test of submiting file -//
    	if( $this -> getRequest() -> isPost() )
    	{
			//- Upload init and validation -//
	    	$article = new Zend_File_Transfer();
	    		$article -> setDestination( 
	    			APPLICATION_PATH .'/../public/data/users/'// . $this -> session -> user[ 'id' ] . '/articles/'
	    		);
	    		/*$article -> addFilter(
	    			'Rename', 
	    			APPLICATION_PATH .'/../public/data/users/1.tex',// . $this -> session -> user[ 'id' ] . '/articles/123.tex', 
	    			'article'
	    		);*/
				//- Validators -//
    			$article 
    			-> addValidator( 'Count', false, 1, 'article' )
    			-> addValidator( 'Size', false, array( 
    					'max'		=> '5MB', 
    					'bytestring'=> false
    				), 
    				'article'
    			)
    			-> addValidator( 'MimeType', false, 'text/x-tex', 'article' );
    			
    		//- Test of valid input data -//    		    		
    		if( !$article -> isValid() )
    		{
    			//- Exception :: Can not upload file -//
    			array_merge(
    				$this -> errors[ 'article' ], 
    				$article -> getErrors()
    			);
    			
    			return false;
    		}
	    		
	    	$article -> receive();
	    	
	    	
	    	//-# Get content of article #-//
	    	$farticle_name = APPLICATION_PATH . '/../public/data/users/1.tex';
	    	$farticle = fopen( $farticle_name, 'r' );
	    		$article_text = iconv( 'cp1251', 'UTF-8', //TODO: find encodinc
	    			fread( $farticle, filesize( $farticle_name ) ) 
	    		);
	    	fclose( $farticle );
	    	
	    	
	    	//-# Parse uploaded file #-//
	    	//- Init parser -//
    		$parser_configuration = new Zend_Config_Ini( 
    			APPLICATION_PATH . '/configs/articleTemplate.ini', 
    			'article' 
    		);
	    	$parser = new Jms_Parser_Analyzer(
	    		$article_text,
	    		$parser_configuration -> toArray()
	    	);
	    	
	    	//- Run process of parsing -//
	    	$parser -> parse();	    	
	    	
	    	if( !$parser -> isValid() )
	    	{
	    		//- Set errors for display -//
	    		$this -> errors = array_merge(
	    			$this -> errors, 
	    			$parser -> getErrors()
	    		);
	    			    		
	    		return false;
	    	}
			
	    	//- Doc is valid -//
	    	Zend_Debug::dump($parser->getValue());
    	}
    }

    public function editAction()
    {
        // action body
    }

    public function viewAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    public function listAction()
    {
        // action body
    }

    public function addtojournalAction()
    {
        // action body
    }

    public function deletefromjournalAction()
    {
        // action body
    }

    public function listfromjournalAction()
    {
        // action body
    }
}
