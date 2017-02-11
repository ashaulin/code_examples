<?php
// including limited styles
$page->showStyle('styles/main_old.scss');
?>
<script type="text/javascript">//<![CDATA[
$(function(){
    <?php if ($pagination !== null): ?>
    var paginator = <?php echo Zend_Json::encode($pagination) ?>;
    $(".list-item").ajaxPaginator(paginator);
    <?php endif; ?>     
});
//]]></script>

<div class="result">
    <table class="standart-view">
        <tr class="main-tr">
            <td>От</td>
            <td class="w100">Тема</td>
            <td>Получено</td>
        </tr>
        <?php foreach($items as $item):
            if($item['opened']) $bold_o = $bold_c = '';
            else {
                $bold_o = '<b>';
                $bold_c = '</b>';
            }
        ?>
        <tr>
            <td class="td-left">
                <nobr>
                    <?=$bold_o.htmlspecialchars( $item['from_name'])?>
                    &lt;
                        <a href="mailto:<?=$item['from_email']?>">
                            <?=$item['from_email']?>
                        </a>
                    &gt;
                    <?=$bold_c?>
                </nobr>
            </td>
            <td class="td-left">
                <?=$bold_o?>
                    <a href="/<?=$page->uri[0]?>/viewemail/<?=$item['view_link']?>/?sid=<?=$sbscrber_id?>&crc=<?=$sbscrber_crc?>" title="Открыть письмо">
                        <?=htmlspecialchars( $item['title'])?>
                    </a>
                <?=$bold_c?>
            </td>
            <td align="center">
                <?=HumenDateTime($item['time'])?>
            </td>
        </tr>
        <?php endforeach;?>
    </table>
</div>
