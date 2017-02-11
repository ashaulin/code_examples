<script language="JavaScript"><!--
function cmsformcheck(e)
{
    r = true;
    __cmsformcheck_fields = new Array("lead_name", "lead_phone");
    __cmsformcheck_checks = new Array("blank", "blank");
    frm = document.getElementById("form");
    r = __cmsformcheck(frm);
    return r;
}
//--></script>

<div class="top-control">
    <span class="title">
        <?php echo $page->title_page?>
    </span>
</div>

<form action="" method="post" id="form" onsubmit="return cmsformcheck()">
<input type="hidden" name="lead_email" value="<?=$items['lead_email'] ?>" />
    <div class="context-view">
        <div class="form-row">
            <div class="form-label">Ваш E-mail:</div>
            <div class="form-value">
                <input type="text" name="" value="<?=$items['lead_email'] ?>" disabled  />
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">Ваше имя:</div>
            <div class="form-value">
                <input type="text" name="lead_name" value="<?=$items['lead_name'] ?>"/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">Ваш телефон:</div>
            <div class="form-value">
                <input type="text" name="lead_phone" value="<?=$items['lead_phone'] ?>"/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-label">Ваш город:</div>
            <div class="form-value">
                <input type="text" name="lead_city" value="<?=$items['lead_city'] ?>"/>
            </div>
        </div>
    </div>
    <div class="form-buttons">
        <div class="form-label">&nbsp;</div>
        <div class="form-value">
            <span class="button mid">
                <span class="left-c"></span>
                  <span class="in"><input type="submit" class="ajax-submit" name="save" value="Сохранить">Сохранить</span>
                   <span class="right-c"></span>
            </span>
        </div>
    </div>
</form>
<div class="clear"></div>
