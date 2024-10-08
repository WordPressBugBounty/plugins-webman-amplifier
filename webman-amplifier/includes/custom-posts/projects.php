<?php
/**
 * WebMan Custom Posts
 *
 * Registering "wm_projects" custom post.
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
			add_action( 'wmhook_wmamp_' . 'register_post_types', 'wma_projects_cp_register', 10 );
		//CP list table columns
			add_action( 'manage_wm_projects_posts_custom_column', 'wma_projects_cp_columns_render' );
		//Registering taxonomies
			add_action( 'wmhook_wmamp_' . 'register_post_types', 'wma_projects_cp_taxonomies', 10 );
		//Permanlinks settings
			add_action( 'admin_init', 'wma_projects_cp_permalinks' );

		/**
		 * The init action occurs after the theme's functions file has been included.
		 * So, if you're looking for terms directly in the functions file,
		 * you're doing so before they've actually been registered.
		 */



	/**
	 * Filters
	 */

		//CP list table columns
			add_filter( 'manage_edit-wm_projects_columns', 'wma_projects_cp_columns_register' );





/**
 * CREATING A CUSTOM POST
 */

	/**
	 * Custom post registration
	 *
	 * @since    1.0
	 * @version  1.5.6
	 */
	if ( ! function_exists( 'wma_projects_cp_register' ) ) {
		function wma_projects_cp_register() {

			// Helper variables

				$permalinks = get_option( 'wmamp-permalinks' );


			// Processing

				// Custom post registration arguments

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_register_' . 'wm_projects', array(
						'query_var'           => 'project',
						'capability_type'     => 'post',
						'public'              => true,
						'show_ui'             => true,
						'has_archive'         => ( isset( $permalinks['projects'] ) && $permalinks['projects'] ) ? ( $permalinks['projects'] ) : ( 'projects' ),
						'exclude_from_search' => false,
						'hierarchical'        => false,
						'rewrite'             => array(
								'slug' => ( isset( $permalinks['project'] ) && $permalinks['project'] ) ? ( $permalinks['project'] ) : ( 'project' ),
							),
						'menu_position'       => 30,
						'menu_icon'           => 'dashicons-portfolio',
						'supports'            => array(
								'title',
								'editor',
								'excerpt',
								'thumbnail',
								'custom-fields',
								'author',
							),
						'labels'              => array(
							'name'                     => _x( 'Projects', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'singular_name'            => _x( 'Project', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'add_new'                  => _x( 'Add New', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'add_new_item'             => _x( 'Add New Project', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'new_item'                 => _x( 'Add New', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'edit_item'                => _x( 'Edit Project', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'view_item'                => _x( 'View Project', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'view_items'               => _x( 'View Projects', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'search_items'             => _x( 'Search Projects', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'not_found'                => _x( 'No project found', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'not_found_in_trash'       => _x( 'No project found in trash', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'filter_items_list'        => _x( 'Filter projects list', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'items_list_navigation'    => _x( 'Projects list navigation', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'items_list'               => _x( 'Projects list', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'attributes'               => _x( 'Project Attributes', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'item_published'           => _x( 'Project published.', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'item_published_privately' => _x( 'Project published privately.', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'item_reverted_to_draft'   => _x( 'Project reverted to draft.', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'item_scheduled'           => _x( 'Project scheduled.', 'Custom post labels: Projects.', 'webman-amplifier' ),
							'item_updated'             => _x( 'Project updated.', 'Custom post labels: Projects.', 'webman-amplifier' ),
						),
						'show_in_rest' => true, // Required for Gutenberg editor.
					) );

				// Register custom post type

					register_post_type( 'wm_projects' , $args );

		}
	} // /wma_projects_cp_register





/**
 * CUSTOM POST LIST TABLE IN ADMIN
 */

	/**
	 * Register table columns
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_projects_cp_columns_register' ) ) {
		function wma_projects_cp_columns_register( $columns ) {

			// Helper variables

				$prefix = 'wmamp-';
				$suffix = '-wm_projects';


			// Processing

				$columns[ $prefix . 'thumb' . $suffix ] = esc_html__( 'Image', 'webman-amplifier' );


			// Output

				return apply_filters( 'wmhook_wmamp_' . 'wma_projects_cp_columns_register' . '_output', $columns );

		}
	} // /wma_projects_cp_columns_register



	/**
	 * Render table columns
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_projects_cp_columns_render' ) ) {
		function wma_projects_cp_columns_render( $column ) {

			// Helper variables

				global $post;

				$prefix = 'wmamp-';
				$suffix = '-wm_projects';


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
							echo '<a href="' . get_permalink() . '">' . $image . '</a>';
						}

						echo '</span>';
					break;

					default:
					break;

				} // /switch

		}
	} // /wma_projects_cp_columns_render





/**
 * TAXONOMIES
 */

	/**
	 * Register taxonomies
	 *
	 * @since    1.0
	 * @version  1.5.6
	 */
	if ( ! function_exists( 'wma_projects_cp_taxonomies' ) ) {
		function wma_projects_cp_taxonomies() {

			// Helper variables

				$permalinks = get_option( 'wmamp-permalinks' );


			// Processing

				// Projects categories

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_taxonomy_' . 'project_category', array(
						'hierarchical'      => true,
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => 'project-category',
						'rewrite'           => array(
								'slug' => ( isset( $permalinks['project_category'] ) && $permalinks['project_category'] ) ? ( $permalinks['project_category'] ) : ( 'project-category' )
							),
						'labels'            => array(
							'name'                  => _x( 'Project Categories', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'singular_name'         => _x( 'Project Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'search_items'          => _x( 'Search Categories', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'all_items'             => _x( 'All Categories', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'parent_item'           => _x( 'Parent Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'parent_item_colon'     => _x( 'Parent Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ) . ':',
							'edit_item'             => _x( 'Edit Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'view_item'             => _x( 'View Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'update_item'           => _x( 'Update Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'add_new_item'          => _x( 'Add New Category', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'new_item_name'         => _x( 'New Category Title', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'not_found'             => _x( 'No categories found', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'no_terms'              => _x( 'No categories', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'items_list_navigation' => _x( 'Project Categories list navigation', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
							'items_list'            => _x( 'Project Categories list', 'Custom taxonomy labels: Projects categories.', 'webman-amplifier' ),
						),
						'show_in_rest' => true, // Required for Gutenberg editor.
					) );

					register_taxonomy( 'project_category', 'wm_projects', $args );

				// Projects tags

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_taxonomy_' . 'project_tag', array(
						'hierarchical'      => false,
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => 'project-tag',
						'rewrite'           => array(
								'slug' => ( isset( $permalinks['project_tag'] ) && $permalinks['project_tag'] ) ? ( $permalinks['project_tag'] ) : ( 'project-tag' )
							),
						'labels'            => array(
							'name'                       => _x( 'Project Tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'singular_name'              => _x( 'Project Tag', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'search_items'               => _x( 'Search Tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'popular_items'              => _x( 'Popular Tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'all_items'                  => _x( 'All Tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'edit_item'                  => _x( 'Edit Tag', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'view_item'                  => _x( 'View Tag', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'update_item'                => _x( 'Update Tag', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'add_new_item'               => _x( 'Add New Tag', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'new_item_name'              => _x( 'New Tag Title', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'separate_items_with_commas' => _x( 'Separate tags with commas', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'add_or_remove_items'        => _x( 'Add or remove tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'choose_from_most_used'      => _x( 'Choose from the most used tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'not_found'                  => _x( 'No tags found', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'no_terms'                   => _x( 'No tags', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'items_list_navigation'      => _x( 'Project Tags list navigation', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
							'items_list'                 => _x( 'Project Tags list', 'Custom taxonomy labels: Projects tags.', 'webman-amplifier' ),
						),
						'show_in_rest' => true, // Required for Gutenberg editor.
					) );

					register_taxonomy( 'project_tag', 'wm_projects', $args );

		}
	} // /wma_projects_cp_taxonomies





/**
 * PERMALINKS SETTINGS
 */

	/**
	 * Create permalinks settings fields in WordPress admin
	 *
	 * @since    1.0
	 * @version  1.3.21
	 */
	if ( ! function_exists( 'wma_projects_cp_permalinks' ) ) {
		function wma_projects_cp_permalinks() {

			// Requirements check

				$obj = get_post_type_object( 'wm_projects' );

				if ( ! $obj->has_archive ) {
					return;
				}


			// Processing

				// Adding sections

					add_settings_section(
							'wmamp-' . 'wm_projects' . '-permalinks',
							__( 'Projects Custom Post Permalinks', 'webman-amplifier' ),
							'wma_projects_cp_permalinks_render_section',
							'permalink'
						);

				// Adding settings fields

					add_settings_field(
							'projects',
							__( 'Projects archive permalink', 'webman-amplifier' ),
							'wma_permalinks_render_field',
							'permalink',
							'wmamp-' . 'wm_projects' . '-permalinks',
							array(
									'name'        => 'projects',
									'placeholder' => apply_filters( 'wmhook_wmamp_' . 'cp_permalink_' . 'projects', 'projects' )
								)
						);

					add_settings_field(
							'project',
							__( 'Single project permalink', 'webman-amplifier' ),
							'wma_permalinks_render_field',
							'permalink',
							'wmamp-' . 'wm_projects' . '-permalinks',
							array(
									'name'        => 'project',
									'placeholder' => apply_filters( 'wmhook_wmamp_' . 'cp_permalink_' . 'project', 'project' )
								)
						);

					add_settings_field(
							'project_category',
							__( 'Project category base', 'webman-amplifier' ),
							'wma_permalinks_render_field',
							'permalink',
							'wmamp-' . 'wm_projects' . '-permalinks',
							array(
									'name'        => 'project_category',
									'placeholder' => apply_filters( 'wmhook_wmamp_' . 'cp_permalink_' . 'project_category', 'project-category' )
								)
						);

					add_settings_field(
							'project_tag',
							__( 'Project tag base', 'webman-amplifier' ),
							'wma_permalinks_render_field',
							'permalink',
							'wmamp-' . 'wm_projects' . '-permalinks',
							array(
									'name'        => 'project_tag',
									'placeholder' => apply_filters( 'wmhook_wmamp_' . 'cp_permalink_' . 'project_tag', 'project-tag' )
								)
						);

		}
	} // /wma_projects_cp_permalinks



	/**
	 * Create permalinks settings section WordPress admin
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wma_projects_cp_permalinks_render_section' ) ) {
		function wma_projects_cp_permalinks_render_section() {
			//Settings section description
				echo apply_filters( 'wmhook_wmamp_' . 'wma_projects_cp_permalinks_render_section' . '_output', '<p>' . __( 'You can change the Projects custom post type permalinks here.', 'webman-amplifier' ) . '</p>' );
		}
	} // /wma_projects_cp_permalinks_render_section





/**
 * META BOXES
 */

	/**
	 * Register metabox fields
	 *
	 * @since  1.0
	 */
	if ( ! function_exists( 'wma_projects_cp_metafields' ) ) {
		function wma_projects_cp_metafields() {
			//Helper variables
				$fields = array();

				//"Attributes" tab
					$fields[1000] = array(
							'type'  => 'section-open',
							'id'    => 'project-attributes-section',
							'title' => _x( 'Attributes', 'Metabox section title.', 'webman-amplifier' ),
						);

						//Project custom link input field
							$fields[1020] = array(
									'type'        => 'text',
									'id'          => 'link',
									'label'       => __( 'Custom link URL', 'webman-amplifier' ),
									'description' => __( 'No link will be displayed / applied when left blank', 'webman-amplifier' ),
								);

						//Project custom link actions
							$fields[1040] = array(
									'type'        => 'select',
									'id'          => 'link-action',
									'label'       => __( 'Custom link action', 'webman-amplifier' ),
									'description' => __( 'Choose how to display / apply the link set above', 'webman-amplifier' ),
									'optgroups'   => true,
									'options'     => array(
											'1OPTGROUP'  => __( 'Project page', 'webman-amplifier' ),
												''         => __( 'Display link on project page', 'webman-amplifier' ),
											'1/OPTGROUP' => '',
											'2OPTGROUP'  => __( 'Apply directly in projects list', 'webman-amplifier' ),
												'modal'    => __( 'Open in popup window (videos and images only)', 'webman-amplifier' ),
												'_blank'   => __( 'Open in new tab / window', 'webman-amplifier' ),
												'_self'    => __( 'Open in same window', 'webman-amplifier' ),
											'2/OPTGROUP' => '',
										),
								);

					$fields[1980] = array(
							'type' => 'section-close',
						);
				// /"Attributes" tab

			//Apply filter to manipulate with metafields array
				$fields = apply_filters( 'wmhook_wmamp_' . 'cp_metafields_' . 'wm_projects', $fields );

			//Sort the array by the keys
				ksort( $fields );

			//Output
				return apply_filters( 'wmhook_wmamp_' . 'wma_projects_cp_metafields' . '_output', $fields );
		}
	} // /wma_projects_cp_metafields



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
				'fields' => 'wma_projects_cp_metafields',

				// Meta box id, unique per meta box.
				'id' => 'wm_projects' . '-metabox',

				// Post types.
				'pages' => array( 'wm_projects' ),

				// Tabbed meta box interface?
				'tabs' => true,

				// Meta box title.
				'title' => __( 'Project settings', 'webman-amplifier' ),
			) );
	}





/**
 * OTHERS
 */

	/**
	 * Adding post type to Jetpack Sitemaps
	 *
	 * @link  https://jetpack.com/support/sitemaps/
	 * @link  https://developer.jetpack.com/hooks/jetpack_sitemap_post_types/
	 *
	 * @since    1.4.3
	 * @version  1.4.3
	 */
	if ( ! function_exists( 'wma_projects_cp_jetpack_sitemaps' ) ) {
		function wma_projects_cp_jetpack_sitemaps( $post_types = array() ) {

			// Processing

				$post_types[] = 'wm_projects';
				array_unique( $post_types );

			// Output

				return $post_types;

		}
	}

add_filter( 'jetpack_sitemap_post_types', 'wma_projects_cp_jetpack_sitemaps' );
