<?php
/**
 * fi_collective functions and definitions
 *
 * @package fi_collective
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 750; /* pixels */

if ( ! function_exists( 'fi_collective_setup' ) ) :
/**
 * Set up theme defaults and register support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 */

add_image_size( 'scene-thumb', 555, 312, true );


/*
* Creating a function to create our CPT
*/

function custom_post_type_scene() {

// Set UI labels for Custom Post Type
	$labels = array(
		'name'                => _x( 'Scenes', 'Post Type General Name', 'fi_collective' ),
		'singular_name'       => _x( 'Scene', 'Post Type Singular Name', 'fi_collective' ),
		'menu_name'           => __( 'Scenes', 'fi_collective' ),
		'parent_item_colon'   => __( 'Parent Scene', 'fi_collective' ),
		'all_items'           => __( 'All Scenes', 'fi_collective' ),
		'view_item'           => __( 'View Scene', 'fi_collective' ),
		'add_new_item'        => __( 'Add New Scene', 'fi_collective' ),
		'add_new'             => __( 'Add New', 'fi_collective' ),
		'edit_item'           => __( 'Edit Scene', 'fi_collective' ),
		'update_item'         => __( 'Update Scene', 'fi_collective' ),
		'search_items'        => __( 'Search Scene', 'fi_collective' ),
		'not_found'           => __( 'Not Found', 'fi_collective' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'fi_collective' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'Scenes', 'fi_collective' ),
		'description'         => __( 'Scene info', 'fi_collective' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions', 'custom-fields', ),
		// You can associate this CPT with a taxonomy or custom taxonomy. 
		'taxonomies'          => array( 'scene' ),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 5,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
// Registering your Custom Post Type
	register_post_type( 'Scenes', $args );

}	
add_action( 'init', 'custom_post_type_scene', 0 );

/**
 * Gravity Wiz // Gravity Forms // Rename Uploaded Files
 *
 * Rename uploaded files for Gravity Forms. You can create a static naming template or using merge tags to base names on user input.
 *
 * Features:
 *  + supports single and multi-file upload fields
 *  + flexible naming template with support for static and dynamic values via GF merge tags
 *
 * Uses:
 *  + add a prefix or suffix to file uploads
 *  + include identifying submitted data in the file name like the user's first and last name
 *
 * @version	  1.2
 * @author    David Smith <david@gravitywiz.com>
 * @license   GPL-2.0+
 * @link      http://gravitywiz.com/...
 */
class GW_Rename_Uploaded_Files {

    public function __construct( $args = array() ) {

        // set our default arguments, parse against the provided arguments, and store for use throughout the class
        $this->_args = wp_parse_args( $args, array(
            'form_id'  => false,
            'field_id' => false,
	        'template' => ''
        ) );

        // do version check in the init to make sure if GF is going to be loaded, it is already loaded
        add_action( 'init', array( $this, 'init' ) );

    }

    public function init() {

        // make sure we're running the required minimum version of Gravity Forms
        if( ! property_exists( 'GFCommon', 'version' ) || ! version_compare( GFCommon::$version, '1.8', '>=' ) ) {
            return;
        }

	    add_action( 'gform_pre_submission', array( $this, 'rename_uploaded_files' ) );

    }

	function rename_uploaded_files( $form ) {

		if( ! $this->is_applicable_form( $form ) ) {
			return;
		}

		foreach( $form['fields'] as &$field ) {

			if( ! $this->is_applicable_field( $field ) ) {
				continue;
			}

			$is_multi_file  = rgar( $field, 'multipleFiles' ) == true;
			$input_name     = sprintf( 'input_%s', $field['id'] );
			$uploaded_files = rgars( GFFormsModel::$uploaded_files, "{$form['id']}/{$input_name}" );

			if( $is_multi_file && ! empty( $uploaded_files ) && is_array( $uploaded_files ) ) {

				foreach( $uploaded_files as &$file ) {
					$file['uploaded_filename'] = $this->rename_file( $file['uploaded_filename'] );
				}

				GFFormsModel::$uploaded_files[ $form['id'] ][ $input_name ] = $uploaded_files;

			} else {

				if( empty( $uploaded_files ) ) {

					$uploaded_files = rgar( $_FILES, $input_name );
					if( empty( $uploaded_files ) || empty( $uploaded_files['name'] ) ) {
						continue;
					}

					$uploaded_files['name'] = $this->rename_file( $uploaded_files['name'] );
					$_FILES[ $input_name ] = $uploaded_files;

				} else {

					$uploaded_files = $this->rename_file( $uploaded_files );
					GFFormsModel::$uploaded_files[ $form['id'] ][ $input_name ] = $uploaded_files;

				}

			}

		}

	}

	function rename_file( $filename ) {

		$file_info = pathinfo( $filename );
		$new_filename = $this->remove_slashes( $this->get_template_value( $this->_args['template'], GFFormsModel::get_current_lead(), $file_info['filename'] ) );

		return sprintf( '%s.%s', $new_filename, rgar( $file_info, 'extension' ) );
	}

	function get_template_value( $template, $entry, $filename ) {

		$form = GFAPI::get_form( $entry['form_id'] );
		$template = GFCommon::replace_variables( $template, $form, $entry, false, true, false, 'text' );

		// replace our custom "{filename}" psuedo-merge-tag
		$template = str_replace( '{filename}', $filename, $template );

		return $template;
	}

	function remove_slashes( $value ) {
		return stripslashes( str_replace( '/', '', $value ) );
	}

	function is_applicable_form( $form ) {

		$form_id = isset( $form['id'] ) ? $form['id'] : $form;

		return $form_id == $this->_args['form_id'];
	}

	function is_applicable_field( $field ) {

		$is_file_upload_field   = in_array( GFFormsModel::get_input_type( $field ), array( 'fileupload', 'post_image' ) );
		$is_applicable_field_id = $this->_args['field_id'] ? $field['id'] == $this->_args['field_id'] : true;

		return $is_file_upload_field && $is_applicable_field_id;
	}

}

# Configuration

new GW_Rename_Uploaded_Files( array(
	'form_id' => 1,
	'template' => '{fi_filename:7}-{Name (First):4.3}-{filename}' // most merge tags are supported, original file extension is preserved
) );


new GW_Rename_Uploaded_Files( array(
	'form_id' => 2,
	'template' => '{fi_filename:7}-{Name (First):4.3}-{filename}' // most merge tags are supported, original file extension is preserved
) );
//BASICS *********************************



function fi_collective_setup() {
	global $cap, $content_width;

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	/**
	 * Add default posts and comments RSS feed links to head
	*/
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails on posts and pages
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	*/
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable support for Post Formats
	*/
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	/**
	 * Setup the WordPress core custom background feature.
	*/
	add_theme_support( 'custom-background', apply_filters( 'fi_collective_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
	
	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on fi_collective, use a find and replace
	 * to change 'fi_collective' to the name of your theme in all the template files
	*/
	load_theme_textdomain( 'fi_collective', get_template_directory() . '/languages' );

	/**
	 * This theme uses wp_nav_menu() in one location.
	*/
	register_nav_menus( array(
		'primary'  => __( 'Header bottom menu', 'fi_collective' ),
	) );

}
endif; // fi_collective_setup
add_action( 'after_setup_theme', 'fi_collective_setup' );

/**
 * Register widgetized area and update sidebar with default widgets
 */
function fi_collective_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'fi_collective' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'fi_collective_widgets_init' );

/**
 * Enqueue scripts and styles
 */
function fi_collective_scripts() {

	// Import the necessary TK Bootstrap WP CSS additions
	wp_enqueue_style( 'fi_collective-bootstrap-wp', get_template_directory_uri() . '/includes/css/bootstrap-wp.css' );

	// load bootstrap css
	wp_enqueue_style( 'fi_collective-bootstrap', get_template_directory_uri() . '/includes/resources/bootstrap/css/bootstrap.min.css' );

	// load Font Awesome css
	wp_enqueue_style( 'fi_collective-font-awesome', get_template_directory_uri() . '/includes/css/font-awesome.min.css', false, '4.1.0' );

	// load fi_collective styles
	wp_enqueue_style( 'fi_collective-style', get_stylesheet_uri() );

	// load bootstrap js
	wp_enqueue_script('fi_collective-bootstrapjs', get_template_directory_uri().'/includes/resources/bootstrap/js/bootstrap.min.js', array('jquery') );

	// load bootstrap wp js
	wp_enqueue_script( 'fi_collective-bootstrapwp', get_template_directory_uri() . '/includes/js/bootstrap-wp.js', array('jquery') );

	wp_enqueue_script( 'fi_collective-skip-link-focus-fix', get_template_directory_uri() . '/includes/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'fi_collective-keyboard-image-navigation', get_template_directory_uri() . '/includes/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
	}

}
add_action( 'wp_enqueue_scripts', 'fi_collective_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/includes/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/jetpack.php';

/**
 * Load custom WordPress nav walker.
 */
require get_template_directory() . '/includes/bootstrap-wp-navwalker.php';
