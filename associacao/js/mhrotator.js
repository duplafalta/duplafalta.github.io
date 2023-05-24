(function($) {
	
	jQuery.extend( jQuery.easing, {
		easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	}
	});

	$.fn.mhrotator = function(options){
	
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
			textCSS:			'.mhtitle {font-size:12px;font-weight:bold;font-family:Arial;color:#333333;line-height:200%;} .mhalt {font-size:12px;font-family:Arial;color:#333333;}',
			captionTop:			150,
			captionLeft:		12,
			captionWidth:		300,
			captionHeight: 		40,
			captionBarColor:	'#ffffff',
			captionBarOpacity:	0.7,
			captionBarPadding:	8,
			captionAlign:		'center',
			showNavButtons:		true,
			autoHideNavButtons:	false,
			navButtonsVAlign:	'top',
			navButtonsVMargin:	16,
			navButtonsAlign:	'right',
			navButtonsMargin:	12,
			navButtonsGap:		6,
			slideButtons:		5,
			showPause:			true,
			showPrev:			true,
			showNext:			true,
			randomPlay:			false,
			autoPlay: 			true,
			loopForever:		true,
			loop:				0,
			showBorder:			true,
			borderColor:		'#ffffff',
			borderSize:			6,
			watermark:			false
		}; 
		
		options = $.extend(defaults, options);		
		var preBlindEffects = new Array('fade','blindLeft','blindRight','blindDownLeft','blindDownRight','blindUpLeft','blindUpRight','blindUpDownLeft','blindUpDownRight');

		// each slideshow
		this.each(function() {
		
			var statusVars = {
				currentSlide: 0,
				totalSlides:  0,
				currentButton:0,
				paused:       false,
				switching:    false,
				loopCount:    -1
			};

			var timeoutID = null;

			// slideshow
			var slideshow = $(this);
			slideshow.addClass('mhRotator');
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
					obj.caption += '<div class="mhtitle">' + obj.title + '</div>';
				if ((obj.title != '') && (obj.alt != ''))
					obj.caption += '<br />';
				if (obj.alt != '')
					obj.caption += '<div class="mhalt">' + obj.alt + '</div>';
				var parentObj = slide.parent();
				if (parentObj.is('a')) 
				{
					obj.link = parentObj.attr('href');
					obj.target = parentObj.attr('target');
				}
				slideObjs.push(obj);
			});
			
			// paused
			statusVars.paused = !options.autoPlay;
			
			// initial status			
			statusVars.totalSlides = slideList.length;
			if (options.randomPlay)
				statusVars.currentSlide = Math.floor( Math.random() * statusVars.totalSlides );
			else
				statusVars.currentSlide = ((options.firstSlide > 0) && (options.firstSlide < statusVars.totalSlides)) ? options.firstSlide : 0;
			
			options.slideButtons = Math.min(slideList.length, options.slideButtons);
			statusVars.currentButton = calcFirstButton(); 
			
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
			
			// background
			slideshow.append($('<div class="slideContainer"></div>'));
			var slideConObj = $('.slideContainer', slideshow);
			slideConObj.css({'height':options.height, 'width':options.width});

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
				slideshow.append($('<div class="mhCaptionBar"><div class="mhCaption"></div></div>'));
				var captionBarObj = $('.mhCaptionBar', slideshow);
				captionBarObj.css({ 'top':options.captionTop + 'px', 'left':options.captionLeft + 'px', 'width':options.captionWidth+'px', 'height':options.captionHeight+'px' });
								
				var captionObj = $('.mhCaption', slideshow);
				captionBarObj.css({'display':'none', 'background':options.captionBarColor, 'opacity':options.captionBarOpacity, 'filter':'alpha(opacity=' + Math.floor(options.captionBarOpacity * 100) + ')', 'text-align':options.captionAlign});
				captionObj.css({'padding':options.captionBarPadding + 'px'});
				if (options.captionAlign == 'center')
					captionObj.css('margin','0 auto');	
				if (slideObjs[statusVars.currentSlide].caption != '')
				{
					slideshow.prepend($('<style type="text/css">' + options.textCSS + '</style>'));
					captionBarObj.fadeIn(options.effectSpeed);
					captionObj.html(slideObjs[statusVars.currentSlide].caption);
				}
			}
			
			// navigation buttons
			if (options.showNavButtons)
			{
				var navButtonsObj = $('<div class="navButtons"></div>');				
				navButtonsObj.appendTo(slideshow);
				
				// auto hide
				if (options.autoHideNavButtons)
				{
					navButtonsObj.hide();
					slideshow.hover(function(){navButtonsObj.show();}, function(){navButtonsObj.hide();});
				}
				
				// top bottom position
				if (options.navButtonsVAlign == 'top')
					navButtonsObj.css({'top':options.navButtonsVMargin+'px'});
				else
					navButtonsObj.css({'bottom':options.navButtonsVMargin+'px'});
				
				// buttons
				var pos = 0;
				
				for (var i= 0; i< options.slideButtons; i++)
				{
					var btn = $("<div class='navButton' id='slideButton" + i + "' rel='" + String(statusVars.currentButton + i) + "' ><div class='navButtonText'>" + String(statusVars.currentButton + i) + "</div></div>").appendTo(navButtonsObj);	
					btn.css({'left':pos + 'px', 'top':'0px', 'background-position':'-128px 0px'});
					pos = pos + 32 + options.navButtonsGap;
					
					if (statusVars.currentSlide == statusVars.currentButton + i)
						btn.css({'background-position':'-128px -32px'});
					
					btn.hover(function(){
						$(this).css({'background-position':'-128px -32px'});
					}, function(){
						if ($(this).attr('rel') != statusVars.currentSlide)
							$(this).css({'background-position':'-128px 0px'});
					});	
					
					btn.click( function(){ 
						if (statusVars.switching)
							return false;
						clearTimeout(timeoutID);
						slideRun($(this).attr('rel'));
					});
				}
				
				if (options.showPrev) {
					var prevBtn = $("<div class='navButton'></div>").appendTo(navButtonsObj);	
					prevBtn.css({'left':pos + 'px', 'top':'0px', 'background-position':'-64px 0px'});
					pos = pos + 32 + options.navButtonsGap;
					
					prevBtn.hover(function(){
						$(this).css({'background-position':'-64px -32px'});
					}, function(){
						$(this).css({'background-position':'-64px 0px'});
					});
					
					prevBtn.click( function(){ 
						if (statusVars.switching)
							return false; 
						clearTimeout(timeoutID);
						slideRun(-2); 
					});
				}
				
				if (options.showPause) {
					
					var playBtn = $("<div id='playButton' class='navButton'></div>").appendTo(navButtonsObj);	
					playBtn.css({'left':pos + 'px', 'top':'0px', 'background-position':'0px 0px'});
					
					playBtn.hover(function(){
						$(this).css({'background-position':'0px -32px'});
					}, function(){
						$(this).css({'background-position':'0px 0px'});
					});
					
					playBtn.click( function(){ 
						statusVars.paused = false;
						statusVars.loopCount = -1;
						playBtn.css('display', 'none');
						pauseBtn.css('display', 'block');
						slideRun(-1);
					});
					
					var pauseBtn = $("<div id='pauseButton' class='navButton'></div>").appendTo(navButtonsObj);	
					pauseBtn.css({'left':pos + 'px', 'top':'0px', 'background-position':'-32px 0px'});
					
					pos = pos + 32 + options.navButtonsGap;
					
					pauseBtn.hover(function(){
						$(this).css({'background-position':'-32px -32px'});
					}, function(){
						$(this).css({'background-position':'-32px 0px'});
					});
					
					pauseBtn.click( function(){
						clearTimeout(timeoutID);
						statusVars.paused = true;
						playBtn.css('display', 'block');
						pauseBtn.css('display', 'none');
					});
					
					if (!statusVars.paused) {
						playBtn.css('display', 'none');
						pauseBtn.css('display', 'block');						
					}
					else {
						playBtn.css('display', 'block');
						pauseBtn.css('display', 'none');		
					}
				}
				
				if (options.showNext) {
					var nextBtn = $("<div class='navButton'></div>").appendTo(navButtonsObj);	
					nextBtn.css({'left':pos + 'px', 'top':'0px', 'background-position':'-96px 0px'});
					pos = pos + 32 + options.navButtonsGap;
					
					nextBtn.hover(function(){
						$(this).css({'background-position':'-96px -32px'});
					}, function(){
						$(this).css({'background-position':'-96px 0px'});
					});
					
					nextBtn.click( function(){ 
						if (statusVars.switching)
							return false;
						clearTimeout(timeoutID);
						slideRun(-3); 
					});
				}
				
				var navWidth = (pos - options.navButtonsGap) > 0 ? (pos - options.navButtonsGap) : 0;
				navButtonsObj.css({ 'width': navWidth + 'px' });
				
				if (options.navButtonsAlign == 'left')
					navButtonsObj.css({'left':options.navButtonsMargin+'px'});
				else if (options.navButtonsAlign == 'right')
					navButtonsObj.css({'right':options.navButtonsMargin+'px'});
				else
					navButtonsObj.css({'left':Math.round(options.width /2 - navWidth /2)+'px'});
				
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
			if ((statusVars.totalSlides > 0) && (!statusVars.paused))
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
							$('#pauseButton', slideshow).trigger("click");
					}
				}
				
				if ((statusVars.totalSlides > 0) && (!statusVars.paused))
					timeoutID = setTimeout(function(){slideRun(-1);}, options.interval);

			});

			function calcFirstButton()
			{
				var firstBtn = Math.floor(statusVars.currentSlide / options.slideButtons) * options.slideButtons;
				
				if ( (firstBtn + options.slideButtons) > statusVars.totalSlides )
					firstBtn = statusVars.totalSlides - options.slideButtons;
				
				return firstBtn;
			}
			
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
						$('.mhCaption', slideshow).html(slideObjs[statusVars.currentSlide].caption);
						$('.mhCaptionBar', slideshow).fadeIn(options.effectSpeed);
					}
					else
					{
						$('.mhCaptionBar', slideshow).fadeOut(options.effectSpeed);
					}
				}

				// nav buttons
				if (options.showNavButtons)
				{
					var prevButton = prevSlide - statusVars.currentButton;
					
					var nextFirstButton = calcFirstButton(); 
					if ( statusVars.currentButton != nextFirstButton)
					{
						statusVars.currentButton = nextFirstButton;				
						for (var i= 0; i< options.slideButtons; i++)
						{
							var btn = $('#slideButton' + i, slideshow);
							btn.attr('rel', statusVars.currentButton + i);
							btn.html("<div class='navButtonText'>" + String(statusVars.currentButton + i) + "</div>");
						}
					}
					
					// switch buttons					
					var currButton = statusVars.currentSlide - statusVars.currentButton;
					$('#slideButton' + prevButton, slideshow).css({'background-position':'-128px 0px'});				
					$('#slideButton' + currButton, slideshow).css({'background-position':'-128px -32px'});						
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