<?php
/**
 * Тестируем API сервиса
 */
 include('../common.php'); 
 
 $user_rs =  ShopUser::GetByName('admin');
echo "go"; 
 $send_data = array(
			'user_name' => 'admin',
			'rid[0]' => 'test-serial',
			'lead_name' => 'KursBesplatno',
			'lead_email' => 'KursBesplatno@email.ru',
			'lead_phone' => '+788888888',
			'lead_city' => 'City',
			'aff' => 223,
			'tag' => 'this is tag',
			'ad' => 1216,
			'doneurl2' => '',
			'service' => 'KursBesplatno',
			);
	
$send_data['hash'] = GetHash($send_data, $user_rs);

echo (Send('http://admin.justclicktest.info/api/AddLeadToGroup', $send_data));

// =======================
function Send($url, $data)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // выводим ответ в браузер

	$res = curl_exec($ch);

	curl_close($ch);
	return $res;
}
	function GetHash($params, $user_rs) {
		$params = http_build_query($params);
		$user_id = $user_rs['user_name'];
		$secret = $user_rs['user_rps_key'];
		$params = "{$params}::{$user_id}::{$secret}";
		return md5($params);
	}
?>