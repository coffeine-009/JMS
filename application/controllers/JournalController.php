<?php
///	***	Class :: Controller :: Journal	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * @author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-07 11:33:24 :: 2013-05-21 14:14:49
	 * 
	 * @address Poland/Krakow/Bydruka/5/414
	 * 																*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Include other files	***	***	***	***	***	***	***	***	***	***	***	***	///
require_once APPLICATION_PATH . '/controllers/BaseController.php';

///	***	Code	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
class JournalController
	extends
		BaseController
{
	///	***	Methods		***	///
    public function init()
    {
    	//- Init -//
    	parent :: init();
    }

    
    //- Default action -//
    public function indexAction()
    {
		//TODO: Default page
    }

    
    //- List of journal -//
    public function listAction()
    {
    	//- Init view -//
    	$this -> view -> logotip = 'Journals';
        $this -> view -> Title = 'Journals';
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
	        /*$connection = Doctrine_Manager :: connection();
	        	$query = 
	        	"SELECT  
	        		journal.id, 
	        		journal.isbn, 
	        		journal_language.title,
	        		COUNT( journal.id ) AS numbers_count, 
	        		journal.creation
	        	FROM
	        		(
	        			journal
		        			LEFT JOIN
		        		journal_language
		        			ON( journal.id = journal_language.id )
		        	)
		        		LEFT JOIN
		        	journal_number
		        		ON( journal.id = journal_number.id_journal )
	        	WHERE 
	        		journal_language.code_language = '%s'
	        	GROUP BY
	        		journal.id
	        	ORDER BY
	        		creation DESC
	        	";
		        	 
		        $statement = $connection -> execute( 
			        sprintf(
			        	$query, 
			        	//- Params -//
			        	'en' 
			        )
		        );
		        $statement -> execute();
		        //$statement->fetch( PDO::FETCH_ASSOC );*/
	        $query = Doctrine_Query :: create()
	        	-> select( 'j.id, j.isbn, jl.title, COUNT( jn.id_journal ) AS numbers_count, j.creation' )
	        	-> from( 'Jms_Journal j' )
	        	-> addFrom( 'j.JournalLanguage jl' )
	        	-> addFrom( 'j.JournalNumber jn' )
	        	-> where( "jl.code_language = 'en'" )
	        	-> groupBy( 'jn.id_journal' )
	        	-> orderBy( 'id' );
	        	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journals = $pager -> execute(
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
	        	'journal_list', 
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
	        $this -> view -> journals = $journals;
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }

	
    //- View journal -//
    public function viewAction()
    {
	    //- Init view -//
	    $this -> view -> logotip = 'Journal';
        $this -> view -> Title = 'Journal';
        $this -> view -> pathOfSite = 'Jourmal => View';
        
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
    		$journal_id = (int)$input -> id;
    		$page = (int)$input -> page;
	        $records_count = (int)$input -> count;
    		
	        //- Get data about journal -//
	        $query = Doctrine_Query :: create()
	        	-> from( 'Jms_Journal j' )
	        	-> addFrom( 'j.JournalLanguage jl' )
	        	-> addFrom( 'j.JournalNumber jn' )
	        	-> where( 'id = ?', array( $journal_id ) )
	        	-> addWhere( "jl.code_language = 'en'" )
	        	-> orderBy( 'id' );
	       	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journal_data = $pager -> execute(
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
	        ) . "/{$journal_id}/{%page}/{$records_count}";
	        
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
	        $this -> view -> logotip = $journal_data[ 0 ][ 'JournalLanguage' ][ 0 ][ 'title' ];
	        $this -> view -> journal = $journal_data[ 0 ];
	        $this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }
    
    
    //- Add new journal -//
    public function addAction()
    {
    	//- Include css -//
    	//$this -> view -> headLink() -> appendStylesheet(
    	//	'/client/application/views/styles/registration.css'
    	//);
    	
    	//- Include JS -//
    	$this -> view -> headScript() -> appendFile(
    		'/client/application/views/scripts/journal.js'
    	);
    	$this -> view -> headScript() -> appendFile(
    		'/client/library/Coffeine/Connect/Ajax/Connect.js'
    	);
    	
    	//- Init -//
    	$this -> errors = array(
    		'cover'			=> array(), 
	    	'isbn'			=> array(), 
	    	'title'			=> array(), 
	    	'description'	=> array()
    	);
    	
     	//- Init view -//
     	$this -> view -> logotip = 'Add journal';
        $this -> view -> Title = 'Add journal';
        $this -> view -> pathOfSite = 'Jourmal => Add';
        
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
    		'isbn'	=> array( 
    			new Zend_Validate_Regex( '/^[[:alnum:]\-]{2,15}$/uix' )
    		), 
    		'title' => array( 
    			new Zend_Validate_Regex( '/^[[:alnum:]\-\ ]+$/uix' ) 
    		), 
    		'description' => array(
    			new Zend_Validate_Regex( '/^[^&\<\>\/\\#]+/uix' )
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
				$journal = new Jms_Journal();
		        	$journal -> isbn = $input -> isbn;		        	
		        	
		        $journal -> save();
		        //- Journal language inf -//
		        $journal_language = new Jms_JournalLanguage();
		        	$journal_language -> id = (int)$journal -> id;
		        	$journal_language -> code_language = 'en';//TODO: Get current
		        	$journal_language -> title = $input -> title;
		        	$journal_language -> description = $input -> description;
		        	
		        $journal_language -> save();
		        
		        //- Create file struct for journal -//
		        $fJournal = new Coffeine_Files_File();
		        
		        	if( 
		        		!$fJournal -> createDirectory( 
							APPLICATION_PATH .'/../public/data/journals/', 
							$journal -> id
		        		) 
		        		|| 
		        		!$fJournal -> createDirectory( 
							APPLICATION_PATH .'/../public/data/journals/' . $journal -> id . '/', 
							'photo'
		        		)
		        	)
		        	{
		        		//- Exception :: File struct not created -//
		        		$this -> errors[ 'cover' ][] = 'Can not create directory';
		        	}
		        
		        //- Upload cover -//
	    		$cover = new Zend_File_Transfer();
	    			$cover -> setDestination( 
	    				APPLICATION_PATH .'/../public/data/journals/' . $journal -> id . '/photo/'
	    			);
	    			$cover -> addFilter(
	    				'Rename', 
	    				APPLICATION_PATH .'/../public/data/journals/' . $journal -> id . '/photo/cover.jpg', 
	    				'cover'
	    			);
	    			$cover-> addValidator(	    				
	    				'Count', 
	    				false, 
	    				1, 
	    				'cover'
	    			);
	    			//- Validators -//
	    			$cover -> addValidator(
	    				'Size', 
	    				false, 
	    				array(
	    					'min'		=> '10kB', 
	    					'max'		=> '5MB', 
	    					'bytestring'=> false
	    				), 
	    				'cover'
	    			);
	    			$cover -> addValidator(
	    				'IsImage', 
	    				false, 
	    				'jpeg', 
	    				'cover'
	    			);
	    		
	    		if( !$cover -> isValid() )
	    		{    			
	    			//- Exception :: Can not upload file -//
	    			array_merge(
	    				$this -> errors[ 'cover' ], 
	    				$cover -> getErrors()
	    			);
	    			
	    			return false;
	    		}
	    		
	    		$cover -> receive();
		        
		        //- Add message -//
		        $this -> _helper -> flashMessenger 
					-> addMessage( 'Journal is added.' );
		        
		        //- Redirect to view journal -//
		        $this -> _redirect( '/journal/' . $journal -> id );
	    	}else
	    		{
	    			//- Exception :: Input invalid -//
	    			array_merge(
	    				$this -> errors[ 'cover' ], 
	    				$input -> getErrors()
	    			);
	    			
	    			//- Set input data -//
	    			$this -> view -> data = array(
	    				'isbn'			=> $input -> isbn, 
		    			'title'			=> $input -> title, 
		    			'description'	=> $input -> description
	    			);
	    			
	    			//throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }

    
    //- Edit journal -//
    public function editAction()
    {
    	//- Include css -//
    	//$this -> view -> headLink() -> appendStylesheet(
    	//	'/client/application/views/styles/registration.css'
    	//);
    	
    	//- Include JS -//
    	$this -> view -> headScript() -> appendFile(
    		'/client/application/views/scripts/journal.js'
    	);
    	$this -> view -> headScript() -> appendFile(
    		'/client/library/Coffeine/Connect/Ajax/Connect.js'
    	);
    	
    	//- Init -//
    	$this -> errors = array(
    		'cover'			=> array(), 
	    	'isbn'			=> array(), 
	    	'title'			=> array(), 
	    	'description'	=> array()
    	);
    	
    	//- Get id of journal -//
    	$journal_id = (int)$this -> getRequest() -> getParam( 'id' );
    	
    	//- Get info about journal -//
    	$journal = Doctrine_Query :: create()
    		-> from( 'Jms_Journal j' )
    		-> addFrom( 'j.JournalLanguage' )
    		-> where( 'id = ?', array( $journal_id ) )
    		-> limit( 1 );
    	
    	$journal = $journal -> fetchArray();
    		
		//- Init view -//
        $this -> view -> Title = 'Edit journal';
        $this -> view -> pathOfSite = 'Jourmal => Edit';
        $this -> view -> journal = $journal[ 0 ];
        
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
    		'isbn'	=> array( 
    			new Zend_Validate_Regex( '/^[[:alnum:]\-]{2,15}$/uix' )
    		), 
    		'title' => array( 
    			new Zend_Validate_Regex( '/^[[:alnum:]\-\ ]+$/uix' ) 
    		), 
    		'description' => array(
    			new Zend_Validate_Regex( '/^[^&\<\>\/\\#]+/uix' )
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
				$journal = Doctrine :: getTable( 'Jms_Journal' )
		        	-> find( $journal_id );
		        	
		        	$journal -> isbn = $input -> isbn;//TODO: ISBN
		 		        	
		        $journal -> save();
		 	    			    		
		        //- Journal language inf -//
		        $connection = Doctrine_Manager :: connection();
		        	$query = 
		        	"UPDATE 
		        		journal_language 
		        	SET
		        		title = '{$input -> title}', 
		        		description = '{$input -> description}'
		        	WHERE
		        		id = {$journal_id} 
		        		AND 
		        		code_language = 'en'
		        	";
		        	 
		        $statement = $connection -> execute( $query );
		        $statement -> execute();
		 	    
		        
		        //- Upload cover -//
		        if( isset( $_POST[ 'cover' ] ) )
		        {
		    		$cover = new Zend_File_Transfer();
		    			$cover -> setDestination( 
		    				APPLICATION_PATH .'/../public/data/journals/' . $journal -> id . '/photo/'
		    			);
		    			$cover -> addFilter(
		    				'Rename', 
		    				APPLICATION_PATH .'/../public/data/journals/' . $journal -> id . '/photo/cover.jpg', 
		    				'cover'
		    			);
		    			$cover-> addValidator(	    				
		    				'Count', 
		    				false, 
		    				1, 
		    				'cover'
		    			);
		    			//- Validators -//
		    			$cover -> addValidator(
		    				'Size', 
		    				false, 
		    				array(
		    					'min'		=> '10kB', 
		    					'max'		=> '5MB', 
		    					'bytestring'=> false
		    				), 
		    				'cover'
		    			);
		    			$cover -> addValidator(
		    				'IsImage', 
		    				false, 
		    				'jpeg', 
		    				'cover'
		    			);
		    		
		    		if( !$cover -> isValid() )
		    		{
		    			//- Exception :: Can not upload file -//
		    			array_merge(
		    				$this -> errors[ 'cover' ], 
		    				$cover -> getMessages()
		    			);
		    			
		    			return false;
		    		}
		    		
		    		//- Create file struct for journal -//
			        $fJournal = new Coffeine_Files_File();
			        
			        	if( 
			        		!$fJournal -> delete( 
								APPLICATION_PATH .'/../public/data/journals/' .  
								$journal -> id . 
								'/photo/cover.jpg'
			        		)
			        	)
			        	{
			        		//- Exception :: File struct not created -//
			        		$this -> errors[ 'cover' ][] = 'Can not reupload cover';
			        	}
		    		
		    		$cover -> receive();
		        }
		        
		        //- Add message -//
		        $this -> _helper -> flashMessenger 
					-> addMessage( 'Journal is edited.' );
		        
		        //- Redirect to view journal -//
		        $this -> _redirect( '/journal/' . $journal -> id );
	    	}else
	    		{
	    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }

    
    //- Delete journal -//
    public function deleteAction()
    {
		//- Init view -//
        $this -> view -> Title = 'Delete journal';
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
				$journal = Doctrine_Query :: create()
					-> delete( 'Jms_Journal' )
		        	-> where( 'id = ?', $input -> id );
		        	
		        $journal -> execute();
		        
		    	//- Create file struct for journal -//
		        $fJournal = new Coffeine_Files_File();
		        
		        	if( 
		        		!$fJournal -> delete( 
							APPLICATION_PATH .'/../public/data/journals/'  . 
							(int)$input -> id
		        		)
		        	)
		        	{
		        		//- Exception :: File struct not created -//
		        		$this -> errors[] = 'Can not delete directory';
		        	}
		        
		        //- Add message -//
		        $this -> _helper -> flashMessenger 
					-> addMessage( 'Journal is deleted.' );
		        
		        //- Redirect to view journal -//
		        $this -> _redirect( '/journals' );
	    	}else
	    		{
	    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }


}