(function($) {
	
	jQuery.extend( jQuery.easing, {
		easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	}
	});

	$.fn.mhslideshow = function(options){
	
		// options
		var defaults = {
			width:				600,
			height:				200,
			firstSlide:			0,
			interval:			3000,
			effectMode:			'blind',			// blind, slide
			effect:				'random',
			totalSlices:		12,
			effectSpeed:		500,
			sliceInterval:		50,
			showShadow:			true,
			showCaption:		true,
			textCSS:			'.title {font-size:12px;font-weight:bold;font-family:Arial;color:#ffffff;line-height:200%;} .alt {font-size:12px;font-family:Arial;color:#ffffff;}',
			captionPosition:	'top',
			captionBarColor:	'#333333',
			captionBarOpacity:	0.8,
			captionBarPadding:	8,
			captionAlign:		'center',
			captionColor:		'#ffffff',
			showNavArrows:		'true',
			autoHideNavArrows:	'true',
			showNavDots:		'true',
			navDotsBottom:		6,
			navDotsAlign:		'right',
			navDotsLeftRightMargin:16,
			randomPlay:			false,
			autoPlay: 			true,
			loopForever:		true,
			loop:				0,
			showBorder:			false,
			borderColor:		'#ffffff',
			borderSize:			6,
			watermark:			false
		}; 
		var options = $.extend(defaults, options);		
		var preBlindEffects = new Array('fade','blindLeft','blindRight','blindDownLeft','blindDownRight','blindUpLeft','blindUpRight','blindUpDownLeft','blindUpDownRight');

		// each slideshow
		this.each(function() {
		
			var statusVars = {
				currentSlide: 0,
				totalSlides:  0,
				paused:       false,
				switching:    false,
				loopCount:    -1
			};

			var timeoutID;

			// slideshow
			var slideshow = $(this);
			slideshow.addClass('mhSlideshow');
			slideshow.css({ 'height': options.height, 'width': options.width });
			
			$('.sliderengine', slideshow).css({'display': 'none'});
			
			// slide object	
			var slideList = $('img', slideshow);
			var slideObjs = [];
			slideList.each(function() {
				var slide = $(this);
				var obj = {};
				obj.src = slide.attr('src');
				obj.title = slide.attr('title');
				obj.alt = slide.attr('alt');
				obj.caption = '';
				if (obj.title != '')
					obj.caption += '<span class="title">' + obj.title + '</span>';
				if ((obj.title != '') && (obj.alt != ''))
					obj.caption += '<br />';
				if (obj.alt != '')
					obj.caption += '<span class="alt">' + obj.alt + '</span>';
				var parentObj = slide.parent();
				if (parentObj.is('a')) 
				{
					obj.link = parentObj.attr('href');
					obj.target = parentObj.attr('target');
				}
				slideObjs.push(obj);
			});
			
			// initial status			
			statusVars.totalSlides = slideList.length;
			if (options.randomPlay)
				statusVars.currentSlide = Math.floor( Math.random() * statusVars.totalSlides );
			else
				statusVars.currentSlide = ((options.firstSlide > 0) && (options.firstSlide < statusVars.totalSlides)) ? options.firstSlide : 0;

			// background
			slideshow.append($('<div class="slideContainer"></div>'));
			var slideConObj = $('.slideContainer', slideshow);
			slideConObj.css({'height':options.height, 'width':options.width});

			// shadow
			if ( (options.showShadow) || (options.showBorder) )
			{
				slideshow.append($('<div class="shadow"></div>'));
				if (options.showShadow)
					$('.shadow', slideshow).append($('<div class="shadowTL"></div><div class="shadowT"></div><div class="shadowTR"></div><div class="shadowR"></div><div class="shadowBR"></div><div class="shadowB"></div><div class="shadowBL"></div><div class="shadowL"></div>'));
				
				if (options.showBorder)
					$('.shadow', slideshow).css({ 'position':'absolute', 'display':'block', 'background':options.borderColor, 'height':options.height+2*options.borderSize,'width':options.width+2*options.borderSize, 'left':-options.borderSize, 'top':-options.borderSize});
				else
					$('.shadow', slideshow).css({ 'height':options.height,'width':options.width });
			}

            // set initial background
			slideConObj.css('background', 'url("'+ slideObjs[statusVars.currentSlide].src +'") no-repeat');
			
			// set link
			if ((slideObjs[statusVars.currentSlide].link != undefined) && (slideObjs[statusVars.currentSlide].link.length > 0))
			{
				slideConObj.css('cursor', 'pointer');
				slideConObj.unbind('click').click( function(event) {
					if ((slideObjs[statusVars.currentSlide].target != undefined) && (slideObjs[statusVars.currentSlide].target.length > 0))
						window.open(slideObjs[statusVars.currentSlide].link, slideObjs[statusVars.currentSlide].target);
					else
						window.location = slideObjs[statusVars.currentSlide].link;	
					event.preventDefault();
				});
			}

			// captions
			if (options.showCaption)
			{
				var captionBarObj = slideshow.append($('<div class="captionBar"><div class="caption"></div></div>'));
				var captionBarObj = $('.captionBar', slideshow);
				if (options.captionPosition == 'bottom')
					captionBarObj.css({ 'bottom':'0px' });
				else
					captionBarObj.css({ 'top':'0px' });
				var captionObj = $('.caption', slideshow);
				captionBarObj.css({'display':'none', 'background':options.captionBarColor, 'opacity':options.captionBarOpacity, 'text-align':options.captionAlign});
				captionObj.css({'padding':options.captionBarPadding + 'px', 'color':options.captionColor});
				if (options.captionAlign == 'center')
					captionObj.css('margin','0 auto');	
				if (slideObjs[statusVars.currentSlide].caption != '')
				{
					slideshow.prepend($('<style type="text/css">' + options.textCSS + '</style>'));
					captionBarObj.fadeIn(options.effectSpeed);
					captionObj.html(slideObjs[statusVars.currentSlide].caption);
				}
			}

			// navigation arrow
			if (options.showNavArrows)
			{
				slideshow.append($('<div class="navArrows"><a class="leftArrow"></a><a class="rightArrow"></a></div>'));
				if (options.autoHideNavArrows)
				{
					var navArrowsObj = $('.navArrows', slideshow);
					navArrowsObj.hide();
					slideshow.hover(function(){navArrowsObj.show();}, function(){navArrowsObj.hide();});
				}
				
				$('a.leftArrow', slideshow).click( function(){ 
					if (statusVars.switching)
						return false; 
					clearTimeout(timeoutID);
					slideRun(-2); 
				});
				
				$('a.rightArrow', slideshow).click( function(){ 
					if (statusVars.switching)
						return false;
					clearTimeout(timeoutID);
					slideRun(-3); 
				});
			}
			
			// navigation dots
			if (options.showNavDots)
			{
				slideshow.append($('<div class="navBar"><div class="navControls"></div></div>'));
				
				var navBarObj = $('.navBar', slideshow);
				navBarObj.css({'text-align':options.navDotsAlign, 'bottom':options.navDotsBottom+'px'});
				if (options.navDotsAlign == 'left')
					navBarObj.css('left', options.navDotsLeftRightMargin+'px');
				else if (options.navDotsAlign == 'right')
					navBarObj.css('left', '-' + options.navDotsLeftRightMargin+'px');

				for (var i = 0; i< statusVars.totalSlides; i++)
					$('.navControls', slideshow).append('<div class="navDot" rel="'+ i +'"></div>');

				var navDotObj = $('.navDot', slideshow);

				navDotObj.click(function(){
					if (statusVars.switching)
						return false;
					clearTimeout(timeoutID);
					slideRun($(this).attr('rel'));
				});
				
				// hover
				navDotObj.hover(function(){
					$(this).addClass('navDotActive');
					}, function(){
					if (statusVars.currentSlide != $(this).attr('rel'))
						$(this).removeClass('navDotActive');
				});
				// active
				$(navDotObj[statusVars.currentSlide]).addClass('navDotActive');
			}

			// slices for animation effect
			if (options.effectMode == 'slide')
			{
				slideConObj.append($('<div class="slide"></div>').css({'left':'0px', 'width':options.width+'px'}));
			}
			else if (options.effectMode == 'blind')
			{
				for (var i = 0; i< options.totalSlices; i++)
				{
					var sliceW = Math.round( options.width /options.totalSlices );
					if (i == options.totalSlices -1 )
						slideConObj.append($('<div class="slice"></div>').css({ 'left':(sliceW*i)+'px', 'width':(options.width -(sliceW*i))+'px' }));
					else
						slideConObj.append($('<div class="slice"></div>').css({ 'left':(sliceW*i)+'px', 'width':sliceW+'px' }));
				}
			}

			// watermark
			if (options.watermark)
			{
				slideshow.append($('<div class="watermark"><a href="http://www.magichtml.com/javascriptslideshow/watermark.html">http://www.magichtml.com</a></div>'));
				$('div.watermark', slideshow).css({'font-size':'12px','font-family':'Arial','background-color':'#FFFFFF','z-index':99999,'position':'absolute','left':'4px','top':'4px','padding':'2px 4px 4px'});
			}

			$('img', slideshow).css({ 'margin':'0px', 'padding':'0px' });

			// start slideshow
			if ((statusVars.totalSlides > 0) && (options.autoPlay))
				timeoutID = setTimeout(function(){slideRun(-1);}, options.interval);
			
			slideshow.bind('switchFinished', function(){ 

				statusVars.switching = false;

				// set background
				slideConObj.css('background', 'url("'+ slideObjs[statusVars.currentSlide].src +'") no-repeat');
				
				if (!options.randomPlay && !options.loopForever)
				{
					if (statusVars.currentSlide == statusVars.totalSlides -1)
					{
						statusVars.loopCount++;
						if (options.loop <= statusVars.loopCount)
							options.autoPlay = false;
					}
				}
				
				if ((statusVars.totalSlides > 0) && (options.autoPlay))
					timeoutID = setTimeout(function(){slideRun(-1);}, options.interval);

			});


			// main function
			function slideRun(nextSlide)
			{

				// calc next slide	
				var prevSlide = statusVars.currentSlide;
				switch(nextSlide)
				{
					case -1:
						if (options.randomPlay)
							statusVars.currentSlide = Math.floor( Math.random() * statusVars.totalSlides );
						else
							statusVars.currentSlide = (statusVars.currentSlide >= (statusVars.totalSlides -1)) ? 0 : ++statusVars.currentSlide;
						break;
					case -2:
						statusVars.currentSlide = (statusVars.currentSlide <= 0) ? statusVars.totalSlides -1 : --statusVars.currentSlide;
						break;
					case -3:
						statusVars.currentSlide = (statusVars.currentSlide >= (statusVars.totalSlides -1)) ? 0 : ++statusVars.currentSlide;
						break;
					default:
						statusVars.currentSlide = ((nextSlide >= 0) && (nextSlide < statusVars.totalSlides)) ? nextSlide : 0;
				}

				// link
				if ((slideObjs[statusVars.currentSlide].link != undefined) && (slideObjs[statusVars.currentSlide].link.length > 0))
				{
					slideConObj.css('cursor', 'pointer');
					slideConObj.unbind('click').click( function(event) {
						if ((slideObjs[statusVars.currentSlide].target != undefined) && (slideObjs[statusVars.currentSlide].target.length > 0))
							window.open(slideObjs[statusVars.currentSlide].link, slideObjs[statusVars.currentSlide].target);
						else
							window.location = slideObjs[statusVars.currentSlide].link;	
						event.preventDefault();
					});
				}
				else
				{
					slideConObj.css('cursor', 'default');
					slideConObj.unbind('click');
				}

				// caption
				if (options.showCaption)
				{
					if (slideObjs[statusVars.currentSlide].caption != '')
					{
						$('.caption', slideshow).fadeOut(options.effectSpeed, function(){
							$(this).html(slideObjs[statusVars.currentSlide].caption);
							$(this).fadeIn(options.effectSpeed);
						});
						$('.captionBar', slideshow).fadeIn(options.effectSpeed);
					}
					else
					{
						$('.captionBar', slideshow).fadeOut(options.effectSpeed);
					}
				}

				// nav controls
				if (options.showNavDots)
				{
					$('.navDot:eq('+prevSlide+')', slideshow).removeClass('navDotActive');
					$('.navDot:eq('+statusVars.currentSlide+')', slideshow).addClass('navDotActive');
				}

				// effect
				if (options.effectMode == 'slide')
				{
					var effect;					
					if (statusVars.currentSlide >= prevSlide)
						effect = 'slideRight';
					else
						effect = 'slideLeft';

					// animation
					statusVars.switching = true;
					var slideObj = $('.slide', slideshow);
					if (effect == 'slideRight')
						slideObj.css({'left':options.width+'px', 'background':'url("'+ slideObjs[statusVars.currentSlide].src +'") no-repeat'});
					else
						slideObj.css({'left':'-'+options.width+'px', 'background':'url("'+ slideObjs[statusVars.currentSlide].src +'") no-repeat'});
					slideObj.animate({'left':'0px'}, options.effectSpeed, 'easeOutCirc', function(){ slideshow.trigger('switchFinished'); });
				}
				else if (options.effectMode == 'blind')
				{						
					// set slices
					var i = 0;
					$('.slice', slideshow).each(function(){
						var sliceW = Math.round( options.width / options.totalSlices );
						$(this).css({ 'height':'0px', 'opacity':'0', 'background': 'url("'+ slideObjs[statusVars.currentSlide].src +'") no-repeat -' + (i * sliceW) +'px 0%' });
						i++;
					});

					var optEffect = jQuery.trim(options.effect);
					var effect;
					if (optEffect == 'random')
					{
						effect = preBlindEffects[Math.floor(Math.random() * preBlindEffects.length)];
					}
					else if (optEffect.indexOf(',') != -1)
					{
						var effectList = optEffect.split(',');
						effect = jQuery.trim(effectList[Math.floor(Math.random() * effectList.length)]);
					}
					else
					{
						effect = optEffect;
					}
					if (jQuery.inArray(effect, preBlindEffects) == -1) 
						effect = preBlindEffects[0];

					// animation
					// 'fade','blindLeft','blindRight','blindDownLeft','blindDownRight','blindUpLeft','blindUpRight','blindUpDownLeft','blindUpDownRight'
					statusVars.switching = true;
					if (effect == 'fade')
					{
						var i = 0;
						$('.slice', slideshow).each(function(){
							$(this).css('height', '100%');
							if(i == options.totalSlices - 1 )
								$(this).animate({ opacity:'1.0' }, options.effectSpeed, '', function(){ slideshow.trigger('switchFinished'); });
							else
								$(this).animate({ opacity:'1.0' }, options.effectSpeed);
							i++;
						});
					}
					else if ((effect == 'blindLeft') || (effect == 'blindRight') || (effect == 'blindDownLeft') || (effect == 'blindDownRight') || (effect == 'blindUpLeft') || (effect == 'blindUpRight') || (effect == 'blindUpDownLeft') || (effect == 'blindUpDownRight'))
					{
						var i = 0;
						var timeInt = options.sliceInterval;
						var slices = $('.slice', slideshow);
						if ((effect == 'blindRight') || (effect == 'blindDownRight') || (effect == 'blindUpRight') || (effect == 'blindUpDownRight'))
							slices = jQuery(jQuery.makeArray(slices).reverse());
						slices.each(function(){
							var slice = $(this);
							var slideW = slice.width();
							if ((effect == 'blindLeft') || (effect == 'blindRight'))
							{
								slice.css({'top':'0px', 'width':'0px', 'height':'100%'});
								if(i == options.totalSlices - 1)
									setTimeout(function(){slice.animate({ width:slideW, opacity:'1.0' }, options.effectSpeed, '', function(){ slideshow.trigger('switchFinished'); });}, timeInt);
								else
									setTimeout(function(){slice.animate({ width:slideW, opacity:'1.0' }, options.effectSpeed);}, timeInt);
							}
							else
							{
								if ((effect == 'blindDownLeft') || (effect == 'blindDownRight'))
								{
									slice.css('top','0px');
								}
								else if ((effect == 'blindUpLeft') || (effect == 'blindUpRight'))
								{
									slice.css('bottom','0px');
								}
								else if ((effect == 'blindUpDownLeft') || (effect == 'blindUpDownRight'))
								{
									if (i % 2 == 0)
										slice.css('top','0px');
									else
										slice.css('bottom','0px');
								}
								if(i == options.totalSlices - 1)
									setTimeout(function(){slice.animate({ height:'100%', opacity:'1.0' }, options.effectSpeed, '', function(){ slideshow.trigger('switchFinished'); });}, timeInt);
								else
									setTimeout(function(){slice.animate({ height:'100%', opacity:'1.0' }, options.effectSpeed);}, timeInt);

							}
							timeInt += options.sliceInterval;
							i++;
						});
					}
				}
			}

		});
		
	}
	
})(jQuery);