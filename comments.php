<?php
if( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME'] ) )
	die( 'Please do not load this page directly. Thanks!' );

if ( post_password_required() ) { ?>
	<p class="nocomments">This post is password protected. Enter the password to view comments.</p>
<?php
	return;
} // if post_password_required

if ( have_comments() ) {
	echo "<h3>Comments</h3>\n";
	echo "<ul id=\"comments\" class=\"commentlist\">\n";

	wp_list_comments(array('callback' => 'prologue_comment'));

	echo "</ul>\n";
	?>
	<div class="navigation">
		<div class="alignleft"><?php previous_comments_link() ?></div>
		<div class="alignright"><?php next_comments_link() ?></div>
	</div>
	<br />
	<?php
} // if comments

comment_form();