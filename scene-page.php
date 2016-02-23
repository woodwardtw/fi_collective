<?php
/**
 * Template Name: Scene Page
 * The template for displaying all the scene images randomly
 *
 *
 * @package fi_collective
 */

get_header(); ?>

 <?php
	$args=array(
	'post_type'=> 'scenes',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'rand',
	);
	$the_query = new WP_Query($args);
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
				echo '<div class="col-md-4 scene">';
				echo  '<a href="' . get_the_permalink() . '">'.the_post_thumbnail('scene-thumb' , array( 'class' => 'alignleft' )) . '<div class="scene-title">' . get_the_title() . '</div></a>';
				echo get_the_post_thumbnail('scene-thumb') . '</div>';
		}
		} else {
							// no posts found
			echo 'No posts found';
						}
						/* Restore original Post Data */
	wp_reset_postdata();
?>


<?php get_footer(); ?>