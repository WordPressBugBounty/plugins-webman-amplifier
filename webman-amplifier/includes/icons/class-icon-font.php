<?php
/**
 * WebMan Font Icons
 *
 * @package     WebMan Amplifier
 * @subpackage  Font Icons
 */



//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * WebMan Font Icons Class
 *
 * @package     WebMan Amplifier
 * @subpackage  Font Icons
 *
 * @since    1.0
 * @version  1.4
 */
if ( ! class_exists( 'WM_Icons' ) ) {

	class WM_Icons {

		/**
		 * VARIABLES DEFINITION
		 */

			/**
			 * @var  array Array of paths and URLs
			 */
			private $paths = array();

			/**
			 * @var  array Array holding the icon CSS class and characters used
			 */
			private $font_config = array();

			/**
			 * @var  string The file with the icon font setup
			 */
			private $font_glyphs_file = '';

			/**
			 * @var  string Icon font name
			 */
			private $font_name = '';

			/**
			 * @var  string Capability to upload font file
			 */
			private $capability = '';

			/**
			 * @var  object
			 */
			protected static $instance;





		/**
		 * INITIALIZATION FUNCTIONS
		 */

			/**
			 * Constructor
			 *
			 * @since   1.0
			 * @access  public
			 */
			public function __construct() {
				$this->setup_globals();
				$this->setup_actions();
				$this->setup_icons();
			} // /__construct



			/**
			 * Return an instance of the class
			 *
			 * @since   1.0
			 * @access  public
			 *
			 * @return  object A single instance of this class
			 */
			public static function instance() {
				if ( ! isset( self::$instance ) ) {
					self::$instance = new WM_Icons;
				}
				return self::$instance;
			} // /instance



			/**
			 * Global variables setup
			 *
			 * @since    1.0
			 * @version  1.1
			 *
			 * @access  private
			 */
			private function setup_globals() {
				//Icon font name
					$this->font_name = 'fontello';

				//Paths and URLs
					$this->paths            = wp_upload_dir();
					$this->paths['fonts']   = 'wmamp_fonts';
					$this->paths['temp']    = trailingslashit( $this->paths['fonts'] ) . 'temp';
					$this->paths['fontdir'] = trailingslashit( $this->paths['basedir'] ) . $this->paths['fonts'] . '/' . $this->font_name;
					$this->paths['tempdir'] = trailingslashit( $this->paths['basedir'] ) . $this->paths['temp'];
					$this->paths['fonturl'] = trailingslashit( $this->paths['baseurl'] ) . $this->paths['fonts'] . '/' . $this->font_name;
					$this->paths['tempurl'] = trailingslashit( $this->paths['baseurl'] ) . trailingslashit( $this->paths['temp'] );
					$this->paths            = apply_filters( 'wmhook_icons_' . 'paths', $this->paths );

				//Capability to upload font file
					$this->capability = apply_filters( 'wmhook_icons_' . 'capability', 'switch_themes' );
			} // /setup_globals



			/**
			 * Setup default icons
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_icons() {
				//Get recent icons array from database
					$icons = get_option( 'wmamp-icons' );

				//If no icons stored, reset the default icons
					if ( empty( $icons ) ) {
						//Include the default icons config file which contains $icons array definition
							$default_fonticons_config_file = apply_filters( 'wmhook_icons_' . 'default_iconfont_config_path', WMAMP_ASSETS_DIR . 'font/config.php' );
							include_once( $default_fonticons_config_file );

						//Assign the $icons array to $font_config variable
							$this->font_config = apply_filters( 'wmhook_icons_' . 'default_iconfont_config_array', $icons );

						//Process the $font_config variable
							$this->write_config();
					}
			} // /setup_icons





		/**
		 * FONT UPLOAD FORM
		 */

			/**
			 * Setup actions
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function setup_actions() {
				//Admin panel assets
					add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
				//Menu registration
					add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			} // /setup_actions



			/**
			 * Scripts and styles
			 *
			 * @since    1.0
			 * @version  1.4
			 *
			 * @access  public
			 */
			public function assets() {

				// Helper variables

					global $current_screen;

					$icon_font_url = WM_Amplifier::fix_ssl_urls( esc_url_raw( apply_filters( 'wmhook_metabox_' . 'iconfont_url', get_option( 'wmamp-icon-font' ) ) ) );


				// Processing

					// Register

						// Styles

							wp_register_style( 'wm-admin-icons',    WMAMP_ASSETS_URL . 'css/admin-icons.css', false, WMAMP_VERSION, 'screen' );
							wp_register_style( 'wm-metabox-styles', WMAMP_ASSETS_URL . 'css/metabox.css',     false, WMAMP_VERSION, 'screen' );

							if ( $icon_font_url ) {
								wp_register_style( 'wm-fonticons', $icon_font_url, false, WMAMP_VERSION, 'screen' );
							}

						// Scripts

							wp_register_script( 'wm-metabox-scripts', WMAMP_ASSETS_URL . 'js/metabox.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-slider' ), WMAMP_VERSION, true );

						// Allow hooking for deregistering

							do_action( 'wmhook_icons_' . 'assets_registered' );

					// Enqueue (only on admin page)

						if ( 'appearance_page_icon-font' == $current_screen->id ) {

							// Styles

								wp_enqueue_style( 'wm-fonticons' );
								wp_enqueue_style( 'wm-admin-icons' );
								wp_enqueue_style( 'wm-metabox-styles' );

								wp_style_add_data(
										'wm-metabox-styles',
										'rtl',
										'replace'
									);

							// Scripts

								wp_enqueue_script( 'media-upload' );
								wp_enqueue_media();
								wp_enqueue_script( 'wm-metabox-scripts' );

						}

						// Allow hooking for dequeuing

							do_action( 'wmhook_icons_' . 'assets_enqueued' );

			} // /assets



			/**
			 * Add admin menu element
			 *
			 * @since    1.0
			 * @version  1.1
			 *
			 * @access  public
			 */
			public function admin_menu() {
				//Saving fields from theme options form
					if (
							isset( $_GET['page'] )
							&& 'icon-font' == $_GET['page']
						) {
						//Check if the user is allowed to edit options
							if ( ! current_user_can( $this->capability ) ) {
								wp_die( __( 'You do not have sufficient permissions to access this page.', 'webman-amplifier' ) );
							} else {
								$this->add_zipped_font();
							}
					}

				//Adding admin menu item under "Appearance" menu
					add_theme_page(
							_x( 'Icon Font', 'Admin page title.', 'webman-amplifier' ), //page_title
							_x( 'Icon Font', 'Admin menu title.', 'webman-amplifier' ), //menu_title
							$this->capability,              //capability
							'icon-font',                    //menu_slug
							array( $this, 'admin_form' )    //form render function callback
						);
			} // /admin_menu



			/**
			 * Render admin form to upload font ZIP file
			 *
			 * @since    1.0
			 * @version  1.3.21
			 *
			 * @access   public
			 */
			public function admin_form() {

				// Helper variables

					$fonticons = get_option( 'wmamp-icons' );
					if ( isset( $fonticons['icons_select'] ) ) {
						$fonticons = wma_ksort( $fonticons['icons_select'] );

						$output  = '<div class="wm-meta-wrap wmamp-icons-classes-list-container">';
						$output .= '<div class="box yellow">';
						$output .= '<h2>' . __( 'List of the recently used icons with their CSS classes:', 'webman-amplifier' ) . '</h2>';
						$output .= '<ol class="wmamp-icons-classes-list">';
						foreach ( $fonticons as $icon => $name ) {
							$output .= '<li>';
							$output .= '<span class="' . $icon . '" aria-hidden="true"></span>';
							$output .= '<label><span>' . esc_html__( 'CSS class:', 'webman-amplifier' ) . '</span><input type="text" value="' . $icon . '" readonly="readonly" onfocus="this.select();" /></label>';
							$output .= '<label><span>' . esc_html__( 'Instant HTML:', 'webman-amplifier' ) . '</span><input type="text" value="' . esc_attr( '<span class="' . $icon . '" aria-hidden="true"></span>' ) . '" readonly="readonly" onfocus="this.select();" /></label>';
							$output .= '</li>';
						}
						$output .= '</ol>';
						$output .= '</div>';
						$output .= '</div>';

						$fonticons = $output;
					} else {
						$fonticons = '';
					}

					// Form fields setup

						$fields = array( array(
								'id'          => 'wmamp-font-zip',
								'label'       => __( 'Fontello ZIP package file', 'webman-amplifier' ),
								'button'      => __( 'Set the file', 'webman-amplifier' ),
								'placeholder' => __( 'Fontello ZIP package file URL', 'webman-amplifier' ),
								'description' => sprintf( __( 'Upload a new icon font ZIP package generated with <a%s>Fontello.com</a>.<br />Use the default button on right to empty the input field and set the default icon font file.<br /><strong>IMPORTANT: Please do not use custom font name when creating your Fontello.com selection. Leave the field blank or use "fontello" as font name. Otherwise the font icons will not be generated.</strong>', 'webman-amplifier' ), ' href="http://fontello.com/" target="_blank"'),
								'default'     => '',
							) );

					// Form fields values setup

						$icon_font = get_option( 'wmamp-icon-font' );
						$zip_file  = get_option( $fields[0]['id'] );
						if (
								! is_array( $zip_file )
								|| ! isset( $zip_file['url'] )
								|| ! trim( $zip_file['url'] )
								|| ! isset( $zip_file['id'] )
								|| ! trim( $zip_file['id'] )
								|| false === stripos( $zip_file['url'], '.zip' )
							) {
							$zip_file = array(
									'url' => '',
									'id'  => '',
								);
						}
						$options = array(
								$fields[0]['id']  => $zip_file,
								'wmamp-icon-font' => ( trim( $icon_font ) ) ? ( esc_url( $icon_font ) ) : ( '' ),
							);


				// Processing

					$output = '<div class="wrap wm-admin-wrap">';

					// Title

						$output .= '<h1>' . __( 'Icon Font Setup', 'webman-amplifier' ) . '</h1>';

					// Status messages

						$message = ( isset( $_GET['message'] ) ) ? ( absint( $_GET['message'] ) ) : ( 0 );

						if ( is_multisite() && false === stripos( get_site_option( 'upload_filetypes' ), 'zip') ) {
							$message = sprintf( __( '<strong>You are currently on a WordPress multisite installation and ZIP file upload is disabled.</strong><br/>Go to your <a%s>Network settings page</a> and add the "zip" file extension to the list of allowed <em>"Upload file types"</em>.', 'webman-amplifier' ), ' href="' . network_admin_url( 'settings.php' ) . '"' );
						} elseif ( 1 === $message ) {
							$message = __( 'The ZIP file was processed successfully and new icon font was set up.', 'webman-amplifier' );
						} elseif ( 2 === $message ) {
							$message = __( '<strong>Error during processing of your ZIP file.</strong>', 'webman-amplifier' );
						} elseif ( 3 === $message ) {
							$message = __( "<strong>Using this feature is reserved for administrators. You don't have the necessary permissions.</strong>", 'webman-amplifier' );
						} elseif ( 4 === $message ) {
							$message = __( "Default icon font file was restored.", 'webman-amplifier' );
						} else {
							$message = '';
						}

						// Display message box if any message sent

							if ( $message ) {
								$output .= '<div id="message" class="updated"><p>' . $message . '</p></div>';
							}

					// Render form

						$output .= '<form class="wm-meta-wrap" method="post" action="' . admin_url( 'themes.php?page=icon-font' ) . '">';
							$output .= '<table class="form-table">';

							// Caption

								$output .= '<caption>';
									$output .= __( 'Icon Font File Setup', 'webman-amplifier' );
								$output .= '</caption>';

							// Save button

								$output .= '<tfoot>';
									$output .= '<tr class="padding-20"><td colspan="2">';

										// Nonce

											$output .= wp_nonce_field( 'icon_font', '_wpnonce', true, false );

										// Button

											$output .= '<input type="submit" name="save-icon-font" id="save-icon-font" class="button button-primary button-large" value="' . __( 'Save changes', 'webman-amplifier' ) . '" />';
											$output .= '<input type="hidden" name="action" value="wmamp-uploading-icon-font" />';

									$output .= '</td></tr>';
								$output .= '</tfoot>';

								$output .= '<tbody>';

								// Font CSS file link

									if ( $options['wmamp-icon-font'] ) {
										$output .= '<tr class="option padding-20"><td colspan="2">';
											$output .= '<div class="box blue">' . sprintf( __( 'To display the icon font, please, use this CSS file: %s', 'webman-amplifier' ), '<br /><code><a href="' . $options['wmamp-icon-font'] . '" target="_blank">' . $options['wmamp-icon-font'] . '</a></code>' ) . '</div>';
										$output .= '</td></tr>';
									}

								// Upload field

									$output .= '<tr class="option zip-wrap option-' . $fields[0]['id'] . '" data-option="' . $fields[0]['id'] . '"><th>';

										// Label

											$output .= '<label for="' . $fields[0]['id'] . '" data-id="' . $fields[0]['id'] . '">' . $fields[0]['label'] . '</label>';

									$output .= '</th><td>';

										// Input field

											$output .= '<input type="text" name="' . $fields[0]['id'] . '[url]" id="' . $fields[0]['id'] . '" value="' . $options[$fields[0]['id']]['url'] . '" class="fieldtype-zip" placeholder="' . $fields[0]['placeholder'] . '" readonly="readonly" />';
											$output .= '<input type="hidden" name="' . $fields[0]['id'] . '[id]" value="' . $options[$fields[0]['id']]['id'] . '" />';

										// Upload button

											$output .= '<a href="#" class="button button-set-zip" data-id="' . $fields[0]['id'] . '">' . $fields[0]['button'] . '</a>';

										// Description

											$output .= '<p class="description">' . $fields[0]['description'] . '</p>';

										// Default value button

											$output .= '<a data-option="' . $fields[0]['id'] . '[url]" class="button-default-value" title="' . __( 'Use default', 'webman-amplifier' ) . '"><span>' . $fields[0]['default'] . '</span></a>';

									$output .= '</td></tr>';

								$output .= '</tbody>';

							$output .= '</table>';
						$output .= '</form>';

						// Available icon classes

							$output .= $fonticons;


					$output .= '</div>';


				// Output

					echo apply_filters( 'wmhook_icons_' . 'admin_form' . '_output', $output );

			} // /admin_form



			/**
			 * Adding ZIPped font file
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function add_zipped_font() {
				if ( isset( $_POST['action'] ) && 'wmamp-uploading-icon-font' == $_POST['action'] ) {
					//Check referer
						check_admin_referer( 'icon_font', '_wpnonce' );

					//Check capability
						if ( ! current_user_can( $this->capability ) ) {
							wp_safe_redirect( admin_url( 'themes.php?page=icon-font&message=3' ) );
							die();
						}

					//Get the ZIP file path
						$attachment = ( isset( $_POST['wmamp-font-zip'] ) ) ? ( $_POST['wmamp-font-zip'] ) : ( '' );
						$attachment = apply_filters( 'wmhook_icons_' . 'uploaded_icon_font_zip_url', $attachment );
						if (
								! is_array( $attachment )
								|| ! isset( $attachment['url'] )
								|| ! trim( $attachment['url'] )
								|| ! isset( $attachment['id'] )
								|| ! trim( $attachment['id'] )
								|| false === stripos( $attachment['url'], '.zip' )
							) {
							delete_option( 'wmamp-font-zip' );
							delete_option( 'wmamp-icon-font' );
							delete_option( 'wmamp-icons' );

							$this->setup_icons();

							if ( ! trim( $attachment['url'] ) ) {
								wp_safe_redirect( admin_url( 'themes.php?page=icon-font&message=4' ) );
							} else {
								wp_safe_redirect( admin_url( 'themes.php?page=icon-font&message=2' ) );
							}
							die();
						}
						$path     = realpath( get_attached_file( $attachment['id'] ) );
						$unzipped = $this->zip_flatten( $path, array(
								'\.eot',
								'\.svg',
								'\.ttf',
								'\.woff',
								'\.json',
								'fontello.css'
							) );

					//If able to unzip and save the files to our temp folder, create a config file
						if ( $unzipped ) {
							$this->create_config();
						}

					//If no name for the font don't add it and delete the temp folder
						if ( ! trim( $this->font_name ) ) {
							$this->delete_folder( $this->paths['tempdir'] );

							wp_safe_redirect( admin_url( 'themes.php?page=icon-font&message=2' ) );
							die();
						}

					//Save the ZIP file info to database
						update_option( 'wmamp-font-zip', $attachment );

					//Return the successful message
						wp_safe_redirect( admin_url( 'themes.php?page=icon-font&message=1' ) );
						die();
				}
			} // /add_zipped_font





		/**
		 * PROCESSING METHODS
		 */

			/**
			 * Extracts the ZIP file to a flat folder and removes obsolete files
			 * And replaces the font file URLs in "fontello.css" file
			 *
			 * @since    1.0
			 * @version  1.4
			 *
			 * @access  private
			 * @uses    PHP ZipArchive class
			 *
			 * @param   string $file   ZIP file name
			 * @param   array  $filter Array of file extensions to keep
			 *
			 * @return  boolean True if ZIP processed successfully
			 */
			private function zip_flatten( $file = '', $filter = array() ) {
				//Set the memory limit
					@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', WP_MAX_MEMORY_LIMIT ) );

				//Create the temporary folder
					$tempdir = wma_create_folder( $this->paths['tempdir'] );
					if ( ! $tempdir ) {
						exit( "Wasn't able to create a temporary folder" );
					}

				//Use the PHP ZipArchive class
					$zip = new ZipArchive;

					if ( $zip->open( $file ) ) {
						//Parse the ZIP archive
							for ( $i=0; $i < $zip->numFiles; $i++ ) {
								$delete = false;

								//Get ZIP file entry (file, folder, subfolder)
									$entry = $zip->getNameIndex( $i );

								//Filter allowed files
									if ( ! empty( $filter ) ) {
										$delete  = true;
										$matches = array();

										foreach ( $filter as $regex ) {
											preg_match( '!' . $regex . '!', $entry, $matches );
											if ( ! empty( $matches ) ) {
												$delete = false;
												break;
											}
										}
									}

								//Skip directories and obsolete files
									if ( '/' == substr( $entry, -1 ) || $delete ) {
										continue;
									}

								//Copy files from ZIP to a new file in temporary directory
									$source_file = $zip->getStream( $entry );
									$target_file = fopen( $this->paths['tempdir'] . '/' . basename( $entry ), 'w' );

									if ( ! $source_file ) {
										exit( 'Unable to extract the file' );
									}

									while ( ! feof( $source_file ) ) {
										$new_content = fread( $source_file, 8192 );

										// Process "fontello.css" file content

											if ( 'fontello.css' == basename( $entry ) ) {

												// Replace font paths

													$new_content = str_replace(
															array(
																'../font/',
																'margin-right: .2em;',
																'margin-left: .2em;'
															),
															array(
																'',
																'/* margin-right: .2em; */',
																'/* margin-left: .2em; */'
															),
															$new_content
														);

												// Remove query string from URLs (to improve caching)

													$new_content = preg_replace(
															'/\?[\d]+/',
															'',
															$new_content
														);

												// Minify the file content

													// Remove CSS comments

														$new_content = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $new_content );

													// Remove tabs, spaces, line breaks, etc.

														$new_content = str_replace( array( "\r\n", "\r", "\n", "\t" ), '', $new_content );
														$new_content = str_replace( array( '  ', '   ', '    ', '     ' ), ' ', $new_content );
														$new_content = str_replace( array( ' { ', ': ', '; }' ), array( '{', ':', '}' ), $new_content );

												// Now we are ready to remove the EOT font format URLs

													$new_content = str_replace(
															array(
																" src:url('fontello.eot');",
																"url('fontello.eot#iefix') format('embedded-opentype'), ",
															),
															'',
															$new_content
														);

											}

										fwrite( $target_file, $new_content );
									}

									fclose( $source_file );
									fclose( $target_file );
							}

						//Close the ZIP file processing
							$zip->close();
					} else {
						exit( "Wasn't able to process the ZIP archive" );
					}

				//Return
					return true;
			} // /zip_flatten



			/**
			 * Creates icons configuration from JSON file
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function create_config() {
				//Find the JSON config file
					$files = scandir( $this->paths['tempdir'] );
					foreach ( $files as $file ) {
						if ( false !== strpos( strtolower( $file ), '.json' ) && '.' != $file[0] ) {
							$this->font_glyphs_file = $file;
						}
					}

				//Check if JSON exists
					if ( empty( $this->font_glyphs_file ) ) {
						$this->delete_folder( $this->paths['tempdir'] );
						exit( 'Found no JSON file with font information in your folder. Was not able to create the necessary config file.' );
					}

				//Open the JSON file
					$json = wp_remote_fopen( trailingslashit( $this->paths['tempurl'] ) . $this->font_glyphs_file );

				//If WordPress wasn't able to get the file (unlikely), try to fetch it old school
					if ( empty( $json ) ) {
						$json = file_get_contents( trailingslashit( $this->paths['tempdir'] ) . $this->font_glyphs_file );
					}

				//Process the JSON file content
					if ( ! is_wp_error( $json ) && ! empty( $json ) ) {
						$this->font_config = array();

						$this->font_config['wmamp-font-file-url'] = trailingslashit( $this->paths['fonturl'] ) . $this->font_name . '.css';

						$json = json_decode( $json, true );

						if ( isset( $json['glyphs'] ) && is_array( $json['glyphs'] ) ) {
							foreach ( $json['glyphs'] as $icon ) {
								if ( isset( $icon['css'] ) && isset( $icon['code'] ) ) {
									$this->font_config[$icon['css']]['class'] = $icon['css'];
									$this->font_config[$icon['css']]['char']  = dechex( $icon['code'] );
								}
							}
						}

						if ( ! empty( $this->font_config ) ) {
							$this->rename_files();
							$this->rename_folder();
							$this->write_config();
						}
					}

				//Return
					return false;
			} // /create_config



			/**
			 * Writes icons configurations in database
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function write_config() {
				$icons = array();

				//Prepare icons array to store in database
					if ( is_array( $this->font_config ) && $this->font_config ) {
						$icons['css-prefix'] = apply_filters( 'wmhook_icons_' . 'icon_css_prefix', 'icon-' );
						$icons['css-suffix'] = apply_filters( 'wmhook_icons_' . 'icon_css_suffix', '' );

						foreach ( $this->font_config as $key => $icon ) {
							if ( ! empty( $icon ) && 'wmamp-font-file-url' !== $key ) {
								$icons['icons'][ $icon['class'] ] = '\\' . $icon['char'];
								$icons['icons_select'][ $icons['css-prefix'] . $icon['class'] . $icons['css-suffix'] ] = ucfirst( str_replace( array( '-', '_' ), ' ', $icon['class'] ) );
							}
						}
					}

				//Prepare default icon font file info
					if ( isset( $this->font_config['wmamp-font-file-url'] ) ) {
						$fontcss = $this->font_config['wmamp-font-file-url'];
					} else {
						$fontcss = apply_filters( 'wmhook_icons_' . 'default_iconfont_css_url', WMAMP_ASSETS_URL . 'font/' . $this->font_name . '.css' );
					}

				//Cache in database
					update_option( 'wmamp-icon-font', $fontcss );
					update_option( 'wmamp-icons', $icons );
			} // /write_config



			/**
			 * Rename the unZIPped files
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function rename_files() {
				$extensions = array( 'eot', 'svg', 'ttf', 'woff', 'css' );
				$folder     = trailingslashit( $this->paths['tempdir'] );

				foreach ( glob( $folder . '*' ) as $file ) {
					$path_parts = pathinfo( $file );
					if ( in_array( $path_parts['extension'], $extensions ) ) {
						rename( $file, trailingslashit( $path_parts['dirname'] ) . $this->font_name . '.' . $path_parts['extension'] );
					}
				}
			} // /rename_files



			/**
			 * Make temporary folder the final one
			 *
			 * @since   1.0
			 * @access  private
			 */
			private function rename_folder() {
				//Delete folder and contents if they already exist
					$this->delete_folder( $this->paths['fontdir'] );

				rename( $this->paths['tempdir'], $this->paths['fontdir'] );
			} // /rename_folder



			/**
			 * Delete folder and contents if they already exist
			 *
			 * @since   1.0
			 * @access  private
			 *
			 * @param   string $folder Folder path
			 */
			private function delete_folder( $folder = '' ) {
				if ( is_dir( $folder ) ) {
					$objects = scandir( $folder );
					foreach ( $objects as $object ) {
						if ( '.' != $object && '..' != $object ) {
							unlink( $folder . '/' . $object );
						}
					}
					reset( $objects );
					@rmdir( $folder );
					if ( is_dir( $folder ) && rmdir( $folder ) ) {
						exit( "Wasn't able to remove previously created folder" );
					}
				}
			} // /delete_folder

	} // /WM_Icons

} // /class WM_Icons check
