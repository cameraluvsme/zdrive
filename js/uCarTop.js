// U-Carトップ画面用js

// API戻り値
var API_RESULT_SUCCESS = '00';

// メーカー・車名ダイアログ用
var LABEL_NO_SELECTED = 'メーカー・車名を選択';
var CAR_NAME_ROW_ITEMS = [
				['英数', 'alpha-line', '英数'],
				['ア', 'a-line', 'ア行'],
				['カ', 'k-line', 'カ行'],
				['サ', 's-line', 'サ行'],
				['タ', 't-line', 'タ行'],
				['ナ', 'n-line', 'ナ行'],
				['ハ', 'h-line', 'ハ行'],
				['マ', 'm-line', 'マ行'],
				['ヤ', 'y-line', 'ヤ行'],
				['ラ', 'r-line', 'ラ行'],
				['ワ', 'w-line', 'ワ行'],
				['その他', 'other-line', 'その他']
			];

// 都道府県用
var LABEL_NOT_SELECTED = '地域を選択';
var AREA_ALL_ITEMS = ['00'];//全国
var AREA_PREF_MAP = {
	'area-hokaido'  : [ '01' ],
	'area-tohoku'   : [ '02', '05', '03', '06', '04', '07',],
	'area-kanto'    : [ '09', '10', '08', '11', '13', '12', '14' ],
	'area-chubu'    : [ '15', '16', '17', '18', '20', '19', '21', '22', '23' ],
	'area-kinki'    : [ '25', '24', '26', '27', '29', '30', '28' ],
	'area-chugoku'  : [ '32', '34', '35', '31', '33' ],
	'area-sikoku'   : [ '38', '39', '37', '36' ],
	'area-kyusyu'   : [ '40', '41', '44', '43', '42', '45', '46', '47' ],
};

// 初期処理
$(function() {
	// ブラウザバック時処理(モーダルリンク)
	// メーカー・車名ラベル編集
	if ($('#Cn_label').val() != '' && $('#Cn_label').val() != LABEL_NO_SELECTED) {
		// ラベルの復元
		$('#select-Cn').text($('#Cn_label').val());
		// ボタン、リンク表示切替
		$('#search-maker-text').css('display', 'block');
		$('#search-maker-btn').css('display', 'none');
	}
	// 地域・都道府県ラベル編集
	if ($('#Ar_label').val() != '' && $('#Ar_label').val() != LABEL_NOT_SELECTED) {
		// ラベルの復元
		$('#select-Ar').text($('#Ar_label').val());
		// ボタン、リンク表示切替
		$('#search-area-text').css('display', 'block');
		$('#search-area-btn').css('display', 'none');
	}

	// ブラウザバック時処理(住所から検索 リストボックス)
	if ($('#uCarDealerSearchmapAr').val() != '') {
		// リストボックスの初期値設定(IE用)
		$('#address01').val($('#uCarDealerSearchmapAr').val()).prop('selected', true);
		$('#address01').parent('div').find('.val').html($('#address01 :selected').text());
		// 市区町村設定
		setCitys(true)
	}

	// 価格帯、T-Valueのテキストエリア設定(IE用)
	$('#price-min').parent('div').find('.val').html($('#price-min :selected').text());
	$('#price-max').parent('div').find('.val').html($('#price-max :selected').text());
	$('#tvalue').parent('div').find('.val').html($('#tvalue :selected').text());

});

// ボタン制御
/* メーカー・車名を選択ボタン(リンク)押下時 */
$('.select-Cn').on('click', function() {
	// メーカー・車名取得
	getMakerCarNames();
});

/* 地域を選択ボタン(リンク)押下時 */
$('.select-Ar').on('click', function() {
	// 地域・都道府県取得
	getAreas();
});

/* 「条件を指定して中古車を探す」の検索ボタン押下時 */
$('form#form_search').submit(function() {
	beforeSearchSubmit();
	clearBlankParam($('form#form_search'));
	return false;
});

/* 「住所から検索」の検索ボタン押下時 */
$('form#form_select_blanch').click(function() {
	if (makeSearchWord()) {
		$('#form_select_blanch').submit();
	}
	return false;
});

/* 検索キーワード設定 */
function makeSearchWord() {

	var ArNm = $('#address01 :selected').text();
	var CcNm = $('#address02 :selected').text();
	var OcNm = $('#address03 :selected').text();

	if (ArNm === "都道府県" || CcNm == "市区町村") {
		return false;
	}
	if (OcNm === "大字・町") {
		OcNm = ""
	}

	$('#searchWord').val(ArNm + CcNm + OcNm);

	return true;
}

// リストボックス制御
/* 都道府県選択時 */
$('#address01').on('change', function() {
	// hiddenパラメータにセット
	$('#uCarDealerSearchmapAr').val($('#address01').val());
	// 市区町村設定
	setCitys();
	// 大字・町初期化
	initOazas();
	// リストボックスの初期値設定
	$('#address02').val('').prop('selected', true);
	$('#address02').parent('div').find('.val').html($('#address02 :selected').text());
	$('#address03').val('').prop('selected', true);
	$('#address03').parent('div').find('.val').html($('#address03 :selected').text());
	// hiddenパラメータの初期値設定
	$('#uCarDealerSearchmapCc').val('');
	$('#uCarDealerSearchmapOc').val('');

});

/* 市区町村設定処理 */
function setCitys(ccSetFlg) {
	// リストボックス(都道府県)の初期設定
	var optCitys = '<option value="">市区町村</option>';

	// 検索ボタンdisable(main.js セレクトボックスとボタン連動 部にて実装)

	if ($('#address01').val() != '') {
		// リストボックスの非活性化
		disabledAddress(true);

		// 検索ノード条件の設定
		var nodes = new TMIAdrapi.SearchNodes({ndBase: true});

		// 検索オプションの設定
		var options = {};
		options.prefCd = $('#address01').val();
		options.sort = TMIAdrapi.Sort.JAPANESE_ASC; // 50音順
		options.nodes = nodes;						// 取得ノード

		// TMIAdrapi.CodeQueryクラスのインスタンス生成
		var query = new TMIAdrapi.CodeQuery(options);
		// 住所絞り込み検索実行
		var search = new TMIAdrapi.Search();
		search.searchByCode(query, function(result, error) {
			if (error) {
				// 検索失敗時の処理
			} else if (result) {
				// 検索成功時の処理
				for (var i=0;i<result.items.length;i++) {
					optCitys += '<option value="' + result.items[i].codes.city + '"';
					if ($('#address02').val() == null && $('#uCarDealerSearchmapCc').val() == result.items[i].codes.city) {
						optCitys += ' selected="selected"';
						$('#address02').parent('div').find('.val').html(result.items[i].names.city);
					}
					optCitys += '>' + result.items[i].names.city + '</option>';
				}
			}
			// リストボックス(都道府県)の値を設定
			$('#address02').html(optCitys);
			// 市区町村の再設定(ブラウザバック対応)
			if (ccSetFlg != null && $('#uCarDealerSearchmapCc').val() != '') {
				// リストボックスの初期値設定(IE用)
				$('#address02').val($('#uCarDealerSearchmapCc').val()).prop('selected', true);
				$('#address02').parent('div').find('.val').html($('#address02 :selected').text());
				if ($('#uCarDealerSearchmapCc').val() != '') {
					// 大字・町設定
					setOazas(true);
				} else {
					// 大字・町初期化
					initOazas();
				}
				// 検索ボタンenable(IE用)
				$('input[name=btn-search-shop]').prop('disabled', false);
			}
			// リストボックスの非活性化解除
			disabledAddress(false);
		});
	} else {
		// リストボックス(都道府県)の値を設定
		$('#address02').html(optCitys);
	}

}

/* 大字・町初期化処理 */
function initOazas() {
	// リストボックス(大字・町)の初期設定
	var optOazas = '<option value="">大字・町</option>';

	// リストボックス(大字・町)の値を設定
	$('#address03').html(optOazas);

}

/* 市区町村選択時 */
$('#address02').on('change', function() {
	// hiddenパラメータにセット
	$('#uCarDealerSearchmapCc').val($('#address02').val());
	// 大字・町設定
	setOazas();
	// リストボックスの初期値設定
	$('#address03').val('').prop('selected', true);
	$('#address03').parent('div').find('.val').html($('#address03 :selected').text());
	// hiddenパラメータの初期値設定
	$('#uCarDealerSearchmapOc').val('');

});

/* 大字・町設定処理 */
function setOazas(ocSetFlg) {
	// リストボックス(大字・町)の初期設定
	var optOazas = '<option value="">大字・町</option>';

	if ($('#address02').val() != '') {
		// リストボックスの非活性化
		disabledAddress(true);

		// 検索ノード条件の設定
		var nodes = new TMIAdrapi.SearchNodes({ndBase: true});

		// 検索オプションの設定
		var options = {};
		options.prefCd = $('#address01').val();
		if($('#address02').val() == null) {
			// ブラウザバックした場合はhidden値から取得
			options.cityCd = $('#uCarDealerSearchmapCc').val();
		} else {
			options.cityCd = $('#address02').val();
		}
		options.sort = TMIAdrapi.Sort.CODE_ASC;		// コード昇順(現行踏襲)
		options.nodes = nodes;						// 取得ノード

		// TMIAdrapi.CodeQueryクラスのインスタンス生成
		var query = new TMIAdrapi.CodeQuery(options);
		// 住所絞り込み検索実行
		var search = new TMIAdrapi.Search();
		search.searchByCode(query, function(result, error) {
			if (error) {
				// 検索失敗時の処理
			} else if (result) {
				// 検索成功時の処理
				// 紐付く大字・町が存在する場合
				if (result.items) {
					for (var i=0;i<result.items.length;i++) {
						optOazas += '<option value="' + result.items[i].codes.oaza + '"';
						if ($('#address03').val() == null && $('#uCarDealerSearchmapOc').val() == result.items[i].codes.oaza) {
							optOazas += ' selected="selected"';
							$('#address03').parent('div').find('.val').html(result.items[i].names.oaza);
						}
						optOazas += '>' + result.items[i].names.oaza + '</option>'
					}
				}
			}
			// リストボックス(大字・町)の値を設定
			$('#address03').html(optOazas);
			// 大字・町の再設定(ブラウザバック対応)
			if (ocSetFlg != null && $('#uCarDealerSearchmapOc').val() != '') {
				// リストボックスの初期値設定(IE用)
				$('#address03').val($('#uCarDealerSearchmapOc').val()).prop('selected', true);
				$('#address03').parent('div').find('.val').html($('#address03 :selected').text());
			}
			// リストボックスの非活性化解除
			disabledAddress(false);
			// 検索ボタンenable(main.js セレクトボックスとボタン連動 部にて実装)

		});
	} else {
		// リストボックス(大字・町)の値を設定
		$('#address03').html(optOazas);
		// 検索ボタンdisable(main.js セレクトボックスとボタン連動 部にて実装)

	}

}

/* 大字・町選択時 */
$('#address03').on('change', function() {
	// hiddenパラメータにセット
	$('#uCarDealerSearchmapOc').val($('#address03').val());

});

/* API通信完了の間リストボックスを非活性にするため実装 */
function disabledAddress(propVal) {

	if (propVal != false) {
		$('#address01').parent('div.select-wrap').addClass('invalid');
		$('#address02').parent('div.select-wrap').addClass('invalid');
		$('#address03').parent('div.select-wrap').addClass('invalid');
		$('.invalid select').prop('disabled', true);
	} else {
		// .invalid除去前にdisabledを除去する
		$('.invalid select').prop('disabled', false);
		$('#address01').parent('div.select-wrap').removeClass('invalid');
		$('#address02').parent('div.select-wrap').removeClass('invalid');
		$('#address03').parent('div.select-wrap').removeClass('invalid');
	}
}


// メーカー・車名
/* メーカー・車名モーダルの内容をリフレッシュする */
function getMakerCarNames() {

	beforeSearchSubmit();

	var gsScAi0010Form = {
		'ar':   $('#Ar_search').val(),
		'pmn':  $('#price-min').val(),
		'pmx':  $('#price-max').val(),
		'tp':   $('#Tp_search').val(),
		'tval': $('#tvalue').val(),
		'new':  $('#New_search').val()
	};

	callApi('/U-Car/api/GsScAi0010', JSON.stringify(gsScAi0010Form), successGsScAi0010Handler, errorGsScAi0010Handler);

}

/* メーカー・車名取得API-正常系ハンドラ */
function successGsScAi0010Handler(data) {
	var json = JSON.stringify(data);
	if (API_RESULT_SUCCESS === data.result) {
		var makerAreaId = '#maker-area';
		var carNmAreaId = '#car-list';
		$(makerAreaId).html('');
		$(carNmAreaId).html('');

		var makerAreaHtmlText = '';
		var carNmAreaHtmlText = '';

		var selectMakerCd = '';

		if (data.makerList != null && data.makerList.length > 0) {
			for (var i = 0; i < data.makerList.length; i++){
				var makerCd = data.makerList[i].makerCd;
				var makerNm = data.makerList[i].makerNm;
				var count = data.makerList[i].count.toString().replace(/(\d)(?=(\d{3})+$)/g , '$1,');

				// メーカー名表示エリアの作成
				makerAreaHtmlText += '<li data-id="#car-list-' + makerCd + '" id="maker-' + makerCd + '" class="selectMaker';
				if (data.makerList[i].count <= 0) {
					makerAreaHtmlText += ' fix-invalid';
				} else if (selectMakerCd == '') {
					selectMakerCd = makerCd;
				}
				makerAreaHtmlText += '" data-makercd="' + makerCd + '">' + makerNm + '<span class="num">（' + count + '台）</span></li>\n';

				// 車種名表示エリアの作成
				// 車種名表示エリア(ヘッダ)
				carNmAreaHtmlText += '<dl id="car-list-' + makerCd + '" class="selectCarNm">\n';
				carNmAreaHtmlText += '<dt>' + makerNm + '<span class="num">（' + count + '台）</span></dt>\n';
				carNmAreaHtmlText += '<dd>\n';
				carNmAreaHtmlText += '<ul class="list check-list">\n';
				// メーカー(全選択)の作成
				carNmAreaHtmlText += '<li>\n';
				carNmAreaHtmlText += '<input type="checkbox" name="' + makerCd + '" value="' + makerCd + '" id="' + makerCd + '" data-all-checked="#car-list" data-value="' + makerNm + '" class="carNmCheckbox makerCheck">\n';
				carNmAreaHtmlText += '<label for="' + makerCd + '">\n';
				carNmAreaHtmlText += '<span class="pal0">' + makerNm + '全て<span class="num">（' + count + '台）</span></span>\n';
				carNmAreaHtmlText += '</label>\n'
				carNmAreaHtmlText += '</li>\n';

				// 各行の作成
				for (var j = 0; j < CAR_NAME_ROW_ITEMS.length; j++){
					// ch車種存在チェックフラグ
					var chFlg = 0;

					for (var k = 0; k < data.makerList[i].chList.length; k++) {

						if (data.makerList[i].chList[k].ch == CAR_NAME_ROW_ITEMS[j][0]) {
							// 車種が存在する場合
							carNmAreaHtmlText += getLineCarHtml(makerCd, CAR_NAME_ROW_ITEMS[j][1], CAR_NAME_ROW_ITEMS[j][2], data.makerList[i].chList[k]);
							chFlg = 1;
							break;
						}
					}
					// 車種が存在しない場合
					if (chFlg == 0) {
						carNmAreaHtmlText += getLineCarHtml(makerCd, CAR_NAME_ROW_ITEMS[j][1], CAR_NAME_ROW_ITEMS[j][2], null);
					}
				}
				// 車種名表示エリア(フッタ)
				carNmAreaHtmlText += '</ul>\n';
				carNmAreaHtmlText += '<div class="index-nav">\n';
				carNmAreaHtmlText += '<ul>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-alpha-line">英</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-a-line">ア</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-k-line">カ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-s-line">サ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-t-line">タ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-n-line">ナ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-h-line">ハ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-m-line">マ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-y-line">ヤ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-r-line">ラ</a></li>\n';
				carNmAreaHtmlText += '<li><a href="#maker-' + makerCd + '-w-line">ワ</a></li>\n';
				carNmAreaHtmlText += '</ul>\n';
				carNmAreaHtmlText += '</div>\n';
				carNmAreaHtmlText += '</dd>\n';
				carNmAreaHtmlText += '</dl>\n';
			}
		}

		$(makerAreaId).html(makerAreaHtmlText);
		$(carNmAreaId).html(carNmAreaHtmlText);
	}

	// メーカー名・車種オーバーレイ アクション設定
	// メーカー名選択時処理
	$('.selectMaker').on('click', function() {
		var chk = $(this).data('makercd');

		$('.selectMaker').removeClass('selected');
		$('.selectCarNm').removeClass('show');
		$("#car-list-" + chk).find("dt").trigger('click');
		// メーカーの更新
		$('#maker-' + chk).addClass('selected');
		// 車種一覧の差し替え
		$('#car-list-' + chk).addClass('show');
		$('#car-list').scrollTop(0);
	});

	var selectArea = $('#selected-car-list');
	selectArea.find('li').remove();
	$('.select-result .num').html('0/10');

	// 画像リンク切れ対応の再定義
	resetNoImage();

	// 車種チェックボックスの再定義
	resetCarNmCheckbox();

	// チェックボックス初期化
	$("[id^=car-list]").find('input[type=checkbox]').each(function(i) {
		$(this).prop('checked', false);
	});

	// チェック検証クリア
	if ($('.checked-limit-txt').hasClass("attention")) {
		$('.checked-limit-txt').removeClass('attention');
	}

	// 選択設定
	selectMakerCarName();

	// 1件目を選択
	if (selectMakerCd != '') {
		$("#maker-" + selectMakerCd).trigger('click');
	}

	// モーダルエリア表示
	$('#modal-maker').css('visibility', 'visible');
}

/**
 *  main.js 画像リンク切れ対応 の再定義
 */
function resetNoImage() {
	// 画像リンク切れ対応
	$('[data-noimage]').each(function(){
		var $img = $(this).prop('tagName') === 'IMG' ? $(this) : $(this).find('img');
		var src = $(this).data('noimage');

		if( src !== '' ) {
			$img.on('error', function(){
				$(this).attr('src', src);
			})
			.each(function() {
				$(this).attr('src',$(this).attr('src'));
			});
		}
	});
}

/**
 * main.js アイテムカウント の再定義
 */
function resetCarNmCheckbox() {
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

	$('[data-all-checked]').off('change');
	$('[data-all-checked]').on('change', function(e){
		e.preventDefault();
		setChecked( $(this) );
	});

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

		$elements.off('change');
		$elements.on('change', function(e){
			e.preventDefault();
			sync();
		});

		sync();
	});

}

/* メーカー・車名取得APIエラーハンドラ */
function errorGsScAi0010Handler(xhr) {
	commonErrorHandler(xhr);
	$('#modal-maker').css('visibility', 'visible');
}

/* メーカーに紐つく車名一覧のHTMLを生成する */
function getLineCarHtml(makerCd, lineId, lineNm, chData) {
	var htmlText = '';

	// 車種画像パス
	var pathCarPubImg = $('#pathCarPubImg').val();

	// 各行(ヘッダ)の作成
	htmlText += '<li id="maker-' + makerCd + '-' + lineId +'" class="ttl">' + lineNm + '</li>\n';

	// 各行(データ)の作成
	if (chData != null) {
		for (var i = 0; i < chData.cnList.length; i++) {
			var cnCd = chData.cnList[i].cnCd;
			var cnNm = chData.cnList[i].cnNm;
			var count = chData.cnList[i].count.toString().replace(/(\d)(?=(\d{3})+$)/g , '$1,');
			htmlText += '<li';
			if (count <= 0) {
				htmlText += ' class="invalid"';
			}
			htmlText += '>\n'
					 +  '<input type="checkbox" name="' + cnCd + '" value="' + cnCd + '" id="' + cnCd + '" data-value="' + cnNm + '" class="carNmCheckbox">\n'
					 +  '<label for="' + cnCd + '">\n'
					 +  '<img data-noimage="/U-Car/resource/img/car_nophoto_s.png" src="' + pathCarPubImg + chData.cnList[i].cnImgPath + '" alt="' + cnNm + '">\n'
					 +  '<span>' + cnNm + '<span class="num">（' + count + '台）</span></span>\n'
					 +  '</label>\n'
					 +  '</li>\n';
		}
	} else {
		htmlText += '<li>\n'
				 +  '<p class="none">該当する車両の在庫はございません</p>\n'
				 +  '</li>\n';
	}

	// 各行(フッタ)の作成
	htmlText += '</li>\n';

	return htmlText;
}

/* メーカー・車名のチェックボックスを復元する */
function selectMakerCarName() {
	var cns = $('#Cn_search').val();
	if (cns == '') {
		return;
	}

	// チェックをつける
	var cnList = cns.split(',');
	var vals = [];
	var labels = [];

	var cnFt = cnList[0];
	var mkCd = cnFt;
	var pos = cnFt.indexOf('_');
	if (pos >= 0) {
		mkCd = cnFt.substring(0, pos);
	}

	$('[id^=car-list]').find('input[type=checkbox]').each(function(i) {
		if ($(this).data('value')) {
			if (cnList.indexOf($(this).val()) >= 0) {
				if (!$(this).parent('li').hasClass('invalid')) {
					$(this).click();
					vals.push($(this).val());
					labels.push($(this).data('value'));
				}
			}
		}
	});

	// ラベル、hiddenパラメータを設定
	var transVal = vals.join(',');
	var transLabel = labels.join('、');
	if (labels.length <= 0) {
		transLabel = LABEL_NO_SELECTED;
		// リクエストパラメータに不正なメーカー・車名が指定されていた場合を考慮して、パラメータをクリアしておく
		transVal = '';
		// ボタン、リンク表示切替
		$('#search-maker-text').css('display', 'none');
		$('#search-maker-btn').css('display', 'block');
	} else {
		// ボタン、リンク表示切替
		$('#search-maker-text').css('display', 'block');
		$('#search-maker-btn').css('display', 'none');
	}
	$('#Cn_search').val(transVal);
	$('#select-Cn').text(transLabel);
	// ブラウザバック用
	$('#Cn_label').val(transLabel);
}

/* 「検索」ボタン押下時（メーカー・車名選択モーダル） */
$('#b_car').on('click', function() {
	var vals = [];
	var labels = [];
	var selectCarObj = $('#selected-car-list').find('li');
	selectCarObj.each(function(i){
		vals.push($(this).data('name'));
		var text = $(this).text();
		var pos = text.lastIndexOf('×');
		var label = text;
		if (pos >= 0) {
			label = text.substring(0, pos);
		}
		labels.push(label);
	});
	var transVal = vals.join(',');
	var transLabel = labels.join('、');
	if (labels.length <= 0) {
		transLabel = LABEL_NO_SELECTED;
		// ボタン、リンク表示切替
		$('#search-maker-text').css('display', 'none');
		$('#search-maker-btn').css('display', 'block');
	} else {
		// ボタン、リンク表示切替
		$('#search-maker-text').css('display', 'block');
		$('#search-maker-btn').css('display', 'none');
	}
	$('#Cn_search').val(transVal);
	$('#select-Cn').text(transLabel);
	// ブラウザバック用
	$('#Cn_label').val(transLabel);
	// モーダルを閉じる
	$('.fancybox-close').trigger('click');
});


// 地域・都道府県
/* 地域・都道府県モーダルの内容をリフレッシュする */
function getAreas() {

	beforeSearchSubmit();

	var gsScAi0020Form = {
		'cn':   $('#Cn_search').val(),
		'pmn':  $('#price-min').val(),
		'pmx':  $('#price-max').val(),
		'tp':   $('#Tp_search').val(),
		'tval': $('#tvalue').val(),
		'new':  $('#New_search').val()
	};
	callApi('/U-Car/api/GsScAi0020', JSON.stringify(gsScAi0020Form), successGsScAi0020Handler, errorGsScAi0020Handler);
}

/* 地域・都道府県取得API-正常系ハンドラ */
function successGsScAi0020Handler(data) {

	var json = JSON.stringify(data);
	var modalEnabled = false;
	if (API_RESULT_SUCCESS === data.result) {
		// 取得データをマップ化
		var prefCntMap = { };
		if (data.provinceList != null) {
			for (var i = 0; i < data.provinceList.length; i++) {
				prefCntMap[data.provinceList[i].provinceCd] = data.provinceList[i].count;
			}
		}

		// 地域でループして台数を反映
		var areaIdx = 1;
		var allAreaCnt = 0;
		$('div .shopmap').each(function(i){
			var areaDefId = $(this).attr('id');
			if (AREA_PREF_MAP[areaDefId]) {
				var areaCnt = 0;
				var areaId = 'a' + areaIdx;
				for (var i = 0; i < AREA_PREF_MAP[areaDefId].length; i++) {
					var prefId = areaId + '_' + (i + 1);
					var prefCd = AREA_PREF_MAP[areaDefId][i];
					var prefCnt = 0;
					if (prefCntMap[prefCd]) {
						prefCnt = prefCntMap[prefCd];
					}
					areaCnt += prefCnt;
					var prefCntStr = prefCnt.toString().replace(/(\d)(?=(\d{3})+$)/g , '$1,');
					$("label[for='" + prefId + "'] span").text('(' + prefCntStr + '台)');
					if (prefCnt > 0) {
						$('#' + prefId).removeClass('invalid');
					} else {
						$('#' + prefId).addClass('invalid');
					}
					$("#" + prefId).val(prefCd);
				}

				allAreaCnt += areaCnt;
				var areaCntStr = areaCnt.toString().replace(/(\d)(?=(\d{3})+$)/g , '$1,');
				$("label[for='" + areaId + "'] span").text('(' + areaCntStr + '台)');
				if (areaCnt > 0) {
					$('#' + areaDefId).removeClass('invalid');
				} else {
					$('#' + areaDefId).addClass('invalid');
				}
				areaIdx += 1;
			}
		});
		var allAreaCntStr = allAreaCnt.toString().replace(/(\d)(?=(\d{3})+$)/g , '$1,');
		$("label[for='a0'] span").text('(' + allAreaCntStr + '台)');
		if (allAreaCnt > 0) {
			$('#area-zenkoku').removeClass('invalid');
		} else {
			$('#area-zenkoku').addClass('invalid');
		}

		// 地域・都道府県選択設定
		selectAreas();
	}

	// 都道府県チェックボックスの再定義
	resetAreaCheckbox();
	resetAreaPrefCheckbox();

	// モーダルエリア表示
	$('#modal-shopmap').css('visibility', 'visible');

}

/**
 *  ※search.js エリア選択(個別に都道府県を選ぶ場合は最大10件まで) にて実行されているcheckbox設定の再実行
 */
function resetAreaCheckbox() {
	$('.check-limit-area').each(function(){
		var $applyWrap = $('.checked-limit', this);
		var $checkboxs = $applyWrap.find('input[type=checkbox]').not('.invalid, .disabled, :disabled').not($applyWrap.find('label.invalid').prev('input[type=checkbox]'));
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
}

/**
 *  ※main.js 都道府県選択で全選択判定に対するcheckbox設定の再実行
 */
function resetAreaPrefCheckbox() {
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
		var $applyCb = $applyWrap.length ? $applyWrap.find('input[type=checkbox]').not($parentCb).not('.invalid, .disabled, :disabled').not($applyWrap.find('label.invalid').prev('input[type=checkbox]')) : [];
		return $applyCb;
	};

	$('[data-apply-checked]').each(function(){
		var $parentCb = $(this);
		var $applyCb = getApplyCb($parentCb, 'apply-checked');

		if( $applyCb.length ) {
			$parentCb.off('change');
			$parentCb.on('change', function(e){
				e.preventDefault();
				$applyCb.prop('checked', $parentCb.prop('checked'));

				if( $(this).data('parent-checkboxs') ) {
					$.each( $(this).data('parent-checkboxs'), function(i, $elm){

						if( isAllChecked(getApplyCb($elm, 'apply-checked')) ) {
							$elm.prop('checked', true);
						}

						// 地域チェック
						getLocalAreas(['a1', 'a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8', 'a9']);
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

			$applyCb.off('change');
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
}

/* 地域・都道府県取得APIエラーハンドラ */
function errorGsScAi0020Handler(xhr) {
	commonErrorHandler(xhr);
	$('#modal-shopmap').css('visibility', 'visible');
}

/* 地域・都道府県選択設定 */
function selectAreas() {
	// チェックボックス初期化
	$('#modal-shopmap').find('[name^=a]').each(function(i){
		$(this).prop('checked', false);
	});

	// チェック検証クリア
	if ($('.checked-limit-txt').hasClass('attention')) {
		$('.checked-limit-txt').removeClass('attention');
	}

	var ars = $('#Ar_search').val();
	if (ars == '') {
		return;
	}

	// チェックをつける
	var arList = ars.split(',');
	var labels = [];
	var vals = [];
	$('#modal-shopmap').find('[name^=a]').each(function(i){
		if ($(this).data('label')) {
			var prefId = $(this).val();
			if (arList.indexOf(prefId) >= 0) {
				if (!$(this).hasClass("invalid")) {
					$(this).prop('checked', true);
					vals.push($(this).val());
					labels.push($(this).data('label'));
				}
			} else {
				$(this).prop('checked', false);
			}
		}
	});

	// 全国チェック
	if ($('#a0').prop('checked')) {
		$('#modal-shopmap').find('[name^=a]').each(function(i){
			if ($(this).data('label')) {
				$(this).prop('checked', true);
			} else {
				$(this).prop('checked', false);
			}
		});
		labels = ['全国'];
		vals = ['00'];
	}
	if ($('#area-zenkoku').hasClass('invalid')) {
		labels = [];
		vals = [];
	}

	// 地域チェック
	getLocalAreas(['a1', 'a2', 'a3', 'a4', 'a5', 'a6', 'a7', 'a8', 'a9']);

	// ラベル、hiddenパラメータを設定
	var transVal = vals.join(',');
	var transLabel = labels.join('、');
	if (labels.length <= 0) {
		transLabel = LABEL_NOT_SELECTED;
		// リクエストパラメータに不正な都道府県コードが指定されていた場合を考慮して、パラメータをクリアしておく
		transVal = '';
		// ボタン、リンク表示切替
		$('#search-area-text').css('display', 'none');
		$('#search-area-btn').css('display', 'block');
	} else {
		// ボタン、リンク表示切替
		$('#search-area-text').css('display', 'block');
		$('#search-area-btn').css('display', 'none');
	}
	$('#Ar_search').val(transVal);
	$('#select-Ar').text(transLabel);
	// ブラウザバック用
	$('#Ar_label').val(transLabel);
}

/**
 * 地域チェックボックスのチェックを行います。
 * @param targetIdList 地域IDリスト
 */
function getLocalAreas(targetIdList) {
	if (targetIdList.length > 0) {
		var isAllAreaCheck = true;
		for(var i = 0; i < targetIdList.length; i++){
			var isAllCheck = true;
			var targetId = targetIdList[i];
			$('#modal-shopmap').find('[name^=' + targetId + '_]').each(function(i){
				if ($(this).data('label')) {
					if (!$(this).hasClass("invalid") && !$(this).prop('checked')) {
						isAllCheck = false;
						isAllAreaCheck = false;
					}
				}
			});

			if (isAllCheck) {
				$('#' + targetId).prop('checked', true);
			} else {
				$('#' + targetId).prop('checked', false);
			}
		}

		if (isAllAreaCheck) {
			$('#a0').prop('checked', true);
		} else {
			$('#a0').prop('checked', false);
		}
	}
}

/* 「地域・都道府県を選択して検索」ボタン押下時（地域・都道府県モーダル） */
$('#b_area').on('click', function() {
	// 選択項目を反映
	if (reflectCheckTarget('modal-shopmap', 'a', 'Ar_search', 'select-Ar', -1, AREA_ALL_ITEMS, 'search-area-text', 'search-area-btn', 'Ar_label')) {
		$('.fancybox-close').trigger('click');
	}
});

/**
 * チェックボックスの選択項目を反映します。
 *
 * @param targetAreaId チェックボックス確認領域ID
 * @param targetId チェックボックスID（接頭辞）
 * @param transValId チェックした値を設定するID
 * @param transLabelId チェックしたラベルを設定するID
 * @param maxSelCnt 最大選択可能件数（負数を指定することで、チェックされなくなります）
 * @param typicalVals 代表的な項目の値（この値が含まれていた場合は、その値及びラベルのみが設定されます）
 * @param dispTextArea モーダルリンク表示用エリアID（表示エリア切替に使用）
 * @param dispBtnArea モーダルボタン表示用エリアID（表示エリア切替に使用）
 * @param hiddenLabelId ブラウザバック時にラベルを復元するための値を設定するID
 * @return 最大選択可能件数を超えた場合は、falseを返します。
 */
function reflectCheckTarget(targetAreaId, targetId, transValId, transLabelId, maxSelCnt, typicalVals, dispTextArea, dispBtnArea, hiddenLabelId) {
	var vals = [];
	var labels = [];
	$('#' + targetAreaId).find('[name^=' + targetId + ']:checked').each(function(i){
		if ($(this).data('label')) {
			if (!$(this).hasClass("invalid")) {
				vals.push($(this).val());
				labels.push($(this).data('label'));
			}
		}
	});

	if (typicalVals !== undefined) {
		var exVals = [];
		var exLabels = [];
		for (var i = 0; i < vals.length; i++) {
			if (typicalVals.indexOf(vals[i]) >= 0) {
				exVals.push(vals[i]);
				exLabels.push(labels[i]);
			}
		}
		if (exVals.length > 0) {
			vals = exVals;
			labels = exLabels;
		}
	}

	if (maxSelCnt >= 0) {
		if (vals.length > maxSelCnt) {
			return false;
		}
	}

	var transVal = vals.join(',');
	var transLabel = labels.join('、');
	if (labels.length <= 0) {
		transLabel = LABEL_NOT_SELECTED;
		// ボタン、リンク表示切替
		$('#' + dispTextArea).css('display', 'none');
		$('#' + dispBtnArea).css('display', 'block');
	} else {
		// ボタン、リンク表示切替
		$('#' + dispTextArea).css('display', 'block');
		$('#' + dispBtnArea).css('display', 'none');
	}

	// リンク文字列、hiddenパラメータの設定
	$('#' + transValId).val(transVal);
	$('#' + transLabelId).text(transLabel);
	// ブラウザバック用
	$('#' + hiddenLabelId).val(transLabel);

	return true;
}

/*
 * 検索実行の前処理を行います。
 */
function beforeSearchSubmit() {
	// checkbox制御
	// 支払総額に切替
	if ($('.Tp_search:checked').val() != null) {
		$('#Tp_search').val($('.Tp_search:checked').val());
	} else {
		$('#Tp_search').val('');
	}

	// 掲載開始から7日間以内の物件
	if ($('.New_search:checked').val() != null) {
		$('#New_search').val($('.New_search:checked').val());
	} else {
		$('#New_search').val('');
	}

}

/**
 * APIを実行します。
 *
 * @param url URL
 * @param data jsonデータ
 * @param successHandler 成功時ハンドラ
 * @param errorHander エラーハンドラ。{@code null}の場合、共通エラーハンドラを使用
 */
function callApi(url, data, successHandler, errorHander) {
	if (errorHander == null) {
		errorHander = commonErrorHandler;
	}
	$.ajax({
		type : 'POST',
		url : url,
		cache : false,
		data : data,
		contentType : 'application/json',
		dataType : 'json',
		timeout : 4000,
		success : successHandler,
		error : errorHander
	});
}

/* 共通エラーハンドラ */
function commonErrorHandler(xhr) {
	var retry_index = 0;
	if (xhr.status === 0 && retry_index < 3) {
		retry_index++;
		$.ajax(this);
	}
}

/* 値が空のパラメータを取り除いて画面遷移 */
function clearBlankParam(form) {
	var query = form.serialize();
	query = cleanQuery(query);
	var linkPath = form.attr('action');
	if (query != null && query != '') {
		linkPath = linkPath + '?' + query;
	}
	location.href = linkPath;
}

/* クエリ文字列洗い替え */
function cleanQuery(query) {
  var arr = [];
  $.each(query.split('&'), function(i, param) {
    if (param.split('=')[1]) { arr.push(param); }
  });
  return arr.join('&');
}
