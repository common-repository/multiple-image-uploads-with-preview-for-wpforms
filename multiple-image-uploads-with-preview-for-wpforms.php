<?php
/**
 * Plugin Name: Multiple image uploads with preview for WPForms
 * Description: This plugin is created for WPForms  it will help you to upload multiples images with preview in the front end
 * Version: 1.3
 * Tags: Image Upload, Multiple Images Upload
 * Author: P5Cure
 * Author URI: https://www.p5cure.com
 * Author Email: meetp5cure@gmail.com
 * Requires at least: WP 5.2
 * Tested up to: WP 5.7
 * Text Domain:  miuwp_wpf
 * Domain Path: /lang
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * PHP version 7
 *
 * @category Plugin
 * @package  Multiple_Image_Uploads_With_Preview_For_WPForms
 * @author   P5Cure <meetp5cure@gmail.com>
 * @license  GPLv2 or later
 * @version  SVN: <svn_id>
 * @link     http://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'miuwpfw_fs' ) ) {
    // Create a helper function for easy SDK access.
    function miuwpfw_fs() {
        global $miuwpfw_fs;

        if ( ! isset( $miuwpfw_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $miuwpfw_fs = fs_dynamic_init( array(
                'id'                  => '8269',
                'slug'                => 'multiple-image-uploads-with-preview-for-wpforms',
                'type'                => 'plugin',
                'public_key'          => 'pk_e317ed7e644cae0869689e08ac178',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'plugins.php',
                    'account'        => false,
                    'support'        => false,
                ),
            ) );
        }

        return $miuwpfw_fs;
    }

    // Init Freemius.
    miuwpfw_fs();
    // Signal that SDK was initiated.
    do_action( 'miuwpfw_fs_loaded' );
}

if ( ! class_exists( 'MultipleImageUploadsPreviewWPForms' ) ) {
	/**
	 * Plugin Main Class
	 *
	 * @category Plugin
	 * @package  Multiple_Image_Uploads_With_Preview_For_WPForms
	 * @author   P5Cure <meetp5cure@gmail.com>
	 * @license  GPLv2 or later
	 * @link     http://www.gnu.org/licenses/gpl-2.0.html
	 */
	class MultipleImageUploadsPreviewWPForms {

		/**
		 * StarterPlugin constructor.
		 *
		 * @since   1.0
		 * @version 1.0
		 */
		public function __construct() {
			 $this->run();
		}

		/**
		 * Runs Plugins
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		protected function run() {
			$this->constants();
			$this->includes();
			$this->add_actions();
			$this->add_filters();
		}

		/**
		 * Define
		 *
		 * @param $name  Name of constant
		 * @param $value Value of constant
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		protected function define( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Defines Constants
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		protected function constants() {
			$this->define( 'miuwp_wpf_VERSION', '1.0' );
			$this->define( 'miuwp_wpf_PREFIX', 'miuwpfpf_' );
			$this->define(
				'miuwp_wpf_TEXT_DOMAIN',
				'multiple-image-uploads-with-preview-wpforms'
			);
			$this->define( 'miuwp_wpf_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
			$this->define( 'miuwp_wpf_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Require File
		 *
		 * @param $required_file Required Files
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		protected function file( $required_file ) {
			if ( file_exists( $required_file ) ) {
				include_once $required_file;
			} else {
				echo "<div class='notice notice-error is-dissmissable'>
                    <p> "
				   . __( 'File Not Found', 'default' ) .
				' </p>
                </div>';
			}
		}

		/**
		 * Include files
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		protected function includes() {
			 /*
			 * Including Core Files
			 * * image.php
			 * * file.php
			 * * media.php
			 */
			$this->file( ABSPATH . 'wp-admin/includes/image.php' );
			$this->file( ABSPATH . 'wp-admin/includes/file.php' );
			$this->file( ABSPATH . 'wp-admin/includes/media.php' );

			/*
			 * Including Plugin Required Files
			 * * functions.php
			 * * class-uploader.php
			*/
			$this->file(
				miuwp_wpf_PLUGIN_DIR_PATH
				. 'includes/multiple-image-uploads-with-preview-wpforms-functions.php'
			);
			$this->file(
				miuwp_wpf_PLUGIN_DIR_PATH
				. 'includes/libraries/class-miuwp-wpf-fileuploader.php'
			);
		}

		/**
		 * Enqueue Styles and Scripts
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		public function wp_enqueue_scripts() {
			/*
			* File Pond CSS
			*/
			wp_enqueue_style(
				miuwp_wpf_TEXT_DOMAIN . '-file-pond-css',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/css/filepond.css',
				array(),
				miuwp_wpf_VERSION,
				'all'
			);

			/*
			* Custom  CSS
			* * Custom CSS
			*/
			wp_enqueue_style(
				miuwp_wpf_TEXT_DOMAIN . '-custom-css',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/css/style.css',
				array(),
				miuwp_wpf_VERSION,
				'all'
			);

			/*
			* File Pond JS
			* * Image Size Validation
			* * Image Crop
			* * Image Preview
			* * Image Validate
			* * File Pond
			* * File Pond Jquery
			* * File Editor
			*/
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . 'file-size-validation',
				miuwp_wpf_PLUGIN_DIR_URL
				. 'assets/js/filepond-plugin-file-validate-size.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . '-file-crop-js',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/js/filepond-plugin-image-crop.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . '-file-image-preview-js',
				miuwp_wpf_PLUGIN_DIR_URL
				. 'assets/js/filepond-plugin-image-preview.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . '-file-validate-js',
				miuwp_wpf_PLUGIN_DIR_URL
				. 'assets/js/filepond-plugin-file-validate-type.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . '-file-js',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/js/filepond.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . 'file-jquery-js',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/js/filepond.jquery.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . '-file-edit-js',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/js/filepond-plugin-image-edit.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);

			/*
			* Custom JS
			* * Custom Js
			* * Localize variables for Js
			*/
			wp_enqueue_script(
				miuwp_wpf_TEXT_DOMAIN . '-custom-js',
				miuwp_wpf_PLUGIN_DIR_URL . 'assets/js/custom.js',
				array( 'jquery' ),
				miuwp_wpf_VERSION,
				true
			);
			wp_localize_script(
				miuwp_wpf_TEXT_DOMAIN . '-custom-js',
				'miuwp_wpf_objects',
				array(
					'plugin_dir_path' => plugin_dir_path( __FILE__ ),
					'plugin_dir_url'  => plugin_dir_url( __FILE__ ),
					'ajax_url'        => admin_url( 'admin-ajax.php' ),
				)
			);
		}

		/**
		 * Add Actions
		 *
		 * @since   1.0
		 * @version 1.0
		 *
		 * @return null
		 */
		protected function add_actions() {
			/*
			 * Add Actions
			 * * Requirements
			 * * Frontend Enqueue Scripts
			 * * Plugins Loaded
			 * * Admin Menu
			 * * Ajax
			*/
			add_action( 'admin_notices', array( $this, 'requirements' ), 10 );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 10 );
			add_action( 'plugins_loaded', array( $this, 'override' ), 10 );
			add_action( 'wp_ajax_miuwp_wpf_ajax', array( $this, 'miuwp_wpf_ajax' ), 10 );
			add_action( 'wp_ajax_nopriv_miuwp_wpf_ajax', array( $this, 'miuwp_wpf_ajax' ), 10 );
		}

		/**
		 * Ajax
		 *
		 * @return null
		 */
		public function miuwp_wpf_ajax() {
			$response = array();
			if ( isset( $_POST['method'] ) ) {
				$method         = sanitize_text_field( $_POST['method'] );
				$post_mime_type = sanitize_text_field( $_FILES['filepond']['type'] );

				$response['type'] = 'success';
				switch ( $method ) :
					case 'upload-file':
						$upload_directory = wp_upload_dir();
						$FileUploader     = new FileUploader(
							'filepond',
							array(
								'limit'       => null,
								'fileMaxSize' => null,
								'extensions'  => null,
								'uploadDir'   => $upload_directory['path'] . '/',
								'required'    => true,
								'title'       => 'auto',
								'replace'     => false,
							)
						);

						$upload = $FileUploader->upload();

						if ( $upload['isSuccess'] ) {
							$files = $upload['files'];

							foreach ( $files as $key => $file ) :
								$upload_url  = $upload_directory['url'] . '/' . $file['name'];
								$upload_path = $upload_directory['path'] . '/' . $file['name'];
								$upload_id   = wp_insert_attachment(
									array(
										'guid'           => $upload_url,
										'post_mime_type' => $post_mime_type,
										'post_title'     => preg_replace( '/\.[^.]+$/', '', $file['name'] ),
										'post_content'   => '',
										'post_status'    => 'inherit',
									),
									$upload_url
								);

								wp_update_attachment_metadata(
									$upload_id,
									wp_generate_attachment_metadata(
										$upload_id,
										$upload_path
									)
								);

								$response['id'][] = $upload_id;
							endforeach;
							$response['message'] = '';
						}

						$response['type'] = 'success';
						break;
					default:
						$response['type']    = 'error';
						$response['message'] = 'No Method Found';
						break;
				endswitch;
			} else {
				$response['type'] = 'error';
			}

			exit( stripslashes( json_encode( $response ) ) );
		}

		/**
		 * Override
		 *
		 * @return null
		 */
		public function override() {
			if ( class_exists( 'WPForms_Field' ) ) {
				$this->file(
					miuwp_wpf_PLUGIN_DIR_PATH
					. 'includes/fields/class-miuwp-wpf-upload-file.php'
				);
			}
		}

		/**
		 * Custom Fields
		 *
		 * @param $fields Fields
		 *
		 * @return mixed
		 */
		public function wpforms_custom_fields( $fields ) {
			$fields['multiple-image-uploads-with-preview'] = array(
				'group_name' => esc_html__( 'Multiple images upload with preview', 'default' ),
				'fields'     => array(
					array(
						'order' => 100,
						'type'  => 'upload-image',
						'icon'  => 'fa-cloud-upload',
						'name'  => esc_html__( 'Image Upload', 'default' ),
					),
				),
			);
			return $fields;
		}


		/**
		 * Field
		 *
		 * @param $field Field
		 *
		 * @version 1.2
		 * @since   1.2
		 *
		 * @return false|string[]
		 */
		protected function create_str_to_array( $field ) {
			$field = str_replace( '"', '', $field );
			$field = str_replace( '\\', '', $field );
			$field = str_replace( ']', '', $field );
			$field = str_replace( '[', '', $field );
			return explode( ',', $field );
		}

		/**
		 * Process Form
		 *
		 * @param $fields    Fields
		 * @param $entry     Entry
		 * @param $form_data ormdata
		 *
		 * @version 1.2
		 * @since   1.2
		 *
		 * @return mixed
		 */
		public function process_form( $fields, $entry, $form_data ) {
			foreach ( $fields as $key => $field ) {
				if ( $field['type'] == 'upload-image' ) {
					$field_ids[] = $key;
				}
			}

			if ( isset( $field_ids ) ) {
				if ( count( $field_ids ) > 0 ) {
					foreach ( $field_ids as $field_id ) {
						if ( isset( $entry['fields'][ $field_id ] ) ) {
							$array_value = $entry['fields'][ $field_id ];
							$image_ids   = $this->create_str_to_array( $array_value['file_id'] );
							$image_ids   = array_unique( $image_ids );

							foreach ( $image_ids as $image_id ) {
								$images[] = "<div class='su-gallery-entry' style='float: left;margin: 10px;' ><a href='" . esc_url( wp_get_attachment_url( $image_id ) ) . "' target='_blank' >" . wp_get_attachment_image( $image_id ) . '</a></div>';
							}

							$images                       = implode( ',', $images );
							$images                       = str_replace( ',', '', $images );
							$fields[ $field_id ]['value'] = $images;
						}
					}
				}
			}
			return $fields;
		}

		/**
		 * Send Email Data
		 *
		 * @param $array  Array
		 * @param $object Object
		 *
		 * @version 1.2
		 * @since   1.2
		 *
		 * @return mixed
		 */
		/**
		public function send_email_data($array, $object)
		{
			return $array;
			foreach ($object->fields as $field) {
				if ($field['type'] == "upload-image") {
					$ids = $this->create_str_to_array($field['value']);
					$ids = array_unique($ids);
					foreach ( $ids as $id ) {
						$images[] = "<div class='su-gallery-entry' style='float: left;margin: 10px;' ><a href='" . esc_url(wp_get_attachment_url( $id )) . "' target='_blank' >" . wp_get_attachment_image($id) . "</a></div>";
						$array['attachments'][] = get_attached_file($id);
					}
				}
			}

			$images = implode(",", $images);
			$images = str_replace(",", "<br>", $images);
			$message = $array['message'];
			$message = $message . " <br>" . $images;
			$array['message'] = $message;

			var_dump($object);
			exit;
		}*/

		/**
		 * Add Filters
		 *
		 * @return null
		 */
		protected function add_filters() {
			/*
			 * Add Filters
			 * * Form Builder Button
			 * * Send Email
			 * * Process Form
			*/
			add_filter(
				'wpforms_builder_fields_buttons',
				array( $this, 'wpforms_custom_fields' ),
				10,
				1
			);
			/**
			 add_filter(
				'wpforms_emails_send_email_data',
				array($this, "send_email_data"),
				10, 2
			);*/
			add_filter(
				'wpforms_process_filter',
				array( $this, 'process_form' ),
				10,
				3
			);
		}

		/**
		 * Requirements
		 *
		 * @return null
		 */
		public function requirements() {
			if ( ! in_array(
				'wpforms-lite/wpforms.php',
				apply_filters(
					'active_plugins',
					get_option( 'active_plugins' )
				)
			)
			) {
				echo "<div class='notice notice-error'><p>"
						. __(
							'Please install and active
                             <a href="https://wordpress.org/plugins/wpforms-lite/">
                                <b>WPForms Plugin</b>
                            </a> to use 
                            <b>Multiple image uploads with preview for WPForms
                            </b>',
							'default'
						) .
					 '</p></div>';
			}
		}
	}
}

new MultipleImageUploadsPreviewWPForms();
