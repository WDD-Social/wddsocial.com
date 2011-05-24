$(function() {
	
	/* Visual tweaks, enhancements, and setup
	****************************************************************** */
	
	$.ajaxSetup({
		type: "POST"
	});
	
	if ($('p.error').text() == '') {
		$('p.error').slideUp(0);
	}
	
	$('.dashboard #latest').css({
		minHeight: $('.dashboard #share').outerHeight(true) + $('.dashboard #events').outerHeight(true) + $('.dashboard #jobs').outerHeight(true)
	});
	
	
	
	/* Fancybox
	****************************************************************** */
	
	$('a.fancybox').fancybox({
		titleShow: false,
		cyclic: true,
		showCloseButton: false
	});
	
	
	
	/* Add Another links
	****************************************************************** */
	
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
	
	
	
	/* Filtering
	****************************************************************** */
	
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
	
	
	
	/* Account page user type switching
	****************************************************************** */
	
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
	
	
	
	/* Flagging
	****************************************************************** */
	
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
	
	
	
	/* Autocompleters
	****************************************************************** */
	
	var removeAutocomplete = function(){
		if ($('#autocomplete').length) {
			$('#autocomplete').remove();
		}
	}
	
	var populateAutocomplete = function(results){
		if (!$('#autocomplete').length) {
			$('<ul id="autocomplete"></ul>').appendTo('body');
		}
		var autocomplete = $('#autocomplete');
		$(autocomplete).empty();
		for (var result in results) {
			var item = results[result];
			var extra = (item.extra === undefined)?'':'<span class="extra">' + item.extra + '</span>';
			$('<li><span class="term">' + item.title + '</span> ' + extra + '</li>').appendTo(autocomplete);
		}
	}
	
	var currentInput;
	
	var populateInput = function(term){
		$(currentInput).val(term);
		removeAutocomplete();
	}
	
	$('.autocompleter').live('keyup focusin',function(e){
		currentInput = $(this);
		var searchTerm = $(currentInput).val();
		if (searchTerm.length > 0 && searchTerm !== '' && e.keyCode !== 27) {
			$.ajax({
				url: '/ajax/autocomplete',
				data: {
					type: $(currentInput).data('autocomplete'),
					term: searchTerm
				},
				success: function(response){
					var obj = $.parseJSON(response);
					var autocompleteResults = [];
					if (obj.status) {
						for (var result in obj.results) {
							autocompleteResults.push(obj.results[result]);
						}
						populateAutocomplete(autocompleteResults);
						
						var offset = $(currentInput).offset();
						$('#autocomplete').css({
							top: offset.top + $(currentInput).outerHeight() - 1,
							left: offset.left + $(currentInput).outerWidth()*.0125,
							width: $(currentInput).outerWidth()*.975
						});
					}
					else {
						removeAutocomplete();
					}
				}
			});
		}
		else {
			removeAutocomplete();
		}
	});
						
	$('#autocomplete > li').live('click',function(){
		populateInput($(this).find('.term').text());
	});
	
	$('.autocompleter').live('focusout',function(){
		setTimeout(removeAutocomplete,250);
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
	
	
	
	/* Form validation
	****************************************************************** */
	
	var displayFormError = function(form, message, count){
		var clearFormError = function(){
			$(form).find('p.error').slideUp(250,'easeOutQuad',function(){
				$(form).find('p.error strong').html('');
			});
		}
		
		$(form).find('p.error strong').html(message);
		$(form).find('p.error').slideDown(250,'easeOutQuad');
		if (count === 'undefined') {
			count = 1;
		}
		var duration = 2000*count;
		setTimeout(clearFormError,duration);
	}
	
	$('input[type="password"].check-length').live('keyup',function(){
		if ($(this).val().length > 0) {
			if ($(this).val().length >= 6) {
				$(this).addClass('pass');
				$(this).removeClass('fail');
			}
			else {
				$(this).addClass('fail');
				$(this).removeClass('pass');
			}
		}
		else {
			$(this).removeClass('pass fail');
		}
	});
	
	$('#signup input').live('focusout',function(){
		if ($(this).val().length > 0 && $(this).hasClass('error')) {
			$(this).removeClass('error');
		}
	});
	
	$('#signup').live('submit',function(){
		var errorMessages = [];
		
		$(this).find('input[type="text"], input[type="password"], input[type="email"]').each(function(){
			if ($(this).val() == '') {
				$(this).addClass('error');
				if (errorMessages.length === 0) {
					errorMessages.push('All fields are required.');
				}
			}
		});
		
		if ($('input[type="password"]').val().length < 6) {
			errorMessages.push('Your password must be at least 6 characters in length.');
			input = $('input[type="password"]');
			if (!$(input).hasClass('error')) {
				$(input).addClass('error');
			}
		}
		
		if (!$('input[name="terms"]').is(':checked')) {
			errorMessages.push('You must agree to our <a href=\"/terms\" title=\"WDD Social Terms of Service\">Terms of Service</a>.');
		}
		
		if (errorMessages.length > 0) {
			var errorMessage = errorMessages.join('</strong></p><p class="error"><strong>');
			displayFormError($(this),errorMessage, errorMessages.length);
			return false;
		}
	});
});