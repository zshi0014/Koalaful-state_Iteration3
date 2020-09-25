<?php
/**
 * This file adds a few hooks to work with Gutenberg.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/editors
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that registers specific hooks to work with the Gutenberg.
 */
class Nelio_Content_Gutenberg {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'rest_api_init', [ $this, 'register_custom_metas' ] );
	}//end init()

	public function register_custom_metas() {

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', [] );
		if ( empty( $post_types ) ) {
			return;
		} //end if

		register_rest_field(
			$post_types,
			'nelio_content',
			array(
				'get_callback'    => [ $this, 'get_values' ],
				'update_callback' => [ $this, 'save' ],
			)
		);

	}//end register_custom_metas()

	public function get_values( $object ) {
		$post_id     = $object['id'];
		$post_helper = Nelio_Content_Post_Helper::instance();

		$suggested = array_map(
			function ( $reference ) {
				return $reference['url'];
			},
			$post_helper->get_references( $post_id, 'suggested' )
		);

		$included = array_map(
			function ( $reference ) {
				return $reference['url'];
			},
			$post_helper->get_references( $post_id, 'included' )
		);

		return array(
			'isAutoShareEnabled'  => $post_helper->is_auto_share_enabled( $post_id ),
			'followers'           => $post_helper->get_post_followers( $post_id ),
			'suggestedReferences' => $suggested,
			'includedReferences'  => $included,
			'efiUrl'              => get_post_meta( $post_id, '_nelioefi_url', true ) ?: '',
			'efiAlt'              => get_post_meta( $post_id, '_nelioefi_alt', true ) ?: '',
		);
	}//end get_values()

	public function save( $values, $post ) {
		if ( ! is_array( $values ) ) {
			return;
		}//end if

		$efi_helper = Nelio_Content_External_Featured_Image_Helper::instance();
		$efi_helper->set_nelio_featured_image( $post->ID, $values['efiUrl'], $values['efiAlt'] );

		$post_helper = Nelio_Content_Post_Helper::instance();
		$post_helper->save_post_followers( $post->ID, $values['followers'] );
		$post_helper->update_post_references( $post->ID, $values['suggestedReferences'], $values['includedReferences'] );
		$post_helper->enable_auto_share( $post->ID, $values['isAutoShareEnabled'] );
	} //end if

}//end class
