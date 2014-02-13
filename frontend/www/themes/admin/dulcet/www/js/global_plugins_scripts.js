/*
 * Superfish v1.4.8 - jQuery menu widget
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 * CHANGELOG: http://users.tpg.com.au/j_birch/plugins/superfish/changelog.txt
 */

;(function($){
	$.fn.superfish = function(op){

		var sf = $.fn.superfish,
			c = sf.c,
			$arrow = $(['<span class="',c.arrowClass,'"> &#187;</span>'].join('')),
			over = function(){
				var $$ = $(this), menu = getMenu($$);
				clearTimeout(menu.sfTimer);
				$$.showSuperfishUl().siblings().hideSuperfishUl();
			},
			out = function(){
				var $$ = $(this), menu = getMenu($$), o = sf.op;
				clearTimeout(menu.sfTimer);
				menu.sfTimer=setTimeout(function(){
					o.retainPath=($.inArray($$[0],o.$path)>-1);
					$$.hideSuperfishUl();
					if (o.$path.length && $$.parents(['li.',o.hoverClass].join('')).length<1){over.call(o.$path);}
				},o.delay);	
			},
			getMenu = function($menu){
				var menu = $menu.parents(['ul.',c.menuClass,':first'].join(''))[0];
				sf.op = sf.o[menu.serial];
				return menu;
			},
			addArrow = function($a){ $a.addClass(c.anchorClass).append($arrow.clone()); };
			
		return this.each(function() {
			var s = this.serial = sf.o.length;
			var o = $.extend({},sf.defaults,op);
			o.$path = $('li.'+o.pathClass,this).slice(0,o.pathLevels).each(function(){
				$(this).addClass([o.hoverClass,c.bcClass].join(' '))
					.filter('li:has(ul)').removeClass(o.pathClass);
			});
			sf.o[s] = sf.op = o;
			
			$('li:has(ul)',this)[($.fn.hoverIntent && !o.disableHI) ? 'hoverIntent' : 'hover'](over,out).each(function() {
				if (o.autoArrows) addArrow( $('>a:first-child',this) );
			})
			.not('.'+c.bcClass)
				.hideSuperfishUl();
			
			var $a = $('a',this);
			$a.each(function(i){
				var $li = $a.eq(i).parents('li');
				$a.eq(i).focus(function(){over.call($li);}).blur(function(){out.call($li);});
			});
			o.onInit.call(this);
			
		}).each(function() {
			var menuClasses = [c.menuClass];
			if (sf.op.dropShadows  && !($.browser.msie && $.browser.version < 7)) menuClasses.push(c.shadowClass);
			$(this).addClass(menuClasses.join(' '));
		});
	};

	var sf = $.fn.superfish;
	sf.o = [];
	sf.op = {};
	sf.IE7fix = function(){
		var o = sf.op;
		if ($.browser.msie && $.browser.version > 6 && o.dropShadows && o.animation.opacity!=undefined)
			this.toggleClass(sf.c.shadowClass+'-off');
		};
	sf.c = {
		bcClass     : 'sf-breadcrumb',
		menuClass   : 'sf-js-enabled',
		anchorClass : 'sf-with-ul',
		arrowClass  : 'sf-sub-indicator',
		shadowClass : 'sf-shadow'
	};
	sf.defaults = {
		hoverClass	: 'sfHover',
		pathClass	: 'overideThisToUse',
		pathLevels	: 1,
		delay		: 800,
		animation	: {opacity:'show'},
		speed		: 'normal',
		autoArrows	: true,
		dropShadows : true,
		disableHI	: false,		// true disables hoverIntent detection
		onInit		: function(){}, // callback functions
		onBeforeShow: function(){},
		onShow		: function(){},
		onHide		: function(){}
	};
	$.fn.extend({
		hideSuperfishUl : function(){
			var o = sf.op,
				not = (o.retainPath===true) ? o.$path : '';
			o.retainPath = false;
			var $ul = $(['li.',o.hoverClass].join(''),this).add(this).not(not).removeClass(o.hoverClass)
					.find('>ul').hide().css('visibility','hidden');
			o.onHide.call($ul);
			return this;
		},
		showSuperfishUl : function(){
			var o = sf.op,
				sh = sf.c.shadowClass+'-off',
				$ul = this.addClass(o.hoverClass)
					.find('>ul:hidden').css('visibility','visible');
			sf.IE7fix.call($ul);
			o.onBeforeShow.call($ul);
			$ul.animate(o.animation,o.speed,function(){ sf.IE7fix.call($ul); o.onShow.call($ul); });
			return this;
		}
	});

})(jQuery);



/*
 * Supersubs v0.2b - jQuery plugin
 * Copyright (c) 2008 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 * 	http://www.opensource.org/licenses/mit-license.php
 * 	http://www.gnu.org/licenses/gpl.html
 *
 *
 * This plugin automatically adjusts submenu widths of suckerfish-style menus to that of
 * their longest list item children. If you use this, please expect bugs and report them
 * to the jQuery Google Group with the word 'Superfish' in the subject line.
 *
 */

;(function($){ // $ will refer to jQuery within this closure

	$.fn.supersubs = function(options){
		var opts = $.extend({}, $.fn.supersubs.defaults, options);
		// return original object to support chaining
		return this.each(function() {
			// cache selections
			var $$ = $(this);
			// support metadata
			var o = $.meta ? $.extend({}, opts, $$.data()) : opts;
			// get the font size of menu.
			// .css('fontSize') returns various results cross-browser, so measure an em dash instead
			var fontsize = $('<li id="menu-fontsize">&#8212;</li>').css({
				'padding' : 0,
				'position' : 'absolute',
				'top' : '-999em',
				'width' : 'auto'
			}).appendTo($$).width(); //clientWidth is faster, but was incorrect here
			// remove em dash
			$('#menu-fontsize').remove();
			// cache all ul elements
			$ULs = $$.find('ul');
			// loop through each ul in menu
			$ULs.each(function(i) {	
				// cache this ul
				var $ul = $ULs.eq(i);
				// get all (li) children of this ul
				var $LIs = $ul.children();
				// get all anchor grand-children
				var $As = $LIs.children('a');
				// force content to one line and save current float property
				var liFloat = $LIs.css('white-space','nowrap').css('float');
				// remove width restrictions and floats so elements remain vertically stacked
				var emWidth = $ul.add($LIs).add($As).css({
					'float' : 'none',
					'width'	: 'auto'
				})
				// this ul will now be shrink-wrapped to longest li due to position:absolute
				// so save its width as ems. Clientwidth is 2 times faster than .width() - thanks Dan Switzer
				.end().end()[0].clientWidth / fontsize;
				// add more width to ensure lines don't turn over at certain sizes in various browsers
				emWidth += o.extraWidth;
				// restrict to at least minWidth and at most maxWidth
				if (emWidth > o.maxWidth)		{ emWidth = o.maxWidth; }
				else if (emWidth < o.minWidth)	{ emWidth = o.minWidth; }
				emWidth += 'em';
				// set ul to width in ems
				$ul.css('width',emWidth);
				// restore li floats to avoid IE bugs
				// set li width to full width of this ul
				// revert white-space to normal
				$LIs.css({
					'float' : liFloat,
					'width' : '100%',
					'white-space' : 'normal'
				})
				// update offset position of descendant ul to reflect new width of parent
				.each(function(){
					var $childUl = $('>ul',this);
					var offsetDirection = $childUl.css('left')!==undefined ? 'left' : 'right';
					$childUl.css(offsetDirection,emWidth);
				});
			});
			
		});
	};
	// expose defaults
	$.fn.supersubs.defaults = {
		minWidth		: 9,		// requires em unit.
		maxWidth		: 25,		// requires em unit.
		extraWidth		: 0			// extra width can ensure lines don't sometimes turn over due to slight browser differences in how they round-off values
	};
	
})(jQuery); // plugin code ends


/*
Uniform v1.7.5
Copyright © 2009 Josh Pyles / Pixelmatrix Design LLC
http://pixelmatrixdesign.com

Requires jQuery 1.4 or newer

Much thanks to Thomas Reynolds and Buck Wilson for their help and advice on this

Disabling text selection is made possible by Mathias Bynens <http://mathiasbynens.be/>
and his noSelect plugin. <http://github.com/mathiasbynens/noSelect-jQuery-Plugin>

Also, thanks to David Kaneda and Eugene Bond for their contributions to the plugin

License:
MIT License - http://www.opensource.org/licenses/mit-license.php

Enjoy!
*/
(function(a){a.uniform={options:{selectClass:"selector",radioClass:"radio",checkboxClass:"checker",fileClass:"uploader",filenameClass:"filename",fileBtnClass:"action",fileDefaultText:"No file selected",fileBtnText:"Choose File",checkedClass:"checked",focusClass:"focus",disabledClass:"disabled",buttonClass:"button",activeClass:"active",hoverClass:"hover",useID:true,idPrefix:"uniform",resetSelector:false,autoHide:true},elements:[]};if(a.browser.msie&&a.browser.version<7){a.support.selectOpacity=false}else{a.support.selectOpacity=true}a.fn.uniform=function(b){function k(b){b=a(b).get();if(b.length>1){a.each(b,function(b,c){a.uniform.elements.push(c)})}else{a.uniform.elements.push(b)}}function j(c){var d=a(c);var e=a("<div />"),f=a("<span>"+b.fileDefaultText+"</span>"),g=a("<span>"+b.fileBtnText+"</span>");if(!d.css("display")=="none"&&b.autoHide){e.hide()}e.addClass(b.fileClass);f.addClass(b.filenameClass);g.addClass(b.fileBtnClass);if(b.useID&&d.attr("id")!=""){e.attr("id",b.idPrefix+"-"+d.attr("id"))}d.wrap(e);d.after(g);d.after(f);e=d.closest("div");f=d.siblings("."+b.filenameClass);g=d.siblings("."+b.fileBtnClass);if(!d.attr("size")){var h=e.width();d.attr("size",h/10)}var i=function(){var a=d.val();if(a===""){a=b.fileDefaultText}else{a=a.split(/[\/\\]+/);a=a[a.length-1]}f.text(a)};i();d.css("opacity",0).bind({"focus.uniform":function(){e.addClass(b.focusClass)},"blur.uniform":function(){e.removeClass(b.focusClass)},"mousedown.uniform":function(){if(!a(c).is(":disabled")){e.addClass(b.activeClass)}},"mouseup.uniform":function(){e.removeClass(b.activeClass)},"mouseenter.uniform":function(){e.addClass(b.hoverClass)},"mouseleave.uniform":function(){e.removeClass(b.hoverClass);e.removeClass(b.activeClass)}});if(a.browser.msie){d.bind("click.uniform.ie7",function(){setTimeout(i,0)})}else{d.bind("change.uniform",i)}if(d.attr("disabled")){e.addClass(b.disabledClass)}a.uniform.noSelect(f);a.uniform.noSelect(g);k(c)}function i(c){var d=a(c);var e=a("<div />"),f=a("<span />");if(!d.css("display")=="none"&&b.autoHide){e.hide()}e.addClass(b.radioClass);if(b.useID&&c.attr("id")!=""){e.attr("id",b.idPrefix+"-"+c.attr("id"))}a(c).wrap(e);a(c).wrap(f);f=c.parent();e=f.parent();a(c).css("opacity",0).bind({"focus.uniform":function(){e.addClass(b.focusClass)},"blur.uniform":function(){e.removeClass(b.focusClass)},"click.uniform touchend.uniform":function(){if(!a(c).attr("checked")){f.removeClass(b.checkedClass)}else{var d=b.radioClass.split(" ")[0];a("."+d+" span."+b.checkedClass+":has([name='"+a(c).attr("name")+"'])").removeClass(b.checkedClass);f.addClass(b.checkedClass)}},"mousedown.uniform touchend.uniform":function(){if(!a(c).is(":disabled")){e.addClass(b.activeClass)}},"mouseup.uniform touchbegin.uniform":function(){e.removeClass(b.activeClass)},"mouseenter.uniform touchend.uniform":function(){e.addClass(b.hoverClass)},"mouseleave.uniform":function(){e.removeClass(b.hoverClass);e.removeClass(b.activeClass)}});if(a(c).attr("checked")){f.addClass(b.checkedClass)}if(a(c).attr("disabled")){e.addClass(b.disabledClass)}k(c)}function h(c){var d=a(c);var e=a("<div />"),f=a("<span />");if(!d.css("display")=="none"&&b.autoHide){e.hide()}e.addClass(b.checkboxClass);if(b.useID&&c.attr("id")!=""){e.attr("id",b.idPrefix+"-"+c.attr("id"))}a(c).wrap(e);a(c).wrap(f);f=c.parent();e=f.parent();a(c).css("opacity",0).bind({"focus.uniform":function(){e.addClass(b.focusClass)},"blur.uniform":function(){e.removeClass(b.focusClass)},"click.uniform touchend.uniform":function(){if(!a(c).attr("checked")){f.removeClass(b.checkedClass)}else{f.addClass(b.checkedClass)}},"mousedown.uniform touchbegin.uniform":function(){e.addClass(b.activeClass)},"mouseup.uniform touchend.uniform":function(){e.removeClass(b.activeClass)},"mouseenter.uniform":function(){e.addClass(b.hoverClass)},"mouseleave.uniform":function(){e.removeClass(b.hoverClass);e.removeClass(b.activeClass)}});if(a(c).attr("checked")){f.addClass(b.checkedClass)}if(a(c).attr("disabled")){e.addClass(b.disabledClass)}k(c)}function g(c){var d=a(c);var e=a("<div />"),f=a("<span />");if(!d.css("display")=="none"&&b.autoHide){e.hide()}e.addClass(b.selectClass);if(b.useID&&c.attr("id")!=""){e.attr("id",b.idPrefix+"-"+c.attr("id"))}var g=c.find(":selected:first");if(g.length==0){g=c.find("option:first")}f.html(g.html());c.css("opacity",0);c.wrap(e);c.before(f);e=c.parent("div");f=c.siblings("span");c.bind({"change.uniform":function(){f.text(c.find(":selected").html());e.removeClass(b.activeClass)},"focus.uniform":function(){e.addClass(b.focusClass)},"blur.uniform":function(){e.removeClass(b.focusClass);e.removeClass(b.activeClass)},"mousedown.uniform touchbegin.uniform":function(){e.addClass(b.activeClass)},"mouseup.uniform touchend.uniform":function(){e.removeClass(b.activeClass)},"click.uniform touchend.uniform":function(){e.removeClass(b.activeClass)},"mouseenter.uniform":function(){e.addClass(b.hoverClass)},"mouseleave.uniform":function(){e.removeClass(b.hoverClass);e.removeClass(b.activeClass)},"keyup.uniform":function(){f.text(c.find(":selected").html())}});if(a(c).attr("disabled")){e.addClass(b.disabledClass)}a.uniform.noSelect(f);k(c)}function f(c){var d=a(c);var e=a("<div>"),f=a("<span>");e.addClass(b.buttonClass);if(b.useID&&d.attr("id")!="")e.attr("id",b.idPrefix+"-"+d.attr("id"));var g;if(d.is("a")||d.is("button")){g=d.text()}else if(d.is(":submit")||d.is(":reset")||d.is("input[type=button]")){g=d.attr("value")}g=g==""?d.is(":reset")?"Reset":"Submit":g;f.html(g);d.css("opacity",0);d.wrap(e);d.wrap(f);e=d.closest("div");f=d.closest("span");if(d.is(":disabled"))e.addClass(b.disabledClass);e.bind({"mouseenter.uniform":function(){e.addClass(b.hoverClass)},"mouseleave.uniform":function(){e.removeClass(b.hoverClass);e.removeClass(b.activeClass)},"mousedown.uniform touchbegin.uniform":function(){e.addClass(b.activeClass)},"mouseup.uniform touchend.uniform":function(){e.removeClass(b.activeClass)},"click.uniform touchend.uniform":function(b){if(a(b.target).is("span")||a(b.target).is("div")){if(c[0].dispatchEvent){var d=document.createEvent("MouseEvents");d.initEvent("click",true,true);c[0].dispatchEvent(d)}else{c[0].click()}}}});c.bind({"focus.uniform":function(){e.addClass(b.focusClass)},"blur.uniform":function(){e.removeClass(b.focusClass)}});a.uniform.noSelect(e);k(c)}function e(b){a(b).addClass("uniform");k(b)}function d(b){$el=a(b);$el.addClass($el.attr("type"));k(b)}b=a.extend(a.uniform.options,b);var c=this;if(b.resetSelector!=false){a(b.resetSelector).mouseup(function(){function b(){a.uniform.update(c)}setTimeout(b,10)})}a.uniform.restore=function(b){if(b==undefined){b=a(a.uniform.elements)}a(b).each(function(){if(a(this).is(":checkbox")){a(this).unwrap().unwrap()}else if(a(this).is("select")){a(this).siblings("span").remove();a(this).unwrap()}else if(a(this).is(":radio")){a(this).unwrap().unwrap()}else if(a(this).is(":file")){a(this).siblings("span").remove();a(this).unwrap()}else if(a(this).is("button, :submit, :reset, a, input[type='button']")){a(this).unwrap().unwrap()}a(this).unbind(".uniform");a(this).css("opacity","1");var c=a.inArray(a(b),a.uniform.elements);a.uniform.elements.splice(c,1)})};a.uniform.noSelect=function(b){function c(){return false}a(b).each(function(){this.onselectstart=this.ondragstart=c;a(this).mousedown(c).css({MozUserSelect:"none"})})};a.uniform.update=function(c){if(c==undefined){c=a(a.uniform.elements)}c=a(c);c.each(function(){var d=a(this);if(d.is("select")){var e=d.siblings("span");var f=d.parent("div");f.removeClass(b.hoverClass+" "+b.focusClass+" "+b.activeClass);e.html(d.find(":selected").html());if(d.is(":disabled")){f.addClass(b.disabledClass)}else{f.removeClass(b.disabledClass)}}else if(d.is(":checkbox")){var e=d.closest("span");var f=d.closest("div");f.removeClass(b.hoverClass+" "+b.focusClass+" "+b.activeClass);e.removeClass(b.checkedClass);if(d.is(":checked")){e.addClass(b.checkedClass)}if(d.is(":disabled")){f.addClass(b.disabledClass)}else{f.removeClass(b.disabledClass)}}else if(d.is(":radio")){var e=d.closest("span");var f=d.closest("div");f.removeClass(b.hoverClass+" "+b.focusClass+" "+b.activeClass);e.removeClass(b.checkedClass);if(d.is(":checked")){e.addClass(b.checkedClass)}if(d.is(":disabled")){f.addClass(b.disabledClass)}else{f.removeClass(b.disabledClass)}}else if(d.is(":file")){var f=d.parent("div");var g=d.siblings(b.filenameClass);btnTag=d.siblings(b.fileBtnClass);f.removeClass(b.hoverClass+" "+b.focusClass+" "+b.activeClass);g.text(d.val());if(d.is(":disabled")){f.addClass(b.disabledClass)}else{f.removeClass(b.disabledClass)}}else if(d.is(":submit")||d.is(":reset")||d.is("button")||d.is("a")||c.is("input[type=button]")){var f=d.closest("div");f.removeClass(b.hoverClass+" "+b.focusClass+" "+b.activeClass);if(d.is(":disabled")){f.addClass(b.disabledClass)}else{f.removeClass(b.disabledClass)}}})};return this.each(function(){if(a.support.selectOpacity){var b=a(this);if(b.is("select")){if(b.attr("multiple")!=true){if(b.attr("size")==undefined||b.attr("size")<=1){g(b)}}}else if(b.is(":checkbox")){h(b)}else if(b.is(":radio")){i(b)}else if(b.is(":file")){j(b)}else if(b.is(":text, :password, input[type='email']")){d(b)}else if(b.is("textarea")){e(b)}else if(b.is("a")||b.is(":submit")||b.is(":reset")||b.is("button")||b.is("input[type=button]")){f(b)}}})}})(jQuery);

