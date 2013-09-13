/**
 * Pop-Up function
 */
function popitup(url, height, class_name)
{
	if(class_name == undefined || class_name == '')
	{
		class_name = height;
	}
	window['popup_' + class_name] = window.open(
		url,
		'popup_' + class_name,
		'height=' + height + ',width=840,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,left=200px,top=200px'
	);
	if(window.focus){
		window['popup_' + class_name].focus();
	}
	
	check_popup('popup_' + class_name);
	
	return false;
}

function check_popup(window_name)
{
	if(window[window_name].closed)
	{
		window.location.reload();
	}
	else
	{
		setTimeout(function()
		{
			check_popup(window_name);
		},500);
	}
}
