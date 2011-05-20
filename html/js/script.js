$(function() {
	
	/* VISUAL TWEAKS, ENHANCEMENTS, AND SETUP
	****************************************************************** */
	
	$.ajaxSetup({
		type: "POST"
	});
	
	$('.dashboard #latest').css({
		minHeight: $('.dashboard #share').outerHeight(true) + $('.dashboard #events').outerHeight(true) + $('.dashboard #jobs').outerHeight(true)
	});
	
	
	
	/* FILTERS, CARDSTACKS, ADD MORE, FANCYBOX
	****************************************************************** */
	
	$('a.fancybox').fancybox({
		titleShow: false,
		cyclic: true,
		showCloseButton: false
	});
	
	$('.add-more').live('click',function(){
		console.log("ADD ANOTHER");
		var addMoreLink = $(this);
		var newHTML = $(this).prev().clone(true);
		if($(newHTML).is('input')){
			$(newHTML).attr('placeholder','');
			$(newHTML).attr('value','');
		}
		if($(newHTML).is('fieldset')){
			var inputs = $(newHTML).find('input');
			$(inputs).each(function(){
				$(this).attr('placeholder','');
				$(this).attr('value','');
			});
		}
		$(newHTML).insertBefore($(addMoreLink));
		return false;
	});
	
	var filter = function(type, parent, duration){
		if(typeof duration == 'undefined'){
			duration = 500;
		}
		if(type === 'All'){
			$(parent).find('article').slideDown(duration,'easeInOutQuad');
		}else{
			var lowerType = type.toLowerCase();
			$(parent)
				.find('article.'+lowerType).slideDown(duration,'easeInOutQuad').parent()
				.find('article:not(article.'+lowerType+')').slideUp(duration,'easeInOutQuad');
		}
	}
	
	$('.filters a').live('click',function(){
		$(this).parent().find('a.current').removeClass('current');
		$(this).addClass('current');
		filter($(this).html(),$(this).parent().parent());
		return false;
	});
	
	
	$('#user-type.radio input[type="radio"]').live('change', function(){
		var type = $(this).attr('id');
		var user = $(this).parent().parent().data('user');
		$('#user-type-details').slideUp(250,function(){
			$.ajax({
				url: '/ajax/content',
				dataType: 'html',
				data: {
					section: 'getUserDetail',
					usertype: type,
					userID: user
				},
				success: function(response){
					$('#user-type-details').html(response).slideDown(250);
					console.log(response);
				}
			});
		});
	});
	
	
	
	/* AJAX LOAD MORE
	****************************************************************** */
	
	// "Load more" variable setup
	var urlArray = window.location.pathname.substring(1).split('/'),
		page = (urlArray[0] === '')?'/':urlArray[0];
		
	switch (page) {
		case 'user':
			pageSegment = 2;
			break;
		default:
			pageSegment = 1;
	}
	var pageNumber = (urlArray[pageSegment] === undefined)?1:urlArray[pageSegment];
	
	if (page === '/' || page === 'home' || page === 'user') {
		var postsPerPage = $('#latest').find('article').length/pageNumber,
			ajaxExtra = {};
			
		if (page === 'user') {
			$.ajax({
				url: '/ajax/get',
				data: {
					query: 'getUserIDByVanityURL',
					vanityURL: urlArray[1]
				},
				success: function(response){
					ajaxExtra = {
						userID: response
					};
				}
			});
			var ajaxQuery = 'getUserLatest';
		}
	}
	else if (page === 'people' || page === 'projects' || page === 'articles' || page === 'events') {
		// SET UP LOAD MORE CODE FOR DIRECTORIES
		var postsPerPage = $('#directory').find('article').length/pageNumber,
			ajaxQuery = 'get' + page.charAt(0).toUpperCase() + page.slice(1),
			ajaxExtra = {
				active: $('#directory .secondary .current').html().toLowerCase()
			};
	}
	
	// "Load more" functionality
	$('p.load-more a').live('click',function(){
		var parent = $(this).parent();
		$.ajax({
			url: '/ajax/more',
			dataType: 'html',
			data: {
				start: pageNumber * postsPerPage,
				limit: postsPerPage,
				query: ajaxQuery,
				extra: ajaxExtra
			},
			success: function(response){
				$(response).insertBefore(parent);
				pageNumber++;
				selectedSection = $(parent).parent();
				filter($(selectedSection).find('div.filters a.current').html(),selectedSection,0)
				$.ajax({
					url: '/ajax/more',
					dataType: 'html',
					data: {
						start: pageNumber * postsPerPage,
						limit: postsPerPage,
						query: ajaxQuery,
						extra: ajaxExtra
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