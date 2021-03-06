<?php
$user			= get_userdata( $current_user->ID );
$first_name		= esc_attr( $user->first_name );
?>

<div id="postbox">
	<form id="new_post" name="new_post" method="post" action="<?php bloginfo( 'url' ); ?>/">
		<input type="hidden" name="action" value="post" />
		<?php wp_nonce_field( 'new-post' ); ?>

		<?php echo get_avatar( $user->user_email, 48 ); ?>

		<label for="posttext">Hi, <?php echo $first_name; ?>. Whatcha up to?</label>
		<textarea name="posttext" id="posttext" rows="3" cols="60"></textarea>

		<label for="tags">Tag it</label>
		<input type="text" name="tags" id="tags" autocomplete="off" />

		<input id="submit" type="submit" value="Post it" />
	</form>
</div> <!-- // postbox -->
