///	***	NETWORK :: HTTP :: CONNECT.JS	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *																		*
	 *	@copyright 2012
	 *		by																*
	 *	@author Vitaliy Tsutsman
	 *																		*
	 *	@date 2012/05/01 - 2012/07/31
	 *																		*
	 *	@adress Ukraine/Ivano-Frankivsk/Rozniativ/Petranka
	 *																		*
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Directive {Include}	***	***	***	***	***	***	***	***	***	***	***	***	***	///


///	***	Description	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///

	/**	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*
	 *
	 *	Connect
	 *
	 *	PARRENTS :: {
	 *		NONE
	 *	}
	 *
	 *	DESCRIPTION :: {
	 *		Http request, POST, GET.
	 *		Ajax
	 *	}
	 *
	*///***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	***	*

///	***	Object release	***	***	***	***	***	***	***	***	***	***	***	***	***	***	///
var Connect = function()//- Construct -//
{
	///	***	Constants	***	///
	this.HTTP_REQUEST_TYPE_GET 	= "GET";
	this.HTTP_REQUEST_TYPE_POST = "POST";
	
	///	***	Properties	***	///
	this.httpType = this.HTTP_REQUEST_TYPE_GET;	//- Type of request		-//
	this.url = null;							//- Url for request 	-//
	this.params = '';							//- Params for request 	-//
};

///	***	Methods	***	***	***	***	***	***	***	***	***	///
Connect.prototype.setRequestType = function( /*const string*/HttpType )// : void
{
	this.httpType = HttpType;
};

//- Set url for connect -//
Connect.prototype.setUrl = function( /*string.url*/Url )// : void
{
	this.url = Url;
};

//- Set params for request -//
Connect.prototype.setParams = function( /*string*/Params )// : void
{
	if( this.params != '' )
	{
		this.params += "&";
	}
	
	this.params += Params;
};

//- Request -//
Connect.prototype.Request = function( /*function*/CallBackResponse )// : void
{
	var url_params = this.url;
	
	if( this.params != '' ){ url_params += "?" + this.params; }
	
	//- Send Request -//
	$.ajax(
		{
			url		: url_params,
			type	: this.httpType, 
			success	: CallBackResponse
		}
	);
	
	this.params = '';
};