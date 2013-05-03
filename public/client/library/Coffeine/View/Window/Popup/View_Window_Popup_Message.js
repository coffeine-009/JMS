

var View_Window_Popup_Message = function( /*string*/Id, /*handle*/hParent )// :: construct
{
	///	***	Properties	***	///
	this.id = Id;
	this.parrent = hParent;
	
	this.handle = null;
	
	this.params = {
		class	: 'view-window-popup-message', 
		position	: {
			top	: document.body.scrollHeight - 120, 
			left: document.body.scrollWidth - 320,//10
		}, 
		size	: {
			height	: "auto", 
			width	: "300px"
		}, 
		background	: "-moz-linear-gradient( top, #000, #222, #000 )", 
		color		: '#f00', 
		opacity		: 0.9
	};
	
	this.title = null;
	this.text = null;
	
	
	this.timer = null;;
}

View_Window_Popup_Message.prototype.setText = function( /*string*/szText )// : void
{
	this.text = szText;
}

View_Window_Popup_Message.prototype.setTitle = function( /*string*/szTitle )// : void
{
	this.title = szTitle;
}

View_Window_Popup_Message.prototype.Display = function()// : void
{
	this.handle = document.createElement( "div" );
	
	//- Set config standart -//
	//this.handle.style.zIndex = 3;
	this.handle.style.position='absolute';
	this.handle.style.top = this.params.position.top + "px";
	this.handle.style.left = this.params.position.left + "px";		
	this.handle.style.width = this.params.size.width;
	this.handle.style.height = this.params.size.height;
	
	//- Set new configuration -//
	this.handle.setAttribute( "id", this.id );
	this.handle.setAttribute( "class", this.params.class );
		
	this.handle.style.color = this.params.color;
	this.handle.style.background = this.params.background;
	this.handle.style.opacity = this.params.opacity;
	
	this.handle.style.padding = 5 + "px";
	this.handle.style.borderRadius = 5 + "px";
	
	
	this.handle.innerHTML = '<div style = "padding:3px; margin-bottom:3px; font-size:11pt; font:bold;">' + this.title + 
			'</div><div style = "font-size:10pt; border-radius : 3px; position:relative; padding:5px;">' + this.text + '</div>';
	
//- Display wnd -//
	this.parrent.appendChild( this.handle );
	
	var t = this;
	this.timer = setInterval( 
		function(){ 
			t.Efect.call( t );
		}, 
		10 
	);
}

View_Window_Popup_Message.prototype.Efect = function()
{
	if( this.params.position.top < 30 )
	{
		clearInterval( this.timer );
		
		var t = this;
		this.timer = setInterval(
			function(){
				t.Destroy.call( t )
			}, 
			5000
		);
		
	}else
		{
			this.params.position.top -= 24;
			this.handle.style.top = this.params.position.top + "px";
		}
}

View_Window_Popup_Message.prototype.Destroy = function()// : void
{
	if( this.handle != null )
	{
		this.parrent.removeChild( this.handle );
		clearInterval( this.timer );
	}
}