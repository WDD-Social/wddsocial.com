(function($){
	$.fn.jslide = function(options){
		var ar = arguments;
		if(typeof ar[0] !== "object"){
			return this.data('jslide_api')[ar[0]](ar[1]);
		};
		
		var o = $.extend({
			direction: 'horizontal',
			duration: 1000,
			easing: 'swing',
			loop: true,
			transition: 'slide',
			start: function(){},
			end: function(){}
		},options);
		
		return this.each(function(){
			o.direction = o.direction.toLowerCase();
		
			var that = $(this),
				filmstrip = that,
				items = filmstrip.children('article'),
				api = {},
				page = 0,
				blocks = Math.ceil(items.length / o.items) - 1,
				swidth = (o.direction==='horizontal')?(blocks+1)*o.width:o.width,
				sheight = (o.direction==='horizontal')?o.height:(blocks+1)*o.height,
				onext = $(o.next),
				oprev = $(o.prev)
			;
			console.log("THAT PARENT: " + that.parent().html());
			
			/*
that.css({
				overflow: 'hidden',
				width: o.width,
				height: o.height,
				position: 'relative'
			});
			
			filmstrip.css({
				position: 'absolute',
				top: 0,
				left: 0,
				padding: 0,
				margin: 0,
				listStyle: 'none',
				display: 'block',
				width: swidth,
				height: sheight
			});
*/
			
			items.css({
				float: 'left'
			});
			
			if(o.loop === false && page === 0){
				oprev.filter(':not(.nohide)').css({visibility: 'hidden'});
			};
			if(o.loop === false && page === blocks){
				onext.filter(':not(.nohide)').css({visibility: 'hidden'});
			};
			
			var anim = function(){
				if(o.transition === 'slide'){
					if(o.direction==='horizontal'){
						filmstrip.stop(true).animate({
							left: -(page*o.width)
						},o.duration,o.easing, function(){
							o.end({page: page});
						});
					}else{
						filmstrip.stop(true).animate({
							top: -(page*o.height)
						},o.duration,o.easing, function(){
							o.end({page: page});
						});
					};
				}else if(o.transition === 'fade'){
					if(o.direction==='horizontal'){
						filmstrip.stop(true).animate({
							opacity: 0
						},o.duration/2, o.easing, function(){
							filmstrip
								.css({
									left: -(page*o.width)
								})
								.animate({
									opacity: 1
								}, o.duration/2, o.easing)
							;
						});
					}else{
						filmstrip.stop(true).animate({
							opacity: 0
						},o.duration/2, o.easing, function(){
							filmstrip
								.css({
									top: -(page*o.height)
								})
								.animate({
									opacity: 1
								}, o.duration/2, o.easing)
							;
						});
					};
				};
					
				
				if(options.slidebar){
					$(options.slidebar).slider('value', page);
				};
			};
			
			var intervalFunction
			
			var prevfn = function(source){
				if(page > 0){
					page--;
					if(o.loop === false && page > blocks){
						page = blocks;
					};
				}else if(o.loop === true){
					page = blocks;	
				};
				
				if(o.loop === false && page === 0){
					oprev.filter(':not(.nohide)').animate({
						opacity: 0
					},200, function(){
						$(this).css({
							visibility: 'hidden'
						})
					});
				};
				
				if(onext.filter(':not(.nohide)').css('visibility') === 'hidden'){
					onext.filter(':not(.nohide)').css({
						visibility: 'visible',
						opacity: 0
					}).animate({
						opacity: 1
					},200);
				};
				
				o.start({page: page});
				anim();
				
				if(source !== 'interval'){
					clearInterval(ss.interval);
					ss.interval = setInterval(ss.intervalfn, ss.o.delay + o.duration);
				};
				
				return false;
			};
			
			var nextfn = function(source){
				if(page < blocks){
					page++;
					if(o.loop === false && page > blocks){
						page = blocks;
					};
				}else if(o.loop === true){
					page = 0;	
				};
				
				if(o.loop === false && page === blocks){
					onext.filter(':not(.nohide)').animate({
						opacity: 0
					},200, function(){
						$(this).css({
							visibility: 'hidden'
						})
					});
				};
				
				if(oprev.filter(':not(.nohide)').css('visibility') === 'hidden'){
					oprev.filter(':not(.nohide)').css({
						visibility: 'visible',
						opacity: 0
					}).animate({
						opacity: 1
					},200);
				};
				
				o.start({page: page});
				anim();
				
				if(source !== 'interval'){
					clearInterval(ss.interval);
					ss.interval = setInterval(ss.intervalfn, ss.o.delay + o.duration);
				};
				
				return false;
			};
			
			oprev.bind('click','prevClick',prevfn);
			
			onext.bind('click','nextClick',nextfn);
			
			if(o.slideshow){
				var ss = {
					o: o.slideshow
				};
				
				ss.c = 0;
				ss.lc = 0;
				ss.intervalfn = function(){
					ss.c += ss.o.delay + o.duration;
					ss.lc ++;
					
					if(ss.o.direction.toLowerCase() === 'prev'){
						prevfn('interval');
					}else{
						nextfn('interval');
					};
					
					if( (ss.o.duration != 0 && ss.c >= ss.o.duration) || (typeof ss.o.limit !== 'undefined' && ss.lc >= ss.o.limit) ){
						clearInterval(ss.interval);
					};
				};
				ss.interval = setInterval(ss.intervalfn, ss.o.delay + o.duration);
			};
			
			api.prev = prevfn;
			api.next = nextfn;
			api.get = function(){
				return {
					page: page,
					max: blocks,
					options: o
				};
			};
			api.go = function(n){
				n = parseInt(n);
				if(n > page){
					page = n - 1;
					nextfn('go');
				}else if(n < page){
					page = n + 1;
					prevfn('go');
				};
			};
			
			that.data('jslide_api', api);
		});
	};
})(jQuery);