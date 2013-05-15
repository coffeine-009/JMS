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
    	//- Redirect to default page -//
		$this -> _redirect( '/article/add' );
    }

    
    //- Add new article(Upload) -//
    public function addAction()
    {
		//- Init view -//
		$this -> view -> logotip = strtoupper( 'Add new article' );
		$this -> view -> Title = 'Add new article';
        $this -> view -> pathOfSite = 'Jourmal => List';        
        
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
	    	$farticle_name = $article -> getFileName( 'article' );//APPLICATION_PATH . '/../public/data/users/1.tex';
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
			
	    	//- Save a valid doc -//
	    	$article_params = $parser -> getValue();

	    	//- Save base info -//
	    	$article_data = new Jms_Article();
	    		$article_data -> id_user = (int)$this -> session -> user[ 'id' ];
	    		$article_data -> code_language = $article_params[ 'language' ][ 'code' ];
	    		
	    	$article_data -> save();

	    	//- Save content -//
	    	$article_data_lang = new Jms_ArticleLanguage();
	    		$article_data_lang -> id = (int)$article_data -> id;
	    		$article_data_lang -> code_language = 'en';
	    		$article_data_lang -> author 	= $article_params[ 'enabstract' ][ 'authors' ];
	    		$article_data_lang -> title 	= $article_params[ 'enabstract' ][ 'title' ];
	    		$article_data_lang -> abstract 	= $article_params[ 'enabstract' ][ 'abstract' ];
	    		
	    	$article_data_lang -> save();
	    	
	    	//- Add messages -//
	    	$this -> _helper -> flashMessenger() -> addMessage(
	    		'Article accepted'
	    	);
	    	
	    	//- Redirect to archive of article -//
	    	$this -> _redirect( '/article/' . $article_data -> id );
    	}
    }

    public function editAction()
    {
        // action body
    }

    public function viewAction()
    {
		//- Init view -//
		$this -> view -> logotip = strtoupper( 'Params of article' );
		$this -> view -> Title = 'View params of article';
        $this -> view -> pathOfSite = 'Jourmal => List';
        
	    //- Filters -//
    	$filters = array(
    		'*' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'id' => array( 
    			'Int' 
    		)
    	);
    	
    	$input = new Zend_Filter_Input( $filters, $validators );
    		$input -> setData(
    			$this -> getRequest() -> getParams()
    		);
    	
    	if( $input -> isValid() )
    	{
    		//- Get article params -//
    		$article = Doctrine_Query :: create()
    			-> from( 'Jms_Article a' )
    			-> addFrom( 'a.ArticleLanguage al' )
    			-> where( 'id = ?', array( (int)$input -> id ) )
    			-> addWhere( "al.code_language = 'en'" )
    			-> addWhere( 'a.id_user = ?', array( $this -> session -> user[ 'id' ] ) )
    			-> orderBy( 'al.code_language' );
    		
    		$article = $article -> fetchArray();
    		//- Init view -//
    		$this -> view -> article = $article[ 0 ];	        
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }

    public function deleteAction()
    {
	    //- Init view -//
        $this -> view -> Title = 'Delete article';
        $this -> view -> pathOfSite = 'Jourmal => Delete';
        
    	//- Filters -//
    	$filters = array(
    		'*' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'id'	=> array( 
    			'NotEmpty', 
    			'Int'
    		)
    	);
    	
    	if( $this -> getRequest() -> isGet() )
    	{
	    	$input = new Zend_Filter_Input( $filters, $validators );
	    		$input -> setData(
	    			$this -> getRequest() -> getParams()
	    		);
    	
	    	if( $input -> isValid() )
	    	{
	    		//- Save info -//
				$article = Doctrine_Query :: create()
					-> delete( 'Jms_Article' )
		        	-> where( 'id = ?', $input -> id );
		        	
		        $article -> execute();
		        
		        $articleLanguage = Doctrine_Query :: create()
					-> delete( 'Jms_ArticleLanguage' )
		        	-> where( 'id = ?', $input -> id );
		        	
		        $articleLanguage -> execute();
		        
		    	//- Create file struct for journal -//
		        /*$fJournal = new Coffeine_Files_File();
		        
		        	if( 
		        		!$fJournal -> delete( 
							APPLICATION_PATH .'/../public/data/journals/'  . 
							$journal -> id
		        		)
		        	)
		        	{
		        		//- Exception :: File struct not created -//
		        		$this -> errors[] = 'Can not delete directory';
		        	}
		        */
		        //- Add message -//
		        $this -> _helper -> flashMessenger 
					-> addMessage( 'Journal is deleted.' );
		        
		        //- Redirect to view journal -//
		        $this -> _redirect( '/articles' );
	    	}else
	    		{
	    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }

    public function listAction()
    {
    	//- Init view -//
		$this -> view -> logotip = strtoupper( 'List of article' );
		$this -> view -> Title = 'List of article';
        $this -> view -> pathOfSite = 'Jourmal => List';
        
    	//- Filters -//
    	$filters = array(
    		'page' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
	    	'count' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'page' => array( 
    			'Int' 
    		), 
    		'count' => array(
    			'Int'
    		)
    	);
    	
    	$input = new Zend_Filter_Input( $filters, $validators );
    		$input -> setData(
    			$this -> getRequest() -> getParams()
    		);
    	
    	if( $input -> isValid() )
    	{
    		//- Data -//
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get list of journals -//
	        $query = Doctrine_Query :: create()
	        	-> from( 'Jms_Article a' )
    			-> addFrom( 'a.ArticleLanguage al' )    			
    			-> addWhere( "al.code_language = 'en'" )
    			-> addWhere( 'a.id_user = ?', array( $this -> session -> user[ 'id' ] ) )    			
	        	-> orderBy( 'id' )
	        	-> addOrderBy( 'al.code_language' );
	        	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $articles = $pager -> execute(
	        	array(), 
	        	Doctrine :: HYDRATE_ARRAY
	        );
	        
	        //- Init maket for pager -//
	        $pagerRange = new Doctrine_Pager_Range_Sliding(
	        	array(
	        		'chunk' => $records_count
	        	), 
	        	$pager
	        );
	        
	        //- Set base url -//
	        $pageUrlBase = $this -> view -> url(
	        	array(), 
	        	'article_list', 
	        	1
	        ) . "/{%page}/{$records_count}";
	        
	        //- Init template for display links -//
	        $pagerLayout = new Doctrine_Pager_Layout(
	        	$pager, 
	        	$pagerRange, 
	        	$pageUrlBase
	        );
	        
	        $pagerLayout -> setTemplate( '<a href = "{%url}">{%page}</a>' );
	        $pagerLayout -> setSelectedTemplate( '<span class = "current">{%page}</span>' );
	        $pagerLayout -> setSeparatorTemplate( '&nbsp' );
	        
	        //- Init view -//
	        $this -> view -> articles = $articles;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
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
