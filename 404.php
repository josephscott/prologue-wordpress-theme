<?php get_header( ); ?>

<div id="main">


<div class="post">
	<h2><?php _e( 'Hi there! You seem to be lost.', 'prologue' ); ?></h2>

	<div class="entry">
		<p><?php _e( 'The address you tried going to doesn&rsquo;t exist on our blog. Don’t worry. It&rsquo;s possible that the page you&rsquo;re looking for has been moved to a different address or you may have mis-typed the address.', 'prologue' );  ?></p>
		<p><?php _e( 'Perhaps searching will help.', 'prologue' );  ?></p>
		<?php get_search_form(); ?>
	</div> <!-- // entry -->
</div>


	</ul>
</div> <!-- // main -->

<?php
get_footer( );
