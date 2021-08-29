<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<?php
      $post_type = get_post_type_object( get_post_type() );
      $post_id = get_the_ID();
      $post_excerpt = get_the_excerpt($post_id);
      if(empty($post_excerpt)){
        $post_excerpt = get_field('header_text', $post_id);
      }
    ?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="searchwp-live-search-result" role="option" id="" aria-selected="false">
			<p class="searchwp-live-search-result-title">
				<?php the_title(); ?>
			</p>
      <?php if($post_excerpt): ?>
        <p class="searchwp-live-search-result-text">
        <?php echo $post_excerpt; ?>
      </p>
      <?php endif; ?>
		</a>
	<?php endwhile; ?>
<?php else : ?>
	<p class="searchwp-live-search-no-results" role="option">
		<em><?php esc_html_e( 'No results found.', 'searchwp-live-ajax-search' ); ?></em>
	</p>
<?php endif; ?>
