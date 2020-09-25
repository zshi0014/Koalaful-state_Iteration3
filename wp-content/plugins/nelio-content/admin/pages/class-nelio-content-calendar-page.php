<?php
/**
 * This file contains the class for rendering the calendar page.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/pages
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Class that renders the calendar page.
 */
class Nelio_Content_Calendar_Page extends Nelio_Content_Abstract_Page {

	public function __construct() {

		parent::__construct(
			'nelio-content',
			'nelio-content',
			_x( 'Calendar', 'text', 'nelio-content' ),
			nc_can_current_user_use_plugin()
		);

	}//end __construct()

	// @Overrides
	protected function add_page_specific_hooks() {

		remove_all_filters( 'admin_notices' );

		add_filter( 'admin_footer_text', '__return_empty_string', 99 );
		add_filter( 'update_footer', '__return_empty_string', 99 );

	}//end add_page_specific_hooks()

	// @Implements
	public function enqueue_assets() {

		$script   = 'NelioContent.initPage( "nelio-content-page", %s );';
		$settings = array(
			'firstDayOfWeek'                 => absint( get_option( 'start_of_week' ) ),
			'icsLinks'                       => $this->get_ics_links(),
			'localFocusDay'                  => $this->get_focus_day(),
			'numberOfNonCollapsableMessages' => $this->get_number_of_non_collapsable_messages(),
		);

		wp_enqueue_style(
			'nelio-content-calendar-page',
			nelio_content()->plugin_url . '/assets/dist/css/calendar-page.css',
			[ 'nelio-content-components' ],
			nc_get_script_version( 'calendar-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-calendar-page', 'calendar-page', true );

		wp_add_inline_script(
			'nelio-content-calendar-page',
			sprintf(
				$script,
				wp_json_encode( $settings ) // phpcs:ignore
			)
		);

	}//end enqueue_assets()

	private function get_ics_links() {

		$ics_secret_key = get_option( 'nc_ics_key', false );
		if ( ! $ics_secret_key ) {
			return false;
		}//end if

		$all_link = add_query_arg(
			array(
				'action' => 'nelio_content_calendar_ics_subscription',
				'key'    => md5( 'all' . $ics_secret_key ),
			),
			admin_url( 'admin-ajax.php' )
		);

		$user_link = add_query_arg(
			array(
				'action' => 'nelio_content_calendar_ics_subscription',
				'user'   => wp_get_current_user()->user_login,
				'key'    => md5( wp_get_current_user()->user_login . $ics_secret_key ),
			),
			admin_url( 'admin-ajax.php' )
		);

		return array(
			'all'  => $all_link,
			'user' => $user_link,
		);

	}//end get_ics_links()

	private function get_focus_day() {

		$date = '';
		if ( isset( $_GET['date'] ) ) { // phpcs:ignore
			$date = sanitize_text_field( $_GET['date'] ); // phpcs:ignore
		}//end if

		$year  = '[0-9]{4}';
		$month = '(0[0-9])|(1[012])';
		$day   = '([0-2][0-9])|(3[01])';

		if ( preg_match( "/^($year)-($month)$/", $date ) ) {
			$date .= '-01';
		}//end if

		if ( ! preg_match( "/^($year)-($month)(-($day))?$/", $date ) ) {
			$date = date_i18n( 'Y-m-d' );
		}//end if

		return $date;

	}//end get_focus_day()

	private function get_number_of_non_collapsable_messages() {
		/**
		 * Filters the number of messages that can’t never be collapsed in any given day in the Editorial Calendar.
		 *
		 * @param {number} number the number of non collapsable messages.
		 */
		return apply_filters( 'nelio_content_number_of_non_collapsable_messags_in_calendar', 6 );
	}//end get_number_of_non_collapsable_messages()

}//end class
