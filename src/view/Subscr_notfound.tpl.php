<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title><?=$page->title?><?php if($page->shop_config['shop_project_title']) echo ' :: ', $page->shop_config['shop_project_title']?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta charset="utf-8" /> 
<link rel="SHORTCUT ICON" href="/favicon.ico" />
	
<style><!--
body { background:#<?php echo $page->shop_config['shop_background_color']; ?>; }
--></style>
<link rel="stylesheet" type="text/css" href="/media/SL/styles.css">
<link rel="stylesheet" type="text/css" href="/media/css/page.css" />

</head>

<body>
<div id="main">
	<div id="content">
		<h1>Аккаунт подписчика не существует</h1>
		<p style="text-align:center;"><b>Вероятно этот аккаунт был удалён из системы.<br />Вы снова можете подписаться на рассылки, на тех страницах где вы делали это ранее.</b></p>
		<hr />
		<div id="copyright">
			<?php if($page->shop_config['shop_copyright']) echo $page->shop_config['shop_copyright']?>
			<?php if (empty($page->user_rs['user_hide_justclick'])) : ?>
				<p>Сайт работает на платформе
				<a href="<?php echo URL::baseDomain(true, true); ?>" onmousedown="this.href='<?php echo URL::baseDomain(true, true); ?>/?p{$page->user_rs['user_id']}'">JustClick.Ru</a></p>
			<?php endif; ?>
			<?php if($page->shop_config['shop_counter']) echo $page->shop_config['shop_counter']?>
		</div>
	</div>
</div>

</body>
</html>
