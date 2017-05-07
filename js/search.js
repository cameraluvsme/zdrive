
(function($) {
	
	var winW = window.innerWidth;
	var winH = window.innerHeight;
	
	/**
	 *  絞り込み＆並べ替えタブ
	 */
	(function() {
		$('.tabs').find('.tab').click(function(e){
			e.preventDefault();
			
			changeTab($(this), $(this).parent('.tabs'));
		});
		
		updateTabs();
		$(window).on('resize', function(){
			if((!_ua.Tablet && !_ua.Mobile ) && (window.innerWidth !== winW || window.innerHeight !== winH)){
				updateTabs();
				
				winW = window.innerWidth;
				winH = window.innerHeight;
			}
		});
		
		$(window).focus(function(){
			setTimeout(function(){
				updateTabs();
			}, 100 );
		});
		
		function updateTabs() {
			//SP
			if( window.innerWidth <= 768 ) {
				$('.tabs').each(function(){
					var $tab = $(this).find('.tab');
					var $activeTab = $tab.filter('.on');
					changeTab($activeTab, $(this));
				});
			} 
			//PC
			else {
				$('.tab-content:not(.sp)').show(0);
				$('.tab-content.sp').hide(0);
			}
		}
		
		function changeTab($tab, $tabs) {
			
			if( !$(':visible', $tabs).length ) {
				return;
			}
			
			var $activeTab = $('.tab.on', $tabs);
			var targetId = $activeTab.data('id');
			
			if( !targetId ) {
				$('.tab', $tabs).each(function(){
					targetId = $(this).data('id');
					$(targetId).hide(0);
				});
			} else {
				$(targetId).hide(0);
			}
			
			$activeTab.removeClass('on');
			
			if( $tab.length > 0 && $tab.get(0) !== $activeTab.get(0) ) {
				targetId = $tab.data('id');
				$(targetId).show(0);
				$tab.addClass('on');
			}
			
			$(window).scroll();
		}
	})();	
	
	
	/**
	 *  セレクト連携
	 */
	(function() {
		$('[data-sync-clone]').each(function(){
			sync($(this));
		});
		$('[data-sync-clone], [data-sync-origin]').change(function(){
			sync($(this));
		});
		
		function sync($select) {
			var selectedIndex = $select.prop('selectedIndex');
			var $selectedOption = $select.find('option').eq(selectedIndex);
			var $syncTarget, $syncChild, group;
			
			if( $select.data('sync-clone') ) {
				$syncTarget = $($select.data('sync-clone'));
				$syncChild = $($select.data('sync-child'));
				
				if( $syncChild.length ) {
					$syncChild.empty();
					group = $selectedOption.data('group');
					
					if( group === undefined ) {
						$syncChild.append($selectedOption.clone(true));
					} else {
						$syncChild.append($select.find('option[data-group != "' + group + '"]').clone(true));
					}
					$syncChild.prop('selectedIndex', 0).trigger('change');
				}
			} else {
				$syncTarget = $($select.data('sync-origin'));
			}
			
			if( $syncTarget.prop('selectedIndex') !== selectedIndex ) {
				$syncTarget.prop('selectedIndex', selectedIndex).trigger('change');
			}
		}
	})();
	
	
	/**
	 *  エリア選択(個別に都道府県を選ぶ場合は最大10件まで)
	 */
	(function() {
		
		$('.check-limit-area').each(function(){
			var $applyWrap = $('.checked-limit', this);
			var $checkboxs = $applyWrap.find('input[type=checkbox]').not('.invalid, .disabled, :disabled').not($applyWrap.find('label.invalid').prev('input[type=checkbox]')).not('.invalid input[type=checkbox]');
			var $txt = $('.checked-limit-txt', this);
			var $btn = $('.checked-limit-btn', this);
			
			var check = function() {
				var len = $checkboxs.filter(':checked').length;
				
				//・11件以上チェックされたら非活性（ただし、すべてチェックされている場合は活性）
				var LIMIT_NUM = 11;
				var limit = len >= LIMIT_NUM && len !== $checkboxs.length;
				var disabled = limit;
				
				$txt.toggleClass('attention', limit);
				$btn.prop('disabled', disabled);
			};
			
			$(document).on('change', $checkboxs, function(e){
				e.preventDefault();
				check();
			});
			
			check();
		});
	})();
	
})(window.jQuery);
