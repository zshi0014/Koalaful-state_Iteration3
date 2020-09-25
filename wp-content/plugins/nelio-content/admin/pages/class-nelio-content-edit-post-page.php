<?php
/**
 * This file customizes the post edit screen.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers required UI elements to customize post edit screen.
 */
class Nelio_Content_Edit_Post_Page {

	private $old_post_values;

	public function init() {

		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'register_assets' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'maybe_enqueue_classic_editor_assets' ] );
		add_action( 'enqueue_block_editor_assets', [ $this, 'maybe_enqueue_gutenberg_assets' ] );

	}//end init()

	public function register_assets() {

		wp_register_style(
			'nelio-content-edit-post',
			nelio_content()->plugin_url . '/assets/dist/css/edit-post.css',
			[ 'nelio-content-components' ],
			nc_get_script_version( 'edit-post' )
		);

		nc_register_script_with_auto_deps( 'nelio-content-edit-post', 'edit-post', true );
		nc_register_script_with_auto_deps( 'nelio-content-gutenberg-editor', 'gutenberg-editor', true );
		nc_register_script_with_auto_deps( 'nelio-content-classic-editor', 'classic-editor', true );

	}//end register_assets()

	public function maybe_enqueue_gutenberg_assets() {

		if ( ! $this->is_calendar_post_type() ) {
			return;
		} //end if

		wp_enqueue_style( 'nelio-content-edit-post' );
		wp_add_inline_style(
			'nelio-content-edit-post',
			'.rich-text ncshare { background: #ffa } .rich-text:focus ncshare[data-rich-text-format-boundary] { background: #fe0 }'
		);

		wp_enqueue_script( 'nelio-content-gutenberg-editor' );
		wp_add_inline_script(
			'nelio-content-gutenberg-editor',
			sprintf(
				'NelioContent.initPage( %s );',
				wp_json_encode( $this->get_init_args() )
			)
		);

	}//end maybe_enqueue_gutenberg_assets()

	public function maybe_enqueue_classic_editor_assets() {

		if ( ! $this->is_classic_editor() ) {
			return;
		}//end if

		if ( ! $this->is_calendar_post_type() ) {
			return;
		} //end if

		wp_enqueue_style( 'nelio-content-edit-post' );
		wp_add_inline_style(
			'nelio-content-edit-post',
			sprintf(
				'.mce-toolbar .mce-btn .mce-i-nelio-content-icon:before{background:none;background-image:url(%s);background-size:1em 1em;content:"";display:block;font-size:20px;height:1em;opacity:0.67;width:1em;}',
				nelio_content()->plugin_url . '/assets/dist/images/logo.svg'
			)
		);

		wp_enqueue_script( 'nelio-content-classic-editor' );
		wp_add_inline_script(
			'nelio-content-classic-editor',
			sprintf(
				'NelioContent.initPage( %s );',
				wp_json_encode( $this->get_init_args() )
			)
		);

	}//end maybe_enqueue_classic_editor_assets()

	private function is_classic_editor() {

		$settings = Nelio_Content_Settings::instance();
		$screen   = get_current_screen();
		if ( ! in_array( $screen->id, $settings->get( 'calendar_post_types', [] ), true ) ) {
			return false;
		}//end if

		return ! did_action( 'enqueue_block_editor_assets' );

	}//end is_classic_editor()

	private function is_calendar_post_type() {
		$settings = Nelio_Content_Settings::instance();
		return in_array( get_post_type(), $settings->get( 'calendar_post_types', [] ), true );
	} //end if

	private function get_init_args() {
		$post        = $this->get_editing_post();
		$post_id     = $post['id'];
		$settings    = Nelio_Content_Settings::instance();
		$post_helper = Nelio_Content_Post_Helper::instance();

		return array(
			'attributes' => array(
				'externalFeatImage' => $this->get_external_featured_image( $post ),
				'followers'         => $post_helper->get_post_followers( $post_id ),
				'references'        => $post_helper->get_references( $post_id, 'all' ),
			),
			'post'       => $post,
			'settings'   => array(
				'dynamicSections'        => array(
					'externalFeatImage' => $settings->get( 'use_external_featured_image' ),
					'notifications'     => $settings->get( 'use_notifications' ),
				),
				'nonce'                  => wp_create_nonce( "nelio_content_save_post_{$post_id}" ),
				'qualityAnalysis'        => array(
					'canImageBeAutoSet' => 'disabled' !== $settings->get( 'auto_feat_image' ),
					'isFullyIntegrated' => $this->is_quality_analysis_fully_integrated(),
					'isYoastIntegrated' => $this->is_yoast_integrated(),
					'supportsFeatImage' => current_theme_supports( 'post-thumbnails' ),
				),
				/** This filter is documented in includes/utils/class-nelio-content-post-saving.php */
				'shouldAuthorBeFollower' => apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true ),
			),
		);
	}//end get_init_args()

	private function get_editing_post() {
		$helper = new Nelio_Content_Post_Helper();
		$post   = $helper->post_to_aws_json( get_the_ID() );
		return array_merge(
			$post,
			array( 'permalinkTemplate' => get_sample_permalink( get_the_ID(), $post['title'], '' )[0] )
		);
	}//end get_editing_post()

	private function is_quality_analysis_fully_integrated() {
		/**
		 * Returns whether the quality analysis should be fully integrated with WordPress or not,
		 * using default sidebars and metaboxes.
		 *
		 * If it isn’t, Nelio Content will only use its own areas to display QA.
		 *
		 * @param $is_visible boolean whether the quality analysis is fully integrated with WP.
		 *                            Default: `true`.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'nelio_content_is_quality_analysis_fully_integrated', true );
	}//end is_quality_analysis_fully_integrated()

	private function is_yoast_integrated() {
		if (
			! is_plugin_active( 'wordpress-seo/wp-seo.php' ) &&
			! is_plugin_active( 'wordpress-seo-premium/wp-seo-premium.php' )
		) {
			return false;
		} //end if

		/**
		 * Whether Yoast should be integrated with Nelio Content’s quality analysis or not.
		 *
		 * @param $integrated boolean Default: true.
		 *
		 * @since 2.0.0
		 */
		return apply_filters( 'nelio_content_is_yoast_integrated_in_quality_analysis', true );
	}//end is_yoast_integrated()

	private function get_external_featured_image( $post ) {
		$post_id = empty( $post ) ? 0 : $post['id'];
		return array(
			'url' => get_post_meta( $post_id, '_nelioefi_url', true ),
			'alt' => get_post_meta( $post_id, '_nelioefi_alt', true ),
		);
	}//end get_external_featured_image()

	private function get_post_followers( $post ) {
		if ( empty( $post ) ) {
			return [];
		}//end if
		if ( empty( $post['followers'] ) ) {
			return [];
		}//end if
		return $post['followers'];
	}//end get_post_followers()

}//end class
