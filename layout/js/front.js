$(function(){
	//Trigger The Selectboxit 
	$("select").selectBoxIt();


	
	
	$('.logs h1 span').click(function() {
		$(this).addClass('active').siblings().removeClass('active');
		$('.logs form').hide();
		$('.' + $(this).data('class')).fadeIn(100);
		console.log($('.' + $(this)));

	});


	$('.ad .live').keyup(function () {

		$($(this).data('class')).text($(this).val());

	});

});