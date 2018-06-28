/* Main menu timers */

jQuery(function($) {

	// get and position the mega menu
	var header = $('#header'),
		mainMenu = $('#main-menu'),
		megaMenu = $('#mega-menu');

	// position the menu
	megaMenu.css('top', header.offset().top + header.height());
	megaMenu.css('left', header.offset().left);

	$(window).on('resize', function() {

		megaMenu.css('left', header.offset().left);
	});


	// hover out of menu
	var megaHoverTimeout = null;
	function clearMouseLeaveMegaArea() {

		if (megaHoverTimeout != null) {
		 	clearTimeout(megaHoverTimeout);
		 	megaHoverTimeout = null;
		}
	}
	function startMouseLeaveMegaArea() {

		clearMouseLeaveMegaArea();

		megaHoverTimeout = setTimeout(function() {
			megaMenu.hide();
			$('#main-menu a').removeClass('selected');
		}, 500);
	}

	megaMenu.on('mouseleave', function() {

		startMouseLeaveMegaArea();

	}).on('mouseenter', function() {

		clearMouseLeaveMegaArea();

	});


 	// hover over menuitems
	$('#main-menu a:not(#donate-menu)')
		.on('mouseover', function() {

			clearMouseLeaveMegaArea();
	
			// find the mega menu name from the link name
			var menuName = $(this).attr('id').replace('-menu','');
	
			megaHover(menuName);
	
		}).on('mouseout', function() {
	
	
			// find the mega menu name from the link name
			var menuName = $(this).attr('id').replace('-menu','');
	
			// make sure this one doesn't get shown on timeout
			if (typeof hoverStates[menuName] != 'undefined') {
				clearTimeout(hoverStates[menuName]);
			}
	
			startMouseLeaveMegaArea();
		});

	var hoverStates = {};


	function megaHover(menuName) {
		// skip when testing small layout on desktop (which has 'hover')
		if ($(window).width() <= 480) {
			return;
		}

		// store this timeout, so we can clear it if the user mouseout on the menu item
		hoverStates[menuName] = setTimeout(function() {
			var megaMenuItem = $('#' + menuName + '-menu');
			// unhighlight others
			megaMenuItem
				.closest('ul')
					.find('a')
					.removeClass('selected');

			// highlight this one
			megaMenuItem
				.addClass('selected');

			// look for mega menu area
			var megaDropDown = $('#' + menuName + '-mega');
			
			// if exists, show it
			if (megaDropDown.length > 0) {
			
				megaMenu.show();
	
				// hide all the other menus, except this one
				megaDropDown
					.siblings()
						.hide()
					.end()
						.show();
				
				// position correctly
				megaMenu.css('top', header.offset().top + header.height());
				megaMenu.css('left', header.offset().left);
			} else {
				
				megaMenu.hide();
				
			}

		}, 300);
	}


	/* tablet stuff OLD 07/01/2015
	if ('ontouchstart' in window) {
		$('#main-menu a').on('click', function(e) {

			// skip on iphone
			if ($(window).width() <= 480) {
				return;
			}

			var menuName = $(this).addClass('selected').attr('id').replace('-menu',''),
				megaArea = $('#' + menuName + '-mega');
			
			// if there is a menu to show
			if (megaArea.length > 0) {

				// stop the click
				e.preventDefault();

				// if the menu is already down, then hide it
				if (megaArea.is(':visible')) {
					megaMenu.hide();
					megaArea.hide();
				} else {
					// position
					megaMenu.show();
					megaArea.show();

					megaMenu.css('top', header.offset().top + header.height());
					megaMenu.css('left', header.offset().left);
				}

				// stop the click
				return false;
			} else {
				megaMenu.hide();
				// do nothing for DONATE
				return;
			}
		});

	}*/


	/* responsive menu OLD 07/01/2015
	$('#header-mobile-button').on('click', function() {
		
		if (mainMenu.is(':visible')) {
			mainMenu.slideUp();
		} else {
			mainMenu.slideDown();
		}
		
	});*/





	/* responsive menu */
	$('#header-mobile-button').on('click', function() {
		if ($('.nav-menu').is(':visible')) {
			$('.nav-menu').slideUp();
		} else {
			$('.nav-menu').slideDown();
		}
	});






});


/*! http://mths.be/placeholder v2.0.7 by @mathias */
(function(window, document, $) {

	var isInputSupported = 'placeholder' in document.createElement('input'),
		 isTextareaSupported = 'placeholder' in document.createElement('textarea'),
		 prototype = $.fn,
		 valHooks = $.valHooks,
		 hooks,
		 placeholder;

	if (isInputSupported && isTextareaSupported) {

		placeholder = prototype.placeholder = function() {
			return this;
		};

		placeholder.input = placeholder.textarea = true;

	} else {

		placeholder = prototype.placeholder = function() {
			var $this = this;
			$this
				.filter((isInputSupported ? 'textarea' : ':input') + '[placeholder]')
				.not('.placeholder')
				.bind({
					'focus.placeholder': clearPlaceholder,
					'blur.placeholder': setPlaceholder
				})
				.data('placeholder-enabled', true)
				.trigger('blur.placeholder');
			return $this;
		};

		placeholder.input = isInputSupported;
		placeholder.textarea = isTextareaSupported;

		hooks = {
			'get': function(element) {
				var $element = $(element);
				return $element.data('placeholder-enabled') && $element.hasClass('placeholder') ? '' : element.value;
			},
			'set': function(element, value) {
				var $element = $(element);
				if (!$element.data('placeholder-enabled')) {
					return element.value = value;
				}
				if (value == '') {
					element.value = value;
					// Issue #56: Setting the placeholder causes problems if the element continues to have focus.
					if (element != document.activeElement) {
						// We can't use `triggerHandler` here because of dummy text/password inputs :(
						setPlaceholder.call(element);
					}
				} else if ($element.hasClass('placeholder')) {
					clearPlaceholder.call(element, true, value) || (element.value = value);
				} else {
					element.value = value;
				}
				// `set` can not return `undefined`; see http://jsapi.info/jquery/1.7.1/val#L2363
				return $element;
			}
		};

		isInputSupported || (valHooks.input = hooks);
		isTextareaSupported || (valHooks.textarea = hooks);

		$(function() {
			// Look for forms
			$(document).delegate('form', 'submit.placeholder', function() {
				// Clear the placeholder values so they don't get submitted
				var $inputs = $('.placeholder', this).each(clearPlaceholder);
				setTimeout(function() {
					$inputs.each(setPlaceholder);
				}, 10);
			});
		});

		// Clear placeholder values upon page reload
		$(window).bind('beforeunload.placeholder', function() {
			$('.placeholder').each(function() {
				this.value = '';
			});
		});

	}

	function args(elem) {
		// Return an object of element attributes
		var newAttrs = {},
				 rinlinejQuery = /^jQuery\d+$/;
		$.each(elem.attributes, function(i, attr) {
			if (attr.specified && !rinlinejQuery.test(attr.name)) {
				newAttrs[attr.name] = attr.value;
			}
		});
		return newAttrs;
	}

	function clearPlaceholder(event, value) {
		var input = this,
				 $input = $(input);
		if (input.value == $input.attr('placeholder') && $input.hasClass('placeholder')) {
			if ($input.data('placeholder-password')) {
				$input = $input.hide().next().show().attr('id', $input.removeAttr('id').data('placeholder-id'));
				// If `clearPlaceholder` was called from `$.valHooks.input.set`
				if (event === true) {
					return $input[0].value = value;
				}
				$input.focus();
			} else {
				input.value = '';
				$input.removeClass('placeholder');
				input == document.activeElement && input.select();
			}
		}
	}

	function setPlaceholder() {
		var $replacement,
				 input = this,
				 $input = $(input),
				 $origInput = $input,
				 id = this.id;
		if (input.value == '') {
			if (input.type == 'password') {
				if (!$input.data('placeholder-textinput')) {
					try {
						$replacement = $input.clone().attr({ 'type': 'text' });
					} catch(e) {
						$replacement = $('<input>').attr($.extend(args(this), { 'type': 'text' }));
					}
					$replacement
						.removeAttr('name')
						.data({
							'placeholder-password': true,
							'placeholder-id': id
						})
						.bind('focus.placeholder', clearPlaceholder);
					$input
						.data({
							'placeholder-textinput': $replacement,
							'placeholder-id': id
						})
						.before($replacement);
				}
				$input = $input.removeAttr('id').hide().prev().attr('id', id).show();
				// Note: `$input[0] != input` now!
			}
			$input.addClass('placeholder');
			$input[0].value = $input.attr('placeholder');
		} else {
			$input.removeClass('placeholder');
		}
	}

}(this, document, jQuery));

/* search purple */
jQuery(function($) {

	$('input, textarea').placeholder();



	$('#search-text')
		.bind('keydown',function(e) {
			if (e.keyCode == 13) {
				document.location.href = '/search/?q=' + encodeURIComponent($(this).val());
				return false;
			}
		});


	$('#search-button').click(function() {
		document.location.href = '/search/?q=' + encodeURIComponent($('#search-text').val());
		return false;
	});

});


/* Modernizr 2.6.2 (Custom Build) | MIT & BSD
 * Build: http://modernizr.com/download/#-backgroundsize-svg-shiv-cssclasses-testprop-testallprops-domprefixes
 */
;



window.Modernizr = (function( window, document, undefined ) {

	var version = '2.6.2',

	Modernizr = {},

	enableClasses = true,

	docElement = document.documentElement,

	mod = 'modernizr',
	modElem = document.createElement(mod),
	mStyle = modElem.style,

	inputElem	,


	toString = {}.toString,		omPrefixes = 'Webkit Moz O ms',

	cssomPrefixes = omPrefixes.split(' '),

	domPrefixes = omPrefixes.toLowerCase().split(' '),

	ns = {'svg': 'http://www.w3.org/2000/svg'},

	tests = {},
	inputs = {},
	attrs = {},

	classes = [],

	slice = classes.slice,

	featureName,



	_hasOwnProperty = ({}).hasOwnProperty, hasOwnProp;

	if ( !is(_hasOwnProperty, 'undefined') && !is(_hasOwnProperty.call, 'undefined') ) {
	  hasOwnProp = function (object, property) {
		return _hasOwnProperty.call(object, property);
	  };
	}
	else {
	  hasOwnProp = function (object, property) {
		return ((property in object) && is(object.constructor.prototype[property], 'undefined'));
	  };
	}


	if (!Function.prototype.bind) {
	  Function.prototype.bind = function bind(that) {

		var target = this;

		if (typeof target != "function") {
			throw new TypeError();
		}

		var args = slice.call(arguments, 1),
			bound = function () {

			if (this instanceof bound) {

				 var F = function(){};
				 F.prototype = target.prototype;
				 var self = new F();

				 var result = target.apply(
				  self,
				  args.concat(slice.call(arguments))
				 );
				 if (Object(result) === result) {
				  return result;
				 }
				 return self;

			} else {

				 return target.apply(
				  that,
				  args.concat(slice.call(arguments))
				 );

			}

		};

		return bound;
	  };
	}

	function setCss( str ) {
		mStyle.cssText = str;
	}

	function setCssAll( str1, str2 ) {
		return setCss(prefixes.join(str1 + ';') + ( str2 || '' ));
	}

	function is( obj, type ) {
		return typeof obj === type;
	}

	function contains( str, substr ) {
		return !!~('' + str).indexOf(substr);
	}

	function testProps( props, prefixed ) {
		for ( var i in props ) {
			var prop = props[i];
			if ( !contains(prop, "-") && mStyle[prop] !== undefined ) {
				return prefixed == 'pfx' ? prop : true;
			}
		}
		return false;
	}

	function testDOMProps( props, obj, elem ) {
		for ( var i in props ) {
			var item = obj[props[i]];
			if ( item !== undefined) {

							if (elem === false) return props[i];

							if (is(item, 'function')){
								return item.bind(elem || obj);
				}

							return item;
			}
		}
		return false;
	}

	function testPropsAll( prop, prefixed, elem ) {

		var ucProp	= prop.charAt(0).toUpperCase() + prop.slice(1),
			props	= (prop + ' ' + cssomPrefixes.join(ucProp + ' ') + ucProp).split(' ');

			if(is(prefixed, "string") || is(prefixed, "undefined")) {
			return testProps(props, prefixed);

			} else {
			props = (prop + ' ' + (domPrefixes).join(ucProp + ' ') + ucProp).split(' ');
			return testDOMProps(props, prefixed, elem);
		}
	}	 tests['backgroundsize'] = function() {
		return testPropsAll('backgroundSize');
	};
	tests['svg'] = function() {
		return !!document.createElementNS && !!document.createElementNS(ns.svg, 'svg').createSVGRect;
	};
	for ( var feature in tests ) {
		if ( hasOwnProp(tests, feature) ) {
									featureName	 = feature.toLowerCase();
			Modernizr[featureName] = tests[feature]();

			classes.push((Modernizr[featureName] ? '' : 'no-') + featureName);
		}
	}



	 Modernizr.addTest = function ( feature, test ) {
		if ( typeof feature == 'object' ) {
		 for ( var key in feature ) {
			 if ( hasOwnProp( feature, key ) ) {
			 Modernizr.addTest( key, feature[ key ] );
			 }
		 }
		} else {

		 feature = feature.toLowerCase();

		 if ( Modernizr[feature] !== undefined ) {
												 return Modernizr;
		 }

		 test = typeof test == 'function' ? test() : test;

		 if (typeof enableClasses !== "undefined" && enableClasses) {
			 docElement.className += ' ' + (test ? '' : 'no-') + feature;
		 }
		 Modernizr[feature] = test;

		}

		return Modernizr;
	 };


	setCss('');
	modElem = inputElem = null;

	;(function(window, document) {
		var options = window.html5 || {};

		var reSkip = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i;

		var saveClones = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i;

		var supportsHtml5Styles;

		var expando = '_html5shiv';

		var expanID = 0;

		var expandoData = {};

		var supportsUnknownElements;

	  (function() {
		try {
			var a = document.createElement('a');
			a.innerHTML = '<xyz></xyz>';
					supportsHtml5Styles = ('hidden' in a);

			supportsUnknownElements = a.childNodes.length == 1 || (function() {
						(document.createElement)('a');
				 var frag = document.createDocumentFragment();
				 return (
				typeof frag.cloneNode == 'undefined' ||
				typeof frag.createDocumentFragment == 'undefined' ||
				typeof frag.createElement == 'undefined'
				 );
			}());
		} catch(e) {
			supportsHtml5Styles = true;
			supportsUnknownElements = true;
		}

	  }());			 function addStyleSheet(ownerDocument, cssText) {
		var p = ownerDocument.createElement('p'),
			parent = ownerDocument.getElementsByTagName('head')[0] || ownerDocument.documentElement;

		p.innerHTML = 'x<style>' + cssText + '</style>';
		return parent.insertBefore(p.lastChild, parent.firstChild);
	  }

		function getElements() {
		var elements = html5.elements;
		return typeof elements == 'string' ? elements.split(' ') : elements;
	  }

			function getExpandoData(ownerDocument) {
		var data = expandoData[ownerDocument[expando]];
		if (!data) {
			data = {};
			expanID++;
			ownerDocument[expando] = expanID;
			expandoData[expanID] = data;
		}
		return data;
	  }

		function createElement(nodeName, ownerDocument, data){
		if (!ownerDocument) {
			ownerDocument = document;
		}
		if(supportsUnknownElements){
			return ownerDocument.createElement(nodeName);
		}
		if (!data) {
			data = getExpandoData(ownerDocument);
		}
		var node;

		if (data.cache[nodeName]) {
			node = data.cache[nodeName].cloneNode();
		} else if (saveClones.test(nodeName)) {
			node = (data.cache[nodeName] = data.createElem(nodeName)).cloneNode();
		} else {
			node = data.createElem(nodeName);
		}

									return node.canHaveChildren && !reSkip.test(nodeName) ? data.frag.appendChild(node) : node;
	  }

		function createDocumentFragment(ownerDocument, data){
		if (!ownerDocument) {
			ownerDocument = document;
		}
		if(supportsUnknownElements){
			return ownerDocument.createDocumentFragment();
		}
		data = data || getExpandoData(ownerDocument);
		var clone = data.frag.cloneNode(),
			i = 0,
			elems = getElements(),
			l = elems.length;
		for(;i<l;i++){
			clone.createElement(elems[i]);
		}
		return clone;
	  }

		function shivMethods(ownerDocument, data) {
		if (!data.cache) {
			data.cache = {};
			data.createElem = ownerDocument.createElement;
			data.createFrag = ownerDocument.createDocumentFragment;
			data.frag = data.createFrag();
		}


		ownerDocument.createElement = function(nodeName) {
				if (!html5.shivMethods) {
				 return data.createElem(nodeName);
			}
			return createElement(nodeName, ownerDocument, data);
		};

		ownerDocument.createDocumentFragment = Function('h,f', 'return function(){' +
			'var n=f.cloneNode(),c=n.createElement;' +
			'h.shivMethods&&(' +
					getElements().join().replace(/\w+/g, function(nodeName) {
				 data.createElem(nodeName);
				 data.frag.createElement(nodeName);
				 return 'c("' + nodeName + '")';
			}) +
			');return n}'
		)(html5, data.frag);
	  }			 function shivDocument(ownerDocument) {
		if (!ownerDocument) {
			ownerDocument = document;
		}
		var data = getExpandoData(ownerDocument);

		if (html5.shivCSS && !supportsHtml5Styles && !data.hasCSS) {
			data.hasCSS = !!addStyleSheet(ownerDocument,
					'article,aside,figcaption,figure,footer,header,hgroup,nav,section{display:block}' +
					'mark{background:#FF0;color:#000}'
			);
		}
		if (!supportsUnknownElements) {
			shivMethods(ownerDocument, data);
		}
		return ownerDocument;
	  }			 var html5 = {

			'elements': options.elements || 'abbr article aside audio bdi canvas data datalist details figcaption figure footer header hgroup mark meter nav output progress section summary time video',

			'shivCSS': (options.shivCSS !== false),

			'supportsUnknownElements': supportsUnknownElements,

			'shivMethods': (options.shivMethods !== false),

			'type': 'default',

			'shivDocument': shivDocument,

			createElement: createElement,

			createDocumentFragment: createDocumentFragment
	  };		window.html5 = html5;

		shivDocument(document);

	}(this, document));

	Modernizr._version		= version;

	Modernizr._domPrefixes	= domPrefixes;
	Modernizr._cssomPrefixes  = cssomPrefixes;



	Modernizr.testProp		= function(prop){
		return testProps([prop]);
	};

	Modernizr.testAllProps	= testPropsAll;

	docElement.className = docElement.className.replace(/(^|\s)no-js(\s|$)/, '$1$2') +

													(enableClasses ? ' js ' + classes.join(' ') : '');

	return Modernizr;

})(this, this.document);
;



/* home page rotator */

jQuery(function($) {

	var currentSlide = 1,
		slides = $('.rotator-item'),
		buttonsContainer = $('.rotator-buttons'),
		buttons = null,
		rotatorTimeout = null,
		slideDuration = 4500,
		effectTime = 1500,
		isAnimating = false,
		activeSlideClassName = 'active-rotator-slide',
		activeButtonClassName = 'active-rotator-button';


	function showSlide(slideToShow) {

		if (isAnimating) {
			return;
		}

		if (slideToShow === currentSlide) {
			return;
		}

		var oldSlide = slides.eq(currentSlide-1),
			newSlide = slides.eq(slideToShow-1),
			oldButton = buttons.eq(currentSlide-1),
			newButton = buttons.eq(slideToShow-1);

		isAnimating = true;


		oldSlide.removeClass(activeSlideClassName);
		newSlide.addClass(activeSlideClassName);

		oldButton.removeClass(activeButtonClassName);
		newButton.addClass(activeButtonClassName);

		oldSlide.fadeOut(effectTime);
		newSlide.fadeIn(effectTime, function() {

			isAnimating = false;
			currentSlide = slideToShow;

			//newButton
			//	.addClass(activeButtonClassName)
			//	.siblings()
			//		.removeClass(activeButtonClassName);

			startTimer();
		});
	}

	function showNext() {

		var next = currentSlide+1;
		if (next > slides.length) {
			next = 1;
		}

		showSlide(next);

	}

	function clearTimer() {
		if (rotatorTimeout !== null) {
			clearTimeout(rotatorTimeout);
			rotatorTimeout = null;
		}
	}

	function startTimer() {
		clearTimer();

		rotatorTimeout = setTimeout(function() {

			showNext();

		}, slideDuration)
	}

	if (slides.length > 1) {

		// setup initial state
		slides
			.first()
				.addClass(activeSlideClassName)
			.siblings('.rotator-item')
				.hide();

		// add classes, create buttons
		slides.each(function(index, el) {
			$(this).addClass('rotator-item-' + (index+1));

			buttonsContainer.append($('<div class="rotator-button" data-number="' + (index+1) + '" id="rotator-button-' + (index+1) + '">' + (index+1) + '</div>'));
		});

		buttons = $('.rotator-button');
		buttons.first()
				.addClass(activeButtonClassName);

		buttonsContainer.width(buttons.first().outerWidth(true) * buttons.length);

		buttons.on('click', function() {
			var number = parseInt($(this).attr('data-number'), 10);
			showSlide(number);
		});

		// begin the magic
		startTimer();
	}
});


/* tabs */
jQuery(function ($) {

	//Hide all content
	$(".tab-content").hide();

	// check for hash
	var hash = document.location.hash,
		firstTabToShow = null;

	if (hash != null && hash.length > 1) {
		firstTabToShow = $('.tab-container .tab-links a[href="' + hash + '"]').parent();
	}

	// if no hash, then select the first one
	if (firstTabToShow == null || firstTabToShow.length == 0) {
		firstTabToShow = $(".tab-container .tab-links li:first");
	}

	// show the default tab
	if (firstTabToShow.length > 0) {
		firstTabToShow.addClass("selected").show(); // Activate first tab
		$(firstTabToShow.find('a').attr('href')).show(); // Show content
	}


	$(".tab-container .tab-links li").click(function (e) {
		e.preventDefault();

		selectTab($(this).find("a").attr("href"));

		return false;
	});

	// hashhange
	$(window).on('hashchange', function (e) {

		e.preventDefault();

		if (document.location.hash.length > 1) {
			selectTab(document.location.hash);
		} else {
			selectTab($(".tab-container .tab-links li:first a").attr('href'));
		}

		return false;
	});


	function selectTab(tabid) {

		var scrollTop = $(document.body).scrollTop()
			tabLi = $('.tab-container .tab-links li a[href="' + tabid + '"]').parent();
			
		// if there is no tab, then just stop
		if (tabLi.length == 0)
			return;

		// check if already selected
		if (tabLi.hasClass('selected'))
			return;

		// select tab
		$(".tab-container .tab-links li").removeClass("selected"); //Remove any "active" class
		tabLi.addClass('selected');

		// show content
		$('.tab-content').hide(); //Hide all tab content
		$(tabid).fadeIn(); //Fade in the active content

		// force hash
		document.location.hash = tabid;

		// reset scroll
		$(document.body).scrollTop(scrollTop);
	}

});


/* FAQ */
jQuery(function() {


	var faqs = $('.dts-faq');

	faqs.each(function() {

		faq = $(this);
		
		// toggle function
		faq.find('dt').click(function() {
			 $(this).toggleClass('selected').next('dd').toggle(100);
		});		

		// start up hide all definitions
		if (faq.hasClass('dts-faq-open')) {
			// do nothing
			faq.find('dt').addClass('selected');
		} else {
			// hide all
			faq.find('dd:not(.faq-item-leave-open)').hide();
			// open first item	
			if (!faq.hasClass('faq-closed')) {
				faq.find('dt:first-child').each(function() {
					  $(this).addClass('selected').next('dd').show();
				});
			}
		}



	});

	/*
	// FAQ
	// start up hide all defintions
	$('.dts-faq dd').hide();

	// toggle function
	$('.dts-faq dt').click(function() {
		$(this).toggleClass('selected').next('dd').toggle(100);
	});

	// open first item
	$('.dts-faq dt:first-child').each(function() {
		$(this).addClass('selected').next('dd').toggle(100);
	});
	*/
});


/*global jQuery */
/*!
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/

(function( $ ){

  $.fn.fitVids = function() {
	var div = document.createElement('div'),
		ref = document.getElementsByTagName('base')[0] || document.getElementsByTagName('script')[0];

  	div.className = 'fit-vids-style';
	div.innerHTML = '&shy;<style>		  \
	  .fluid-width-video-wrapper {		  \
		 width: 100%;					  \
		 position: relative;			  \
		 padding: 0;						 \
	  }									  \
											\
	  .fluid-width-video-wrapper iframe,  \
	  .fluid-width-video-wrapper object,  \
	  .fluid-width-video-wrapper embed {  \
		 position: absolute;			  \
		 top: 0;						  \
		 left: 0;						  \
		 width: 100%;					  \
		 height: 100%;					  \
	  }									  \
	</style>';

	ref.parentNode.insertBefore(div,ref);

	return this.each(function(){
	  var selectors = [
		"iframe[src^='../player.vimeo.com']",
		"iframe[src^='../www.youtube.com']",
		"iframe[src^='../media.dts.edu']" //,
		//"object",
		//"embed"
	  ];

	  var $allVideos = $(this).find(selectors.join(','));

	  $allVideos.each(function(){
		var $this = $(this),
			height = this.tagName == 'OBJECT' ? $this.attr('height') : $this.height(),
			aspectRatio = height / $this.width();
		$this.wrap('<div class="fluid-width-video-wrapper" />').parent('.fluid-width-video-wrapper').css('padding-top', (aspectRatio * 100)+"%");
		$this.removeAttr('height').removeAttr('width');
	  });
	});

  }
})( jQuery );


jQuery(function($) {
	$("#content").fitVids();
});


// validation
jQuery(function($) {
	if ($.updnValidatorCallout) {
		$.updnValidatorCallout.attachAll();
	}
});


if (typeof window.dts == 'undefined')
	window.dts = {};
if (typeof window.dts.settings == 'undefined')
	window.dts.settings = {};
	
window.dts.settings.enableFixedMenu = true;	

// fixed menu
jQuery(function($) {

	if (Modernizr.backgroundsize) {

		var shouldICheckIt = false,
			win = $(window),
			headerOuter = $('#header-outer'),
			header = $('#header'),
			lastScrollTop = 0;

		if (headerOuter.length == 0 || header.length == 0) {
			return;
		}

		// we don't use 'scroll' since that tends to kill browsers
		win.on('scroll resize', function() {
			shouldICheckIt = true;
		});

		setInterval(function () {

			if (shouldICheckIt) {

				checkHeader();
				shouldICheckIt = false;
			}

		}, 100);


		function checkHeader() {
		
			if (!window.dts.settings.enableFixedMenu) {
				return;				
			}

			var windowWidth = win.width(),			
				currentScrollTop = win.scrollTop(),
				isScrollingUp = lastScrollTop > currentScrollTop;
						

			if (isScrollingUp && windowWidth > 480 && currentScrollTop > 100 + headerOuter.offset().top + headerOuter.outerHeight(true) ) {

				if (!headerOuter.hasClass('header-fixed')) {
					headerOuter.addClass('header-fixed');
					header.hide().fadeIn();

					if (windowWidth < 1280) {
						header.width( Math.max(windowWidth, 1024) );
					}
				}
			} else {

				headerOuter.removeClass('header-fixed');
				header.css('width','');
			}
			
			lastScrollTop = currentScrollTop;

		}

		checkHeader();
	}
});


/* Summary/Details */
jQuery(function() {
	var detailsIsSupported = ('open' in document.createElement('details'));
	
	if (!detailsIsSupported) {
		
		$('html').addClass('no-details');
		
		
		// put the inner content into another div
		$('details').each(function() {
			var details = $(this),
				summary = $('summary',details),
				otherStuff = details.children(':not(summary)'),
				startsOpen = 
					// has an open tag
					(typeof details.attr('open') == 'string')
					||
					// doesn't have a closed tag 
					!(typeof details.attr('closed') == 'string');
				
			
			if (startsOpen) {
				details
					.addClass('open')
					.prop('closed',false)
					.prop('open',true);					
			} else {
				otherStuff.hide();	
				details
					.removeClass('open')
					.prop('closed',true)
					.prop('open',false);				
			}
			
			summary.on('click', function() {
				
				if (details.prop('open')) {
					otherStuff.hide();
					details
						.removeClass('open')
						.prop('closed',true)
						.prop('open',false);				
				} else {
					otherStuff.show();
					details
						.addClass('open')
						.prop('closed',false)
						.prop('open',true);									
				}
					
			});			
		});	
	}
});
/*! matchMedia() polyfill - Test a CSS media type/query in JS. Authors & copyright (c) 2012: Scott Jehl, Paul Irish, Nicholas Zakas. Dual MIT/BSD license */
window.matchMedia=window.matchMedia||(function(e,f){var c,a=e.documentElement,b=a.firstElementChild||a.firstChild,d=e.createElement("body"),g=e.createElement("div");g.id="mq-test-1";g.style.cssText="position:absolute;top:-100em";d.appendChild(g);return function(h){g.innerHTML='&shy;<style media="'+h+'"> #mq-test-1 { width: 42px; }</style>';a.insertBefore(d,b);c=g.offsetWidth==42;a.removeChild(d);return{matches:c,media:h}}})(document);
/*! Picturefill - Responsive Images that work today. (and mimic the proposed Picture element with span elements). Author: Scott Jehl, Filament Group, 2012 | License: MIT/GPLv2 */

(function( w ){

	// Enable strict mode
	"use strict";

	w.picturefill = function() {
		var ps = w.document.getElementsByTagName( "div" );

		// Loop the pictures
		for( var i = 0, il = ps.length; i < il; i++ ){
			if( ps[ i ].getAttribute( "data-picture" ) !== null ){

				var sources = ps[ i ].getElementsByTagName( "div" ),
					matches = [];

				// See if which sources match
				for( var j = 0, jl = sources.length; j < jl; j++ ){
					var media = sources[ j ].getAttribute( "data-media" );
					// if there's no media specified, OR w.matchMedia is supported 
					if( !media || ( w.matchMedia && w.matchMedia( media ).matches ) ){
						matches.push( sources[ j ] );
					}
				}

			// Find any existing img element in the picture element
			var picImg = ps[ i ].getElementsByTagName( "img" )[ 0 ];

			if( matches.length ){
				var matchedEl = matches.pop();
				if( !picImg || picImg.parentNode.nodeName === "NOSCRIPT" ){
					picImg = w.document.createElement( "img" );
					picImg.alt = ps[ i ].getAttribute( "data-alt" );
				}
				else if( matchedEl === picImg.parentNode ){
					// Skip further actions if the correct image is already in place
					continue;
				}

				picImg.src =  matchedEl.getAttribute( "data-src" );
				matchedEl.appendChild( picImg );
				picImg.removeAttribute("width");
				picImg.removeAttribute("height");
			}
			else if( picImg ){
				picImg.parentNode.removeChild( picImg );
			}
		}
		}
	};

	// Run on resize and domready (w.load as a fallback)
	if( w.addEventListener ){
		w.addEventListener( "resize", w.picturefill, false );
		w.addEventListener( "DOMContentLoaded", function(){
			w.picturefill();
			// Run once only
			w.removeEventListener( "load", w.picturefill, false );
		}, false );
		w.addEventListener( "load", w.picturefill, false );
	}
	else if( w.attachEvent ){
		w.attachEvent( "onload", w.picturefill );
	}

}( this ));
/*
* jQuery (ASP.NET) Validator Callout plugin
*   http://updatepanel.net/2009/04/19/jquery-aspnet-validator-callout-plugin/
*
* Copyright (c) 2009 Ting Zwei Kuei
*
* Dual licensed under the MIT and GPL licenses.
*   http://www.opensource.org/licenses/mit-license.php
*   http://www.opensource.org/licenses/gpl-3.0.html
*/
(function($) {
    $.fn.updnValidatorCallout = function(options) {
        // Prepare options.
        options = $.extend({}, $.fn.updnValidatorCallout.defaults, options);
        // Currently open callout.
        var _current = null;
        // Overrides ValidatorOnChange in WebUIValidation.js to ensure input and label styles are updated.
        if (window.ValidatorOnChange && !window._ValidatorOnChange) {
            window._ValidatorOnChange = window.ValidatorOnChange;
            window.ValidatorOnChange = function(ev) {
                window._ValidatorOnChange(ev);
                ev = $.event.fix(ev);   // Normalizes browser event object.
                var $input = $(ev.target);
                // Selects all labels associated with the input element.
                var $label = $("label[for='" + $input.attr("id") + "']");
                var $callout = $input.data("callout");
                var vals = $input.attr("Validators");
                if (window.AllValidatorsValid && window.AllValidatorsValid(vals)) {
                    if ($input.hasClass(options.errorInputCssClass)) {
                        $input.removeClass(options.errorInputCssClass);
                        $label.removeClass(options.errorLabelCssClass);
                    }
                    if ($callout) {
                        $callout.trigger("close");
                    }
                } else {
                    if (!$input.hasClass(options.errorInputCssClass)) {
                        $input.addClass(options.errorInputCssClass);
                        $label.addClass(options.errorLabelCssClass);
                    }
                    if ($callout) {
                        $callout.trigger("open");
                    }
                }
            };
        }
        // Plugin implementation.
        return this.each(function() {
            var val = this;
            // Only create callout if controltovalidate has a value.
            if (this.controltovalidate) {
                var $input = $(document.getElementById(this.controltovalidate));
                // Create a separate callout for each input element.
                var $callout = $input.data("callout");
                if (!$callout) {
                    $callout = $("<div/>")
                    .appendTo(document.body)
                    .addClass(options.calloutCssClass)
                    .hide()
                    .bind("open", function(ev) {
                        if (_current) {
                            _current.trigger("close");
                        }
                        var pos = $input.offset();
                        _current = $(this).css({
                            position: "absolute",
                            left: Math.floor(pos.left + $input.outerWidth() + options.offsetX),
                            top: Math.floor(pos.top + options.offsetY)
                        }).fadeIn("fast");
                    })
                    .bind("close", function(ev) {
                        $(this).hide();
                        _current = null;
                    });
                    // Add callout pointer
                    $("<span/>").appendTo($callout).addClass(options.pointerCssClass);
                }
                // Move validator inside of callout.
                $callout.append(this);
                // Force "SetFocusOnError" property of the validator to true.
                // This will display the callout for the first validator in error state.
                this.focusOnError = "t";
                // Open callout when input element gains focus.
                $input.focus(function(ev) {
                    if (!val.isvalid) {
                        $callout.trigger("open");
                    }
                });
                // Associate the callout element with the validator.
                $input.data("callout", $callout);
                // Helper function to open/close callout and add/remove error state styles.
                var updateDisplay = function(isValid) {
                    // For non-IE browsers, ValidatorUpdateDisplay sets visibility to
                    // show/hide validators, so I use jQuey toggle to set display as well.
                    $(val).toggle(!isValid);
                    // Selects all labels associated with the input element.
                    var $label = $("label[for='" + $input.attr("id") + "']");
                    if (!isValid) {
                        if (!$input.hasClass(options.errorInputCssClass)) {
                            $input.addClass(options.errorInputCssClass);
                            $label.addClass(options.errorLabelCssClass);
                        }
                        if (!_current) {
                            $callout.trigger("open");
                        }
                    } else {
                        var vals = $input.attr("Validators");
                        if (window.AllValidatorsValid && window.AllValidatorsValid(vals)) {
                            if ($input.hasClass(options.errorInputCssClass)) {
                                $input.removeClass(options.errorInputCssClass);
                                $label.removeClass(options.errorLabelCssClass);
                            }
                            $callout.trigger("close");
                        }
                    }
                };
                // Overrides evaluationfunction of each validator to update
                // input and label styles according to the validation result.
                if (this.evaluationfunction) {
                    var _evaluationfunction = this.evaluationfunction;
                    this.evaluationfunction = function(val) {
                        var isValid = _evaluationfunction(val);
                        updateDisplay(isValid);
                        return isValid;
                    };
                }
                // Set initial state.
                updateDisplay(val.isvalid);
            }
        });
    };
    $.fn.updnValidatorCallout.defaults = {
        calloutCssClass: "updnValidatorCallout",
        pointerCssClass: "updnValidatorCalloutPointer",
        errorInputCssClass: "updnValidationErrorInput",
        errorLabelCssClass: "updnValidationErrorLabel",
        offsetX: 0,
        offsetY: 0
    };
    $.updnValidatorCallout = {
        attachAll: function(options) {
            if (window.Page_Validators) {
                $(window.Page_Validators).updnValidatorCallout(options);
            }
        }
    };
})(jQuery);