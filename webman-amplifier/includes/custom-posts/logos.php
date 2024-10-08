<?php
/**
 * WebMan Custom Posts
 *
 * Registering "wm_logos" custom post.
 *
 * @package     WebMan Amplifier
 * @subpackage  Custom Posts
 *
 * @since    1.0
 * @version  1.5.6
 */



//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;





/**
 * ACTIONS AND FILTERS
 */

	/**
	 * Actions
	 */

		//Registering CP
			add_action( 'wmhook_wmamp_' . 'register_post_types', 'wma_logos_cp_register', 10 );
		//CP list table columns
			add_action( 'manage_wm_logos_posts_custom_column', 'wma_logos_cp_columns_render' );
		//Registering taxonomies
			add_action( 'wmhook_wmamp_' . 'register_post_types', 'wma_logos_cp_taxonomies', 10 );

		/**
		 * The init action occurs after the theme's functions file has been included.
		 * So, if you're looking for terms directly in the functions file,
		 * you're doing so before they've actually been registered.
		 */



	/**
	 * Filters
	 */

		//CP list table columns
			add_filter( 'manage_edit-wm_logos_columns', 'wma_logos_cp_columns_register' );





/**
 * CREATING A CUSTOM POST
 */

	/**
	 * Custom post registration
	 *
	 * @since    1.0
	 * @version  1.5.6
	 */
	if ( ! function_exists( 'wma_logos_cp_register' ) ) {
		function wma_logos_cp_register() {

			// Processing

				// Custom post registration arguments

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_register_' . 'wm_logos', array(
						'query_var'           => 'logo',
						'capability_type'     => 'post',
						'public'              => true,
						'show_ui'             => true,
						'exclude_from_search' => true,
						'show_in_nav_menus'   => false,
						'hierarchical'        => false,
						'rewrite'             => false,
						'menu_position'       => 33,
						'menu_icon'           => 'dashicons-awards',
						'supports'            => array(
								'title',
								'thumbnail',
								'author',
							),
						'labels'              => array(
							'name'                     => _x( 'Logos', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'singular_name'            => _x( 'Logos', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'add_new'                  => _x( 'Add New', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'add_new_item'             => _x( 'Add New', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'new_item'                 => _x( 'Add New', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'edit_item'                => _x( 'Edit', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'view_item'                => _x( 'View', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'view_items'               => _x( 'View Logos', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'search_items'             => _x( 'Search', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'not_found'                => _x( 'No item found', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'not_found_in_trash'       => _x( 'No item found in trash', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'featured_image'           => _x( 'Logo image', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'set_featured_image'       => _x( 'Set logo image', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'remove_featured_image'    => _x( 'Remove logo image', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'use_featured_image'       => _x( 'Use as logo image', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'filter_items_list'        => _x( 'Filter logos list', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'items_list_navigation'    => _x( 'Logos list navigation', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'items_list'               => _x( 'Logos list', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'attributes'               => _x( 'Logo Attributes', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'item_published'           => _x( 'Logo published.', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'item_published_privately' => _x( 'Logo published privately.', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'item_reverted_to_draft'   => _x( 'Logo reverted to draft.', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'item_scheduled'           => _x( 'Logo scheduled.', 'Custom post labels: Logos.', 'webman-amplifier' ),
							'item_updated'             => _x( 'Logo updated.', 'Custom post labels: Logos.', 'webman-amplifier' ),
						)
					) );

				// Register custom post type

					register_post_type( 'wm_logos' , $args );

		}
	} // /wma_logos_cp_register





/**
 * CUSTOM POST LIST TABLE IN ADMIN
 */

	/**
	 * Register table columns
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_logos_cp_columns_register' ) ) {
		function wma_logos_cp_columns_register( $columns ) {

			// Helper variables

				$prefix = 'wmamp-';
				$suffix = '-wm_logos';


			// Processing

				$columns[ $prefix . 'thumb' . $suffix ] = esc_html__( 'Logo', 'webman-amplifier' );
				$columns[ $prefix . 'link' . $suffix ] = esc_html__( 'Custom link', 'webman-amplifier' );


			// Output

				return apply_filters( 'wmhook_wmamp_' . 'wma_logos_cp_columns_register' . '_output', $columns );

		}
	} // /wma_logos_cp_columns_register



	/**
	 * Render table columns
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_logos_cp_columns_render' ) ) {
		function wma_logos_cp_columns_render( $column ) {

			// Helper variables

				global $post;

				$prefix = 'wmamp-';
				$suffix = '-wm_logos';


			// Processing

				switch ( $column ) {

					case $prefix . 'link' . $suffix:
						$link = esc_url( stripslashes( wma_meta_option( 'link' ) ) );
						echo '<a href="' . $link . '" target="_blank">' . $link . '</a>';
					break;

					case $prefix . 'thumb' . $suffix:
						$size  = apply_filters( 'wmhook_wmamp_' . 'cp_admin_thumb_size', 'thumbnail' );
						$image = ( has_post_thumbnail() ) ? ( get_the_post_thumbnail( null, $size ) ) : ( '' );

						$hasThumb = ( $image ) ? ( ' has-thumb' ) : ( ' no-thumb' );

						echo '<span class="wm-image-container' . $hasThumb . '">';

						if ( get_edit_post_link() ) {
							edit_post_link( $image );
						} else {
							echo $image;
						}

						echo '</span>';
					break;

					default:
					break;

				} // /switch

		}
	} // /wma_logos_cp_columns_render





/**
 * TAXONOMIES
 */

	/**
	 * Register taxonomies
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_logos_cp_taxonomies' ) ) {
		function wma_logos_cp_taxonomies() {

			// Processing

				// Logos categories

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_taxonomy_' . 'logo_category', array(
						'hierarchical'      => true,
						'show_in_nav_menus' => false,
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => 'logo-category',
						'rewrite'           => false,
						'labels'            => array(
							'name'                  => _x( 'Logo Categories', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'singular_name'         => _x( 'Logo Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'search_items'          => _x( 'Search Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'all_items'             => _x( 'All Categories', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'parent_item'           => _x( 'Parent Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'parent_item_colon'     => _x( 'Parent Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ) . ':',
							'edit_item'             => _x( 'Edit Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'view_item'             => _x( 'View Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'update_item'           => _x( 'Update Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'add_new_item'          => _x( 'Add New Category', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'new_item_name'         => _x( 'New Category Title', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'not_found'             => _x( 'No categories found', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'no_terms'              => _x( 'No categories', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'items_list_navigation' => _x( 'Logo Categories list navigation', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
							'items_list'            => _x( 'Logo Categories list', 'Custom taxonomy labels: Logos categories.', 'webman-amplifier' ),
						)
					) );

					register_taxonomy( 'logo_category', 'wm_logos', $args );

		}
	} // /wma_logos_cp_taxonomies





/**
 * META BOXES
 */

	/**
	 * Register metabox fields
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wma_logos_cp_metafields' ) ) {
		function wma_logos_cp_metafields() {
			//Helper variables
				$fields = array();

				//"Attributes" tab
					$fields[1000] = array(
							'type'  => 'section-open',
							'id'    => 'logo-settings-section',
							'title' => __( 'Logo settings', 'webman-amplifier' ),
						);

						//Logo image
							$fields[1020] = array(
									'type'    => 'html',
									'content' => '<tr class="option padding-20"><td colspan="2"><div class="box blue"><a href="#" class="button-primary button-set-featured-image" style="margin-right: 1em">' . __( 'Set featured image', 'webman-amplifier' ) . '</a> ' . __( 'Set the logo image as the featured image of the post', 'webman-amplifier' ) . '</div></td></tr>',
								);

						//Logo custom link input field
							$fields[1040] = array(
									'type'        => 'text',
									'id'          => 'link',
									'label'       => __( 'Custom link URL', 'webman-amplifier' ),
									'description' => __( 'No link will be displayed / applied when left blank', 'webman-amplifier' ),
									'validate'    => 'url',
								);

						//Logo custom link actions
							$fields[1060] = array(
									'type'        => 'select',
									'id'          => 'link-action',
									'label'       => __( 'Custom link action', 'webman-amplifier' ),
									'description' => __( 'Choose how to display / apply the link set above', 'webman-amplifier' ),
									'options'     => array(
											'_blank' => __( 'Open in new tab / window', 'webman-amplifier' ),
											'_self'  => __( 'Open in same window', 'webman-amplifier' ),
										),
								);

					$fields[1980] = array(
							'type' => 'section-close',
						);
				// /"Attributes" tab

			//Apply filter to manipulate with metafields array
				$fields = apply_filters( 'wmhook_wmamp_' . 'cp_metafields_' . 'wm_logos', $fields );

			//Sort the array by the keys
				ksort( $fields );

			//Output
				return apply_filters( 'wmhook_wmamp_' . 'wma_logos_cp_metafields' . '_output', $fields );
		}
	} // /wma_logos_cp_metafields



	/**
	 * Create actual metabox
	 *
	 * @since  1.0
	 */
	if ( function_exists( 'wma_add_meta_box' ) ) {
		wma_add_meta_box( array(
				// Meta fields function callback (should return array of fields).
				// The function callback is used for to use a WordPress globals
				// available during the metabox rendering, such as $post.
				'fields' => 'wma_logos_cp_metafields',

				// Meta box id, unique per meta box.
				'id' => 'wm_logos' . '-metabox',

				// Post types.
				'pages' => array( 'wm_logos' ),

				// Tabbed meta box interface?
				'tabs' => false,

				// Meta box title.
				'title' => __( 'Logo settings', 'webman-amplifier' ),

				// Wrap the meta form around visual editor? (This is always tabbed.)
				'visual-wrapper' => false,
			) );
	}
