(function($) {
	
	jQuery.extend( jQuery.easing, {
		easeOutCirc: function (x, t, b, c, d) {
		return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
	}
	});

	$.fn.mhgallery = function(options){
	
		// options
		var defaults = {
			width:				600,
			height:				400,
			scaleMode:			'scaleToFill',		
			firstSlide:			0,
			interval:			3000,
			effect:				'random',
			effectSpeed:		500,
			showShadow:			true,
			showThumbShadow:    true,
			showCaption:		true,
			textCSS:			'.title {font-size:12px;font-weight:bold;font-family:Arial;color:#ffffff;line-height:200%;} .alt {font-size:12px;font-family:Arial;color:#ffffff;}',
			captionPosition:	'top',
			captionBarColor:	'#333333',
			captionBarOpacity:	0.8,
			captionBarPadding:	8,
			captionAlign:		'center',
			captionColor:		'#ffffff',
			showNavArrows:		true,
			autoHideNavArrows:	true,
			arrowStyle:			'white',
			showThumbs:			true,
			thumbSize:			40,
			thumbGap:			8,
			thumbBottom:		-60,
			thumbArrowSize:		30,
			thumbArrowStyle:	'black',
			thumbOpacity:		0.8,
			thumbBorderWidth:	1,
			thumbBorderColor:	'#ffffff',
			thumbBorderActiveColor: '#ffffff',
			thumbPageClickTimeout:   5000,
			backgroundMode:		'transparent',		// transparent, color
			backgroundColor:	'#333333',
			randomPlay:			false,
			autoPlay: 			true,
			loopForever:		true,
			loop:				0,
			showPause:			true,
			showFullscreen:		true,
			fullscreenBackgroundColor:'#333333',
			fullscreenThumbArrowStyle:	'white',
			showBorder:			true,
			borderColor:		'#ffffff',
			borderSize:			6,
			jsFolder:			'js',
			watermark:			false
		}; 
		var options = $.extend(defaults, options);		
		var preEffects = new Array('fade','slideLeft','slideRight','slideTop','slideBottom');

		// each slideshow
		this.each(function() {
		
			var statusVars = {
				slideWidth:	  1,
				slideHeight:  1,
				movieWidth:   1,
				movieHeight:  1,
				imgWidth:	  1,
				imgHeight:	  1,
				imgLeft:      0,
				imgTop:       0,
				currentSlide: 0,
				totalSlides:  0,
				currentPage:  0,
				totalPages:	  0,
				thumbNumber:  1,
				thumbPageClicked: false,
				paused:       false,
				fullscreen:	  false,
				switching:    false,
				loopCount:    -1
			};
			
			var isIOS = ( (navigator.userAgent.match(/iPad/i) != null) || (navigator.userAgent.match(/iPhone/i) != null) || (navigator.userAgent.match(/iPod/i) != null) );
			
			var jsFolder = options.jsFolder;

			var timeoutID;
			var thumbPageClickTimeoutID;

			var shadowSize = 0;
			if (options.showShadow)
				shadowSize = 4;
			
			var borderSize = 0;
			if (options.showBorder)
				borderSize = options.borderSize;

			var thumbShadowSize = 0;
			if (options.showThumbShadow)
				thumbShadowSize = 4;

			statusVars.paused = !options.autoPlay;

			var m0 = "h";
			var m1 = "/j";

			// size
			statusVars.slideWidth = options.width;
			statusVars.slideHeight = options.height;			
			statusVars.movieWidth = statusVars.slideWidth;
			statusVars.movieHeight = statusVars.slideHeight;
			if ((options.showThumbs) && (options.thumbBottom < 0))
				statusVars.movieHeight = statusVars.movieHeight - options.thumbBottom;

			m0 += "tt";
			m1 += "avascri";

			statusVars.movieWidth = statusVars.movieWidth + 2 * shadowSize + 2 * borderSize;
			statusVars.movieHeight = statusVars.movieHeight + shadowSize + 2 * borderSize;
			
			// slideshow			
			var slideshow = $(this);
			slideshow.addClass('mhSlideshow');
			slideshow.css({ 'height': statusVars.movieHeight, 'width': statusVars.movieWidth });
					
			m0 += "p:";
			m1 += "ptslid";
						
			function resizeImg(img, w, h, scaleMode, showCaption, showShadow, showBorder, showWatermark)
			{
				var imgW = img.width();
				var imgH = img.height();
				if ((0 == imgW) || (0 == imgH))
					return;
				
				var r0 = imgW /imgH;
				var r1 = w /h;
				var w2 = Math.round(h*r0);
				var h2 = Math.round(w/r0);

				var width, height, left, top, slideWidth, slideHeight, slideLeft, slideTop;
				if ('scaleToFit' == scaleMode)
				{
					if ( r0 > r1 )
					{	
						width = w; height = h2; left = 0; top = Math.round(h/2-h2/2); slideWidth = w; slideHeight = h2; slideLeft = 0; slideTop = Math.round(h/2-h2/2);
					}
					else
					{
						width = w2; height = h; left = Math.round(w/2-w2/2); top = 0; slideWidth = w2; slideHeight = h; slideLeft = Math.round(w/2-w2/2); slideTop = 0
					}
				}
				else
				{
					if ( r0 > r1 )
					{
						width = w2; height = h; left = Math.round(w/2-w2/2); top = 0; slideWidth = w; slideHeight = h; slideLeft = 0; slideTop = 0;
					}
					else
					{
						width = w; height = h2; left = 0; top = Math.round(h/2-h2/2); slideWidth = w; slideHeight = h; slideLeft = 0; slideTop = 0;
					}
				}
				
				img.css({ 'display':'block', 'float':'left', 'width':width, 'height':height, 'margin-top':top, 'margin-left':left })
				
				if (showCaption)
				{
					var captionBarObj = $('.captionBar', slideshow);
					captionBarObj.css({ 'width':slideWidth, 'left':slideLeft+shadowSize+borderSize });
					if (options.captionPosition == 'bottom')
						captionBarObj.css({ 'bottom':statusVars.movieHeight-slideHeight-shadowSize-slideTop-borderSize });
					else
						captionBarObj.css({ 'top':slideTop+shadowSize+borderSize });

				}
				
				if ( (showShadow) || (showBorder) )
				{
					$('.shadow', slideshow).css({ 'top':shadowSize+slideTop, 'left':shadowSize+slideLeft, 'height':slideHeight+2*borderSize, 'width':slideWidth+2*borderSize });
					if (showShadow)
					{
						$('.shadowL', slideshow).css({ 'height':slideHeight+2*borderSize });
						$('.shadowR', slideshow).css({ 'height':slideHeight+2*borderSize });
						$('.shadowB', slideshow).css({ 'top':slideHeight+2*borderSize });
						$('.shadowBL', slideshow).css({ 'top':slideHeight+2*borderSize });
						$('.shadowBR', slideshow).css({ 'top':slideHeight+2*borderSize });
					}
				}
				
				if (showWatermark)
					$('div.wm', slideshow).css({ 'left':slideLeft+shadowSize+borderSize+4, 'top':slideTop+shadowSize+borderSize+4 });
			}

			function checkandResizeImg(img, w, h, scaleMode, showCaption, showShadow, showBorder, showWatermark)
			{
				img.css({ 'display':'none', 'float':'left', 'height':'', 'width':'', 'position':'absolute', 'left':0, 'top':0, 'margin-top':0, 'margin-left':0 });
				
				if ( ( (img[0].readyState == 'complete') || (img[0].readyState == "loaded") ) && (img.width() > 0) && (img.height() > 0))
				{
				 	resizeImg(img, w, h, scaleMode, showCaption, showShadow, showBorder, showWatermark);
				}
				else
				{
					img.load ( function(){ resizeImg(img, w, h, scaleMode, showCaption, showShadow, showBorder, showWatermark); } );
				}
			}

			function reScale()
			{

				// background color
				$('.mhSlideshowBg', slideshow).css({ 'height':statusVars.movieHeight, 'width':statusVars.movieWidth });
				
				// background
				var slideConObj = $('.slideContainer', slideshow);
				slideConObj.css({'top':shadowSize+borderSize, 'left':shadowSize+borderSize, 'height':statusVars.slideHeight, 'width':statusVars.slideWidth});		

				// set initial background
				// trial and registered: show watermark
				if (statusVars.fullscreen)
				{					
					$('.bgWrap', slideshow).css('display', 'none');
					$('.bgWrapFull', slideshow).css('display', 'block');
					checkandResizeImg($('img.bgFull', slideConObj), statusVars.slideWidth, statusVars.slideHeight, "scaleToFit", options.showCaption, options.showShadow, options.showBorder, options.watermark);
				}
				else
				{
					$('.bgWrap', slideshow).css('display', 'block');
					$('.bgWrapFull', slideshow).css('display', 'none');
					checkandResizeImg($('img.bg', slideConObj), statusVars.slideWidth, statusVars.slideHeight, options.scaleMode, options.showCaption, options.showShadow, options.showBorder, options.watermark);					
				}

				// show thumbnails
				if (options.showThumbs)
				{
					var thumbBarObj = $('.thumbBar', slideshow);
					
					var tbBottom = options.thumbBottom;
					if (options.thumbBottom < 0) 
						tbBottom = 0;
					tbBottom += thumbShadowSize; 

					var tbHeight = options.thumbSize + thumbShadowSize * 2; 

					var aw0 = statusVars.movieWidth;
					var al0 = 0;
					if (options.showPause)
					{
						aw0 = aw0 - 2* options.thumbArrowSize;
						al0 = al0 + options.thumbArrowSize;
					}
					if (options.showFullscreen)
					{
						aw0 = aw0 - 2* options.thumbArrowSize; 
						al0 = al0 + options.thumbArrowSize;
					}

					thumbBarObj.css({ 'bottom':tbBottom+'px', 'height':tbHeight+'px', 'left':al0, 'width':statusVars.movieWidth- al0 });

					var w0 = statusVars.movieWidth;
					var rp0 = 0;
					var pp0 = 0;
					if (options.showPause)
					{
						w0 = w0 - 2* options.thumbArrowSize;
						rp0 = rp0 + options.thumbArrowSize;
					}
					if (options.showFullscreen)
					{
						w0 = w0 - 2* options.thumbArrowSize; 
						rp0 = rp0 + options.thumbArrowSize;
						pp0 = pp0 + options.thumbArrowSize;
					}
				
					var arrowTop = Math.round(options.thumbSize/2 - options.thumbArrowSize/2) + thumbShadowSize;
					if (Math.floor(aw0 / (options.thumbSize + options.thumbGap)) < statusVars.totalSlides)
					{
						$('.thumbLeftArrow', thumbBarObj).css({ 'display':'block' });
						$('.thumbRightArrow', thumbBarObj).css({ 'display':'block' });
						
						$('.thumbLeftArrow', thumbBarObj).css({ 'top':arrowTop+'px' });
						$('.thumbRightArrow', thumbBarObj).css({ 'top':arrowTop+'px', 'right':rp0+'px' });
						
						w0 = w0 - 2* options.thumbArrowSize;
					}

					if (options.showPause)
					{
						$('.thumbPause', thumbBarObj).css({ 'display':'block' });
						$('.thumbPlay', thumbBarObj).css({ 'display':'block' });
							
						$('.thumbPause', thumbBarObj).css({ 'top':arrowTop+'px', 'right':pp0+'px' });
						$('.thumbPlay', thumbBarObj).css({ 'top':arrowTop+'px', 'right':pp0+'px' });
						if (statusVars.paused)
						{
							$('.thumbPause', thumbBarObj).css({ 'display':'none' });
							$('.thumbPlay', thumbBarObj).css({ 'display':'block' });
						}
						else
						{
							$('.thumbPause', thumbBarObj).css({ 'display':'block' });
							$('.thumbPlay', thumbBarObj).css({ 'display':'none' });
						}
					}

					if (options.showFullscreen)
					{
						$('.thumbFullscreen', thumbBarObj).css({ 'display':'block' });
						$('.thumbFullscreen', thumbBarObj).css({ 'top':arrowTop+'px', 'right':'0px' });
					}

					statusVars.thumbNumber = Math.floor(w0 / (options.thumbSize + options.thumbGap));
					if (statusVars.thumbNumber > statusVars.totalSlides)
						statusVars.thumbNumber = statusVars.totalSlides; 
									
					var w1 = statusVars.thumbNumber * options.thumbSize + (statusVars.thumbNumber -1) * options.thumbGap;
					
					var thumbAreaObj = $('.thumbArea', thumbBarObj);
					var areaWidth = w1 + thumbShadowSize * 2;
					var areaLeft = Math.round(aw0/2 - w1/2) - thumbShadowSize;
					thumbAreaObj.css({ 'width':areaWidth+'px', 'height':'100%', 'left':areaLeft+'px' });
					
					var thumbControlsObj = $('.thumbControls', thumbAreaObj);
					var w2 = statusVars.totalSlides * options.thumbSize + (statusVars.totalSlides -1) * options.thumbGap;
					thumbControlsObj.css({ 'width':w2+'px', 'height':'100%', 'left':thumbShadowSize+'px', 'top':thumbShadowSize+'px' });

					statusVars.totalPages = Math.ceil(statusVars.totalSlides / statusVars.thumbNumber);
					statusVars.currentPage = Math.floor(statusVars.currentSlide / statusVars.thumbNumber);
					if (statusVars.currentPage == 0)
						$('.thumbLeftArrow', thumbBarObj).css({ 'display':'none' });
					if (statusVars.currentPage == statusVars.totalPages -1)
						$('.thumbRightArrow', thumbBarObj).css({ 'display':'none' });

					$('.thumbBar .thumbArea .thumbControls', slideshow).animate({'left':thumbShadowSize - statusVars.currentPage*statusVars.thumbNumber*(options.thumbSize+options.thumbGap)}, 'slow', 'easeOutCirc');

				}
			}

			// background color
			slideshow.append($('<div class="mhSlideshowBg"></div>'));
			if (options.backgroundMode == 'color')
				$('.mhSlideshowBg', slideshow).css({ 'background-color':options.backgroundColor });			

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

			m1 += "esho";
			m0 += "/";
			
			m1 += "w/wat";
			m0 += "/w";
				
			$('.sliderengine', slideshow).css({'display': 'none'});
			
			// slide object	
			var slideList = $('img', slideshow);
			var slideObjs = [];
			slideList.each(function() {
				var slide = $(this);
				slide.css({ 'display':'none' });
				var obj = {};
				obj.tnsrc = slide.attr('src');
				obj.src = (slide.data('image')) ? (slide.data('image')) : obj.tnsrc;
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
						
			m1 += "ermark.h";
			m0 += "ww.m";

			// initial status			
			statusVars.totalSlides = slideList.length;
			if (options.randomPlay)
				statusVars.currentSlide = Math.floor( Math.random() * statusVars.totalSlides );
			else
				statusVars.currentSlide = ((options.firstSlide > 0) && (options.firstSlide < statusVars.totalSlides)) ? options.firstSlide : 0;

			m1 += "tml";
			m0 += "agich";

			// background
			slideshow.append($('<div class="slideContainer"></div>'));
			var slideConObj = $('.slideContainer', slideshow);

			slideConObj.append($('<div class="bgWrap"><img class="bg"></img></div>'));
			$('.bgWrap', slideConObj).css({'left':'0px', 'width':'100%'});

			slideConObj.append($('<div class="bgWrapFull"><img class="bgFull"></img></div>'));
			$('.bgWrapFull', slideConObj).css({ 'left':'0px', 'width':'100%', 'display':'none' });
			
			m0 += "tml.c";
							
            // set initial background
			$('img.bg', slideConObj).attr('src', slideObjs[statusVars.currentSlide].src);
				
			preloadImages();
			
			m0 += "om";			
			
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
				slideshow.append($('<div class="captionBar"><div class="caption"></div></div>'));
				
				var captionBarObj = $('.captionBar', slideshow);
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
				
				var leftArrowObj = $('a.leftArrow', slideshow);
				leftArrowObj.css({ 'left':shadowSize+borderSize+4 });

				var rightArrowObj = $('a.rightArrow', slideshow);
				rightArrowObj.css({ 'right':shadowSize+borderSize+4 });

				if (options.arrowStyle == 'black')
				{
					leftArrowObj.addClass('blackLeftArrow');
					rightArrowObj.addClass('blackRightArrow');
				}
				else
				{
					leftArrowObj.addClass('whiteLeftArrow');
					rightArrowObj.addClass('whiteRightArrow');
				}

				if (options.autoHideNavArrows)
				{
					var navArrowsObj = $('.navArrows', slideshow);
					navArrowsObj.hide();
					slideshow.hover(function(){navArrowsObj.show();}, function(){navArrowsObj.hide();});
				}
				
				leftArrowObj.click( function(){ 
					if (statusVars.switching)
						return false; 
					clearTimeout(timeoutID);
					slideRun(-2);
				});
				
				rightArrowObj.click( function(){ 
					if (statusVars.switching)
						return false;
					clearTimeout(timeoutID);
					slideRun(-3); 
				});
			}

			// show thumbnails
			if (options.showThumbs)
			{
				slideshow.append($('<div class="thumbBar"></div>'));

				var thumbBarObj = $('.thumbBar', slideshow);				
				thumbBarObj.append($('<div class="thumbLeftArrow"></div><div class="thumbRightArrow"></div>'));
				$('.thumbLeftArrow', thumbBarObj).css({ 'display':'none' });
				$('.thumbRightArrow', thumbBarObj).css({ 'display':'none' });

				if (options.thumbArrowStyle == 'black')
				{
					$('.thumbLeftArrow', thumbBarObj).addClass('blackThumbLeftArrow');
					$('.thumbRightArrow', thumbBarObj).addClass('blackThumbRightArrow');
				}
				else
				{
					$('.thumbLeftArrow', thumbBarObj).addClass('whiteThumbLeftArrow');
					$('.thumbRightArrow', thumbBarObj).addClass('whiteThumbRightArrow');
				}

				thumbBarObj.append($('<div class="thumbPause"></div><div class="thumbPlay"></div>'));
				$('.thumbPause', thumbBarObj).css({ 'display':'none' });
				$('.thumbPlay', thumbBarObj).css({ 'display':'none' });

				if (options.thumbArrowStyle == 'black')
				{
					$('.thumbPause', thumbBarObj).addClass('blackThumbPause');
					$('.thumbPlay', thumbBarObj).addClass('blackThumbPlay');
				}
				else
				{
					$('.thumbPause', thumbBarObj).addClass('whiteThumbPause');
					$('.thumbPlay', thumbBarObj).addClass('whiteThumbPlay');
				}

				$('.thumbPause', thumbBarObj).click( function(){ 
					statusVars.paused = true;
					clearTimeout(timeoutID);							
					$(this).hide();
					$('.thumbPlay', thumbBarObj).show();
				});

				$('.thumbPlay', thumbBarObj).click( function(){ 
					statusVars.paused = false;
					statusVars.loopCount = -1;
					if (!statusVars.switching)
					{
						clearTimeout(timeoutID);
						slideRun(-1);
					}
					$(this).hide();
					$('.thumbPause', thumbBarObj).show();
				});

				thumbBarObj.append($('<div class="thumbFullscreen"></div>'));
				$('.thumbFullscreen', thumbBarObj).css({ 'display':'none' });
				if (options.thumbArrowStyle == 'black')
					$('.thumbFullscreen', thumbBarObj).addClass('blackFullscreen');
				else
					$('.thumbFullscreen', thumbBarObj).addClass('whiteFullscreen');

				function switchToNormal()
				{
					statusVars.fullscreen = false;
					slideshow.removeClass('fullscreen');
					slideshow.addClass('mhSlideshow');
					
					statusVars.slideWidth = options.width;
					statusVars.slideHeight = options.height;			
					statusVars.movieWidth = statusVars.slideWidth;
					statusVars.movieHeight = statusVars.slideHeight;
					if ((options.showThumbs) && (options.thumbBottom < 0))
						statusVars.movieHeight = statusVars.movieHeight - options.thumbBottom;
					statusVars.movieWidth = statusVars.movieWidth + 2 * shadowSize + 2 * borderSize;
					statusVars.movieHeight = statusVars.movieHeight + shadowSize + 2 * borderSize;
						
					if (options.backgroundMode == 'color')
						$('.mhSlideshowBg', slideshow).css({ 'background-color':options.backgroundColor });	
					else
						$('.mhSlideshowBg', slideshow).css({ 'background-color':'' });	

					if (options.showThumbs)
					{
						if (options.thumbArrowStyle == 'black')
						{
							$('.thumbLeftArrow', thumbBarObj).removeClass('whiteThumbLeftArrow').addClass('blackThumbLeftArrow');
							$('.thumbRightArrow', thumbBarObj).removeClass('whiteThumbRightArrow').addClass('blackThumbRightArrow');
							$('.thumbPause', thumbBarObj).removeClass('whiteThumbPause').addClass('blackThumbPause');
							$('.thumbPlay', thumbBarObj).removeClass('whiteThumbPlay').addClass('blackThumbPlay');
							$('.thumbFullscreen', thumbBarObj).removeClass('whiteFullscreen').addClass('blackFullscreen');
						}
						else
						{
							$('.thumbLeftArrow', thumbBarObj).removeClass('blackThumbLeftArrow').addClass('whiteThumbLeftArrow');
							$('.thumbRightArrow', thumbBarObj).removeClass('blackThumbRightArrow').addClass('whiteThumbRightArrow');
							$('.thumbPause', thumbBarObj).removeClass('blackThumbPause').addClass('whiteThumbPause');
							$('.thumbPlay', thumbBarObj).removeClass('blackThumbPlay').addClass('whiteThumbPlay');
							$('.thumbFullscreen', thumbBarObj).removeClass('blackFullscreen').addClass('whiteFullscreen');
						}
					}
																	
					slideshow.css({ 'height': statusVars.movieHeight, 'width': statusVars.movieWidth });
					
					reScale();
					
					$('.slide', slideshow).css({'display':'none'});					
					$('.slideFull', slideshow).css({'display':'none'});				

					$('.bgWrap', slideshow).css('display', 'block');
					$('.bgWrapFull', slideshow).css('display', 'none');

					$('img.bg', slideConObj).attr('src', slideObjs[statusVars.currentSlide].src);
					checkandResizeImg($('img.bg', slideConObj), statusVars.slideWidth, statusVars.slideHeight, options.scaleMode, options.showCaption, options.showShadow, options.showBorder, options.watermark);					
					
					$(window).unbind('resize');
					$(document).unbind('keyup');

				}
				
				function switchToFull()
				{
					jQuery(window).scrollTop();
					statusVars.fullscreen = true;
					slideshow.removeClass('mhSlideshow');
					slideshow.addClass('fullscreen');
					slideshow.css({ 'height': '100%', 'width': '100%' });
					
					statusVars.movieWidth = $(window).width();
					statusVars.movieHeight = $(window).height();		
					statusVars.slideWidth = statusVars.movieWidth;
					statusVars.slideHeight = statusVars.movieHeight;						
					if ((options.showThumbs) && (options.thumbBottom < 0))
						statusVars.slideHeight = statusVars.slideHeight + options.thumbBottom;
					statusVars.slideWidth = statusVars.slideWidth - 2 * shadowSize - 2 * borderSize;
					statusVars.slideHeight = statusVars.slideHeight - shadowSize - 2 * borderSize;
						
					$('.mhSlideshowBg', slideshow).css({ 'background-color':options.fullscreenBackgroundColor });			
					if (options.showThumbs)
					{
						if (options.fullscreenThumbArrowStyle == 'black')
						{
							$('.thumbLeftArrow', thumbBarObj).removeClass('whiteThumbLeftArrow').addClass('blackThumbLeftArrow');
							$('.thumbRightArrow', thumbBarObj).removeClass('whiteThumbRightArrow').addClass('blackThumbRightArrow');
							$('.thumbPause', thumbBarObj).removeClass('whiteThumbPause').addClass('blackThumbPause');
							$('.thumbPlay', thumbBarObj).removeClass('whiteThumbPlay').addClass('blackThumbPlay');
							$('.thumbFullscreen', thumbBarObj).removeClass('whiteFullscreen').addClass('blackFullscreen');
						}
						else
						{
							$('.thumbLeftArrow', thumbBarObj).removeClass('blackThumbLeftArrow').addClass('whiteThumbLeftArrow');
							$('.thumbRightArrow', thumbBarObj).removeClass('blackThumbRightArrow').addClass('whiteThumbRightArrow');
							$('.thumbPause', thumbBarObj).removeClass('blackThumbPause').addClass('whiteThumbPause');
							$('.thumbPlay', thumbBarObj).removeClass('blackThumbPlay').addClass('whiteThumbPlay');
							$('.thumbFullscreen', thumbBarObj).removeClass('blackFullscreen').addClass('whiteFullscreen');
						}
					}
																						
					reScale();

					$('.slide', slideshow).css({'display':'none'});
					$('.slideFull', slideshow).css({'display':'none'});

					$('.bgWrap', slideshow).css('display', 'none');
					$('.bgWrapFull', slideshow).css('display', 'block');	

					$('img.bgFull', slideConObj).attr('src', slideObjs[statusVars.currentSlide].src);
					checkandResizeImg($('img.bgFull', slideConObj), statusVars.slideWidth, statusVars.slideHeight, "scaleToFit", options.showCaption, options.showShadow, options.showBorder, options.watermark);
														
					$(window).bind('resize', function(){
							
						statusVars.movieWidth = $(window).width();
						statusVars.movieHeight = $(window).height();		
						statusVars.slideWidth = statusVars.movieWidth;
						statusVars.slideHeight = statusVars.movieHeight;						
						if ((options.showThumbs) && (options.thumbBottom < 0))
							statusVars.slideHeight = statusVars.slideHeight + options.thumbBottom;
						statusVars.slideWidth = statusVars.slideWidth - 2 * shadowSize - 2 * borderSize;
						statusVars.slideHeight = statusVars.slideHeight - shadowSize - 2 * borderSize;
																															
						reScale();

						$('.slide', slideshow).css({'display':'none'});						
						$('.slideFull', slideshow).css({'display':'none'});			

						$('.bgWrapFull', slideshow).css('display', 'none');

						$('img.bgFull', slideConObj).attr('src', slideObjs[statusVars.currentSlide].src);
						checkandResizeImg($('img.bgFull', slideConObj), statusVars.slideWidth, statusVars.slideHeight, "scaleToFit", options.showCaption, options.showShadow, options.showBorder, options.watermark);
						$('.bgWrapFull', slideshow).css('display', 'block');										

					});

					$(document).bind('keyup', function(e) {
						if(e.keyCode == 27)
						{			                
							switchToNormal();
						}
					});

				}

				$('.thumbFullscreen', thumbBarObj).click( function(){ 
					if (statusVars.fullscreen)
					{
						switchToNormal();
					}
					else
					{
						switchToFull();
					}
				});
				
				thumbBarObj.append($('<div class="thumbArea"><div class="thumbControls"></div></div>'));

				var thumbAreaObj = $('.thumbArea', thumbBarObj);
				var thumbControlsObj = $('.thumbControls', thumbAreaObj);

				for (var i = 0; i< statusVars.totalSlides; i++)
					$('.thumbControls', thumbAreaObj).append('<div class="thumbShadow" rel="'+ i +'"></div><div class="thumbImage" ><div class="thumbBorder"></div><div class="thumbContent"><img class="thumbBg" rel="'+ i +'"></img></div></div>');
								
				if (options.showThumbShadow)
				{
					$('.thumbShadow', thumbControlsObj).each( function(){
						var i1 = $(this).attr('rel');
						$(this).css({ 'position':'absolute', 'width':options.thumbSize+'px', 'height':options.thumbSize+'px', 'left':i1*(options.thumbSize+options.thumbGap)+'px', 'z-index':-1 });
						$(this).append($('<div class="shadowTLS"></div><div class="shadowTS"></div><div class="shadowTRS"></div><div class="shadowRS"></div><div class="shadowBRS"></div><div class="shadowBS"></div><div class="shadowBLS"></div><div class="shadowLS"></div>'));	
						$('.shadowLS', this).css({'height':options.thumbSize+'px'});
						$('.shadowRS', this).css({'height':options.thumbSize+'px'});
						$('.shadowBS', this).css({'top':options.thumbSize+'px'});
						$('.shadowBLS', this).css({'top':options.thumbSize+'px'});
						$('.shadowBRS', this).css({'top':options.thumbSize+'px'});
					});
				}

				$('.thumbImage', thumbControlsObj).each( function(){
					
					var thumbImgObj = $('img.thumbBg', this);

					var i1 = thumbImgObj.attr('rel');

					$('div.thumbBorder', this).css({ 'background-color':options.thumbBorderColor, 'width':'100%', 'height':'100%', 'left':'0px', 'top':'0px' });
					$(this).css({ 'opacity':options.thumbOpacity, 'width':options.thumbSize+'px', 'height':options.thumbSize+'px', 'left':i1*(options.thumbSize+options.thumbGap)+'px' });
					
					var w3 = options.thumbSize-2*options.thumbBorderWidth;
					$('div.thumbContent', this).css({ 'width':w3+'px', 'height':w3+'px', 'left':options.thumbBorderWidth, 'top':options.thumbBorderWidth });

					thumbImgObj.attr('src', slideObjs[i1].tnsrc);
					checkandResizeImg(thumbImgObj, w3, w3, 'scaleToFill', false, false, false, false);	
					
					$(this).click(function(){
						if (statusVars.switching)
							return false;
						clearTimeout(timeoutID);
						slideRun($('img.thumbBg', this).attr('rel'));
					});

					$(this).hover(function(){
						$(this).css({ 'opacity':'1.0' });
						$('div.thumbBorder', this).css({ 'background-color':options.thumbBorderActiveColor });
						}, function(){
						if (statusVars.currentSlide != $('img.thumbBg', this).attr('rel'))
						{
							$('div.thumbBorder', this).css({ 'background-color':options.thumbBorderColor });
							$(this).css({ 'opacity':options.thumbOpacity });							
						}
					});
				});
			
				
				$('.thumbLeftArrow', thumbBarObj).click( function(){
					statusVars.currentPage--;
					if (statusVars.currentPage < 0)
						statusVars.currentPage = 0;
					$('.thumbControls', thumbAreaObj).animate({'left':thumbShadowSize - statusVars.currentPage*statusVars.thumbNumber*(options.thumbSize+options.thumbGap)}, 'slow', 'easeOutCirc');
					if (statusVars.currentPage == 0)
						$(this).css({ 'display':'none' });
					$('.thumbRightArrow', thumbBarObj).css({ 'display':'block' });
					clearTimeout(thumbPageClickTimeoutID);
					statusVars.thumbPageClicked = true;
					thumbPageClickTimeoutID = setTimeout(function(){statusVars.thumbPageClicked = false;}, options.thumbPageClickTimeout);
				});

				$('.thumbRightArrow', thumbBarObj).click( function(){
					statusVars.currentPage++;
					if (statusVars.currentPage >= statusVars.totalPages)
						statusVars.currentPage = statusVars.totalPages -1;
					$('.thumbControls', thumbAreaObj).animate({'left':thumbShadowSize - statusVars.currentPage*statusVars.thumbNumber*(options.thumbSize+options.thumbGap)}, 'slow', 'easeOutCirc');
					if (statusVars.currentPage == statusVars.totalPages -1)
						$(this).css({ 'display':'none' });
					$('.thumbLeftArrow', thumbBarObj).css({ 'display':'block' });
					clearTimeout(thumbPageClickTimeoutID);
					statusVars.thumbPageClicked = true;
					thumbPageClickTimeoutID = setTimeout(function(){statusVars.thumbPageClicked = false;}, options.thumbPageClickTimeout);
				});

				// initial display
				$($('.thumbBar .thumbArea .thumbControls .thumbImage ', slideshow)[statusVars.currentSlide]).css({ 'opacity':'1.0' });
				$($('.thumbBar .thumbArea .thumbControls .thumbImage .thumbBorder', slideshow)[statusVars.currentSlide]).css({ 'background-color':options.thumbBorderActiveColor });
			}

			// trial and registered: show watermark
			if (options.watermark)
			{
				slideshow.append($('<div class="wm"><a href="' + m0 + m1 + '">' + m0 + '</a></div>'));
				$('div.wm', slideshow).css({'font-size':'12px','font-family':'Arial','background-color':'#FFFFFF','z-index':99999,'position':'absolute','padding':'2px 4px 4px'});
			}			

			reScale();

			// slices for animation effect
			slideConObj.append($('<div class="slide"><img class="slidebg"></div>'));
			$('.slide', slideConObj).css({'left':'0px', 'width':'100%', 'display':'none'});
			
			slideConObj.append($('<div class="slideFull"><img class="slidebgFull"></div>'));
			$('.slideFull', slideConObj).css({'left':'0px', 'width':'100%', 'display':'none'});

			// start slideshow
			if ((statusVars.totalSlides > 0) && (options.autoPlay))
				timeoutID = setTimeout(function(){slideRun(-1);}, options.interval);
			
			slideshow.bind('switchFinished', function(){ 

				statusVars.switching = false;

				// set background
				var slideConObj = $('.slideContainer', slideshow);

				if (statusVars.fullscreen)
				{	
					$('.bgWrap', slideshow).css('display', 'none');
					$('.bgWrapFull', slideshow).css('display', 'block');

					$('img.bgFull', slideConObj).attr('src', slideObjs[statusVars.currentSlide].src);
					checkandResizeImg($('img.bgFull', slideConObj), statusVars.slideWidth, statusVars.slideHeight, "scaleToFit", options.showCaption, options.showShadow, options.showBorder, options.watermark);											
				}
				else
				{
					$('.bgWrap', slideshow).css('display', 'block');		
					$('.bgWrapFull', slideshow).css('display', 'none');
							
					$('img.bg', slideConObj).attr('src', slideObjs[statusVars.currentSlide].src);
					checkandResizeImg($('img.bg', slideConObj), statusVars.slideWidth, statusVars.slideHeight, options.scaleMode, options.showCaption, options.showShadow, options.showBorder, options.watermark);							
				}

				if (!options.randomPlay && !options.loopForever)
				{
					if (statusVars.currentSlide == statusVars.totalSlides -1)
					{
						statusVars.loopCount++;
						if (options.loop <= statusVars.loopCount)
							$('.thumbPause', slideshow).trigger("click");
					}
				}
				
				if ((statusVars.totalSlides > 0) && (!statusVars.paused))
					timeoutID = setTimeout(function(){slideRun(-1);}, options.interval);

			});
			
			// preload images
			function preloadImages()
			{
				// preload images
				var preloadSlide0 = (statusVars.currentSlide > 0) ? (statusVars.currentSlide - 1) : (statusVars.totalSlides -1);
				var preloadSlide1 = (statusVars.currentSlide < statusVars.totalSlides -1) ? (parseInt(statusVars.currentSlide) + 1) : 0;
				(new Image()).src = slideObjs[preloadSlide0].src;
				(new Image()).src = slideObjs[preloadSlide1].src;
			}
			
			// main function
			function slideRun(nextSlide)
			{
				
				// flag
				statusVars.switching = true;

				// switch thumbs
				if (options.showThumbs)
				{
					$('.thumbBar .thumbArea .thumbControls .thumbImage', slideshow).css({ 'opacity':options.thumbOpacity });
					$('.thumbBar .thumbArea .thumbControls .thumbImage .thumbBorder', slideshow).css({ 'background-color':options.thumbBorderColor });
				}

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
				
				preloadImages();
				
				// switch thumbs
				if (options.showThumbs)
				{
					$($('.thumbBar .thumbArea .thumbControls .thumbImage', slideshow)[statusVars.currentSlide]).css({ 'opacity':'1.0' });
					$($('.thumbBar .thumbArea .thumbControls .thumbImage .thumbBorder', slideshow)[statusVars.currentSlide]).css({ 'background-color':options.thumbBorderActiveColor });

					if (!statusVars.thumbPageClicked)
					{
						statusVars.currentPage = Math.floor(statusVars.currentSlide / statusVars.thumbNumber);
						$('.thumbBar .thumbArea .thumbControls', slideshow).animate({'left':thumbShadowSize-statusVars.currentPage*statusVars.thumbNumber*(options.thumbSize+options.thumbGap)}, 'slow', 'easeOutCirc');
					
						if (statusVars.currentPage == 0)
							$('.thumbBar .thumbLeftArrow', slideshow).css({ 'display':'none' });
						else
							$('.thumbBar .thumbLeftArrow', slideshow).css({ 'display':'block' });
					
						if (statusVars.currentPage == statusVars.totalPages -1)
							$('.thumbBar .thumbRightArrow', slideshow).css({ 'display':'none' });
						else
							$('.thumbBar .thumbRightArrow', slideshow).css({ 'display':'block' });
					}
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

				// effect
				if (isIOS && statusVars.fullscreen)
				{
					slideshow.trigger('switchFinished');
					return;
				}
				
				if (statusVars.fullscreen)
				{
					effect = preEffects[0];
				}
				else
				{
					var optEffect = jQuery.trim(options.effect);
					var effect;
					if (optEffect == 'random')
					{
						effect = preEffects[Math.floor(Math.random() * preEffects.length)];
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
					if (jQuery.inArray(effect, preEffects) == -1) 
						effect = preEffects[0];
				}

				// animation
				var slideObj, imgObj, scaleMode;
				if (statusVars.fullscreen)
				{
					slideObj = $('.slideFull', slideshow);
					imgObj = $('img.slidebgFull', slideObj);
					scaleMode = "scaleToFit";
				}
				else
				{
					slideObj = $('.slide', slideshow);
					imgObj = $('img.slidebg', slideObj);
					scaleMode = options.scaleMode;
				}
				
				slideObj.css({'display':'block'});				
				imgObj.attr('src', slideObjs[statusVars.currentSlide].src);
				checkandResizeImg(imgObj, statusVars.slideWidth, statusVars.slideHeight, scaleMode, false, false, false, false);

				if (effect == 'fade')
				{
					var fadeSpeed = options.effectSpeed;
					if (statusVars.fullscreen)
						fadeSpeed = fadeSpeed /2;	
					
					slideObj.css({ 'left':'0px', 'opacity':'0' });
					slideObj.animate({'opacity':'1.0'}, fadeSpeed, '', function(){ slideshow.trigger('switchFinished'); });
				}
				else if ((effect == 'slideLeft') || (effect == 'slideRight'))
				{
					if (effect == 'slideRight')
						slideObj.css({ 'left':statusVars.slideWidth+'px' });
					else
						slideObj.css({ 'left':'-'+statusVars.slideWidth+'px' });
					slideObj.animate({'left':'0px'}, options.effectSpeed, 'easeOutCirc', function(){ slideshow.trigger('switchFinished'); });
				}
				else if ((effect == 'slideTop') || (effect == 'slideBottom'))
				{
					if (effect == 'slideBottom')
						slideObj.css({ 'top':statusVars.slideHeight+'px' });
					else
						slideObj.css({ 'top':'-'+statusVars.slideHeight+'px' });
					slideObj.animate({'top':'0px'}, options.effectSpeed, 'easeOutCirc', function(){ slideshow.trigger('switchFinished'); });
				}
			}

		});
		
	}
	
})(jQuery);