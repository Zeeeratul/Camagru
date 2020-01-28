function myFunction()
{
	var value = document.getElementById("myCheck").checked;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			document.getElementById('myCheck').innerHTML = this.responseText;
		}
	}
	if (value == false)
	{
		xmlhttp.open("GET", "includes/toggle_email.inc.php?bool=false", true);
		xmlhttp.send();
	}
	else
	{
		xmlhttp.open("GET", "includes/toggle_email.inc.php?bool=true", true);
		xmlhttp.send();
	}
}