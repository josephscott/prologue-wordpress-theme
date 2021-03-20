
<div id="sidebar">
	<ul>

<?php
if( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) {
	$before = "<li><h2>Recent Projects</h2>\n";
	$after = "</li>\n";

	$num_to_show = 35;

	echo prologue_recent_projects( $num_to_show, $before, $after );
} // if dynamic_sidebar
?>

		<li class="credits">
			<p><a href="https://wordpress.com/" rel="generator">Get a free blog at WordPress.com</a><br /> <?php printf( __( 'Theme: %1$s by %2$s.' ), 'Prologue', '<a href="https://wordpress.com/themes/" rel="designer">WordPress.com</a>' ); ?></p>
		</li>
	</ul>
</div> <!-- // sidebar -->
