<?php
/**
 * @package fi_collective
 */
?>


<?php // Styling Tip!

// Want to wrap for example the post content in blog listings with a thin outline in Bootstrap style?
// Just add the class "panel" to the article tag here that starts below.
// Simply replace post_class() with post_class('panel') and check your site!
// Remember to do this for all content templates you want to have this,
// for example content-single.php for the post single view. ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header>
		<h1 class="page-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

		<?php if ( 'post' == get_post_type() ) : ?>
		
		<?php endif; ?>
	</header><!-- .entry-header -->
	<div class="col-md-6">
	<?php echo the_post_thumbnail('scene-thumb' , array( 'class' => 'alignleft' ));?>
	<h2>File Type stuff</h2>
	<img src="https://c2.staticflickr.com/2/1605/24459888894_7a35bf01a1.jpg" class="responsive">
	</div>
	<div class="col-md-6 needs well well-lg">
		Asset info
		<?php

			$values = get_field('media_type');
			if($values)
			{
				echo '<ul>';

				foreach($values as $value)
				{
					echo '<li>' . $value . '</li>';
				}

				echo '</ul>';
			}

			// always good to see exactly what you are working with
			var_dump($values);

			?>

			<?php

			$values = get_field('scene');
			if($values)
			{
				echo '<ul>';

				foreach($values as $value)
				{
					echo '<li>' . $value . '</li>';
				}

				echo '</ul>';
			}

			// always good to see exactly what you are working with
			var_dump($values);

			?>
	</div>

	<footer class="entry-meta">
		<?php if ( 'scenes' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'fi_collective' ) );
				if ( $categories_list && fi_collective_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'fi_collective' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'fi_collective' ) );
				if ( $tags_list ) :
			?>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'fi_collective' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'fi_collective' ), __( '1 Comment', 'fi_collective' ), __( '% Comments', 'fi_collective' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'fi_collective' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
