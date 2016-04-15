<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package fi_collective
 */

get_header(); ?>

	<?php // add the class "panel" below here to wrap the content-padder in Bootstrap style ;) ?>
	<div class="content-padder">

		<?php if ( have_posts() ) : ?>

			<header>
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							/* Queue the first post, that way we know
							 * what author we're dealing with (if that is the case).
							*/
							the_post();
							printf( __( 'Author: %s', 'fi_collective' ), '<span class="vcard">' . get_the_author() . '</span>' );
							/* Since we called the_post() above, we need to
							 * rewind the loop back to the beginning that way
							 * we can run the loop properly, in full.
							 */
							rewind_posts();

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'fi_collective' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'fi_collective' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'fi_collective' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'fi_collective' );

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'fi_collective');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'fi_collective' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'fi_collective' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'fi_collective' );

						else :
							_e( 'Scenes', 'fi_collective' );

						endif;
					?>
				</h1>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				 <?php
	
				echo '<div class="col-md-3 scene"><a href="' . get_the_permalink() . '">';
				if ( has_post_thumbnail() ) {
			                	the_post_thumbnail('front-page-thumb',array( 'class' => 'responsive scene-thumb' ));
			      } 

			      elseif ( in_category( 'audio' )) {
			      	echo '<img src="'. htmlspecialchars(site_url('/wp-content/themes/fi_collective/imgs/default-thumb-audio.jpg')).'" class="img-front front-page-thumb wp-post-image" />';
			      }

			      else {           	
				echo '<img src="'. htmlspecialchars(site_url('/wp-content/themes/fi_collective/imgs/default-thumb-big.jpg')).'" class="img-front front-page-thumb wp-post-image" />';}
				echo get_the_title() .'</div></a>'; 

		
						/* Restore original Post Data */
	wp_reset_postdata();
?>


			<?php endwhile; ?>

			<?php fi_collective_content_nav( 'nav-below' ); ?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'archive' ); ?>

		<?php endif; ?>

	</div><!-- .content-padder -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
