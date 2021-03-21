<?php
$content_width = 462;

add_filter( 'the_content', 'make_clickable' );

add_theme_support( 'automatic-feed-links' );

function get_recent_post_ids( $return_as_string = true ) {
	global $wpdb;

	$recent_ids =  (array) $wpdb->get_results( "
		SELECT MAX(ID) AS post_id
		FROM {$wpdb->posts}
		WHERE post_type = 'post'
		  AND post_status = 'publish'
		GROUP BY post_author
		ORDER BY post_date_gmt DESC
	", ARRAY_A );

	if( $return_as_string === true ) {
		$ids_string = '';
		foreach( $recent_ids as $post_id ) {
			$ids_string .= "{$post_id['post_id']}, ";
		}

		// Remove trailing comma
		$ids_string = substr( $ids_string, 0, -2 );

		return $ids_string;
	}

	$ids = array( );
	foreach( $recent_ids as $post_id ) {
		$ids[] = $post_id['post_id'];
	}

	return $ids;
}

function prologue_recent_projects_widget( $args ) {
	extract( $args );
	$options = get_option( 'prologue_recent_projects' );

	$title = empty( $options['title'] ) ? __( 'Recent Tags' ) : $options['title'];
	$num_to_show = empty( $options['num_to_show'] ) ? 35 : $options['num_to_show'];

	$num_to_show = (int) $num_to_show;

	$before = $before_widget;
	$before .= $before_title . $title . $after_title;

	$after = $after_widget;

	echo prologue_recent_projects( $num_to_show, $before, $after );
}

function prologue_recent_projects( $num_to_show = 35, $before = '', $after = '' ) {
	$cache = wp_cache_get( 'prologue_theme_tag_list', '' );
	if( !empty( $cache[$num_to_show] ) ) {
		$recent_tags = $cache[$num_to_show];
	}
	else {
		$all_tags = (array) get_tags( array( 'get' => 'all' ) );

		$recent_tags = array( );
		foreach( $all_tags as $tag ) {
			if( $tag->count < 1 )
				continue;

			$tag_posts = get_objects_in_term( $tag->term_id, 'post_tag' );
			$recent_post_id = max( $tag_posts );
			$recent_tags[$tag->term_id] = $recent_post_id;
		}

		arsort( $recent_tags );

		$num_tags = count( $recent_tags );
		if( $num_tags > $num_to_show ) {
			$reduce_by = (int) $num_tags - $num_to_show;

			for( $i = 0; $i < $reduce_by; $i++ ) {
				array_pop( $recent_tags );
			}
		}

		wp_cache_set( 'prologue_theme_tag_list', array( $num_to_show => $recent_tags ) );
	}

	echo $before;
	echo "<ul>\n";

	foreach( $recent_tags as $term_id => $post_id ) {
		$tag = get_term( $term_id, 'post_tag' );
		$tag_link = get_tag_link( $tag->term_id );
?>

<li>
<a class="rss" href="<?php echo esc_url( get_tag_feed_link( $tag->term_id ) ); ?>">RSS</a>&nbsp;<a href="<?php echo esc_url( $tag_link ); ?>"><?php echo esc_html( $tag->name ); ?></a>&nbsp;(&nbsp;<?php echo esc_html( $tag->count ); ?>&nbsp;)
</li>

<?php
	} // foreach get_tags
?>

	</ul>

<p><a class="allrss" href="<?php bloginfo( 'rss2_url' ); ?>">All Updates RSS</a></p>

<?php
	echo $after;
}

function prologue_flush_tag_cache( ) {
	wp_cache_delete( 'prologue_theme_tag_list' );
}
add_action( 'save_post', 'prologue_flush_tag_cache' );

function prologue_recent_projects_control( ) {
	$options = $newoptions = get_option( 'prologue_recent_projects' );

	if( $_POST['prologue_submit'] ) {
		$newoptions['title'] = strip_tags( stripslashes( $_POST['prologue_title'] ) );
		$newoptions['num_to_show'] = strip_tags( stripslashes( $_POST['prologue_num_to_show'] ) );
	}

	if( $options != $newoptions ) {
		$options = $newoptions;
		update_option( 'prologue_recent_projects', $options );
	}

	$title = esc_attr( $options['title'] );
	$num_to_show = esc_attr( $options['num_to_show'] );
?>

<input type="hidden" name="prologue_submit" id="prologue_submit" value="1" />

<p><label for="prologue_title"><?php _e('Title:') ?>
<input type="text" class="widefat" id="prologue_title" name="prologue_title" value="<?php echo esc_attr( $title ); ?>" />
</label></p>

<p><label for="prologue_num_to_show"><?php _e('Num of tags to show:') ?>
<input type="text" class="widefat" id="prologue_num_to_show" name="prologue_num_to_show" value="<?php echo esc_attr( $num_to_show ); ?>" />
</label></p>

<?php
}
wp_register_sidebar_widget( 'prologue_recent_projects_widget', __( 'Recent Projects' ), 'prologue_recent_projects_widget' );
wp_register_widget_control( 'prologue_recent_projects_widget', __( 'Recent Projects' ), 'prologue_recent_projects_control' );

function load_javascript( ) {
//	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_print_scripts', 'load_javascript' );

register_sidebar( [
	'name' => 'Sidebar 1',
	'id' => 'sidebar-1'
] );


define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/there-is-no-image.jpg');
define('HEADER_IMAGE_WIDTH', 726);
define('HEADER_IMAGE_HEIGHT', 150);
define('NO_HEADER_TEXT', true);

function prologue_admin_header_style( ) {
?>
<style type="text/css">
#headimg h1, #desc {
	display: none;
}
#headimg {
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
}
</style>
<?php
}
add_theme_support( 'custom-header' );


function prologue_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">
	<div id="div-comment-<?php comment_ID() ?>">
	<?php echo get_avatar( $comment->comment_author_email, 32 ); ?>
	<h4>
		<?php comment_author_link( ); ?>
		<span class="meta"><?php comment_time( ); ?> on <?php comment_date( ); ?> | <a href="#comment-<?php comment_ID( ); ?>">#</a><?php echo comment_reply_link(array('add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ')) ?><?php edit_comment_link( __( 'e' ), ' | ',''); ?></span>
	</h4>
	<?php comment_text( ); ?>
	</div>

<?php
}

function prologue_comment_noreply($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">
	<?php echo get_avatar( $comment->comment_author_email, 32 ); ?>
	<h4>
		<?php comment_author_link( ); ?>
		<span class="meta"><?php comment_time( ); ?> on <?php comment_date( ); ?> | <a href="#comment-<?php comment_ID( ); ?>">#</a> <?php edit_comment_link( __( 'e' ), '&nbsp;|&nbsp;',''); ?></span>
	</h4>
	<?php comment_text( ); ?>

<?php
}

function prologue_comment_fields($fields) {
	$comment_field = '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>';

	$fields['author'] = '<p class="comment-form-author">' . '<label for="author">' . __( 'Name (required)' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>';

	$fields['email'] = '<p class="comment-form-email"><label for="email">' . __( 'Email (required)' ) . '</label><input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>';

	array_unshift( $fields, $comment_field );

	return $fields;
}
add_filter( 'comment_form_default_fields', 'prologue_comment_fields' );

function prologue_comment_form_defaults($defaults) {
	if ( ! is_user_logged_in() )
		$defaults['comment_field'] = '';

	$defaults['comment_notes_before'] = '';
	$defaults['comment_notes_after'] = '';

	return $defaults;
}
add_filter( 'comment_form_defaults', 'prologue_comment_form_defaults' );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @since Prologue 1.4.2
 */
function prologue_wp_title( $title, $sep ) {
	global $page, $paged;

	if ( is_feed() )
		return $title;

	// Add the blog name
	$title .= get_bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $sep $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $sep " . sprintf( __( 'Page %s', 'prologue' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'prologue_wp_title', 10, 2 );

/**
 * WP.com: Show a nice admin message saying Prologue was replaced by P2.
 */
function prologue_add_notice() {
	$current_theme = wp_get_theme()->Name;

	if ( ! get_user_meta( get_current_user_id(), "$current_theme-theme-update", true ) ) {
		add_settings_error(
			'theme-update',
			'theme-update',
			sprintf(
				__( 'Howdy! Your current theme, <em>%1$s</em>, has seen an update in the form of a brand new theme, <a href="%3$s">%2$s</a>. If you want to try out the cool new features in P2 just head over to <a href="%3$s"><em>Appearance</em>&rarr;<em>Themes</em></a> and activate <a href="%4$s">P2</a>&mdash;or check out any one of the other fantastic themes we&rsquo;ve been adding lately. %5$s', 'prologue' ),
				$current_theme, // Old theme
				'P2', // New theme
				admin_url( 'themes.php?s=p2' ), // Link to new theme
				esc_url( 'https://wordpress.com/themes/p2/' ), // Link to announcement post
				sprintf( '<a id="dismiss-theme-update" class="alignright" style="font-size: 16px;" title="%s" href="#">&times;</a>', __( 'Dismiss', 'prologue' ) ) // Dismiss
			),
			'updated'
		);

		remove_action( 'admin_notices', 'show_tip' );
		if ( ! has_filter( 'admin_notices', 'settings_errors' ) )
			add_action( 'admin_notices', 'settings_errors' );

		wp_enqueue_script( 'dismiss-theme-update', get_template_directory_uri() . '/js/dismiss-theme-update.js', array( 'jquery' ), 20130225 );
		wp_localize_script( 'dismiss-theme-update', 'dismissThemeUpdate', array(
			'theme' => $current_theme,
			'nonce' => wp_create_nonce( "$current_theme-theme-update" ),
		) );
	}
}
add_action( 'admin_init', 'prologue_add_notice' );

/**
 * Updates user setting when theme update notice was dismissed.
 */
function prologue_dismiss_theme_update() {
	$current_theme = wp_get_theme()->Name;
	check_ajax_referer( "$current_theme-theme-update", 'nonce' );

	if ( $_REQUEST['theme'] == $current_theme ) {
		update_user_meta( get_current_user_id(), "$current_theme-theme-update", true );
		wp_die( 1 );
	}
}
add_action( 'wp_ajax_dismiss_theme_update', 'prologue_dismiss_theme_update' );
