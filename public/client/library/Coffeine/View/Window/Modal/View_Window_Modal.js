
var View_Window_Modal = function( /*string*/Id, /*handle*/hParent )// :: construct
{
	///	***	Properties	***	///
	this.id = Id;
	
	this.parrent = hParent;
	
	this.hWndAccessDenny = null;
	this.hWnd = null;
	
	this.params = {
		size	: {
			height	: 350, 
			width	: 300
		}
	};
	
	this.content = null;
}

///	***	Methods	***	***	***	***	***	***	***	***	***	***	***	***	***	///
View_Window_Modal.prototype.getCloseHandle = function()// : handle
{
	return this.hWndAccessDenny;
}

View_Window_Modal.prototype.setHeight = function( /*int*/Height )// : void
{
	this.params.size.height = Height;
}

View_Window_Modal.prototype.setWidth = function( /*int*/Width )// : void
{
	this.params.size.width = Width;
}

View_Window_Modal.prototype.setContent = function( /*string*/HTML )
{
	this.content = HTML;
}

//- Denny parrent -//
View_Window_Modal.prototype.Denny = function( /*object*/Config )// : void
{
	this.hWndAccessDenny = document.createElement( "div" );
	
		//- Set config standart -//
		this.hWndAccessDenny.style.position='absolute';
		this.hWndAccessDenny.style.top = 0;
		this.hWndAccessDenny.style.left = 0;		
		this.hWndAccessDenny.style.width = document.body.scrollWidth + 'px';
		this.hWndAccessDenny.style.height = document.body.scrollHeight + 'px';
		
		//- Set new configuration -//
		this.hWndAccessDenny.setAttribute( "id", Config.id );
		this.hWndAccessDenny.setAttribute( "class", Config.class );
			
		this.hWndAccessDenny.style.color = Config.color;
		this.hWndAccessDenny.style.background = Config.background;
		this.hWndAccessDenny.style.opacity = Config.opacity;
		
	//- Display wnd -//
		this.parrent.appendChild( this.hWndAccessDenny );
}

//- Destroy -//
View_Window_Modal.prototype.Destroy = function()// : void
{
	if( this.hWndAccessDenny != null )
	{
		this.parrent.removeChild( this.hWndAccessDenny );
		this.parrent.removeChild( this.hWnd );
	}
}

//- Create Modal window -//
View_Window_Modal.prototype.Create = function()// : void
{
	this.hWnd = document.createElement( "div" );
		
		//- Set standart configuration -//
		this.hWnd.setAttribute( "class", "registration_window" );
		this.hWnd.style.border = "10px solid #999";
		this.hWnd.style.borderRadius = "5px";
		this.hWnd.style.position='absolute';
		this.hWnd.style.top = (window.innerHeight / 2) - (this.params.size.height / 2) + 'px';
		this.hWnd.style.left = document.body.scrollWidth / 2 - (this.params.size.width / 2) + 'px';
		this.hWnd.style.color='#f00'
		this.hWnd.style.background = "#fff";
		//windowModal.style.opacity = 0.7;//alert();
		this.hWnd.style.width = this.params.size.width + 'px';//300
		this.hWnd.style.height = this.params.size.height + 'px';//350
	
		this.hWnd.innerHTML = this.content;
		
	this.parrent.appendChild( this.hWnd );
}

//- Display modal wondow -//
View_Window_Modal.prototype.Display = function()// : void
{
	//- Access denny -//
	this.Denny(
		{
			id			: this.id, 
    		class		: "wbg",
    		color		: "#f00", 
    		background	: "#000", 
    		opacity		: 0.7
    	}
	);
	
	this.Create();
}

