<?php
/**
 * Nelio Content subscription-related functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils/functions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This function returns information about the current subscription.
 *
 * @return array information about the current subscription.
 *
 * @since 1.0.0
 */
function nc_get_subscription() {

	return get_option( 'nc_subscription', false );

}//end nc_get_subscription()

/**
 * Returns whether the current user is a paying customer or not.
 *
 * @return boolean whether the current user is a paying customer or not.
 *
 * @since 1.0.0
 */
function nc_is_subscribed() {

	$subscription = nc_get_subscription();
	return ! empty( $subscription );

}//end nc_is_subscribed()

/**
 * This helper function updates the subscription information (as stored in
 * the `nc_subscription` option) using the site information received from
 * AWS.
 *
 * @param array $site_info site information received from AWS.
 *
 * @since  1.2.0
 */
function nc_update_subscription_information_with_site_object( $site_info ) {

	// If the site info we obtained is incomplete or is not related to this
	// site, just leave.
	if ( ! isset( $site_info['id'] ) || nc_get_site_id() !== $site_info['id'] ) {
		return;
	}//end if

	update_option(
		'nc_site_limits',
		array(
			'maxProfiles'           => $site_info['maxProfiles'],
			'maxProfilesPerNetwork' => $site_info['maxProfilesPerNetwork'],
		)
	);

	// If site info doesn't contain any information about a subscription, save
	// a "free version" subscription object.
	if ( ! isset( $site_info['subscription'] ) ) {
		delete_option( 'nc_subscription' );
		return;
	}//end if

	// If we do have information about the subscription, update it.
	$subscription = array(
		'creationDate'    => $site_info['creation'],
		'email'           => $site_info['subscription']['account']['email'],
		'firstname'       => $site_info['subscription']['account']['firstname'],
		'lastname'        => $site_info['subscription']['account']['lastname'],
		'mode'            => $site_info['subscription']['mode'],
		'endDate'         => false, // To be initialized.
		'license'         => false, // To be initialized.
		'nextChargeDate'  => false, // To be initialized.
		'nextChargeTotal' => false, // To be initialized.
		'plan'            => false, // To be initialized.
		'state'           => false, // To be initialized.
	);

	// End date.
	if ( isset( $site_info['subscription']['endDate'] ) ) {
		$subscription['endDate'] = $site_info['subscription']['endDate'];
	} else {
		$subscription['endDate'] = '';
	}//end if

	// License.
	if ( isset( $site_info['subscription']['license'] ) ) {
		$subscription['license'] = $site_info['subscription']['license'];
	} else {
		$subscription['license'] = '';
	}//end if

	// Next Charge Date.
	if ( isset( $site_info['subscription']['nextChargeDate'] ) ) {
		$subscription['nextChargeDate'] = $site_info['subscription']['nextChargeDate'];
	} else {
		$subscription['nextChargeDate'] = '';
	}//end if

	// Next Charge Total.
	if ( isset( $site_info['subscription']['nextChargeTotal'] ) ) {
		$subscription['nextChargeTotal'] = $site_info['subscription']['nextChargeTotal'];
	} else {
		$subscription['nextChargeTotal'] = '';
	}//end if

	// Period.
	if ( strpos( $site_info['subscription']['product'], 'yearly' ) ) {
		$subscription['plan'] = 'yearly';
	} else {
		$subscription['plan'] = 'monthly';
	}//end if

	// State.
	if ( 'canceled' === $site_info['subscription']['state'] ) {
		$subscription['state'] = 'canceled';
	} else {
		$subscription['state'] = 'active';
	}//end if

	update_option( 'nc_subscription', $subscription );

}//end nc_update_subscription_information_with_site_object()

/**
 * This helper function updates the current subscription by pulling the
 * information from AWS.
 *
 * @since  1.5.0
 */
function nc_refresh_subscription() {

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

	if ( nc_is_response_valid( $response ) ) {
		$site_info = json_decode( $response['body'], true );
		nc_update_subscription_information_with_site_object( $site_info );
	}//end if

}//end nc_refresh_subscription()

