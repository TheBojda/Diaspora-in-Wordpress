<?php

	ob_start();

	require_once "webfinger.php";
	require_once "crypto.php";

	function decrypt_data($data, $iv, $key) {
		$cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

		// initialize encryption handle
		if (mcrypt_generic_init($cypher, $key, $iv) != -1) {
			// decrypt
			$decrypted = mdecrypt_generic($cypher, $data);

			// clean up
			mcrypt_generic_deinit($cypher);
			mcrypt_module_close($cypher);

			return $decrypted;
		}

		return false;
	}
	
	function normalize_xml($xml) {
		return trim(str_replace(array("\n", "\r", "\\", chr(1)), "", $xml));
	}

	function verify_signature($me_data, $sig, $pubkey) {
		$type = 'application/xml';
		$encoding = 'base64url';
		$alg = 'RSA-SHA256';
		$original_data = str_replace(array(" ","\t","\r","\n"), array("","","",""), $me_data);

		$signed_data = $original_data  . '.' . base64url_encode($type) . '.' 
			. base64url_encode($encoding) . '.' . base64url_encode($alg) ;
		echo "signed_data: $signed_data\n";
		
		$pkey = openssl_get_publickey($pubkey);
		var_dump($pkey);
		
		//return openssl_verify($signed_data, base64url_decode($sig), $pkey, 'sha256');
		
		// from friendica
		$rawsig = '';
		openssl_public_decrypt(base64url_decode($sig),&$rawsig,$pkey);
		return ($rawsig && substr($rawsig,-32) === hash('sha256',$signed_data,true));
	}

	function get_user_pkey($handle) {
		global $wpdb;
		$table_name = $wpdb->prefix . "diw_contacts";
	
		$sql = "SELECT * FROM $table_name WHERE diaspora_handle = '" . $handle . "'";
		echo "sql: $sql\n";
		$diaspora_user = $wpdb->get_row($sql);
		var_dump($diaspora_user);
		return $diaspora_user->pub_key;
	}

	$post = $_POST['xml'];
	$xml = trim(urldecode($post));
	
	echo "xml: ".normalize_xml($xml)."\n";
	
	$dom = new DOMDocument();
	$me_ns = "http://salmon-protocol.org/ns/magic-env";
	$dom->loadXML(normalize_xml($xml));
	$encrypted_header = $dom->getElementsByTagName("encrypted_header")->item(0)->nodeValue;
	$data = $dom->getElementsByTagNameNS($me_ns, "data")->item(0)->nodeValue;
	$sig = $dom->getElementsByTagNameNS($me_ns, "sig")->item(0)->nodeValue;

	$me_data = $data;

	echo "encrypted_header: ".$encrypted_header."\n";

	$encrypted_header = json_decode(base64_decode($encrypted_header));

	$aes_key = base64_decode($encrypted_header->aes_key);
	$private_key = openssl_pkey_get_private(get_user_meta(1, "diasporawp_private_key", true));
	$decrypted = "";
	openssl_private_decrypt($aes_key, &$decrypted, $private_key); 
	$aes_key = json_decode($decrypted);

	echo "decrypted: $decrypted\n";

	$decrypted_header = decrypt_data(
		base64_decode($encrypted_header->ciphertext),
		base64_decode($aes_key->iv), 
		base64_decode($aes_key->key));

	echo "decrypted_header: $decrypted_header\n";

	$dom = new DOMDocument();
	$dom->loadXML(normalize_xml($decrypted_header));
	$iv = $dom->getElementsByTagName("iv")->item(0)->nodeValue;
	$aes_key = $dom->getElementsByTagName("aes_key")->item(0)->nodeValue;

	$data = decrypt_data(
		base64_decode(base64_decode($data)),
		base64_decode($iv), 
		base64_decode($aes_key));

	echo "data: $data\n";

	$startpos = strpos($data, "<XML>");
	$endpos = strpos($data, "</XML>");

	$data = substr($data, $startpos + 5, $endpos - $startpos - 5);

	global $wpdb;
	$table_name = $wpdb->prefix . "diw_contacts";
	
	$dom = new DOMDocument();
	$dom->loadXML(normalize_xml($data));
	$elements = $dom->getElementsByTagName("request");
	if( $elements->length > 0 ) {
		$item = $elements->item(0);
		$sender_handle = $item->getElementsByTagName("sender_handle")->item(0)->nodeValue;
		//echo "sender_handle: $sender_handle<br/>";
		$webfinger_data = get_webfinger_data($sender_handle);
		
		$sign_check = verify_signature($me_data, $sig, $webfinger_data['pubkey']);
		echo "sign_check: $sign_check\n";
		
		if($sign_check) {
			$wpdb->query("DELETE FROM $table_name WHERE diaspora_handle='$sender_handle';");
			$wpdb->insert($table_name, array(
				'diaspora_handle' => $sender_handle,
				'pub_key' => $webfinger_data['pubkey'],
				'profile_page' => $webfinger_data['profile_page'],
				'guid' => $webfinger_data['guid'],
				'seed_location' => $webfinger_data['seed_location'],
				'hcard_url' => $webfinger_data['hcard_url'],
				'full_name' => $webfinger_data['full_name'],
				'image_url' => $webfinger_data['image_url']
			));
			echo "add contact: $sender_handle\n";
		}
	}
	$elements = $dom->getElementsByTagName("retraction");
	if( $elements->length > 0 ) {
		$item = $elements->item(0);
		$sender_handle = $item->getElementsByTagName("diaspora_handle")->item(0)->nodeValue;
		echo "sender_handle: $sender_handle<br/>\n";
		
		$sign_check = verify_signature($me_data, $sig, get_user_pkey($sender_handle));
		echo "sign_check: $sign_check\n";
		
		if($sign_check) {
			$wpdb->query("DELETE FROM $table_name WHERE diaspora_handle='$sender_handle';");
			echo "remove contact: $sender_handle\n";
		}
	}

	file_put_contents('log.txt', ob_get_contents(), FILE_APPEND);
	ob_end_clean();
?>