<?php
	global $wpdb;
	
	$table_name = $wpdb->prefix . "diw_contacts";
?>
<form action="#" method="post" enctype="multipart/form-data">
	<h2>Diaspora contacts</h2>
	
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row">
					<!--
					<table>
						<tr>
							<td><strong>My contacts</strong></td>
						</tr>
						<tr>
							<td>Family</td>
						</tr>
					</table>
					-->
				</th>
				<td>
					<?php 
						$contacts = $wpdb->get_results("SELECT * FROM $table_name;"); 
						foreach($contacts as $contact):
					?>
					<table>
						<tr>
							<td><img src="<?php echo $contact->image_url; ?>" /></td>
							<td>
								<strong><a target="_blank" href="<?php echo $contact->profile_page; ?>"><?php echo utf8_decode($contact->full_name);?></a></strong><br/>
								<?php echo $contact->diaspora_handle; ?>
							</td>
						</tr>
					</table>
					<hr align="left" width="450"/>
					<?php
						endforeach;
					?>
				</td>
			</tr>
		</tbody>
	</table>

</form>