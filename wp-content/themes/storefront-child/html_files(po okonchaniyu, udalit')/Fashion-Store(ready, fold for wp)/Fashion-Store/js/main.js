// Модальное окно

// открыть по кнопке
	$('a').click(function() { 
	$('contacts').fadeIn();
	$('contacts').addClass('disabled');
	$('verh').addClass('disabled');
	$('div-multi').addClass('blur-filter');
	$('hero').addClass('blur-filter');
	$('frame4').addClass('blur-filter');
	$('footer').addClass('blur-filter');
});

// закрыть на крестик
	$('#close').click(function() { 
	$('#boy').fadeOut();
	$('main').removeClass('blur-filter');
	$('#left').removeClass('blur-filter');
	$('#right').removeClass('blur-filter');
	$('#up').removeClass('blur-filter');
	
});