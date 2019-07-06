$(function(){
	//Trigger The Selectboxit 
	$("select").selectBoxIt();


	
	$('.cats h3').click(function() {
		$(this).next('.slide').fadeToggle();
	});

});