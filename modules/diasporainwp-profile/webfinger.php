<?php
	$user_data = get_userdata(1);
	$pubkey = base64_encode(get_user_meta(1, "diasporawp_public_key", true));
?>
<?php echo"<?xml version='1.0' encoding='UTF-8'?>" ?>
<XRD xmlns="http://docs.oasis-open.org/ns/xri/xrd-1.0">
	<Subject>acct:<?php echo $webfinger_user ?></Subject>
	<Alias>"<?php bloginfo('url'); ?>"</Alias>
	<Link rel="http://microformats.org/profile/hcard" type="text/html" href="<?php bloginfo('url'); ?>/index.php?hcard=<?php echo md5($webfinger_user); ?>"/>
	<Link rel="http://joindiaspora.com/seed_location" type = 'text/html' href="<?php bloginfo('url'); ?>"/>
	<Link rel="http://joindiaspora.com/guid" type = 'text/html' href="<?php echo md5($webfinger_user); ?>"/>

	<Link rel='http://webfinger.net/rel/profile-page' type='text/html' href="http://www.gravatar.com/<?php echo md5( strtolower( trim( $user_data->user_email ) ) ); ?>"/>
	<Link rel="http://schemas.google.com/g/2010#updates-from" type="application/atom+xml" href="<?php bloginfo('atom_url'); ?>"/>

	<Link rel="diaspora-public-key" type = 'RSA' href="<?php echo  $pubkey; ?>"/>
</XRD>
