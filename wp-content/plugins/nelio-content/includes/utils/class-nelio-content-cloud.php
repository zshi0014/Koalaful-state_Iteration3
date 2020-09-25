<?php
/**
 * This file contains some functions to sync WordPress with Nelio’s cloud.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements some functions to sync WordPress with Nelio’s cloud.
 */
class Nelio_Content_Cloud {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		if ( nc_use_editorial_calendar_only() ) {
			return;
		} //end if

		add_action( 'nelio_content_save_post', [ $this, 'maybe_sync_post' ] );
		add_action( 'nelio_content_update_post_in_cloud', [ $this, 'maybe_sync_post' ] );
		add_action( 'plugins_loaded', [ $this, 'add_hooks_for_updating_post_in_cloud_on_publish' ] );

	}//end init()

	public function add_hooks_for_updating_post_in_cloud_on_publish() {

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		foreach ( $post_types as $post_type ) {
			add_action( "publish_{$post_type}", [ $this, 'maybe_sync_post' ] );
		}//end foreach

	}//end add_hooks_for_updating_post_in_cloud_on_publish()

	public function maybe_sync_post( $post_id ) {

		// If it's a revision or an autosave, do nothing.
		if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
			return;
		}//end if

		// If we don't have social profiles, we don't need to know anything about the posts.
		if ( ! get_option( 'nc_has_social_profiles' ) ) {
			return;
		}//end if

		// Only sync posts whose type is controlled by the plugin.
		$settings = Nelio_Content_Settings::instance();
		if ( ! in_array( get_post_type( $post_id ), $settings->get( 'calendar_post_types' ), true ) ) {
			return;
		}//end if

		// If everything's OK, save it.
		$post_helper = Nelio_Content_Post_Helper::instance();
		$post        = $post_helper->post_to_aws_json( $post_id );
		if ( empty( $post ) ) {
			return;
		}//end if

		if ( ! $post_helper->has_post_changed_since_last_sync( $post_id ) ) {
			return;
		}//end if

		$attempts = get_post_meta( $post_id, '_nc_cloud_sync_attempts', true );
		if ( empty( $attempts ) ) {
			$attempts = 0;
		}//end if
		++$attempts;

		$synched = $this->sync_post( $post_id, $post );
		if ( ! $synched && 3 >= $attempts ) {
			update_post_meta( $post_id, '_nc_cloud_sync_attempts', $attempts );
			wp_schedule_single_event( time() + 30, 'nelio_content_update_post_in_cloud', [ $post_id ] );
		} else {
			delete_post_meta( $post_id, '_nc_cloud_sync_attempts' );
			$post_helper->mark_post_as_synched( $post_id );
		}//end if

	}//end maybe_sync_post()

	private function sync_post( $post_id, $post ) {

		$data = array(
			'method'    => 'PUT',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode( $post ),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/post/%s', 'wp' ),
			nc_get_site_id(),
			$post_id
		);

		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		if ( ! isset( $response['response'] )
			|| ! isset( $response['response']['code'] )
			|| 200 !== $response['response']['code'] ) {
			return false;
		}//end if

		return true;

	}//end sync_post()

}//end class
