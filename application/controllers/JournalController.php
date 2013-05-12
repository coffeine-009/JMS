<?php
///	***	Class :: Controller :: Authorization	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 * 																*
	 * @copyroght 2013
	 * 		by
	 * 	@author Vitaliy Tsutsman
	 * 
	 * @date 2013-05-07 11:33:24 :: 2013-..-.. ..:..:..
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
    	parent :: init();
    }

    public function indexAction()
    {
        // action body
    }

    //- List of journal -//
    public function listAction()
    {
    	//- Init view -//
        $this -> view -> Title = 'Journals';
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
	        	-> from( 'Jms_Journal' )
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

	//- View -//
    //- View journal -//
    public function viewAction()
    {
	    //- Init view -//
        //$this -> view -> Title = 'Journals';
        $this -> view -> pathOfSite = 'Jourmal => List';
        
    	//- Filters -//
    	$filters = array(
    		'id' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
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
	        	-> addFrom( 'j.JournalNumber jn' )
	        	-> where( 'id = ?', array( $journal_id ) )
	        	-> orderBy( 'id' );
	       	
	        //- Pager init -//
	        $pager = new Doctrine_Pager(
	        	$query, 
	        	$page,  
	        	$records_count
	        );
	        
	        //- Journals -//
	        $journal_data = $pager -> execute(
	        	//array(), 
	        	//Doctrine :: HYDRATE_ARRAY
	        );
	        /*
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
			*/
	        //- Init view -//
	        $this -> view -> logotip = $journal_data[ 0 ][ 'title' ];
	        $this -> view -> journal = $journal_data[ 0 ];
	        //$this -> view -> pages = $pagerLayout -> display( null, true );
    	}else
    		{
    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
    		}
    }
    
    //- Add new journal -//
    public function addAction()
    {
    	//- Init -//
    	$this -> errors = array(
    		'cover'			=> array(), 
	    	'isbn'			=> array(), 
	    	'title'			=> array(), 
	    	'description'	=> array()
    	);
    	
     	//- Init view -//
        $this -> view -> Title = 'Add journal';
        $this -> view -> pathOfSite = 'Jourmal => Add';
        
    	//- Filters -//
    	$filters = array(
    		'*' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)/*, 
    		'title' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	), 
	    	'description' => array(
	    		'HtmlEntities', 
	    		'StripTags', 
	    		'StringTrim'
	    	)*/
    	);
    	
    	//- Validators -//
    	$validators = array(
    		'*'	=> array( 
    			'NotEmpty'
    		), 
    		'isbn'	=> array( 
    			
    		), 
    		'title' => array( 
    			 
    		), 
    		'description' => array(
    			
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
		        	$journal -> issn = $input -> isbn;//TODO: ISBN
		        	$journal -> title = $input -> title;
		        	$journal -> description = $input -> description;
		        	
		        $journal -> save();
		        
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
	    			
	    			//throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }

    //- Edit journal -//
    public function editAction()
    {
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
    		-> from( 'Jms_Journal' )
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
    			
    		), 
    		'title' => array( 
    			 
    		), 
    		'description' => array(
    			
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
		        	
		        	$journal -> issn = $input -> isbn;//TODO: ISBN
		        	$journal -> title = $input -> title;
		        	$journal -> description = $input -> description;
		        	
		        $journal -> save();
		        
		        
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
							$journal -> id
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
		        $this -> _redirect( '/journal/list' );
	    	}else
	    		{
	    			throw new Zend_Controller_Action_Exception( 'Invalid input' );
	    		}
    	}
    }


}