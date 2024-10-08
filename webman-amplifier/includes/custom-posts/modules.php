<?php
/**
 * WebMan Custom Posts
 *
 * Registering "wm_modules" custom post.
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
			add_action( 'wmhook_wmamp_' . 'register_post_types', 'wma_modules_cp_register', 10 );
		//CP list table columns
			add_action( 'manage_wm_modules_posts_custom_column', 'wma_modules_cp_columns_render' );
		//Registering taxonomies
			add_action( 'wmhook_wmamp_' . 'register_post_types', 'wma_modules_cp_taxonomies', 10 );

		/**
		 * The init action occurs after the theme's functions file has been included.
		 * So, if you're looking for terms directly in the functions file,
		 * you're doing so before they've actually been registered.
		 */



	/**
	 * Filters
	 */

		//CP list table columns
			add_filter( 'manage_edit-wm_modules_columns', 'wma_modules_cp_columns_register' );





/**
 * CREATING A CUSTOM POST
 */

	/**
	 * Custom post registration
	 *
	 * @since    1.0
	 * @version  1.5.6
	 */
	if ( ! function_exists( 'wma_modules_cp_register' ) ) {
		function wma_modules_cp_register() {

			// Processing

				// Custom post registration arguments

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_register_' . 'wm_modules', array(
						'query_var'           => 'module',
						'capability_type'     => 'page',
						'public'              => true,
						'show_ui'             => true,
						'exclude_from_search' => true,
						'show_in_nav_menus'   => false,
						'hierarchical'        => false,
						'rewrite'             => false,
						'menu_position'       => 45,
						'menu_icon'           => 'dashicons-format-aside',
						'supports'            => array(
								'title',
								'editor',
								'thumbnail',
								'author',
							),
						'labels'              => array(
							'name'                     => _x( 'Content Modules', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'singular_name'            => _x( 'Content Module', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'add_new'                  => _x( 'Add New', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'add_new_item'             => _x( 'Add New module', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'new_item'                 => _x( 'Add New', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'edit_item'                => _x( 'Edit Module', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'view_item'                => _x( 'View Module', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'view_items'               => _x( 'View Modules', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'search_items'             => _x( 'Search Modules', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'not_found'                => _x( 'No module found', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'not_found_in_trash'       => _x( 'No module found in trash', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'filter_items_list'        => _x( 'Filter content modules list', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'items_list_navigation'    => _x( 'Content Modules list navigation', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'items_list'               => _x( 'Content Modules list', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'attributes'               => _x( 'Content Module Attributes', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'item_published'           => _x( 'Content Module published.', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'item_published_privately' => _x( 'Content Module published privately.', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'item_reverted_to_draft'   => _x( 'Content Module reverted to draft.', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'item_scheduled'           => _x( 'Content Module scheduled.', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
							'item_updated'             => _x( 'Content Module updated.', 'Custom post labels: Content Modules.', 'webman-amplifier' ),
						)
					) );

				// Register custom post type

					register_post_type( 'wm_modules' , $args );

		}
	} // /wma_modules_cp_register





/**
 * CUSTOM POST LIST TABLE IN ADMIN
 */

	/**
	 * Register table columns
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_modules_cp_columns_register' ) ) {
		function wma_modules_cp_columns_register( $columns ) {

			// Helper variables

				$prefix = 'wmamp-';
				$suffix = '-wm_modules';


			// Processing

				$columns[ $prefix . 'thumb' . $suffix ] = esc_html__( 'Image', 'webman-amplifier' );


			// Output

				return apply_filters( 'wmhook_wmamp_' . 'wma_modules_cp_columns_register' . '_output', $columns );

		}
	} // /wma_modules_cp_columns_register



	/**
	 * Render table columns
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_modules_cp_columns_render' ) ) {
		function wma_modules_cp_columns_render( $column ) {

			// Helper variables

				global $post;

				$prefix = 'wmamp-';
				$suffix = '-wm_modules';


			// Processing

				switch ( $column ) {

					case $prefix . 'link' . $suffix:
						$link = esc_url( stripslashes( wma_meta_option( 'link' ) ) );
						echo '<a href="' . $link . '" target="_blank">' . $link . '</a>';
					break;

					case $prefix . 'thumb' . $suffix:
						$size  = apply_filters( 'wmhook_wmamp_' . 'cp_admin_thumb_size', 'thumbnail' );
						$image = ( has_post_thumbnail() ) ? ( get_the_post_thumbnail( null, $size ) ) : ( '' );

						$fontIcon    = wma_meta_option( 'icon-font' );
						$iconColor   = wma_meta_option( 'icon-color' );
						$iconBgColor = wma_meta_option( 'icon-color-background' );

						$styleIcon      = ( $iconColor ) ? ( ' style="color: ' . $iconColor . '"' ) : ( '' );
						$styleContainer = ( $iconBgColor ) ? ( ' style="background-color: ' . $iconBgColor . '"' ) : ( '' );

						if ( $fontIcon ) {
							$image = '<span class="' . $fontIcon . '"' . $styleIcon . ' aria-hidden="true"></span>';
						}

						$hasThumb = ( $image ) ? ( ' has-thumb' ) : ( ' no-thumb' );

						echo '<span class="wm-image-container' . $hasThumb . '"' . $styleContainer . '>';

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
	} // /wma_modules_cp_columns_render





/**
 * TAXONOMIES
 */

	/**
	 * Register taxonomies
	 *
	 * @since    1.0
	 * @version  1.4.1
	 */
	if ( ! function_exists( 'wma_modules_cp_taxonomies' ) ) {
		function wma_modules_cp_taxonomies() {

			// Processing

				// Module tags

					$args = apply_filters( 'wmhook_wmamp_' . 'cp_taxonomy_' . 'module_tag', array(
						'hierarchical'      => false,
						'show_in_nav_menus' => false,
						'show_ui'           => true,
						'show_admin_column' => true,
						'query_var'         => 'module-tag',
						'rewrite'           => false,
						'labels'            => array(
							'name'                       => _x( 'Module Tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'singular_name'              => _x( 'Module Tag', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'search_items'               => _x( 'Search Tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'popular_items'              => _x( 'Popular Tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'all_items'                  => _x( 'All Tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'edit_item'                  => _x( 'Edit Tag', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'view_item'                  => _x( 'View Tag', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'update_item'                => _x( 'Update Tag', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'add_new_item'               => _x( 'Add New Tag', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'new_item_name'              => _x( 'New Tag Title', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'separate_items_with_commas' => _x( 'Separate tags with commas', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'add_or_remove_items'        => _x( 'Add or remove tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'choose_from_most_used'      => _x( 'Choose from the most used tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'not_found'                  => _x( 'No tags found', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'no_terms'                   => _x( 'No tags', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'items_list_navigation'      => _x( 'Module Tags list navigation', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
							'items_list'                 => _x( 'Module Tags list', 'Custom taxonomy labels: Content Modules tags.', 'webman-amplifier' ),
						)
					) );

					register_taxonomy( 'module_tag', 'wm_modules', $args );

		}
	} // /wma_modules_cp_taxonomies





/**
 * META BOXES
 */

	/**
	 * Register metabox fields
	 *
	 * @since    1.0
	 * @version  1.3.19
	 */
	if ( ! function_exists( 'wma_modules_cp_metafields' ) ) {
		function wma_modules_cp_metafields() {
			//Helper variables
				$fields    = $icons = array();
				$fonticons = get_option( 'wmamp-icons' );

			//Prepare font icons
				if ( isset( $fonticons['icons_select'] ) ) {
					$fonticons = array_merge( array( '' => '' ), $fonticons['icons_select'] );
				} else {
					$fonticons = array();
				}

				//"Settings" tab
					$fields[1000] = array(
							'type'  => 'section-open',
							'id'    => 'module-settings-section',
							'title' => _x( 'Settings', 'Metabox section title.', 'webman-amplifier' ),
						);

						//Module custom link input field
							$fields[1020] = array(
									'type'        => 'text',
									'id'          => 'link',
									'label'       => __( 'Custom link URL', 'webman-amplifier' ),
									'description' => __( 'No link will be displayed / applied when left blank', 'webman-amplifier' ),
									'validate'    => 'url',
								);

						//Module custom link actions
							$fields[1040] = array(
									'type'        => 'select',
									'id'          => 'link-action',
									'label'       => __( 'Custom link action', 'webman-amplifier' ),
									'description' => __( 'Choose how to display / apply the link set above', 'webman-amplifier' ),
									'options'     => array(
											'_blank' => __( 'Open in new tab / window', 'webman-amplifier' ),
											'_self'  => __( 'Open in same window', 'webman-amplifier' ),
										),
									'default'    => '_self',
								);

						//Module type (setting icon box)
							$fields[1060] = array(
									'type'        => 'checkbox',
									'id'          => 'icon-box',
									'label'       => __( 'Use as icon box', 'webman-amplifier' ),
									'description' => __( 'Creates an icon box', 'webman-amplifier' ),
								);

						//Conditional subsection displayed if icon box set
							$fields[1200] = array(
									'type'  => 'sub-section-open',
									'id'    => 'module-icon-section',
								);

								//Featured image setup
									$fields[1220] = array(
											'type'    => 'html',
											'content' => '<tr class="option padding-20"><td colspan="2"><div class="box blue"><a href="#" class="button-primary button-set-featured-image" style="margin-right: 1em">' . __( 'Set featured image', 'webman-amplifier' ) . '</a> ' . __( 'Set the icon as featured image of the post. If the font icon is used instead, it will be prioritized.', 'webman-amplifier' ) . '</div></td></tr>',
										);

								if ( ! empty( $fonticons ) ) {
								//Choose font icon
									$fields[1240] = array(
											'type'        => 'radio',
											'id'          => 'icon-font',
											'label'       => __( '...or choose a font icon', 'webman-amplifier' ),
											'description' => __( 'Select from predefined font icons', 'webman-amplifier' ),
											'options'     => $fonticons,
											'inline'      => true,
											'filter'      => true,
											'custom'      => '<span class="{{value}}" title="{{value}}" style="display: inline-block; width: 20px; height: 20px; line-height: 1em; font-size: 20px; vertical-align: top; color: #444;" aria-hidden="true"></span>',
											'hide-radio'  => true,
										);

								//Icon color
									$fields[1260] = array(
											'type'        => 'color',
											'id'          => 'icon-color',
											'label'       => __( 'Font icon color', 'webman-amplifier' ),
											'description' => __( 'Set the color of the icon font', 'webman-amplifier' ),
											'conditional' => array(
													'option'       => array(
															'tag'  => 'input',
															'name' => 'wm-icon-font',
															'type' => 'radio',
														),
													'option_value' => array( '' ),
													'operand'      => 'IS_NOT',
												),
										);
								}

								//Icon background
									$fields[1280] = array(
											'type'        => 'color',
											'id'          => 'icon-color-background',
											'label'       => __( 'Icon background color', 'webman-amplifier' ),
											'description' => __( 'Set the color of the icon background', 'webman-amplifier' ),
										);

							$fields[1480] = array(
									'type'        => 'sub-section-close',
									'id'          => 'module-icon-section',
									'conditional' => array(
											'option'       => array(
													'tag'  => 'input',
													'name' => 'wm-icon-box',
													'type' => 'checkbox',
												),
											'option_value' => array( 1 ),
											'operand'      => 'IS',
										),
								);

					$fields[1980] = array(
							'type' => 'section-close',
						);
				// /"Settings" tab

			//Apply filter to manipulate with metafields array
				$fields = apply_filters( 'wmhook_wmamp_' . 'cp_metafields_' . 'wm_modules', $fields );

			//Sort the array by the keys
				ksort( $fields );

			//Output
				return apply_filters( 'wmhook_wmamp_' . 'wma_modules_cp_metafields' . '_output', $fields );
		}
	} // /wma_modules_cp_metafields



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
				'fields' => 'wma_modules_cp_metafields',

				// Meta box id, unique per meta box.
				'id' => 'wm_modules' . '-metabox',

				// Post types.
				'pages' => array( 'wm_modules' ),

				// Tabbed meta box interface?
				'tabs' => true,

				// Meta box title.
				'title' => __( 'Module settings', 'webman-amplifier' ),
			) );
	}
