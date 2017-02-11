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
            <td>Автор</td>
            <td class="td-left w100">Название</td>
            <td></td>
        </tr>
        <?php
        foreach ($items as $item) {
            if ($item['active'] > 0) {
                $ico = 'delete';
                $txt = 'Отписаться';
                if (!empty($item['owner_id'])) {
                    $prefix = '';
                    $bold_o = '<b>';
                    $bold_c = '</b>';
                    $warning = ' Вы будете отписаны от ВСЕХ рассылок данного автора';
                }
                else {
                    $prefix = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ';
                    $bold_o = $bold_c = '';
                    $warning ='';
                }
            } else {
                $prefix = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ';
                $bold_o = $bold_c = '';
                $ico = 'add';
                $txt = 'Подписаться';
            }
            $category = !empty($item['category_id']) ? '&category_id='.EncodeInteger($item['category_id']) : '&owner_id='.EncodeInteger($item['owner_id']);
            $button = '<nobr><img src="/media/cmslist/file_'.$ico.'.png" width="16" height="16" align="top" /> <a href="/'.$page->uri[0].'/rassilki/?sid='.$sbscrber_id.'&crc='.$sbscrber_crc.$category.'&act='.$ico.'"'.(($ico == 'delete') ? "onclick=\"if(confirm('Вы уверены?{$warning}')) return true; else return false;\"" : "").'>'.$txt.'</a></nobr>';
        ?>
        <tr>
            <td class="td-left">
                <nobr><?=$bold_o.$item['user_full_name'].$bold_c?></nobr>
            </td>
            <td class="td-left">
                <?=$bold_o.$prefix.$item['group_title'].$bold_c?>
            </td>
            <td class="td-left"><?=$button?></td>
        </tr>
        <?php } ?>
    </table>
</div>
