<?php

	function get_webfinger_data($diaspora_handle) {
		list($user, $host) = split("@", $diaspora_handle);
	
		$host_meta = file_get_contents("http://" . $host . "/.well-known/host-meta");
	
		$webfinger_template = '';
	
		$dom = new DOMDocument();
		$dom->loadXML($host_meta);
		$elements = $dom->getElementsByTagName("Link");
		for($i=0; $i<$elements->length; $i++) {
			$item = $elements->item($i);
			$rel_attribute = $item->attributes->getNamedItem("rel");
			if( $rel_attribute ) {
				if( $rel_attribute->nodeValue == 'lrdd' ) {
					$webfinger_template = $item->attributes->getNamedItem("template")->nodeValue;
				}
			}
		}
	
		$webfinger_url = str_replace("{uri}", $diaspora_handle, $webfinger_template);
		$webfinger = file_get_contents($webfinger_url);
		$webfinger = str_replace("&quot;", '"', $webfinger);
	
		$hcard_url = '';
		$seed_location = '';
		$guid = '';
		$profile_page = '';
		$pubkey = '';
	
		$dom = new DOMDocument();
		$dom->loadXML($webfinger);
		$elements = $dom->getElementsByTagName("Link");
		for($i=0; $i<$elements->length; $i++) {
			$item = $elements->item($i);
			$rel_attribute = $item->attributes->getNamedItem("rel");
			if( $rel_attribute ) {
				if( $rel_attribute->nodeValue == 'http://microformats.org/profile/hcard' ) {
					$hcard_url = $item->attributes->getNamedItem("href")->nodeValue;
				}
				if( $rel_attribute->nodeValue == 'http://joindiaspora.com/seed_location' ) {
					$seed_location = $item->attributes->getNamedItem("href")->nodeValue;
				}
				if( $rel_attribute->nodeValue == 'http://joindiaspora.com/guid' ) {
					$guid = $item->attributes->getNamedItem("href")->nodeValue;
				}
				if( $rel_attribute->nodeValue == 'http://webfinger.net/rel/profile-page' ) {
					$profile_page = $item->attributes->getNamedItem("href")->nodeValue;
				}
				if( $rel_attribute->nodeValue == 'diaspora-public-key' ) {
					$pubkey = base64_decode($item->attributes->getNamedItem("href")->nodeValue);
				}
			}
		}
		
		$hcard = file_get_contents($hcard_url);
		
		$full_name = '';
		$image_url = '';
		
		$dom = new DOMDocument();
		$dom->loadHTML($hcard);
		$elements = $dom->getElementsByTagName("dl");
		for($i=0; $i<$elements->length; $i++) {
			$item = $elements->item($i);
			$class_attribute = $item->attributes->getNamedItem("class");
			if( $class_attribute ) {
				if( $class_attribute->nodeValue == 'entity_fn' ) {
					$full_name = $item->getElementsByTagName('span')->item(0)->nodeValue;
				}
				if( $class_attribute->nodeValue == 'entity_photo_small' ) {
					$image_url = $item->getElementsByTagName('img')->item(0)->attributes->getNamedItem("src")->nodeValue;
				}
			}
		}
		
		return array(
			'hcard_url' => $hcard_url,
			'seed_location' => $seed_location,
			'guid' => $guid,
			'profile_page' => $profile_page,
			'pubkey' => $pubkey,
			'full_name' => $full_name,
			'image_url' => $image_url
		);
	}

?>