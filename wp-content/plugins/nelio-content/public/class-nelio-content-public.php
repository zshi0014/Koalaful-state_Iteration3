<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/public
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Public {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.3.4
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $_instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Public the single instance of this class.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Adds hooks into WordPress.
	 *
	 * @since 2.0.0
	 */
	public function init() {

		add_action( 'admin_bar_menu', [ $this, 'add_calendar_in_admin_bar' ], 99 );
		add_filter( 'the_content', [ $this, 'remove_share_blocks' ], 99 );

		$aux = Nelio_Content_External_Featured_Image_Public::instance();
		$aux->init();

		$aux = Nelio_Content_Ics_Calendar::instance();
		$aux->init();

	}//end init()

	/**
	 * Adds the Calendar entry in the admin bar (under the "Site" menu) and
	 * shortcuts to all blogs' calendars in a multisite installation (under the
	 * "My Sites" menu).
	 *
	 * @since  1.0.3
	 * @access public
	 */
	public function add_calendar_in_admin_bar() {

		if ( ! nc_can_current_user_use_plugin() ) {
			return;
		}//end if

		global $wp_admin_bar;

		$wp_admin_bar->add_node(
			array(
				'parent' => 'site-name',
				'id'     => 'nelio-content-calendar',
				'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
				'href'   => admin_url( 'admin.php?page=nelio-content' ),
			)
		);

		if ( is_multisite() ) {

			$original_blog_id = get_current_blog_id();

			// Add this option for each blog in "My Sites" where current user has
			// access to the calendar.
			foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {

				switch_to_blog( $blog->userblog_id );

				if ( ! $this->is_calendar_available() ) {
					continue;
				}//end if

				$wp_admin_bar->add_node(
					array(
						'parent' => 'blog-' . get_current_blog_id(),
						'id'     => 'nelio-content-calendar-blog-' . get_current_blog_id(),
						'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
						'href'   => admin_url( 'admin.php?page=nelio-content' ),
					)
				);

			}//end foreach

			switch_to_blog( $original_blog_id );

		}//end if

	}//end add_calendar_in_admin_bar()

	/**
	 * Strips all ncshare tags from the content.
	 *
	 * @param string $content The original content.
	 *
	 * @return string The content with all `ncshare` tagsstripped.
	 *
	 * @since  1.3.4
	 * @access public
	 */
	public function remove_share_blocks( $content ) {

		$content = preg_replace( '/<.?ncshare[^>]*>/', '', $content );
		return $content;

	}//end remove_share_blocks()

	/**
	 * Returns whether the current user can access Nelio Content's calendar.
	 *
	 * @since  1.0.5
	 * @access public
	 */
	private function is_calendar_available() {

		if ( ! nc_can_current_user_use_plugin() ) {
			return false;
		}//end if

		return true;

	}//end is_calendar_available()

}//end class
