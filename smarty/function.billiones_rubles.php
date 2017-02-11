<?php

require_once('Cache.php');
require_once('DB.php');

function smarty_function_billiones_rubles($params, &$smarty)
{
    $key_name = "show_billiones_rubles";
    $key_name2 = "show_billiones_rubles_old";

    $_glob_sum = Cache::Get($key_name);
    $_glob_sum_old = Cache::Get($key_name2);

    $_glob_sum_inc = 9; // если не можем вычислить прирост суммы в секунду, пусть будет такой
    if (!empty($_glob_sum) && !empty($_glob_sum_old)){
        $_glob_sum_inc = round(((int)$_glob_sum - (int)$_glob_sum_old)/120, 0);
    }
    $_glob_sum_inc = ($_glob_sum_inc == 0) ? 9 : $_glob_sum_inc;

    if (empty($_glob_sum)){
        $sql = "select SUM(JC_bill_goods.good_dohod) as sum FROM  `JC_bill_goods` , `JC_bills` where JC_bill_goods.bill_id=JC_bills.bill_id and JC_bills.bill_pay_status='paid'";
        $_sum_rs = DB::QueryRow($sql);
        $_glob_sum = round($_sum_rs['sum'], 0);
        Cache::Set($key_name, $_glob_sum, 120);
        if(empty($_glob_sum_old))
            Cache::Set($key_name2, $_glob_sum);
        else
        {
            $_glob_sum_old = $_glob_sum_old + ((int)$_glob_sum - (int)$_glob_sum_old)/240; // старое значение увеличиваем на половину суммы прироста
            Cache::Set($key_name2, $_glob_sum_old);
        }
    }
    return "<style tyle=\"text/css\">\n".
            "@import \"/media/countup/jquery.countdown.css?2\" screen;\n".
            "</style>\n".
            "<script language=\"JavaScript\" src=\"/media/countup/jquery.countdown.js?3\"></script>\n".
            "<script language=\"JavaScript\" type=\"text/javascript\">
        $(function(){
            $('#countdown').countdown({
                bsumm    : {$_glob_sum},
                incr    : {$_glob_sum_inc},
            });
        });
    </script>";
}

?>
