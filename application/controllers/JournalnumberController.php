<?php
///	***	Class :: Controller :: JournalNumber	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-12 14:10:28 :: 2013-..-.. ..:..:..
	 * 
	 * @address Poland/Krakow/Bydruka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class JournalnumberController
	extends
		//Zend_Controller_Action
		BaseController
{

    public function init()
    {
		//- Init -//
		parent :: init();
		
		//- Init others view formats -//
		$this -> _helper -> getHelper( 'contextSwitch' )
			-> addActionContext( 'getjournalnumbers', 'json' )
			-> initContext();
    }

    //- Default -//
    public function indexAction()
    {
		//TODO: Default page
    }


    //- List of numbers in journal -//
    public function listAction()
    {
    	//- Init view -//
        $this -> view -> Title = 'Numbers of journal';
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
    		'*'		=> array(
    			'NotEmpty'
    		), 
    		'id'	=> array(
    			'Int'
    		), 
    		'page'	=> array(
    			'Int'
    		), 
    		'count'	=> array(
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
    		$journal_number_id = (int)$input -> id;
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get data about journal -//
	        $query = Doctrine_Query :: create()
	        	-> select( 'jn.id, jn.volume, jn.issue, jn.creation, j.id, jl.title, COUNT( a.id ) AS count' )
	        	-> from( 'Jms_JournalNumber jn' )
	        	-> addFrom( 'jn.Journal j' )
	        	-> addFrom( 'j.JournalLanguage jl' )
	        	-> addFrom( 'jn.Article a' )
	        	-> where( 'j.id = ?', array( $journal_number_id ) )
	        	-> groupBy( 'a.id' )
	        	-> addGroupBy( 'jn.id' )
	        	-> orderBy( 'j.id' );
	       	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journal_number_data = $pager -> execute(
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
	        $pageUrlBase = /*$this -> view -> url(
	        	array(), 
	        	null, //'journal_number_list', 
	        	1
	        ) .*/ "/journal/{$journal_number_id}/numbers/{%page}/{$records_count}";
	        
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
	        $this -> view -> logotip = $journal_number_data[ 0 ][ 'Journal' ][ 'JournalLanguage' ][ 0 ][ 'title' ];
	        $this -> view -> journal = $journal_number_data[ 0 ][ 'Journal' ];
	        $this -> view -> journalNumbers = $journal_number_data;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }

    
    //- Add new number to journal -//
    public function addAction()
    {
    	//- Errors -//
    	$this -> errors = array(
    		'filesystem'=> array(), 
    		'volume'	=> array(), 
    		'issue'		=> array()
    	);

    	//- Get params -//
        $journal_id = (int)$this -> getRequest() -> getParam( 'id' );
    	
	    //- Init view -//
        $this -> view -> Title = 'Add number to journal';
        $this -> view -> pathOfSite = 'Jourmal => Add';
        $this -> view -> journal = array(
        	'id'	=> $journal_id
        );
        
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
    		'*'	=> array( 
    			'NotEmpty'
    		), 
    		'volume'	=> array( 
    			'Int'
    		), 
    		'issue' => array( 
    			 'Int'
    		)
    	);
    	
    	if( $this -> getRequest() -> isPost() )
    	{
	    	$input = new Zend_Filter_Input( $filters, $validators );
	    		$input -> setData(
	    			$this -> getRequest() -> getParams()
	    		);
	    	
	    	if( $input -> isValid() )
	    	{
	    		//- Save info -//	    			    		
	    		//- Add journal -//
				$journalNumber = new Jms_JournalNumber();
					$journalNumber -> id_journal 	= $journal_id;
		        	$journalNumber -> volume 		= (int)$input -> volume;
		        	$journalNumber -> issue 		= (int)$input -> issue;
		        			        	
		        $journalNumber -> save();		        
		        
		        //- Create file struct for journal -//
		        $fJournal = new Coffeine_Files_File();
		        
		        	if( 
		        		!$fJournal -> createDirectory( 
							APPLICATION_PATH .'/../public/data/journals/' .  
							$journalNumber -> id_journal . 
							'/numbers/', 
							$journalNumber -> id
		        		) 
		        		|| 
		        		!$fJournal -> createDirectory( 
							APPLICATION_PATH .'/../public/data/journals/' .  
							$journalNumber -> id_journal . '/numbers/' . 
							$journalNumber -> id, 
							'articles'
		        		)
		        		|| 
		        		!$fJournal -> createDirectory( 
							APPLICATION_PATH .'/../public/data/journals/' .  
							$journalNumber -> id_journal . 
							'/numbers/' . $journalNumber -> id, 
							'recension'
		        		)
		        	)
		        	{
		        		//- Exception :: File struct not created -//
		        		$this -> errors[ 'filesystem' ][] = 'Can not create filestruct';
		        	}
		       
		        
		        //- Add message -//
		        $this -> _helper -> flashMessenger 
					-> addMessage( 'Number is added to Journal.' );
		        
		        //- Redirect to view journal -//
		        $this -> _redirect( '/journal/' . $journal_id . '/number/' . $journalNumber -> id );
	    	}else
	    		{
	    			//- Set input data -//
			        $this -> view -> data = array(
			    		'volume'	=> $input -> volume,  
			    		'issue'		=> $input -> issue
			    	);
		    	
	    			//- Exception :: Input invalid -//
	    			$this -> errors[ 'filesystem' ] = array_merge(
	    				$this -> errors[ 'filesystem' ], 
	    				$input -> getErrors()
	    			);
	    			
	    			//throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}	    	
    	}
    }

    public function editAction()
    {
        // action body
    }

    public function deleteAction()
    {
        // action body
    }

    
    //- View info about number from journal -//
    public function viewAction()
    {
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
    		'*'	=> array( 
    			'NotEmpty'
    		), 
    		'id'	=> array( 
    			'Int'
    		), 
    		'idn'	=> array( 
    			'Int'
    		), 
    		'page'	=> array( 
    			'Int'
    		), 
    		'count'	=> array( 
    			'Int'
    		)
    	);
    	
    	//- Get params -//
        $journal_id = (int)$this -> getRequest() -> getParam( 'id' );
    	
	    //- Init view -//
        $this -> view -> Title = 'Number of journal';
        $this -> view -> logotip = 'Number of journal';
        $this -> view -> pathOfSite = 'Jourmal => Add';
        $this -> view -> journal = array(
        	'id'	=> $journal_id
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
	        /*$connection = Doctrine_Manager :: connection();
		        $query = 
		        "SELECT
		        	*
		        FROM
		        	(
			        	journal_number
			        		RIGHT JOIN
			        	article
			        		ON( journal_number.id = article.id_journal_number )
			        )
			        	LEFT JOIN
			        article_language
			        	ON( article.id = article_language.id )
			    WHERE
			    	journal_number.id = %s
			    		AND
			    	article_language.code_language = '%s'
			    ORDER BY
			    	article.creation DESC
		        ";
		        
			$statement = $connection -> execute( 
				sprintf(
					$query, 
					//- Params -//
					(int)$input -> idn, 
					'en'
				)
			);
			$statement -> execute();*/
	        $query = Doctrine_Query :: create()
	        	-> select( 
	        		'jn.id, 
	        		jn.volume, 
	        		jn.issue, 
	        		jn.creation, 
	        		a.id, 
	        		a.code_language, 
	        		a.creation, 
	        		al.author, 
	        		al.title' 
	        	)
    			-> from( 'Jms_Article a' )
    			-> addFrom( 'a.JournalNumber jn' )
    			//-> from( 'Jms_JournalNumber jn' )
    			//-> addFrom( 'jn.Article a' )
    			-> addFrom( 'a.ArticleLanguage al' )
    			-> where( 'jn.id = ' . (int)$input -> idn )
    			-> addWhere( 'a.id_journal_number IS NOT NULL' )
    			-> addWhere( 'al.code_language = \'en\'' )
	        	-> orderBy( 'a.id' );
	        	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journalNumbers = $pager -> execute(
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
	        $pageUrlBase = /*$this -> view -> url(
	        	array(), 
	        	'journal_number_view', 
	        	1
	        ) . */"/journal/{$journal_id}/number/{$input -> idn}/{%page}/{$records_count}";
	        
	        //- Init template for display links -//
	        $pagerLayout = new Doctrine_Pager_Layout(
	        	$pager, 
	        	$pagerRange, 
	        	$pageUrlBase
	        );
	        
	        $pagerLayout -> setTemplate( '<a href = "{%url}">{%page}</a>' );
	        $pagerLayout -> setSelectedTemplate( '<span class = "current">{%page}</span>' );
	        $pagerLayout -> setSeparatorTemplate( '&nbsp' );

	        //- Get data about Number of journal -//
	        $q = Doctrine_Query :: create()
	        	-> select( 'jn.id, jn.volume, jn.issue, jn.creation' )
	        	-> from( 'Jms_JournalNumber jn' )
	        	-> where( 'jn.id = ' . (int)$input -> idn )
	        	-> limit( 1 );
	        	
	        $number = $q -> fetchArray();
	        
	        //- Init view -//
	        $this -> view -> number = $number[ 0 ];
	        $this -> view -> journalNumbers = $journalNumbers;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			//- Exception :: Invalid input -//
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }

    
    //- Get journal numbers -//
    public function getjournalnumbersAction()
    {
    	$journal_id = (int)$this -> getRequest() -> getParam( 'id' );
    	
    	$numbers_q = Doctrine_Query :: create()
    		-> select()
    		-> from( 'Jms_JournalNumber jn' )
    		-> where( 'jn.id_journal = ?', $journal_id )
    		-> orderBy( 'jn.id DESC' );
    		
    	$numbers_q -> execute();
    	
    	$numbers = $numbers_q -> fetchArray();
    	    		
    	$this -> view -> status = 1;
    	$this -> view -> msg = 'OK';
    	$this -> view -> data = $numbers;
    }

}
