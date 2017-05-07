

var App = {};

/**
 * スマホ、タブレット判定
 */
var _ua = (function(u){
  return {
	Tablet:(u.indexOf("windows") != -1 && u.indexOf("touch") != -1 && u.indexOf("tablet pc") == -1)
	  || u.indexOf("ipad") != -1
	  || (u.indexOf("android") != -1 && u.indexOf("mobile") == -1)
	  || (u.indexOf("firefox") != -1 && u.indexOf("tablet") != -1)
	  || u.indexOf("kindle") != -1
	  || u.indexOf("silk") != -1
	  || u.indexOf("playbook") != -1,
	Mobile:(u.indexOf("windows") != -1 && u.indexOf("phone") != -1)
	  || u.indexOf("iphone") != -1
	  || u.indexOf("ipod") != -1
	  || (u.indexOf("android") != -1 && u.indexOf("mobile") != -1)
	  || (u.indexOf("firefox") != -1 && u.indexOf("mobile") != -1)
	  || u.indexOf("blackberry") != -1
 };

})(window.navigator.userAgent.toLowerCase());


var flexSliderFirst = true;
var BREAKPOINT = 768;
var currentScrollY;

(function($) {
	var winW = window.innerWidth;
	var winH = window.innerHeight;

	/**
	 * Touch and Mouse Browser
	 */
	Modernizr.touch_exclude_mouse = (function() {
		if (Modernizr.touch == false) {
			return false;
		}
		var ua = navigator.userAgent.toLowerCase();
		if (ua.indexOf('iphone') > -1 || ua.indexOf('ipad') > -1 ||
			ua.indexOf('ipod') > -1 || ua.indexOf('android') > -1) {
			return true;
		}
		return false;
	})();


	/**
	 * hide address bar for iPhone
	 */
	if (Modernizr.touch) {
		if (window.location.hash.length === 0) {
			window.onload = function(){
				window.setTimeout('scrollTo(0,1)', 100);
			};
		}
	}


	/**
	 * scroll to content and scroll to page top
	 */
	(function() {
		var scrollToContent = function(e) {

			$('body').append($('<div></div>').addClass('iosfix'));
			window.setTimeout(function() {
				$('.iosfix').remove();
			}, 510);

			var anchor = '#' + $(this).attr('href').split('#')[1];
			var target = {
				top: 0,
				left: 0
			};
			var opt = {
				easing: 'swing',
				duration: 500
			};

			var $fancyBoxMobile = $('.fancybox-mobile:visible');
			var $fancyBoxOverlay = $('.fancybox-lock .fancybox-overlay:visible');

			var detectTop = function($anchor) {
				var targetOffsetTop;
				if( $fancyBoxMobile.length > 0 || $fancyBoxOverlay.length <= 0) {
					targetOffsetTop = $anchor.offset().top;
				} else {
					targetOffsetTop = ($anchor.offset().top + $fancyBoxOverlay.scrollTop()) - $(window).scrollTop();
				}
				return targetOffsetTop;
			};

			if (anchor === '#to-page-top' || anchor === '#') {
				$.scrollTo(0, 400, {easing:'easeInOutQuart'});
			}
			else if ($(anchor).length > 0) {
				target.top = detectTop($(anchor));

				if($(anchor).is('.scroll-offset')){
					target.top -= 40;
				}
				if($(anchor).is('.scroll-offset-100')){
					target.top -= 100;
				}
				
				if( ($(document).height() - target.top) < window.innerHeight ) {
					target.top = $(document).height() - window.innerHeight;
				}
				
				if( $fancyBoxMobile.length > 0 || $fancyBoxOverlay.length <= 0) {
					opt.onAfter = function(){
						$(window).scrollTop(target.top);
					};
					$.scrollTo(target, opt);
				} else {
					$fancyBoxOverlay.scrollTo(target, opt);
				}
			}

			return false;
		};
		$("a[href^='#'], area[href^='#']").not('.btn-modal').click(scrollToContent);
	})();



	/**
	 * rollover
	 */
	(function() {
		var rollover = {
			hoverin : function(){
				$(this).stop().animate({opacity:0.75});
			},
			hoverout: function(){
				$(this).stop().animate({opacity:1.0});
			}
		};
		$(document).on('mouseenter', '.rollover', rollover.hoverin);
		$(document).on('mouseleave', '.rollover', rollover.hoverout);
	})();


	/* Force to load css */
//	function loadCss(href, check) {
//		var head = document.getElementsByTagName('head')[0];
//		var link = document.createElement('link');
//		link.rel = 'stylesheet';
//		link.type = 'text/css';
//		link.href = href;
//		head.appendChild(link);
//	}


	/**
	 * flip-slider
	 */
	(function() {

		$('.flip-slider').each(function(){
			var $this = $(this);
			var $parent = $this.parent();
			var dist = $this.find('> *').outerWidth(true);
			var flipsnaps = Flipsnap($this.get(0), {
				distance: dist
			});
			var $flipItems = $(flipsnaps.element).find('li');
			var $currentItems;
			
			$this.data('fs', flipsnaps);
			
			$parent.after('<div class="sp ta-c"><ul class="locator clearfix"></ul></div>');
			var $locator = $parent.next().find('.locator');
			
			$parent.after('<div class="comment-wrap"></div>');
			var $commentWrap = $parent.next();
			updateComment($flipItems.eq(flipsnaps.currentPoint), $commentWrap);
			$parent.css('visibility', 'visible');
			
			$this.children().each(function(index){
				$locator.append('<li data-id="' + index + '"></li>');
			});

			var $locators = $('.locator');
			$locators.each(function(){
				$('li', this).first().addClass('on');
			});
			
			//click
			$('li', $locators).click(function(e) {
				var clickID = $(this).index();
				flipsnaps.moveToPoint(clickID);
				e.preventDefault();
			});
			
			//next,prev
			if( $this.children().length > 1 ) {
				$parent.append('<div class="prev disabled">prev</div><div class="next">next</div>');
				var $prev = $('.prev', $parent).click(function(e) {
					flipsnaps.toPrev();
					e.preventDefault();
				});
				var $next = $('.next', $parent).click(function(e) {
					flipsnaps.toNext();
					e.preventDefault();
				});
			}
			
			flipsnaps.bind('fspointmove', flipsnaps.element, function() {
				$locators.each(function(){
					$('li', this).filter('.on').removeClass('on');
					$('li', this).eq(flipsnaps.currentPoint).addClass('on');
				});
				
				$next.toggleClass('disabled', !flipsnaps.hasNext());
				$prev.toggleClass('disabled', !flipsnaps.hasPrev());
					
				updateComment($flipItems.eq(flipsnaps.currentPoint), $commentWrap);
			}, false);

			refreshSlide($this);
			
			

		});

		//リサイズ
		$(window).on('resize', function(){
			$('.flip-slider').each(function(){
				refreshSlide($(this));
			});
		});
		
		function updateComment($currentItems, $commentWrap) {
			$commentWrap.find('.comment').remove();
			
			var $comment = $currentItems.find('.comment');
			if( $comment.length > 0 ) {
				$commentWrap.append($comment.clone());
			}
		}
		
		function refreshSlide($this) {
			var w, h;
			var flipsnaps = $this.data('fs');

			flipsnaps.disableTouch = $('.locator:visible').length <= 0;

			if( flipsnaps.disableTouch ) {
				flipsnaps.moveToPoint(0);
			} else {
				w = $this.find('li').width();
				flipsnaps.distance = w;
				flipsnaps.refresh();
			}

			$('img.roll-clip').css({ clip:"rect(0px," + $('img.roll-clip').width() + "px," + $('img.roll-clip').height() + "0px,0px)" });
		}
	})();


	/**
	 * table addClass
	 */
	(function() {
		$('.table-tbody-even tbody:nth-child(even), .table-tr-even tbody tr:nth-child(even)').addClass('even');
		$('.table-tbody-odd tbody:nth-child(odd), .table-tr-odd tbody tr:nth-child(odd)').addClass('odd');
	})();


	/**
	 * table highlight
	 */
	(function() {
		var tableClickTime = (new Date).getTime();

		$('.table-highlight li, div.table-highlight').find('div, ul, dl').on('click', function(e){
			e.stopPropagation();

			var diff = (new Date).getTime() - tableClickTime;
			tableClickTime = (new Date).getTime();

			if( diff > 100 ) {
				var href = $(this).parents('li').find('.row-link').attr('href');
				var target = $(this).parents('li').find('.row-link').attr('target');

                if(href === undefined){
                    href = $(this).find('.row-link').attr('href');
                }
                
                if(href === undefined){
                    console.log('Link Error');
                }
                else{
                    if( target != '_blank' ){
                        location.href = href;
                    }
                    else{
                        window.open(href);
                        return false;
                    }
                }
			}
		});

		$('.table-highlight li, .table-highlight-tr li').find('a:not(.row-link), label').on('click', function(e){
			e.stopPropagation();
			tableClickTime = (new Date).getTime();
		});

	})();


	/**
	 * tile
	 */
	(function() {
		$(window).resize(function(){
			spTile();
			pcTile();
		});
		spTile();
		pcTile();
	})();
	function spTile() {
		$('.sp-tile').find('tbody tr').each( function(i, elm){
			$(this).find('th, td').css('height','auto');
			if( isSPSize() ) {
				var h1 = Math.max($(this).find('th').eq(0).outerHeight(true), $(this).find('td').eq(0).outerHeight(true));
				var h2 = Math.max($(this).find('th').eq(1).outerHeight(true), $(this).find('td').eq(1).outerHeight(true));
				$(this).find('th').eq(0).css('height',h1 + 'px');
				$(this).find('td').eq(0).css('height',h1 + 'px');
				$(this).find('th').eq(1).css('height',h2 + 'px');
				$(this).find('td').eq(1).css('height',h2 + 'px');
			}
		});
	}
	function pcTile() {
		$('.pc-tile').each(function(){
			var child = $(this).data('tile-item');
			var num = $(this).data('tile-num');
			if( !child ) {
				child = '> *';
			}

			var $tileItem = $(this).find(child);

			if( !num ) {
				num = 2;
			}

			if( isSPSize() ) {
				$tileItem.css('height', 'auto');
			} else {
				$tileItem.tile(num);
			}

		});
	}

	/**
	 * SP サイズフラグ
	 */
	$('body').data('isSPSize', isSPSize());
	$(window).on('resize', function(){
		var isSP = isSPSize();
		if( $('body').data('isSPSize') !== isSP ) {
			$('body').data('isSPSize', isSP);
			$(window).trigger('spSizeChange', isSP);
		}
	});


	/**
	 * トグル（アコーディオン）
	 */
	(function() {
		$('.btn-toggle, .toggle').parents('.dt').on('click', function(e){
			//SPディーラー検索
			if(!$(this).find('input').attr('type')){
				toggleAction(e,$(this),'dealer');
			}
		});
		$('.btn-toggle, .toggle').on('click', function(e){
			//SPの検索
			if(($(this).prev('label').hasClass('checkbox'))){
				toggleAction(e,$(this).parent('.dt'),'');
			}
			else{
				//既存トグル
				if($(this).parents('#dealer_search_area').get(0) == null ){
					//dealer_search_areaを除外
					toggleAction(e,$(this),'other');
				}
			}
		});
		/*****************************************************************************/

		$(window).on('spSizeChange', function(e, state){
			if( !state ) {
				$('.btn-toggle.sp.up, .toggle.sp.up').each(function(){
					var $this = $(this);
					var target = $this.data('target');
					var $target = $(target);

					$target.removeClass('show');
					$this.removeClass('up');

					if($this.data('down-text')) {
						$this.text( $this.data('down-text') );
					}

					$target.css({ 'height': '', 'max-height': '' });
				});
			}
		});
	})();

	function toggleAction(e,target_obj,kind){
		e.preventDefault();
	
		var $this = target_obj;
		var target;
		var maxH = 0;
		var state;
		var $state_target;
	
		if(kind == 'other' ){
			//既存処理
			target = $this.parent('div').next();
			$state_target = $this;
		}
		else if( kind == 'dealer' ){
			target = $this.next();
			$state_target = $this.find('a');
		}
		else{
			//ディーラー
			target = $this.next();
			$state_target = $this.find('a');
		}
	
		var $target = $(target);	//jqueryObject
	
		if( !target ) {
			$target = $this.next();
		}
	
		state = $state_target.hasClass('show');
		if( state ) {
			$state_target.removeClass('show');
			$state_target.removeClass('up');
	
			if($state_target.data('down-text')) {
				$state_target.text( $state_target.data('down-text') );
			}
			$target.css({ 'height': '', 'max-height': '' });
	
		} else {
			$state_target.addClass('show');
			$state_target.addClass('up');
			if($state_target.data('up-text')) {
				$state_target.text( $state_target.data('up-text') );
			}
	
			maxH = getMaxHeight($target);
			$target.css({ 'height': maxH, 'max-height': maxH });
		}
	
	}
	
	
	/**
	 * モーダルを縦中央にする
	 */
	function modalCenterY() {
		//縦中央
		var $wrap = $('.fancybox-wrap');
		var loopCnt = 0;
		var intervalID;
		var wrapH = 0;
		clearInterval(intervalID);
		if( $wrap.length > 0 ) {
			intervalID = setInterval(function(){
				var oldH = wrapH;
				wrapH = $wrap.outerHeight(false);
				var centerY = Math.max(60, (window.innerHeight * 0.5 - wrapH * 0.5));
				var top = $wrap.position().top;
				$wrap.css('top', centerY);
				if( oldH === wrapH ) {
					loopCnt++;
					if( loopCnt > 5 ) {
						clearInterval(intervalID);
					}
				} else {
					loopCnt = 0;
				}
			}, 20);
		}
	}
	
	/**
	 * リスト アコーディオン
	 */
	(function() {
		$(document).on('click', '.sp-list-accordion dt > a', function(e){
			if( isSPSize() ) {
				e.preventDefault();
			}
		});
		$(document).on('click', '.sp-list-accordion dt', function(e){
			//e.preventDefault();

			if( isSPSize() ) {
				var $dt = $(e.currentTarget);
				var $dd = $dt.next('dd');
				var $dl = $dt.parent('dl');
				var $parent = $dl.parents('.sp-list-accordion');
				var $show = $parent.find('dl.show').not($dl);
				var state = !$dl.hasClass('show');
				var maxH = 0;
				var timeOutID;
				var delay = 400;
				
				if( !$parent.hasClass('no-anime') ) {
					if( state ) {
						maxH = getMaxHeight($dd);
						$dd.css({ 'height': maxH, 'max-height': maxH });
					} else {
						$dd.css({ 'max-height': '' });
					}

					$show.find('dd').css({ 'max-height': '' });
				}
				
				if( $show.length ) {
					$show.removeClass('show');
					$parent.trigger('toggleChange', { 'state': false, 'target': $show });
				} else {
					delay = 0;
				}
				
				clearTimeout(timeOutID);
				timeOutID = setTimeout(function(){
					$dl.toggleClass('show', state);
					$parent.trigger('toggleChange', { 'state': state, 'target': $dl });
				}, delay );

				modalCenterY();

			}
		});

		$(window).on('spSizeChange', function(e, state){
			
		});
	})();
	
	
	/**
	 * モーダル内index-navスクロール追従
	 */
	(function() {
		var resetNav = function($indexNav){
			$indexNav.toggleClass('on', false);
			$indexNav.css('top', 0);
		};
		var update = function($parent){
			var $indexNav = $parent.find('.sp-list-accordion:visible .show .index-nav');
			var $dd = $parent.find('.sp-list-accordion:visible .show dd');
			var scrollTop = $parent.scrollTop();

			if( $indexNav.length > 0 ) {

				var maxH = getMaxHeight($dd);
				var initTop = $('.fancybox-mobile:visible').length ? $dd.offset().top : ($dd.offset().top - $(window).scrollTop()) + $parent.scrollTop();
				var limitTop = initTop + maxH - $indexNav.height() - 1;

				$indexNav.toggleClass('on', scrollTop > initTop);
				$indexNav.css('width', $indexNav.parent().outerWidth(true));

				if( scrollTop > limitTop ) {
					//var limit = Math.max( limitTop - scrollTop , limitTop - $('.fancybox-overlay').offset().top);
					var limit = limitTop - scrollTop;
					$indexNav.css('top', limit);
				} else {
					$indexNav.css('top', 0);
				}
			}
		};

		$(document).on('toggleChange', '.sp-list-accordion.has-index-nav', function(e, data){
			var $accordion = $(e.currentTarget);
			var $dd = data.target.find('dd');
			var $indexNav = $dd.find('.index-nav');
			if( data.state ) {
				resetNav($indexNav);
			} else {
				resetNav($accordion.find('.index-nav'));
			}
		});
		$(document).on('modalOpen', function(e, data){
			if( $(data.target.content).find('.sp-list-accordion.has-index-nav').length ) {
				var $scrollContainer = getScrollContainer();
				$scrollContainer.on('scroll', function(){
					update($scrollContainer);
				});
				update($scrollContainer);
			}
		});
		$(document).on('modalClose', function(e, data){
			if( $(data.target.content).find('.sp-list-accordion.has-index-nav').length ) {
				var $list = $('.sp-list-accordion.has-index-nav');
				resetNav($list.find('.show .index-nav'));
				//$list.find('.show').removeClass('show');
			}
		});
	})();


	/**
	 * スライドショー
	 */
	(function() {
		if($('#slide-wrapper').length) {
			var flexItemCnt = $('#slide-wrapper  ul.slides li').length;
			var $move_obj = $('<div id="slide_bar">').insertAfter('body').css('background','#fff').css('position','absolute').css('top',-10);

			$('#slide-wrapper').flexslider({
				animation: 'slide',
				pauseOnAction: false,
				slideshowSpeed: 5000,
				animationSpeed: 400,
				start: function(){
					resetFlexSliderNav();
				}

			});

			$(window).resize(function(){
				resetFlexSliderNav();
			});
		}
		function resetFlexSliderNav() {
			var width = $('#slide-wrapper').width();
			var newWidth = Math.floor(width / flexItemCnt);
			var nokori = Math.floor(width % flexItemCnt);

			$('.flex-control-paging li a').css('width' , newWidth);

			//animate objの初期位置を指定
			$move_obj.css('width' , newWidth)
				.css('height',$('.flex-control-paging li').eq(0).height())
				.css('z-index','3')
				.css('position','absolute')
				.css('top',$('.flex-control-paging li').eq(0).offset().top)
				.css('left',$('.flex-control-paging li').eq(0).offset().left);

			//iphoneは残りがある場合最後に出す
			if (nokori > 0) {
				$('.flex-control-paging li').eq(flexItemCnt - 2).find('a').css('width',(newWidth + nokori));
			}
		}
	})();


	/**
	 * カスタム select
	 */
	(function() {
		$('.select-wrap').each(function(i, elm) {
			var txt = $('select option:selected', this).text();
			$(this).prepend('<div class="val">' + txt + '</div>');
		});

		$('.select-wrap select').change(function(){
			var txt = $('option:selected', this).text();
			$(this).prev('.val').text(txt);
		});
	})();


	/**
	 *  モーダル
	 */
	(function() {

		if( $('.btn-modal').length ) {

			var fancyOption = {
					autoResize  : true,
					padding	    : 30,
					margin	    : [20, 20, 20, 20],
					fitToView	: true,
					width		: 'auto',
					height		: 'auto',
					autoCenter  : false,
					closeClick	: false,
					openEffect	: 'none',
					closeEffect	: 'none',
					openSpeed	: 0,
					closeSpeed	: 0,
					scrolling   : 'no',
					beforeShow  : function() {
						$.fancybox.reposition();
						updateModal(this);
						initSwipe($(this.inner));
						modalCenterY();
						
						$(this.wrap).css('visibility', 'hidden');
						
						currentScrollY = $( window ).scrollTop();

						$('#wrapper').css({
							position: 'fixed',
							width: '100%',
							top: -1 * currentScrollY
						});
					},
					afterShow  : function() {
						$.fancybox.reposition();
						$(document).trigger('modalOpen', {'target': this});

						var $wrap = $(this.wrap);
						setTimeout(function(){
							$wrap.css('visibility', 'visible');
						}, 300);
					},
					afterClose  : function() {
						updateModal(this);
						closeModal(this);
						$(document).trigger('modalClose', {'target': this});
						
						$( '#wrapper' ).attr( { style: '' } );
						$( 'html, body' ).prop( { scrollTop: currentScrollY } );
					},
					onUpdate    : function(){
						updateModal(this);
					}
			};

			$(window).on('resize', function(){
				if((!_ua.Tablet && !_ua.Mobile ) && (window.innerWidth !== winW || window.innerHeight !== winH)){
					winW = window.innerWidth;
					winH = window.innerHeight;
					$.fancybox.reposition();
				}
			});

			App.modalOpen = function(modalID){
				fancyOption = $.extend(true, { href : modalID }, fancyOption);
				$.fancybox(fancyOption);
			};

			$('.btn-modal').each(function(){
				$(this).fancybox(fancyOption);
			});

			var group = {};
			$('.btn-modal[data-fancybox-group][href ^= "#"]').each(function(){
				var $this = $(this);
				var rel = $this.data('fancybox-group');
				var modalID = $this.attr('href');

				if( !group[rel] ) {
					group[rel] = [];
				}

				var isAdd = modalID !== "#";

				$.each( group[rel], function(i, elm) {
					if( elm.attr('href') === $this.attr('href') ) {
						isAdd = false;
						return false;
					}
				});
				if( isAdd ) {
					group[rel].push($this);
				}
			});

			$.each( group, function(i, elm) {
				if( elm.length > 1 ) {
					$.each( elm, function(j, elm2) {
						var prevIndex = j-1;
						var nextIndex = j+1;

						prevIndex = prevIndex < 0 ? elm.length - 1 : prevIndex;
						nextIndex = nextIndex >= elm.length ? 0 : nextIndex;

						var $prev = elm[prevIndex];
						var $next = elm[nextIndex];
						var prevID = $prev.attr('href');
						var nextID = $next.attr('href');
						var html = '<div class="nav"><a href="' + prevID + '" class="btn-mdl-prev">Prev</a><a href="' + nextID + '"  class="btn-mdl-next">Next</a></div>';
						html += '<div class="mdl-num">' + (j+1) + '/' + elm.length + '</div>';

						$(elm2.attr('href')).append(html);
					});
				}
			});

			$(document).on('click', '.btn-mdl-prev, .btn-mdl-next', function(e){
				e.preventDefault();

				var id = $(this).attr('href');
				var option = $.extend(true, {}, fancyOption);
				option.content = $(id);
				$.fancybox(option);

				var $inner = $(this).parents('.fancybox-inner');
				initSwipe($inner);
			});

		}

		function initSwipe($elm){
			if($elm.find('.btn-mdl-prev').length){
				$elm.swipe('destroy');
				$elm.swipe( {
					swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
						if( direction === 'right' ) {
							$(this).find('.btn-mdl-prev').click();
						} else if( direction === 'left' ) {
							$(this).find('.btn-mdl-next').click();
						}
						return false;
					},
					threshold:0
				});
			}
		}

		function updateModal(obj){
			var $wrap = $(obj.wrap);
			var $inner = $(obj.inner);
			var $skin = $(obj.skin);
			var $modal = $inner.find('.modal');
			var height = $modal.height();
			var width = $inner.find('.modal-damage').length ? $inner.find('.fit img, .fit2 img').width() : $modal.width();
			var padding = 30;
			var borderW = parseInt($wrap.css('border-top-width'));
			borderW = isNaN(borderW) ? 0 : borderW;
			
			obj.minHeight = height;
			$wrap.css({'width': width + (padding * 2)});
			$inner.css({'height': 'auto', 'width': width - (borderW * 2), 'overflow':'visible'});
			$skin.css('padding', padding);
			$.fancybox.reposition();
		}

		function closeModal(obj){
			var $modal = $(obj.content);
			var $form = $modal.find('form');
			if( $modal.data('close-reset') && $form.length ) {
				$form[0].reset();
			}
		}

	})();


	/**
	 *  ページ下部固定
	 */
	(function() {
		var $pageTop = $('.to-page-top');
		var $nav, scrollY, diff;

		if( $pageTop.length > 0 ) {
			$(window).load(function(){
				$(window).scroll(function () {
					$nav = $('.sp-fixed:visible');
					scrollY = $(window).scrollTop() + window.innerHeight;
					diff = scrollY - $pageTop.offset().top;
					$nav.toggleClass('off', diff > 0);
				});
				$(window).scroll();
			});
		}
	})();


	/**
	 *  ページ上部固定
	 */
	(function() {
		var $fixed = $('.fixed-top');
		var scrollY, initTop;

		if( $fixed.length > 0 ){
			initTop = $fixed.offset().top;

			$(window).load(function(){
				$(window).scroll(function () {
					if( !isSPSize() ) {
						if(!$fixed.hasClass('on') && initTop !== $fixed.offset().top) {
							initTop = $fixed.offset().top;
						}

						$fixed = $fixed.filter(':visible');
						$fixed.toggleClass('on', $(window).scrollTop() > initTop);
					} else {
						if( $fixed.hasClass('on') ) {
							$fixed.toggleClass('on', false);
						}
					}
				});
				$(window).scroll();
			});
		}
	})();


	/**
	 * チェックボックスとボタン連動
	 */
	(function() {
		$('[data-sync-checked]').each(function(){
			var $this = $(this);
			var $checkboxs = $($this.data('sync-checked'));
			var checkedMax = $this.data('sync-checked-max');
			var checkedMaxAll = $this.data('sync-checked-max-all');
			var $checkboxAll = $($this.data('sync-checked-all'));
			var $clone = $($this.data('sync-checked-clone'));
			var $text = $($this.data('sync-checked-text'));

			if( checkedMax === undefined ) {
				checkedMax = $checkboxs.length;
			}
			var sync = function() {
				var lenMax = checkedMax;
				
				if( checkedMaxAll && $checkboxAll.prop('checked') ) {
					lenMax = $checkboxs.length;
				}
				
				var len = $checkboxs.filter(':checked').length;
				var checked = len > 0 && len <= lenMax;
				
				$this.prop('disabled', !checked);

				if( $clone.length > 0 ) {
					$clone.prop('disabled', !checked);
				}
				if( $text.length > 0 ) {
					$text.toggleClass('attention', !checked && len > 0);
				}
			};
            //ロード時の処理
            setTimeout(function(){
                sync();
            },100);
            //チェックボックス変化時の処理
			$(document).on('change', [$checkboxAll.selector, $checkboxs.selector], function(e){
                e.preventDefault();
                sync();
			});

			sync();
		});
	})();

	/**
	 * チェックボックス連動
	 */
	(function() {
		var isAllChecked = function($applyCb) {
			var isChecked = true;
			$applyCb.each(function(){
				if( !$(this).prop('checked') ) {
					isChecked = false;
					return false;
				}
			});
			return isChecked;
		};
		var getApplyCb = function($parentCb, dataName) {
			var $applyWrap = $($parentCb.data(dataName));
			var $applyCb = $applyWrap.length ? $applyWrap.find('input[type=checkbox]').not($parentCb).not('.invalid, .disabled, :disabled').not($applyWrap.find('label.invalid').prev('input[type=checkbox]')).not($('.invalid, .disabled').find('input[type=checkbox]')) : [];
			return $applyCb;
		};

		$('[data-apply-checked]').each(function(){
			var $parentCb = $(this);
			var $applyCb = getApplyCb($parentCb, 'apply-checked');

			if( $applyCb.length ) {
				$parentCb.on('change', function(e){
					e.preventDefault();
					$applyCb.prop('checked', $parentCb.prop('checked'));

					if( $(this).data('parent-checkboxs') ) {
						$.each( $(this).data('parent-checkboxs'), function(i, $elm){

							if( isAllChecked(getApplyCb($elm, 'apply-checked')) ) {
								$elm.prop('checked', true);
							}
						});
					}
				});

				$applyCb.each(function(){
					var cbs = $(this).data('parent-checkboxs');
					if( !$.isArray(cbs) ) {
						cbs = [];
					}
					cbs.push($parentCb);
					$(this).data('parent-checkboxs', cbs);
				});

				$applyCb.on('change', function(e){
					e.preventDefault();

					if( !$(this).prop('checked') ) {
						$.each( $(this).data('parent-checkboxs'), function(i, $elm){
							$elm.prop('checked', false);
						});
					} else {
						if( isAllChecked($applyCb) ) {
							$parentCb.prop('checked', true).trigger('change');
						}
					}
				});
			}
		});

		$('[data-not-apply-checked]').each(function(){
			var $parentCb = $(this);
			var $applyCb = getApplyCb($parentCb, 'not-apply-checked');

			$applyCb.on('change', function(e){
				e.preventDefault();
				var reverseCb = $(this).data('reverse-checkboxs');
				if( reverseCb ) {
					if( $(this).prop('checked') ) {
						$.each( reverseCb, function(i, $elm){
							$elm.prop('checked', false);
						});
					} else {
						var isChecked = false;
						$applyCb.each(function(){
							if( $(this).prop('checked') ) {
								isChecked = true;
								return false;
							}
						});

						if( !isChecked ) {
							$.each( $(this).data('reverse-checkboxs'), function(i, $elm){
								$elm.prop('checked', true);
							});
						}
					}
				}
			});
		});
		$('[data-not-apply-checked]').on('change', function(e){
			e.preventDefault();

			var $parentCb = $(this);
			var $applyWrap = $($parentCb.data('not-apply-checked'));
			var $applyCb = $applyWrap.length ? $applyWrap.find('input[type=checkbox]').not(this) : [];
			if( $parentCb.prop('checked') && $applyCb.length) {
				$applyCb.prop('checked', false);
			}

			$applyCb.each(function(){
				var cbs = $(this).data('reverse-checkboxs');
				if( !$.isArray(cbs) ) {
					cbs = [];
				}
				cbs.push($parentCb);
				$(this).data('reverse-checkboxs', cbs);
			});
		});
	})();


	/**
	 * チェックボックス+labelに.invlaidを付与
	 */
	(function() {
		var applyInvalid = function($parentCb){
			var $applyWrap = $($parentCb.data('apply-invalid'));
			var $applyCb = $applyWrap.length ? $applyWrap.find('input[type=checkbox]').not($parentCb).not('.fix-invalid') : [];

			if( $applyCb.length ) {
				var checked = $parentCb.prop('checked');
				if( checked ) {
					$applyCb.prop('checked', !checked);
					$applyCb.trigger('change');
				}
				/*$applyCb.toggleClass('invalid', checked);*/
			}
		};
		$('[data-apply-invalid]').on('change', function(e){
			e.preventDefault();
			applyInvalid( $(this) );
		});
		$('[data-apply-invalid]').each(function(){
			applyInvalid( $(this) );
		});
	})();


	/**
	 * チェックボックスをすべてcheckedかつinvalidにする
	 */
	(function() {
		var setChecked = function($parentCb){
			var $parentWrap = $parentCb.parents('ul');
			var $allCb = $($parentCb.data('all-checked')).find('input[type=checkbox]');
			var $applyCb = $parentWrap.length ? $parentWrap.find('input[type=checkbox]').not($parentCb).not('.fix-invalid') : [];

			if( $applyCb.length ) {
				var checked = $parentCb.prop('checked');
				if( checked ) {
					$applyCb.filter(':checked').prop('checked', false);
					$applyCb.trigger('change');
				}

				$applyCb.prop('checked', checked).prop('disabled', checked);

				$applyCb.each(function(){
					var $sameCb = $allCb.not(this).filter('[name = "' + $(this).attr('name') + '"]');
					$sameCb.prop('checked', checked).prop('disabled', checked);
				});
			}
		};
		$('[data-all-checked]').on('change', function(e){
			e.preventDefault();
			setChecked( $(this) );
		});
		$('[data-all-checked]').each(function(){
			setChecked( $(this) );
		});
	})();


	/**
	 * リスト連動
	 */
	(function() {
		var sync = function($elm, $syncList){
			if( $elm.length === 0 ) {
				return;
			}

			var $parentList = $elm.parents('[data-sync-list]');
			var $oldItem = $parentList.find('.selected');
			$oldItem.removeClass('selected');
			$elm.addClass('selected');

			var $allItem = $syncList.find('> *');
			var $selectItem = $syncList.find($elm.data('id'));
			$allItem.removeClass('show');
			$selectItem.addClass('show');
		};

		$('[data-sync-list]').each(function(){
			var $thisList = $(this);
			var $syncList = $($thisList.data('sync-list'));
			var $items = $thisList.find('[data-id]');
			$items.on('click', function(e){
				e.preventDefault();
				sync($(this), $syncList);
				$syncList.scrollTop(0);
			});
			sync($items.filter('.selected'), $syncList);


			$(document).on('toggleChange', $syncList.selector , function(e, data){
				var $dl = data.target;
				var $siblingList = $('[data-sync-list = "#' + $(e.currentTarget).attr('id') + '"]');
				var $target = $siblingList.find('[data-id = "#' + $dl.attr('id') + '"]');

				if( data.state ) {
					$siblingList.find('.selected').removeClass('selected');
				}

				$target.toggleClass('selected', data.state);
			});
		});


	})();


	/**
	 * アイテムカウント
	 */
	(function() {
		$('[data-count-list]').each(function(){
			var $this = $(this);
			var $elements = $($this.data('count-list'));
			var $syncElements = $($elements.data('sync-items'));
			var $checkboxs = $syncElements.find('input[type=checkbox]');
			var limitMax = $this.data('limit-max');
			var txt = limitMax ? '/' + limitMax : '';

			var $txt = $($this.data('limit-txt'));
			var $btn = $($this.data('limit-btn'));


			var sync = function() {
				var len = $elements.children().length;
				var flg = len >= limitMax;
				var $notChecked = $checkboxs.not(':checked').not('.fix-invalid');
				$this.text(len + txt);

				//リミット
				$txt.toggleClass('attention', flg);
				/*$btn.toggleClass('disabled', flg);*/
				$notChecked.next().toggleClass('invalid', flg);
			};

			$elements.on('change', function(e){
				e.preventDefault();
				sync();
			});

			sync();
		});
	})();


	/**
	 * リストアイテム同期
	 */
	(function() {
		$('[data-sync-items]').each(function(){
			var $this = $(this);
			var $list = $($this.data('sync-items'));
			var $syncCb = $list.length ? $list.find('input[type=checkbox]') : [];

			$(document).on('change', $syncCb.selector, function(e){
				e.preventDefault();

				var $target = $(e.currentTarget);
				var checked = $target.prop('checked');
				var val = $target.data('value');
				var name = $target.attr('name');
				var $item = $this.find('[data-name = "' + name + '"]');
				var $sameCb = $list.find('[name = "' + name + '"]');

				$sameCb.prop('checked', checked);

				if( checked ) {
					if( $item.length === 0 ) {
						$this.append( '<li data-name="' + name + '">' + val + '<a href="javascript:void(0);">×</a></li>' );
						$this.trigger('change');
					}
				} else {
					if( $item.length > 0 ) {
						$item.remove();
						$this.trigger('change');
					}
				}

			});
		});

		$(document).on('click', '[data-sync-items] li a' , function(e){
			e.preventDefault();

			var $target = $(e.currentTarget);
			var $item = $target.parent('li');
			var name = $item.data('name');
			var $parent = $item.parents('[data-sync-items]');
			var $list = $($parent.data('sync-items'));
			var $syncCb = $list.length ? $list.find('input[type=checkbox]') : [];
			var $cb = $syncCb.filter('[name = ' + name + ']');

			if( $cb.length ) {
				$cb.prop('checked', false);
				$cb.trigger('change');
			} else {
				$item.remove();
				$parent.trigger('change');
				$list.trigger('change');
			}

		});

		$(document).on('click', '[data-reset-items]', function(e){
			e.preventDefault();

			var $target = $(e.currentTarget);
			var $list = $($target.data('reset-items'));
			var $items = $list.children();

			$items.find('a').trigger('click');
			$list.trigger('change');
		});

	})();


	/* トップ都道府県選択セレクトボックスとボタン連動*/

	(function() {
		$('[data-sync-selected]').each(function(){
			var $this = $(this);
			var $selects = $($this.data('sync-selected'));

			var sync = function() {
                var oncl = "location.href='/U-Car/dealer_search_map/'";
//              var disabled = false;
				$selects.each(function() {
					if( $(this).prop('selectedIndex') === 0 ) {
//						disabled = true;
                        oncl = 'javascript:void(0);';
						return;
					}
				});
//				$this.prop('disabled', disabled);
                $this.attr('onclick',oncl);
			};

			$(document).on('change', $selects.selector, function(e){
				e.preventDefault();
				sync();
			});

			sync();
		});
	})();


	/**
	 *  ブロック要素伸縮
	 */
	(function() {
		$('.liquid-fit').each(function(){
			$(this).data('init-width', $(this).width());
		});
		$(window).on('resize', function(){
			$('.liquid-fit').each(function(){
				var $this = $(this);
				var $parent = $this.parent();
				var parentW = $parent.width();
				var thisW = $this.data('init-width');
				var zoom = parentW / thisW;

				if( zoom < 1 ) {
					$this.css('zoom', zoom);
				} else {
					$this.css('zoom', '');
				}
			});
		});
		$(window).resize();
	})();


	/**
	 *  デバッグ用
	 */
	function _log(txt) {
		$('#debug').html( txt + "<br>" + $('#debug').html());
	}
	function _initDebug() {
		$('body').prepend('<div id="debug" style="overflow: auto; position:fixed; top:50px; left: 10px; border:1px solid #000; background: #fff; z-index: 9900; padding: 5px; height: 400px; width: 150px;"></div>');
	}

	/**
	 *  input[type=number]のホイール動作を無効化
	 */
	$('form').on('focus', 'input[type=number]', function (e) {
		$(this).on('mousewheel.disableScroll', function (e) {
			e.preventDefault();
		});
	});
	$('form').on('blur', 'input[type=number]', function (e) {
		$(this).off('mousewheel.disableScroll')
	});

	/** フォームクリア*/
	$("#inputClearBtn").on("click", function() {
		clearInputInfo();
	});
	var clearInputInfo = function () {
		$("#mokujiTable tbody > tr").each(function() {
			$(this).find('input.komoku').val('');
			$(this).find('input.tag').val('');
		});
	};

	/**
	 *  画像リンク切れ対応
	 */
	$('[data-noimage]').each(function(){
		var $img = $(this).prop('tagName') === 'IMG' ? $(this) : $(this).find('img');
		var src = $(this).data('noimage');

		if( src !== '' ) {
			$img.filter('[src = ""]').attr('src' , src);
			$img.on('error', function(){
				$(this).attr('src', src);
			})
			.each(function() {
				$(this).attr('src',$(this).attr('src'));
			});
		}
	});

	/**
	 *  レスポンシブ判定
	 */
	function isSPSize() {
		return window.innerWidth <= BREAKPOINT;
	}


	/**
	 *  スクロール要素取得
	 */
	function getScrollContainer() {
		return $('.fancybox-mobile:visible').length ? $(document) : $('.fancybox-lock .fancybox-overlay');
	}

	/**
	 *  要素の最大高さ取得
	 */
	function getMaxHeight($elm) {
		var maxHeight = $elm.css('max-height');
		var display = $elm.css('display');
		
		$elm.css('max-height', 'none');
		$elm.css('display', 'block');
		
		var maxH = $elm.outerHeight(true);
		
		$elm.css('max-height', maxHeight);
		$elm.css('display', display);
		
		$elm.children().each(function(){
			var h = $(this).outerHeight(true);
			if( maxH < h ) {
				maxH = h;
			}
		});
		
		return maxH;
	}
})(window.jQuery);

/**
*  mylistページ初期モーダル with Cookie
*/
/********************************/
//  メイン処理
/********************************/
function CookieModalOpen(key,target,max_age){
    
    var cookie_flg = check_cookie(key);
    if( cookie_flg === 1){
        //cookieがあれば何もしない
    }
    else if(cookie_flg === 0){
        //cookieがなかった場合のの
        set_cookie(key,1,max_age);
        cookie_modal_open(target);
    }
}
/********************************/
//  Cookieの設定
/********************************/
function set_cookie(key,value,max_age){
    if(max_age === undefined){
        //期限が設定されていなければ90日
        max_age = 90;
    }
    var date = new Date();
    date.setDate( date.getDate() + max_age );
    var cookie = key + '=' + value + '; expires=' + date.toGMTString();

    document.cookie = cookie;
    return;
}
/********************************/
//  モーダルを開く
/********************************/
function cookie_modal_open(target){
    $target = $(target);
    $.fancybox($target);
    return;
}
/********************************/
//  Cookieのチェック
/********************************/
function check_cookie(key){
    var all_cookies = new Array();
    all_cookies = get_all_cookies();
    var flg = 0;
    for(var k in all_cookies){
        if(k === key){
            return 1;
        }
    }
    return 0;
}
/********************************/
//  Cookieデータを連想配列で取得
/********************************/
function get_all_cookies(){
    var result = new Array();

    var allcookies = document.cookie;
    if( allcookies != '' )
    {
        var cookies = allcookies.split( '; ' );

        for( var i = 0; i < cookies.length; i++ )
        {
            var cookie = cookies[ i ].split( '=' );

            // クッキーの名前をキーとして 配列に追加する
            result[ cookie[ 0 ] ] = decodeURIComponent( cookie[ 1 ] );
        }
    }

    return result;
}


/********************************/
//  お気に入りボタン
/********************************/
function favTggl(_this){
	var $this = $(_this);
	var $textHolder = $this.find('span').length ? $this.find('span') : $this;
	
	if(!$this.hasClass('stared')){
		$textHolder.text('お気に入り登録済');
		$this.addClass('stared').removeClass('star');
	}
	else{
		$textHolder.text('お気に入り追加');
		$this.addClass('star').removeClass('stared');
	}
}
function favHover( _this, kind ){
	var status;
	var REGISTERED		= 'registered';		//お気に入り登録済み
	var UNREGISTERED	= 'unregistered';	//未登録
	var $this = $(_this);
	var $textHolder = $this.find('span').length ? $this.find('span') : $this;
	
	if(!$this.hasClass('stared')){
			status = UNREGISTERED;
	}
	else{
			status = REGISTERED;
	}
	if( kind === 'on' ){
			//マウスオン
			if(status == REGISTERED){
					//登録済み
					$textHolder.text('お気に入り解除');
			}
	}
	else{
			//マウスアウト
			if(status == REGISTERED){
					//登録済み
					$textHolder.text('お気に入り登録済');
			}
	}
}

