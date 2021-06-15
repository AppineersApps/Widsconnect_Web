$("#com_app_yearly_subscription").blur(function(){
	
	var a = $("#com_app_yearly_subscription").val();

	var b = a.toString().split(".")[1];
	//alert(b);

	if(b == 49 || b == 99)
	{
		return true;
	}
	else
	{
		$("#com_app_yearly_subscription").val("");
		$("#com_app_yearly_subscription").attr("placeholder", "accept decimal values 49 and 99 only.");
	}

	return false;
	/*if(b <= 50)
	{
		b = 49;
	}

	if(b > 50)
	{
		b = 99;
	}
*/

});