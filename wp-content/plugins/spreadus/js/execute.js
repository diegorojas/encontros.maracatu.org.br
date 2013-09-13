jQuery(document).ready(function($)
{
	$('.close').click(function()
	{
		$(this).parents('.notice').slideUp(150);
	});
	
	setTimeout(function()
	{
		$('.notice').slideUp(200);
	}, 5000);
	
	
	$('form [title]').tipsy({
		fade: true,
		trigger: 'focus',
		gravity: 'w'
	});
	
	$('#shortener').change(function()
	{
		newShortner = $(this).attr('value');
		$('tr.shortener.active').addClass('hidden').removeClass('active');
		$('tr.shortener.' + newShortner).addClass('active').removeClass('hidden');
	});
});