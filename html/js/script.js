$(function() {
	
	/* VISUAL TWEAKS, ENHANCEMENTS, AND SETUP
	****************************************************************** */
	
	$('.dashboard #latest').css({
		minHeight: $('.dashboard #share').outerHeight(true) + $('.dashboard #events').outerHeight(true) + $('.dashboard #jobs').outerHeight(true)
	});
	
	$.ajaxSetup({
		type: "POST"
	});
	
	
	
	/* FILTERS, CARDSTACKS, SLIDERS
	****************************************************************** */
	
	/* $('#projects.slider').jslide({
		width: 620,
		height: 165,
		items: 2,
		loop: true,
		slideshow: {
			direction: 'next',
			duration: 100,
			delay: 500
		}
	}); */
	
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
	
	
	
	/* AJAX LOADING
	****************************************************************** */
	
	var urlArray = window.location.pathname.substring(1).split('/');
	var page = (urlArray[0] === '')?'/':urlArray[0];
	var pageNumber = (urlArray[1] === undefined)?1:urlArray[1];
	
	if (page === '/' || page === 'home' || page === 'user') {
		var ajaxURL = '/ajax/latest';
		var postsPerPage = $('#latest').find('article').length/pageNumber;
		if (page === 'user') {
			var ajaxQuery = 'user';
		}
		else {
			var ajaxQuery = '';
		}
	}
	else if (page === 'people' || page === 'projects' || page === 'articles' || page === 'events') {
		console.log("ELSE IF RUNNING!!!!!!!!");
	}
	
	$('p.load-more a').live('click',function(){
		var parent = $(this).parent();
		$.ajax({
			url: ajaxURL,
			type: 'POST',
			dataType: 'html',
			data: {
				start: pageNumber * postsPerPage,
				limit: postsPerPage,
				query: ajaxQuery
			},
			success: function(response){
				$(response).insertBefore(parent);
				
				pageNumber++;
				$.ajax({
					url: ajaxURL,
					dataType: 'html',
					data: {
						start: pageNumber * postsPerPage,
						limit: postsPerPage,
						query: ajaxQuery
					},
					success: function(response){
						if ($('<div></div>').append(response).find('article').length == 0) {
							$(parent).remove();
						}
					}
				});
			}
		});
		
		return false;
	});
});