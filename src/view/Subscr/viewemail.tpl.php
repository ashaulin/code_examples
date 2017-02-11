<div class="top-control">
    <span class="title">
        <?php echo $page->title_page?>
    </span>
</div>

<div class="context-view">
    <div class="form-row">
        <div class="form-label">От:</div>
        <div class="form-value">
            <div class="indentTop7">
                <?=$item['from_name']?>
                &lt;
                    <a href="mailto:<?=$item['from_email']?>">
                        <?=$item['from_email']?>
                    </a>
                &gt;
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">Тема:</div>
        <div class="form-value">
            <div class="indentTop7">
                <?=$item['title']?>
            </div>
        </div>
    </div>
    <div class="form-row">
        <div class="form-label">Получено:</div>
        <div class="form-value">
            <div class="indentTop7">
                <?=$item['time']?>
            </div>
        </div>
    </div>
</div>

<div class="context-view">
    <div class="message">
        <?=$item['message']?>
    </div>
</div>
