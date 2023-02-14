function fecha_banner()
{
	var banner_obj = document.getElementById('banner_popup');
	banner_obj.style.display = 'none';
}

function fecha_banner_timeout()
{
	setTimeout('fecha_banner()', 10000);
}

function abre_banner()
{
	var banner_obj = document.getElementById('banner_popup');

	banner_obj.style.left = '200px';
	banner_obj.style.top = '100px';

	banner_obj.style.display = '';

	fecha_banner_timeout();
}