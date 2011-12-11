<?php echo"<?xml version='1.0' encoding='UTF-8'?>" ?>
<XRD xmlns='http://docs.oasis-open.org/ns/xri/xrd-1.0'
	xmlns:hm='http://host-meta.net/xrd/1.0'>
	<hm:Host><?php bloginfo('url'); ?></hm:Host>
	<Link rel='lrdd'
		template='<?php bloginfo('url'); ?>/index.php?webfinger={uri}'>
		<Title>Resource Descriptor</Title>
	</Link>
</XRD>