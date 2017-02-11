<?php $query = "/?sid={$_REQUEST['sid']}&crc={$_REQUEST['crc']}"; ?>

<div class="top-control">
    <span class="title">
        <?php echo $page->title_page ?>
        <!-- <a class="tit_label" id="video-help-open" href="#">Помощь</a>-->
    </span>
</div>
<!--
<div id="video-help" class="hidden">
    <iframe width="640" height="360" <?php /* TODO: вставить ссылку на правильное видео, об создании заявки на импорт подписчиков */ ?> src="http://www.youtube.com/embed/T9LzjKKdSQ8?rel=0&wmode=opaque" frameborder="0" allowfullscreen></iframe>
</div>
-->

<?php if( $action == 'exlam' ) { ?>
    <form action="" method="post" id="form" onsubmit="return cmsformcheck();">
        <input type="hidden" name="id" id="request-id" value="<?php echo empty( $item ) ? '' : $item['id'] ?>" />
        <input type="hidden" name="owner_id" value="<?php echo empty( $item ) ? '' : $item['owner_id'] ?>" />

        <div class="context-view red">
            <div class="form-row">
                <!--div class="form-label">&nbsp;</div-->
                <div class="form-value">
                    <p><b>Внимание!</b> Все ваши подписки и настройки будут удалены.</p>
                    <p>Вы действительно желаете удалить свой аккаунт?</p>
                </div>
            </div>
        </div>
        <div class="form-buttons">
            <div class="form-label">&nbsp;</div>
            <div class="form-value">
                <span class="button small newred">
                    <span class="left-c"></span>
                    <span class="in"><input type="submit" class="ajax-submit" name="delete" value="Удалить">Удалить</span>
                    <span class="right-c"></span>
                </span>
                <span class="indentLeft5">или</span>
                <a class="blue indentLeft5" href="<?='/'.$page->uri[0].'/in'.$query?>">Отменить</a>
            </div>
        </div>
    </form>
<?php } elseif( $action == 'not' ) { ?>
    <div class="context-view">
        <div class="form-row">
            <!--div class="form-label">&nbsp;</div-->
            <div class="form-value">
                Вы отказались от удаления аккаунта.
            </div>
        </div>
    </div>
<?php } ?>
