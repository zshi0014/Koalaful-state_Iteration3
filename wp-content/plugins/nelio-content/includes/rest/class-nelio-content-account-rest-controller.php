<?php
/**
 * This file contains the class that defines REST API endpoints for
 * managing a Nelio Content account.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Account_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Account_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Account_REST_Controller the single instance of this class.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * Hooks into WordPress.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function init() {

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );

	}//end init()

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_site_data' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site/free',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_free_site' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site/use-license',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'use_license_in_site' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
					'args'                => array(
						'license' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_valid_license',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/site/remove-license',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'remove_license_from_site' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/upgrade-to-yearly',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'upgrade_to_yearly_plan' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription',
			array(
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => [ $this, 'cancel_subscription' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/uncancel',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'uncancel_subscription' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/subscription/invoices',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_invoices_from_subscription' ],
					'permission_callback' => 'nc_can_current_user_manage_account',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/authentication-token',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_authentication_token' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

	}//end register_routes()

	/**
	 * Retrieves information about the site.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_site_data() {

		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id(), 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update subscription information with response.
		$site_info = json_decode( $response['body'], true );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		return new WP_REST_Response( $account, 200 );

	}//end get_site_data()

	/**
	 * Creates a new free site in AWS and updates the info in WordPress.
	 *
	 * @return WP_REST_Response The response
	 */
	public function create_free_site() {

		if ( nc_get_site_id() ) {
			return new WP_Error(
				'site-already-exists',
				_x( 'Site already exists.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'accept'       => 'application/json',
				'content-type' => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'url'      => home_url(),
					'timezone' => nc_get_timezone(),
					'language' => nc_get_language(),
				)
			),
		);

		$url      = nc_get_api_url( '/site', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		update_option( 'nc_api_secret', $site_info['secret'] );

		nc_update_subscription_information_with_site_object( $site_info );
		$this->notify_site_created();

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();
		return new WP_REST_Response( $account, 200 );

	}//end create_free_site()

	/**
	 * Connects a site with a subscription.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function use_license_in_site( $request ) {

		$parameters = $request->get_json_params();
		$license    = $parameters['license'];

		if ( nc_get_site_id() ) {

			$data = array(
				'method'    => 'POST',
				'timeout'   => 30,
				'sslverify' => ! nc_does_api_use_proxy(),
				'headers'   => array(
					'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
					'accept'        => 'application/json',
					'content-type'  => 'application/json',
				),
				'body'      => wp_json_encode(
					array(
						'license' => $license,
					)
				),
			);

			$url = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );

		} else {

			$data = array(
				'method'    => 'POST',
				'timeout'   => 30,
				'sslverify' => ! nc_does_api_use_proxy(),
				'headers'   => array(
					'accept'       => 'application/json',
					'content-type' => 'application/json',
				),
				'body'      => wp_json_encode(
					array(
						'url'      => home_url(),
						'timezone' => nc_get_timezone(),
						'language' => nc_get_language(),
						'license'  => $license,
					)
				),
			);

			$url = nc_get_api_url( '/site/subscription', 'wp' );

		}//end if

		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		// If this is a new site, let's save the ID and the secret.
		if ( ! nc_get_site_id() ) {
			update_option( 'nc_site_id', $site_info['id'] );
			update_option( 'nc_api_secret', $site_info['secret'] );
			nc_update_subscription_information_with_site_object( $site_info );
			$this->notify_site_created();
		} else {
			nc_update_subscription_information_with_site_object( $site_info );
		}//end if

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();
		return new WP_REST_Response( $account, 200 );

	}//end use_license_in_site()

	/**
	 * Removes the license from this site (if any).
	 *
	 * @return WP_REST_Response The response
	 */
	public function remove_license_from_site() {

		if ( empty( nc_get_site_id() ) ) {
			return new WP_Error(
				'no-site-id',
				_x( 'No license can be removed, because the plugin is not set up.', 'text', 'nelio-content' )
			);
		}//end if

		if ( empty( nc_get_subscription() ) ) {
			return new WP_Error(
				'no-subscription',
				_x( 'No license can be removed, because there’s no account available.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/free', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		return new WP_REST_Response( $account, 200 );

	}//end remove_license_from_site()

	/**
	 * Upgrades the subscription to a yearly subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function upgrade_to_yearly_plan() {

		$data = array(
			'method'    => 'PUT',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'product' => 'nc-yearly',
				)
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		// Update subscription.
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		return new WP_REST_Response( $account, 200 );

	}//end upgrade_to_yearly_plan()

	/**
	 * Cancels a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function cancel_subscription() {

		if ( ! nc_get_site_id() ) {
			return new WP_Error(
				'no-site-id',
				_x( 'Subscription cannot be canceled, because there’s no account available.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'DELETE',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		return new WP_REST_Response( $account, 200 );

	}//end cancel_subscription()

	/**
	 * Un-cancels a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function uncancel_subscription() {

		if ( ! nc_get_site_id() ) {
			return new WP_Error(
				'no-site-id',
				_x( 'Subscription cannot be reactivated, because there’s no account available.', 'text', 'nelio-content' )
			);
		}//end if

		$data = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/uncancel', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Update site ID and subscription information.
		$site_info = json_decode( $response['body'], true );

		if ( ! isset( $site_info['id'] ) ) {
			return new WP_Error(
				'unable-to-process-response',
				_x( 'Response from Nelio Content’s API couldn’t be processed.', 'text', 'nelio-content' )
			);
		}//end if

		update_option( 'nc_site_id', $site_info['id'] );
		nc_update_subscription_information_with_site_object( $site_info );

		// Regenerate the account result and send it to the JS.
		$account = $this->create_account_object();

		return new WP_REST_Response( $account, 200 );

	}//end uncancel_subscription()

	/**
	 * Obtains the invoices of a subscription.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_invoices_from_subscription() {

		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
		);

		$url      = nc_get_api_url( '/site/' . nc_get_site_id() . '/subscription/invoices', 'wp' );
		$response = wp_remote_request( $url, $data );

		// If the response is an error, leave.
		$error = nc_extract_error_from_response( $response );
		if ( ! empty( $error ) ) {
			return $error;
		}//end if

		// Regenerate the invoices result and send it to the JS.
		$invoices = json_decode( $response['body'], true );
		$invoices = array_map(
			function( $invoice ) {
				$invoice['chargeDate'] = date( get_option( 'date_format' ), strtotime( $invoice['chargeDate'] ) );
				return $invoice;
			},
			$invoices
		);

		return new WP_REST_Response( $invoices, 200 );

	}//end get_invoices_from_subscription()

	/**
	 * Gets an authentication token for the current user.
	 *
	 * @return WP_REST_Response The response
	 */
	public function get_authentication_token() {
		return new WP_REST_Response( nc_generate_api_auth_token(), 200 );
	}//end get_authentication_token()

	/**
	 * This helper function creates an account object.
	 *
	 * @param object $site The data about the site.
	 *
	 * @return array an account object.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	private function create_account_object() {

		$subscription = nc_get_subscription();
		if ( empty( $subscription ) ) {
			return array(
				'subscription' => 'none',
				'mode'         => 'regular',
			);
		}//end if

		return array(
			'creationDate'        => $subscription['creationDate'],
			'email'               => $subscription['email'],
			'firstname'           => $subscription['firstname'],
			'lastname'            => $subscription['lastname'],
			'photo'               => get_avatar_url( $subscription['email'], array( 'default' => 'blank' ) ),
			'mode'                => $subscription['mode'],
			'license'             => $subscription['license'],
			'endDate'             => $subscription['endDate'],
			'nextChargeDate'      => $subscription['nextChargeDate'],
			'nextChargeTotal'     => $subscription['nextChargeTotal'],
			'subscription'        => $subscription['plan'],
			'state'               => $subscription['state'],
			'urlToManagePayments' => nc_get_api_url( '/fastspring/' . $subscription['id'] . '/url', 'browser' ),
		);

	}//end create_account_object()

	private function notify_site_created() {

		/**
		 * Fires once the site has been registered in Nelio’s cloud.
		 *
		 * When fired, the site has a valid site ID and an API secret.
		 *
		 * @since 2.0.0
		 */
		do_action( 'nelio_content_site_created' );

	}//end notify_site_created()

}//end class
