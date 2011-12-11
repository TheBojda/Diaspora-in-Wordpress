<?php
	$user_data = get_userdata(1);
?>
<div id='content'>
	<h1>Laszlo Fazekas</h1>
	<div id='content_inner'>
		<div class='entity_profile vcard author' id='i'>
			<h2>User profile</h2>
			<dl class='entity_nickname'>
				<dt>Nickname</dt>
				<dd>
					<a class='nickname url uid' href='<?php bloginfo('url'); ?>' rel='me'><?php echo $user_data->first_name;?> <?php echo $user_data->last_name;?></a>
				</dd>
			</dl>
			<dl class='entity_given_name'>
				<dt>First name</dt>
				<dd>
					<span class='given_name'><?php echo $user_data->first_name;?></span>
				</dd>
			</dl>
			<dl class='entity_family_name'>
				<dt>Family name</dt>
				<dd>
					<span class='family_name'><?php echo $user_data->last_name;?></span>
				</dd>
			</dl>
			<dl class='entity_fn'>
				<dt>Full name</dt>
				<dd>
					<span class='fn'><?php echo $user_data->first_name;?> <?php echo $user_data->last_name;?></span>
				</dd>
			</dl>
			<dl class='entity_url'>
				<dt>URL</dt>
				<dd>
					<a class='url' href='<?php bloginfo('url'); ?>' id='pod_location' rel='me'><?php bloginfo('url'); ?></a>
				</dd>
			</dl>
			<dl class='entity_photo'>
				<dt>Photo</dt>
				<dd>
					<?php echo get_avatar( 1, 300 ); ?>
				</dd>
			</dl>
			<dl class='entity_photo_medium'>
				<dt>Photo</dt>
				<dd>
					<?php echo get_avatar( 1, 100 ); ?>
				</dd>
			</dl>
			<dl class='entity_photo_small'>
				<dt>Photo</dt>
				<dd>
					<?php echo get_avatar( 1, 50 ); ?>
				</dd>
			</dl>
			<dl class='entity_searchable'>
				<dt>Searchable</dt>
				<dd>
					<span class='searchable'>true</span>
				</dd>
			</dl>
		</div>
	</div>
</div>