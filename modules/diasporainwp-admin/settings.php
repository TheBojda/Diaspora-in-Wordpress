<?php
	if($_POST['update_rsa_keys']) {
		update_user_meta(1, "diasporawp_public_key", $_POST['public_key']);
		update_user_meta(1, "diasporawp_private_key", $_POST['private_key']);
	}

	$public_key = get_user_meta(1, "diasporawp_public_key", true);
	$private_key = get_user_meta(1, "diasporawp_private_key", true);
?>
<form action="#" method="post" enctype="multipart/form-data">
	<h2>RSA private/public key</h2>
	
	<pre>
	Generate private key on Linux with OpenSSL:
	openssl genrsa -out private.pem 4096
	
	Generate public key from the private key:
	openssl rsa -in private.pem -pubout -out public.pem
	</pre>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">RSA public key</th>
				<td>
					<textarea name="public_key" cols="85" rows="10"><?php echo $public_key; ?></textarea>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">RSA private key</th>
				<td>
					<textarea name="private_key" cols="85" rows="10"><?php echo $private_key; ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
		
	<input name="update_rsa_keys" type="submit" value="Update RSA keys" />
</form>