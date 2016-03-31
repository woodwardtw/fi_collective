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
	<?php echo the_post_thumbnail('scene-thumb' , array( 'class' => 'alignleft img-fluid' ));?>
		<div class="tips">
		<h2>Tips</h2>
			<img src="https://c2.staticflickr.com/2/1605/24459888894_7a35bf01a1.jpg" class="responsive">
		</div>
	</div>
	<div class="col-md-6 needs well well-lg">
		<h2>We need  things. . . </h2>	
		<h3>images</h3>
			<?php
   			global $post;
    		$post_slug=$post->post_name;
			$mediatype = 'image';
			$scene = $post_slug;
			$sub_tag = get_term_by('slug','submission','post_tag');
			$sub_tag = $sub_tag->term_taxonomy_id;
			$posts = get_posts(array(
				'numberposts' => -1,
				'post_type' => 'post',
				'category_name' => $mediatype .'+' . $scene,
				'tag__not_in' => array( $sub_tag ),				)
			);

			if($posts)
			{

				foreach($posts as $post)
				{
					$cats = array();
					foreach (get_the_category() as $c) {
					$cat = get_category($c);
					array_push($cats, $cat->term_id);
					}

					if (sizeOf($cats) > 0) {
					$post_categories = implode(', ', $cats);
					} else {
					$post_categories = 'Not Assigned';
					};
 
					echo '<div class="asset-list"><div class="asset-name">' . get_the_title($post->ID) . '</div><div class="asset-links"><a href="' . get_site_url() . '/form/?title=' . $scene . '&cats=' . $post_categories . '&fi_filename=' . $scene . '-' .  urlencode(get_the_title($post->ID)) . '" class="clickit"> Submit</a> <a href="' . get_site_url() . '/category/'. $scene .'+'. $mediatype .'" class="clickit">View</a></div></div>'  ;
				}

			}
			else {
				echo 'No images need. Thanks.';
			}

			?>
			<?php
			$mediatype = 'image-alpha';
			$posts = get_posts(array(
				'numberposts' => -1,
				'post_type' => 'post',
				'category_name' => $mediatype .'+' . $scene,
				'tag__not_in' => array( $sub_tag ),				)
			);

			if($posts)
			{

				foreach($posts as $post)
				{
					$cats = array();
					foreach (get_the_category() as $c) {
					$cat = get_category($c);
					array_push($cats, $cat->term_id);
					}

					if (sizeOf($cats) > 0) {
					$post_categories = implode(', ', $cats);
					} else {
					$post_categories = 'Not Assigned';
					};
 
					echo '<div class="asset-list alpha"><div class="asset-name">' . get_the_title($post->ID) . '</div><div class="asset-links"><a href="' . get_site_url() . '/form/?title=' . urlencode(get_the_title($post->ID)) . '&cats=' . urlencode($post_categories) . ', 13&fi_filename=' . $scene . '-' .  urlencode(get_the_title($post->ID)) .'" class="clickit"> Submit</a> <a href="' . get_site_url() . '/category/'. $scene .'+'. $mediatype .'" class="clickit">View</a></div></div>'  ;
				}

			}
			else {
			}

			?>
			<h3>audio</h3>
			<?php
			$mediatype = 'audio';
			$posts = get_posts(array(
				'numberposts' => -1,
				'post_type' => 'post',
				'category_name' => $mediatype .'+' . $scene,
				'tag__not_in' => array( $sub_tag ),				)
			);

			if($posts)
			{

				foreach($posts as $post)
				{
					$cats = array();
					foreach (get_the_category() as $c) {
					$cat = get_category($c);
					array_push($cats, $cat->term_id);
					}

					if (sizeOf($cats) > 0) {
					$post_categories = implode(', ', $cats);
					} else {
					$post_categories = 'Not Assigned';
					};
 
					echo '<div class="asset-list audio"><div class="asset-name">' . get_the_title($post->ID) . '</div><div class="asset-links"><a href="' . get_site_url() . '/form-audio/?title=' . urlencode(get_the_title($post->ID)) . '&cats=' . $post_categories . ',13&fi_filename=' . $scene . '-' .  urlencode(get_the_title($post->ID)) . '" class="clickit"> Submit</a> <a href="' . get_site_url() . '/category/'. $scene .'+'. $mediatype .'" class="clickit">View</a></div></div>' ;
				}

			}
			else {
			}

			?>
	<!--	<h3>other</h3>
			<?php
			$mediatype = 'other';
			$posts = get_posts(array(
				'numberposts' => -1,
				'post_type' => 'post',
				'category_name' => $mediatype .'+' . $scene,
				'tag__not_in' => array( $sub_tag ),				)
			);

			if($posts)
			{

				foreach($posts as $post)
				{
					$cats = array();
					foreach (get_the_category() as $c) {
					$cat = get_category($c);
					array_push($cats, $cat->term_id);
					}

					if (sizeOf($cats) > 0) {
					$post_categories = implode(', ', $cats);
					} else {
					$post_categories = 'Not Assigned';
					};
 
					echo '<div class="asset-list other"><div class="asset-name">' . get_the_title($post->ID) . '</div><div class="asset-links"><a href="' . get_site_url() . '/form/?title=' . urlencode(get_the_title($post->ID)) . '&cats=' . $post_categories . ',13" class="clickit"> Submit</a> <a href="' . get_site_url() . '/category/scene/scene-1a+image/'. $scene .'+'. $mediatype .'" class="clickit">View</a></div></div>' ;
				}

			}
			else {
			}

			?>-->

	
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
		<?php endif; // End if 'post' == get_post_type() ?>

		
		<?php endif; ?>

	</footer><!-- .entry-meta -->
</article><!-- #post-## -->
