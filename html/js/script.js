$(function() {
	
	/* Visual tweaks, enhancements, and setup
	****************************************************************** */
	
	$.ajaxSetup({
		type: "POST"
	});
	
	$('.dashboard #latest').css({
		minHeight: $('.dashboard #share').outerHeight(true) + $('.dashboard #events').outerHeight(true) + $('.dashboard #jobs').outerHeight(true)
	});
	
	
	
	/* Filters, cardstacks, add more, flagging, fancybox
	****************************************************************** */
	
	// Fancybox setup
	$('a.fancybox').fancybox({
		titleShow: false,
		cyclic: true,
		showCloseButton: false
	});
	
	// "Add Another" link in forms
	$('.add-more').live('click',function(){
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
	
	// filtering function
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
	
	// Changing user type on account page
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
	
	// Ajax Flagging
	$('.secondary a.flag').live('click',function(){
		var flag = $(this);
		$(flag).toggleClass('current');
		var URL = $(this).attr('href').substring(1).split('/');
		if (URL[0] === 'flag') {
			$.ajax({
				url: '/ajax/flag',
				dataType: 'text',
				data: {
					type: URL[1],
					vanityURL: URL[2]
				},
				success: function(response){
					var rsp = $.parseJSON(response);
					if (rsp.status == false) {
						$(flag).toggleClass('current');
					}
				}
			});
		}
		return false;
	});
	
	
	
	/* Ajax load more
	****************************************************************** */
	
	// "Load more" variable setup
	var urlArray = window.location.pathname.substring(1).split('/'),
		page = (urlArray[0] === '')?'/':urlArray[0];
		
	switch (page) {
		case 'user':
			pageSegment = 2;
			break;
		default:
			pageSegment = 2;
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
		var link = $(this);
		var parent = $(this).parent();
		$(link).toggleClass('loading').html('<img src="images/site/icon-load-more.gif" alt="Loading..." />Loading...');
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
				$(link).toggleClass('loading').html('Load More');
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