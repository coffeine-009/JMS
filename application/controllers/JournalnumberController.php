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
    }

    public function indexAction()
    {
        // action body
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
	        	-> from( 'Jms_JournalNumber jn' )
	        	-> addFrom( 'jn.Journal j' )
	        	-> where( 'j.id = ?', array( $journal_number_id ) )
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
	        $pageUrlBase = $this -> view -> url(
	        	array(), 
	        	'journal_view', 
	        	1
	        ) . "/{$journal_number_id}/{%page}/{$records_count}";
	        
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
	        $this -> view -> logotip = $journal_number_data[ 0 ][ 'Journal' ][ 'title' ];
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
	    			array_merge(
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
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get list of journals -//
	        $query = Doctrine_Query :: create()
    			-> from( 'Jms_Article a' )
    			-> addFrom( 'Jms_JournalNumber jn' )
    			-> where( 'jn.id = ?', array( (int)$input -> id ) )
    			-> addWhere( 'a.id_journal_number = ?', array( (int)$input -> id ) )
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
	        $pageUrlBase = $this -> view -> url(
	        	array(), 
	        	'journal_number_view', 
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
	        $this -> view -> journalNumbers = $journalNumbers;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			//- Exception :: Invalid input -//
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }


}
