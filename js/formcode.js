function GetFormCode()
{
	var goods_checked = $('.checkbox_tree input[type=checkbox]:checked');
	if(!goods_checked.length){
		$('#code-test').html();
		$('#code-result').val('Вам необходимо выбрать минимум 1 продукт!');
        $('.hide-if-no-goods-selected').hide();
		return false;
	}
    $('.hide-if-no-goods-selected').show();
    
	//лимит на количество символов у утм-меток
	$("[name='medium_input'], [name='source'], [name='campaign'], [name='content'], [name='term']").each(function(e){
		name = $(this).attr('name');
		limit = (name == 'content' || name == 'term') ? 255 : 50;
		$(this).val(stringLimit($(this).val(), limit));
	});

	var width = $('#form-width').val();
	if (width < 220)
	{
		width = 220;
		$('#form-width').val(220);
	}
	
	bimg = btext = '';
	if ($('input[name=button]:checked').val() == 'image')
	{
		var bimg = $('#code-button-image').val();
		bimg = '&image='+bimg;
	}
	else
	{
		var btext = $('#code-button-text').val();
		if (btext.length > 200)
		{
			btext = btext.substr(0, 200);
			$('#code-button-text').val(btext);
		}
		if (btext=='') 
			btext = 'Заказать';
		//fix for length text
		txtBtnWidth = $('[name="compiledbtn"]:checked').closest('td').find('label').width();
		if((txtBtnWidth + 15) > width ){
			
			do {
				len = btext.length;
				len = len -1;
				btext = btext.substr(0, len);
				$('[name="compiledbtn"]').closest('td').find('a span').text(btext);
				txtWdt = $('[name="compiledbtn"]:checked').closest('td').find('label').width();
	        } while ((txtWdt + 15) > width);
		   	notify('Длина текста превышает лимит!');
		}

		txtBtnHeight = $('[name="compiledbtn"]:checked').closest('td').find('label span').height();
		var btn_height = $('#btn-height').val();
		var btn_height_top = btn_height - txtBtnHeight;
		if(btn_height < txtBtnHeight){
			btn_height = txtBtnHeight;
			btn_height_top = 0;
			$('#btn-height').val(txtBtnHeight);
		}else if(btn_height > 70){
			btn_height = txtBtnHeight;
			btn_height_top = 70 - txtBtnHeight;
			$('#btn-height').val(70);
		}
		
		var btn_width = $('#btn-width').val();
		if(btn_width < txtBtnWidth){
			btn_width = txtBtnWidth;
			$('#btn-width').val(txtBtnWidth);
		}else if(btn_width > (width-50)){
			btn_width = width-50;
			$('#btn-width').val(width-50);
		}
		
		var formBtnCSS = $("input[name='compiledbtn']:checked").val();
		$('[name="compiledbtn"]').closest('td').find('a span').text(btext);
		
		btext = btext.replace(/"/g, '&quot;').replace(/'/g, "&#039;");
		btext = encodeURIComponent(btext);
		btext = '&button=' + btext + '&btn_css=' + formBtnCSS + '&btn_width=' + btn_width + '&btn_height=' + btn_height + '&btn_htop=' + btn_height_top;
	}

	var fkupon = $('#form-kupon').val();
	var fcolor = $('#form-color').val();
	var tag = $('#code-tag').val();
	//var aff = $('#code-aff').val();
	var aff = '';
	if( ($('#code-aff').val() == $('#code-aff').attr('rel_name')) && $('#code-aff').attr('rel_name').length) 
		var aff = $('#code-aff').attr('rel_id');
	
//-----------------------------------УТМ-МЕТКИ-----------------------------------
	if(aff.length){//если метка партнерская
		isUtmDisabled = true;
		
		$('[name="source"]').val($('#code-aff').attr('rel_name'));
		$('[name="campaign"]').val('');
		$('[name="content"]').val('');
		$('[name="term"]').val('');
		$('[name="medium_input"]').val('affiliate');
		
		$('[name="medium"]').attr('disabled', true);
        if (JC.jquery('[name="medium"]').val()) {
            JC.jquery('[name="medium"]').val('').change();
        }
		$('.medium-input').show();
	}else{//иначе метка авторская
		isUtmDisabled = false;
		if($('[name="medium"]').is(':disabled')){
			$('[name="medium"]').attr('disabled', false);
			JC.jquery('[name="medium"]').val('').change();
		}
	}
	
	$('[name="medium_input"]').attr('disabled', isUtmDisabled);
	$('[name="source"]').attr('disabled', isUtmDisabled);
	$('[name="campaign"]').attr('disabled', isUtmDisabled);
	$('[name="content"]').attr('disabled', isUtmDisabled);
	$('[name="term"]').attr('disabled', isUtmDisabled);
	
	var utm_source = $.trim($('[name="source"]').val());
	var utm_campaign = $.trim($('[name="campaign"]').val());
	var utm_content = $.trim($('[name="content"]').val());
	var utm_term = $.trim($('[name="term"]').val());
	var utm_medium = $('[name="medium"]').val();
	if(utm_medium == '')
		utm_medium = $.trim($('[name="medium_input"]').val());
	else
		$('[name="medium_input"]').val('');
//-----------------------------------УТМ-МЕТКИ-----------------------------------
	
	var goods_options = $('.checkbox_tree input[type=checkbox]');
	var label = $('.checkbox_tree label');
	var goods = new Array();
	for (var i=0;i<goods_options.length;i++)
	{
		if(goods_options[i].checked)
		{
			if(label[i].textContent)
				var good_name = label[i].textContent;
			if(label[i].innerText)
				var good_name = label[i].innerText;	
			if(label[i]){
				var beg = good_name.indexOf("[")+1;
				var end = good_name.indexOf("]")-1;
				var good = good_name.substr(beg, end);
				if(good.length > 0)goods.push(good);
			}
		}
	}
	goods = goods.join(':');

	var fields_options = $('.fields-required input:checked');
	var products = $('input.chb_tree_nochild:checked');
	//fields_options.length = (fields_options.length == 0) ? 1 : fields_options.length;
	
	var fields = new Array();
	var url_offerta_lock = true;
	$('.fields-required input').each(function(e){
		if($(this).is(':checked')){
			if($(this).attr('name') == 'offerta')
				url_offerta_lock = false;
			else
				fields.push($(this).attr('name'));
		}
	});	
	fields = fields.join(',');
	
	
	if (url_offerta_lock) {
        $('#url-offerta').hide();
		$('#url-offerta').attr('disabled', true);
		var url_ofrt = '';
	} else {
        $('#url-offerta').show();
		$('#url-offerta').attr('disabled', false);
		var url_ofrt = $('#url-offerta').val();
	}
	
	
	
	if (tag.length > 50)
	{
		tag = tag.substr(0, 50);
		$("#code-tag").val(tag);
	}
	
	var font = $('input.font-size:checked').val();
	
	if (products.length < 2) {
            var prod_length = 0;
        } else {
            var prod_length = products.length * 50 + 10;
        }
	var height = Math.ceil((prod_length + fields_options.length*35 + 70 + Number(btn_height) + 28)*font/14);
	
	var query_str = new Array();
	if (url_ofrt != "") {
		if (url_ofrt.search(/https?:\/\//) == 0) {
			query_str.push("offerta="+encodeURIComponent(url_ofrt));
		} else {
			query_str.push("offerta=http://"+encodeURIComponent(url_ofrt));
		}
	};
	if (tag != "") query_str.push("tag="+encodeURIComponent(tag));
	if (fkupon != "") query_str.push("kupon="+fkupon);
	/* теперь это не нужно, так как передаем через партнерские утм-метки*/
	//if (aff != "") query_str.push("aff="+aff);
	if (fields != "") 
		query_str.push("required="+fields);
	if (font != "") query_str.push("font="+font);
	
	if(utm_medium.length) 
		query_str.push("utm[utm_medium]="+encodeURIComponent(utm_medium));
	if(utm_source.length) 
		query_str.push("utm[utm_source]="+encodeURIComponent(utm_source));
	if(utm_campaign.length) 
		query_str.push("utm[utm_campaign]="+encodeURIComponent(utm_campaign));
	if(utm_content.length) 
		query_str.push("utm[utm_content]="+encodeURIComponent(utm_content));
	if(utm_term.length) 
		query_str.push("utm[utm_term]="+encodeURIComponent(utm_term));
	
	query_str = query_str.join('&');
	if (query_str != "") query_str = "&" + query_str;

	var main_domain = $('#main-domain').text();
	var result_code = '<div align="center"><div id="order_form_owner"></div><script language="JavaScript">\nvar pref = \'\';\nvar p = \'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz\';\nfor (var i=0;i<5;i++)\n\tpref+=p.charAt(Math.floor(Math.random()*p.length));\nvar url = location.href;\nif (url.indexOf(\'#\')!=-1)\n\turl = location.href.substr(0, location.href.indexOf(\'#\'));\nvar src = \''+main_domain+'/order_form/?goods='+goods+'&color='+fcolor+'&width='+width+query_str+btext+bimg+'&__pref=\'+pref+\'&__parent=\'+encodeURIComponent(url);\nvar delay = Math.round(Math.random()*20)+30;\nif (typeof UpdateFormHeight != \'function\') {\n\tfunction UpdateFormHeight(pref, frame) {\n\t\tvar h=null;\n\t\tif (!h) {\n\t\t\tvar re = new RegExp("^#"+pref+"(\\\\d+)");\n\t\t\tif (location.hash.match(re)) {\n\t\t\t\th=RegExp.$1;\n\t\t\t\tlocation.hash = \'\';\n\t\t\t}\n\t\t}\n\t\tif (!h)\n\t\t\tfor (var i=0; i<2000; i+=10)\n\t\t\t\tif (top.frames[pref+i]) {\n\t\t\t\t\th=i;\n\t\t\t\t\tbreak;\n\t\t\t\t}\n\t\tif (h)\n\t\t\tframe.style.height=h+\'px\';\n\t\tsetTimeout(function(){ UpdateFormHeight(pref, frame); }, delay*4);\n\t}\n}\nvar html = \'<iframe src="\'+src+ \'" frameborder="0" scrolling="no" style="width:'+width+'px;height:'+height+'px;overflow:hidden;padding:15px 0px;box-sizing:initial;" onload="var th=this; setTimeout(function(){ UpdateFormHeight(pref, th); }, \'+delay+\')"></iframe>\';\ndocument.getElementById("order_form_owner").innerHTML = html;\n</script></div>\n';
	$('#code-result').val(result_code);
	
	var url = location.href;
	var pref = 'test';
	var src = "/order_form/?goods=" + goods + "&color=" + fcolor + "&width=" + width+query_str+btext+bimg + "&__pref=" + pref + '&__parent=' + encodeURIComponent(url);
	var delay = Math.round(Math.random()*20)+30;
	$('#code-test').html("<iframe src=\""+src+ "&__pref=test\" frameborder=\"0\" scrolling=\"no\" style=\"width:"+width+"px;height:"+height+"px;overflow:hidden;box-sizing:initial;\" onload=\"var th=this; var pref='test'; setTimeout(function(){ UpdateFormHeight(pref, th); }, "+delay+")\"></iframe>");
}


function UpdateFormHeight(pref, frame) {
	var delay = Math.round(Math.random()*20)+30;
	var h=null;
	if (!h) {
		var re = new RegExp("^#"+pref+"(\\d+)");
		if (location.hash.match(re)) {
			h=RegExp.$1;
			location.hash = '';
		}
	}
	if (!h)
		for (var i=0; i<2000; i+=10)
			if (top.frames[pref+i]) {
				h=i;
				break;
			}
	if (h){
		h=+h+40;
		frame.style.height=h+'px';
	}
	//setTimeout(function(){ UpdateFormHeight(pref, frame); }, delay*4);
}

function SelectKey(e, time) {
	var keyCode = (e.keyCode ? e.keyCode : e.which);
   
	if($.inArray(keyCode, [17,65]) == -1){
		setTimeout('GetFormCode();',time);
	}
}

function AddAutoVals(event, ui)
{
	if (ui.item)
	{
		$('#code-aff').attr('rel_id', ui.item.id);
		$('#code-aff').attr('rel_name', ui.item.value);
		$('#code-aff').val(ui.item.value);
		GetFormCode();
	}
}

function DriveinValue(event, ui) {
    var index;
    for (index = 0; index < ui.content.length; ++index) {
        if (ui.content[index].value == $('#code-aff').val()) {
            $('#code-aff').attr('rel_id', ui.content[index].id);
            $('#code-aff').attr('rel_name', ui.content[index].value);
            GetFormCode();
            break;
        }
    }
}

function GoodTreeClick()
{
	setTimeout(GetFormCode, 2);
}
$(function(){
	GetFormCode();
	$('.checkbox_tree input[type=checkbox]').click(GoodTreeClick);
	$('.fields-required input[type=checkbox]').click(GetFormCode);
	$('#form-color').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
			GetFormCode();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});

	$('#url-offerta').keyup(GetFormCode);
	$('#form-width').live('keyup', function(e){
		SelectKey(e, 1000);
	});
	$('#btn-width, #btn-height').live('keyup', function(e){
		SelectKey(e, 1000);
	});
	$('[name="compiledbtn"]').change(GetFormCode);
	$('#cb-code-button-text').click(function(){
		if (this.checked){
			$('#code-button-text').removeAttr('disabled');
			$('#code-button-image').attr('disabled', 'disabled');
			$('.compiledButton').show();
			$('.compiledText').show();
			$('.uploadButton').hide();
		}
		GetFormCode();
	});
	$('#cb-code-button-image').click(function(){
		if (this.checked){
			$('#code-button-image').removeAttr('disabled');
			$('#code-button-text').attr('disabled', 'disabled');
			$('.compiledButton').hide();
			$('.compiledText').hide();
			$('.uploadButton').show();
		} 
		GetFormCode();
	});
	$('#code-button-text').live('keyup', function(e){
		SelectKey(e, 1000);
	});
	$('#form-color').keyup(GetFormCode);
	$('#form-kupon').keyup(GetFormCode);
	$('#code-tag').keyup(GetFormCode);
	$('#code-aff').keyup(GetFormCode);
	$('#code-aff').autocomplete({
		source: '/rassilki/getpartners',
		select: AddAutoVals,
        response: DriveinValue,
		appendTo: $('#code-aff').parent(),
		minLength: 3,
		delay: 100
	});
	$('input.font-size').click(GetFormCode);
	
	JC.jquery('select').change(GetFormCode);
	$('input[name="medium_input"]').keyup(GetFormCode);
	$('input[name="source"]').keyup(GetFormCode);
	$('input[name="campaign"]').keyup(GetFormCode);
	$('input[name="content"]').keyup(GetFormCode);
	$('input[name="term"]').keyup(GetFormCode);

    $('.g-button').on('click', function(){
        $(this).closest('label').click();
        return false;
    });
});
