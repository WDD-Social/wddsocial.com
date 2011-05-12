$(function() {
	
	/* VISUAL TWEAKS AND ENHANCEMENTS
	****************************************************************** */
	
	$('.dashboard #latest').css({
		minHeight: $('.dashboard #share').outerHeight(true) + $('.dashboard #events').outerHeight(true) + $('.dashboard #jobs').outerHeight(true)
	});
	
	/* SETUP
	****************************************************************** */
	
	$.ajaxSetup({
		type: "POST"
	});
	
	$('.filters a').live('click',function(){
		$(this).parent().find('a.current').removeClass('current');
		$(this).addClass('current');
		if($(this).html() === 'All'){
			$(this).parent().parent().find('article').slideDown(500,'easeInOutQuad');
		}else{
			var class = $(this).text().toLowerCase();
			$(this).parent().parent()
				.find('article.'+class).slideDown(500,'easeInOutQuad').parent()
				.find('article:not(article.'+class+')').slideUp(500,'easeInOutQuad');
		}
		return false;
	});
	
	var i = 0;
	$('#load-more').live('click',function(){
		$.ajax({
			url: '/ajax/latest',
			dataType: 'html',
			data: {
				start: i,
				limit: 1
			},
			success: function(response){
				$('#latest').append(response);
			}
		});
		i++;
		return false;
	});
});