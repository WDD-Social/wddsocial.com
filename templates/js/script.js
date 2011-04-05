$(function() {
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
});
