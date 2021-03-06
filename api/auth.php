<?php if (!defined ('SYSTEM_ROOT')) exit (); ?>
<?php
	function auth_register ()
	{
		// 获取
		$url = PANEL_URL . '/function.php?mod=register';
		file_put_contents("1.txt", $url);
		$data = array (
			'url' => system_geturl ()
		);
		$header = array (
			'version' => system_getversion ()
		);
		$ret = json_decode (system_fetch ($url, $data, NULL, $header), true);

		// 分析
		if ($ret['code'] == 0) {
			option_update ('api_sid', $ret['sid']);
			option_update ('api_skey', $ret['skey']);
		} else {
			die ('请求skey失败，原因：' . $ret['msg']);
		}
	}
	
	function auth_getsid ()
	{
		return option_getvalue ('api_sid');
	}

	function auth_getskey ()
	{
		return option_getvalue ('api_skey');
	}

	function auth_getsign ($data)
	{
		// 初始化变量
		$key = file_get_contents (SYSTEM_ROOT . '/public.pem');

		// 签名变量
		$data .= '|-_-|' . auth_getskey () . '|-_-|' . time ();
		$data .= '|-_-|' . md5 ($data);

		// RSA加密
		openssl_public_encrypt ($data, $sdata, $key);

		// 返回
		return base64_encode ($sdata);
	}
?>