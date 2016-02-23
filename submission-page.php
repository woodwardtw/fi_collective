<?php
/**
 * Template Name: Submissions Page
 * The template for displaying all the submissions images randomly
 *
 *
 * @package fi_collective
 */

get_header(); ?>

 <?php
	$args=array(
	'post_type'=> 'post',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'rand',
	'tag' => 'submission',
	);
	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
				echo '<div class="col-md-4 scene"><a href="' . get_the_permalink() . '">';
				if ( has_post_thumbnail() ) {
			                	the_post_thumbnail('scene-thumb',array( 'class' => 'responsive scene-thumb' ));
			      } 

			      elseif ( in_category( 'audio' )) {
			      	echo '<img src="'. htmlspecialchars(site_url('/wp-content/themes/fi_collective/imgs/default-thumb-audio.jpg')).'" class=" responsive scene-thumb" />';
			      }

			      else {           	
				echo '<img src="'. htmlspecialchars(site_url('/wp-content/themes/fi_collective/imgs/default-thumb-big.jpg')).'" class="responsive scene-thumb" />';}
				echo '<div class="sub-title">' . get_the_title() .'</div></div></a>'; 
				}
						}
						/* Restore original Post Data */
	wp_reset_postdata();
?>


<?php get_footer(); ?>

