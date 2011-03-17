$(function() {
	$('p.error').slideUp(0);
	$('#mc-embedded-subscribe-form').live('submit',function(){
		if($('#mce-EMAIL').val() === '' || $('#mce-FNAME').val() === '' || $('#mce-LNAME').val() === ''){
			$('p.error').html('Please fill out all fields before submitting.').stop().slideDown(300,function(){
				var timer = setTimeout("$('p.error').slideUp(300);",3000);
			});
			return false;
		}
	});
	$('#mc-unsubscribe-form').live('submit',function(){
		if($('#mce-EMAIL').val() === ''){
			$('p.error').html('Please fill out all fields before submitting.').stop().slideDown(300,function(){
				var timer = setTimeout("$('p.error').slideUp(300);",3000);
			});
			return false;
		}
	});
});