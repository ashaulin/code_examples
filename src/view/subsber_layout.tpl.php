<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:fb="http://ogp.me/ns/fb#" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
	<title><?=$page->title?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="NOINDEX, NOFOLLOW" />
	<meta property="og:title" content="<?=$page->og_title;?>"/>
	<meta property="og:site_name" content="Justclick"/>
	<meta charset="utf-8" /> 
	<link rel="SHORTCUT ICON" href="/favicon.ico" />
	
	<?php 
		$page->showStyle('/media/jquery/css/infoshop/jquery-ui-1.8.16.custom.css');
		$page->showStyle('/media/cmslist/cmslist.css');
		$page->showStyle('/media/cmsform/cmsform.css');
		$page->showStyle('/media/colorpicker/css/colorpicker.css');
		$page->showStyle('/media/css/page_new.css');
		$page->showStyle('/media/css/fonts.css');
		
		$page->showScript('/media/jquery/jquery.js');
		$page->showScript('/media/cmsform/cmsform.js');
		$page->showScript('/media/cmslist/cmslist.js');
		$page->showScript('/media/js/common.js');
		$page->showScript('/media/js/jquery.cookie.js');
		$page->showScript('/media/colorpicker/js/colorpicker.js');
		$page->showScript('/media/common/countdown.js');
		$page->showScript('/media/js/common_new.js');
	?>
	
	<?php $page->showStyles(); ?>
	<?php $page->showScripts(); ?>
	
	<script language="JavaScript" type="text/javascript">
	$(function(){
		$('#video-help-open').click(function(){
			$('#video-help').slideToggle();
		});
	});
	</script>
</head>
<body>

<div id="alert_loading" class="new-loading" style="display: none;"><span class="loading">Идет загрузка…</span><span class="alert-text"></span></div>  
<div id="opaco" style="display: none;"></div>
<div id="popup" style="display: none;"></div>
<div class="notice" style="display: none;">
	<a class="slide"></a>
	<div class="inner">some notification</div>
	<div class="hidden">
		<div class="inner">
			<h5>another title(if need)</h5>
			<div>another some notification(if need)</div>
		</div>
		<div class="subArea">
			<a href="" class="button small orange"><span class="left-c"></span><span class="in notice-close">Теперь я знаю!</span><span class="right-c"></span></a>
		</div>
	</div>
</div>
<input type="hidden" name="domainName" value="<?php echo URL::baseDomain(true, true);?>" />
<input type="hidden" name="msgStatus" value="<?php echo($page->_msgStatus);?>" />


<section>
	<div class="bg-repeat"></div>
	<div class="container">
		<div class="bs_menu"></div>
		<nav id="admin-panel">
		<!-- Menu -->
			<dl><dt>Почтовый ящик</dt>
				<?php $query = "/?sid={$_REQUEST['sid']}&crc={$_REQUEST['crc']}"; ?>
				<dd><a class="<?php echo (in_array($page->uri[1],array('in'))) ? 'active' : ''?>" href="<?='/'.$page->uri[0].'/in'.$query?>">Входящие</a></dd>
			</dl>
			<dl><dt>Управление подпиской</dt>
				<dd><a class="<?php	echo (in_array($page->uri[1],array('rassilki'))) ? 'active' : ''?>" href="<?='/'.$page->uri[0].'/rassilki'.$query?>">Рассылки</a></dd>
				<dd><a class="<?php echo (in_array($page->uri[1],array('profile'))) ? 'active' : ''?>" href="<?='/'.$page->uri[0].'/profile'.$query?>">Настройки</a></dd>
				<dd><a class="<?php echo (in_array($page->uri[1],array('delete'))) ? 'active' : ''?>" href="<?='/'.$page->uri[0].'/delete'.$query?>">Удалить аккаунт</a></dd>
			</dl>				
		</nav>
		<article id="main-view">
			<?php $page->showMainSpace($variables) ?>
		</article>
		<div class="clr"></div>
	</div>
	<div class="f-placeholder"></div>
	<footer>
		<div class="container">
			<div class="boxes">
				<div class="fs12">
					© JustClick.ru, 2013.<br/>Все права защищены.
				</div>
			</div>
			<div class="boxes fixWidth250">
				<div class="fs11">
					 <div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
					<div class="fb-like" data-href="https://www.facebook.com/justclick.ru" data-send="false" data-width="150" data-show-faces="false" data-font="arial" data-colorscheme="dark"></div>
					<div class="clr"></div>
				</div>
			</div>
			<nav class="nav-control">
				<ul>
					<li><a href="/">JustClick - сервис автоматизации интернет бизнеса.</a></li>
					<li><a href="mailto:support@justclick.ru">Техническая поддержка</a></li>
				</ul>
			</nav>
		</div>
	</footer>	
</section>


</body>
</html>
