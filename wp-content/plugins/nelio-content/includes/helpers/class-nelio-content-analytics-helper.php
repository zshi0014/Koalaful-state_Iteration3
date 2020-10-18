<?php
/**
 * This file contains a class with some analytics-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/helpers
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements analytics-related helper functions.
 */
class Nelio_Content_Analytics_Helper {

	// Notice: remember to update “get_ga_data” to actually pull new metrics from GA.
	private static $pageview_metrics = [ 'total', 'twitter', 'facebook', 'linkedin', 'instagram', 'pinterest', 'tumblr' ];

	// Notice: remember to update “update_statistics” to actually pull new metrics from each social network.
	private static $engagement_metrics = [ 'total', 'twitter', 'facebook', 'pinterest' ];

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {
		add_action( 'cron_schedules', [ $this, 'add_cron_interval' ] );
		add_action( 'init', [ $this, 'maybe_enable_cron_tasks' ] );
		add_action( 'wp_update_comment_count', [ $this, 'update_comment_count' ], 10, 3 );
	}//end init()

	/**
	 * Enables or disables cron tasks on WordPress’ init based on current settings.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function maybe_enable_cron_tasks() {
		$settings = Nelio_Content_Settings::instance();
		if ( $settings->get( 'use_analytics' ) ) {
			$this->enable_analytics_cron_tasks();
		} else {
			$this->disable_analytics_cron_tasks();
		}//end if
	}//end maybe_enable_cron_tasks()

	/**
	 * Add custom cron intervals.
	 *
	 * @param array $schedules List of schedules.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function add_cron_interval( $schedules ) {

		$schedules['nc_four_hours'] = array(
			'interval' => 4 * HOUR_IN_SECONDS,
			'display'  => esc_html_x( 'Every Four Hours (Nelio Content)', 'text', 'nelio-content' ),
		);
		return $schedules;

	}//end add_cron_interval()

	/**
	 * Enable WP cron tasks for analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function enable_analytics_cron_tasks() {

		$time = time();

		add_action( 'nelio_content_analytics_today_cron_hook', [ $this, 'update_today_posts' ] );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_today_cron_hook' ) ) {
			wp_schedule_event( $time, 'nc_four_hours', 'nelio_content_analytics_today_cron_hook' );
		}//end if

		add_action( 'nelio_content_analytics_month_cron_hook', [ $this, 'update_month_posts' ] );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_month_cron_hook' ) ) {
			wp_schedule_event( $time + 3600, 'nc_four_hours', 'nelio_content_analytics_month_cron_hook' );
		}//end if

		add_action( 'nelio_content_analytics_other_cron_hook', [ $this, 'update_other_posts' ] );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_other_cron_hook' ) ) {
			wp_schedule_event( $time + 7200, 'nc_four_hours', 'nelio_content_analytics_other_cron_hook' );
		}//end if

		add_action( 'nelio_content_analytics_top_cron_hook', [ $this, 'update_top_posts' ] );
		if ( ! wp_next_scheduled( 'nelio_content_analytics_top_cron_hook' ) ) {
			wp_schedule_event( $time + 10800, 'nc_four_hours', 'nelio_content_analytics_top_cron_hook' );
		}//end if

	}//end enable_analytics_cron_tasks()

	/**
	 * Disable WP cron tasks for analytics.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function disable_analytics_cron_tasks() {

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_today_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_today_cron_hook' );

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_month_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_month_cron_hook' );

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_other_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_other_cron_hook' );

		$timestamp = wp_next_scheduled( 'nelio_content_analytics_top_cron_hook' );
		wp_unschedule_event( $timestamp, 'nelio_content_analytics_top_cron_hook' );

	}//end disable_analytics_cron_tasks()

	/**
	 * Update analytics of all posts published today.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_today_posts() {

		// Let's get the posts published today.
		$today = getdate();
		$args  = array(
			'posts_per_page' => -1,
			'date_query'     => array(
				array(
					'year'  => $today['year'],
					'month' => $today['mon'],
					'day'   => $today['mday'],
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_today_posts()

	/**
	 * Update analytics of 10 random posts published this month.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_month_posts() {

		// Let's get the posts to update.
		$args = array(
			'posts_per_page' => 10,
			'date_query'     => array(
				array(
					'column' => 'post_date_gmt',
					'after'  => '1 month ago',
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_month_posts()

	/**
	 * Update analytics of 10 random posts published before this month.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_other_posts() {

		// Let's get 10 posts before this month.
		$args = array(
			'posts_per_page' => 10,
			'date_query'     => array(
				array(
					'column' => 'post_date_gmt',
					'before' => '1 month ago',
				),
			),
		);

		$posts_to_update = $this->get_posts_using_last_update( $args );
		foreach ( $posts_to_update as $post ) {

			$post_id = $post['id'];
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_other_posts()

	/**
	 * Update analytics of top posts.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_top_posts() {

		// Let's get 5 top posts (according to engagement).
		$args = array(
			'posts_per_page' => 5,
			'meta_key'       => '_nc_engagement_total', // slow query ok
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
		);

		$engagement = wp_list_pluck( $this->get_posts( $args ), 'id' );

		// Let's get 5 top posts (according to pageviews).
		$args = array(
			'posts_per_page' => 5,
			'post__not_in'   => $engagement,
			'meta_key'       => '_nc_pageviews_total', // slow query ok
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
		);

		$pageviews = wp_list_pluck( $this->get_posts( $args ), 'id' );

		// Update posts.
		$posts = array_merge( $engagement, $pageviews );
		foreach ( $posts as $post_id ) {
			$this->update_statistics( $post_id );
		}//end foreach

	}//end update_top_posts()

	/**
	 * Helper function that, given a certain post ID, updates its analytics.
	 *
	 * @param  integer $post_id the post whose analytics has to be updated.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_statistics( $post_id ) {

		// Safe guards.
		if ( empty( $post_id ) ) {
			return;
		}//end if

		if ( ! $this->needs_to_be_updated( $post_id ) ) {
			return;
		}//end if

		// Compute engagement.
		$url        = get_permalink( $post_id );
		$engagement = array(
			'twitter'   => $this->get_twitter_count( $post_id ),
			'facebook'  => $this->get_facebook_count( $url ),
			'pinterest' => $this->get_pinterest_count( $url ),
			'comments'  => intval( wp_count_comments( $post_id )->approved ),
		);
		$this->save_engagement( $post_id, $engagement );

		// Compute pageviews.
		$settings = Nelio_Content_Settings::instance();
		$ga_view  = $settings->get( 'google_analytics_view' );
		if ( ! empty( $ga_view ) ) {
			$date      = get_the_date( 'Y-m-d', $post_id );
			$pageviews = $this->get_ga_data( $ga_view, $post_id, $url, $date );
			$this->save_pageviews( $ga_view, $post_id, $pageviews );
		}//end if

		// Refresh last update.
		update_post_meta( $post_id, '_nc_last_update', time() );

	}//end update_statistics()

	/**
	 * Helper function that, given a certain post ID, retrieves its analytics.
	 *
	 * @param  integer $post_id the post whose analytics has to be recovered.
	 *
	 * @return array the statistics of the given post.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_post_stats( $post_id ) {

		// LAST UPDATE.
		$last_update = get_post_meta( $post_id, '_nc_last_update', true );
		if ( empty( $last_update ) ) {
			$last_update = 0;
		}//end if

		// ENGAGEMENT.
		$engagement = [];
		foreach ( self::$engagement_metrics as $metric ) {
			$value = get_post_meta( $post_id, '_nc_engagement_' . $metric, true );
			$value = '' === $value ? -1 : absint( $value );

			$engagement[ $metric ] = $this->human_number( $value );
		}//end foreach
		$value                  = intval( wp_count_comments( $post_id )->approved );
		$engagement['comments'] = $this->human_number( $value );

		// PAGEVIEWS.
		$settings   = Nelio_Content_Settings::instance();
		$ga_view_id = $settings->get( 'google_analytics_view' );

		$pageviews = get_post_meta( $post_id, '_nc_pageviews_data', true );
		if ( empty( $pageviews ) || empty( $pageviews[ $ga_view_id ] ) ) {
			$value = get_post_meta( $post_id, '_nc_pageviews_total_' . $ga_view_id, true );
			$value = '' === $value ? -1 : $value;

			$pageviews = array(
				'total' => intval( $value ),
			);
		} else {
			$pageviews = $pageviews[ $ga_view_id ];
		}//end if

		$aux = [];
		foreach ( self::$pageview_metrics as $metric ) {
			if ( ! isset( $pageviews[ $metric ] ) ) {
				continue;
			}//end if

			$value = $pageviews[ $metric ];
			$value = '' === $value ? -1 : absint( $value );

			$aux[ $metric ] = $this->human_number( $value );
		}//end foreach
		$pageviews = $aux;

		return array(
			'engagement' => $engagement,
			'pageviews'  => $pageviews,
		);

	}//end get_post_stats()

	/**
	 * Get a set of posts from WordPress, embedded in an object for pagination.
	 *
	 * @param array $params Parameters to filter the search.
	 *
	 * @return object an object with two keys: `items` with the list of posts and `pagination` with info for pagination.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function get_paginated_posts( $params ) {

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
		);

		$args = wp_parse_args( $params, $defaults );

		if ( isset( $args['meta_key'] ) &&
			'_nc_pageviews_total' === $args['meta_key'] ) {
			$args['meta_key'] = '_nc_pageviews_total_' . $settings->get( 'google_analytics_view' ); // slow query ok
		}//end if

		$query = new WP_Query( $args );
		$posts = [];

		$post_helper = Nelio_Content_Post_Helper::instance();
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json( get_the_ID() );
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if
		}//end while

		wp_reset_postdata();

		// Build result object, ready for pagination.
		$page = isset( $params['paged'] ) ? $params['paged'] : 1;
		return array(
			'items'      => $posts,
			'pagination' => array(
				'more'  => $page < $query->max_num_pages,
				'pages' => $query->max_num_pages,
			),
		);

	}//end get_paginated_posts()

	/**
	 * Get a set of posts from WordPress.
	 *
	 * @param array $params Parameters to filter the search.
	 *
	 * @return array The retrieved posts.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_posts( $params ) {
		$result = $this->get_paginated_posts( $params );
		return $result['items'];
	}//end get_posts()

	/**
	 * Get a set of posts from WordPress. First, return those posts whose
	 * statistics weren't updated before. Then, complete the returned list of
	 * posts with those posts whose update time is older.
	 *
	 * @param  array $params Parameters to filter the search.
	 *
	 * @return array         The retrieved posts.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function get_posts_using_last_update( $params ) {

		$params = wp_parse_args( $params, array( 'posts_per_page' => 10 ) );

		$post_helper = Nelio_Content_Post_Helper::instance();

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'meta_query'  => array( // slow query ok
				array(
					'key'     => '_nc_last_update',
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$args = wp_parse_args( $params, $defaults );

		$query = new WP_Query( $args );

		$posts = [];
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json( get_the_ID() );
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if
		}//end while

		wp_reset_postdata();

		$posts_to_find = intval( $params['posts_per_page'] );
		if ( count( $posts ) === $posts_to_find ) {
			return $posts;
		}//end if

		if ( -1 !== $posts_to_find ) {
			$params['posts_per_page'] = $posts_to_find - count( $posts );
		}//end if

		$defaults = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'meta_key'    => '_nc_last_update', // slow query ok
			'orderby'     => 'meta_value_num',
			'order'       => 'ASC',
		);

		$args = wp_parse_args( $params, $defaults );

		$query = new WP_Query( $args );
		while ( $query->have_posts() ) {

			$query->the_post();
			$aux = $post_helper->post_to_json( get_the_ID() );
			if ( ! empty( $aux ) ) {
				array_push( $posts, $aux );
			}//end if
		}//end while

		wp_reset_postdata();

		return $posts;
	}//end get_posts_using_last_update()

	/**
	 * Helper function that updates the total engagement value when a new comment
	 * occurs in WordPress or an old comment changes its status.
	 *
	 * @param  integer $post_id the post whose engagement needs to be updated.
	 * @param  integer $new the new comment count.
	 * @param  integer $old the old comment count.
	 *
	 * @since  1.2.0
	 * @access public
	 */
	public function update_comment_count( $post_id, $new, $old ) {

		$total = 0;
		foreach ( self::$engagement_metrics as $metric ) {
			if ( 'total' === $metric ) {
				continue;
			}//end if
			$total += intval( get_post_meta( $post_id, "_nc_engagement_{$metric}", true ) );
		}//end foreach

		update_post_meta( $post_id, '_nc_engagement_total', $total + $new );

	}//end update_comment_count()

	/**
	 * Helper function that obtains the access token and refresh token in Google
	 * Analytics.
	 *
	 * @param string $code the provisional code to exchange in Google Analytics.
	 *
	 * @return string The token to access Google Analytics.
	 */
	public function refresh_access_token( $code ) {

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
					'code' => $code,
				)
			),
		);

		$url      = nc_get_api_url( '/ga/token', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			update_option( 'nc_ga_token_error', true );
			return false;
		}//end if

		$json = json_decode( $response['body'] );
		if ( ! isset( $json->token ) ||
			! isset( $json->refresh ) ||
			! isset( $json->expiration ) ) {
			update_option( 'nc_ga_token_error', true );
			return false;
		}//end if

		$expiration = $json->expiration - ( 10 * MINUTE_IN_SECONDS );
		set_transient( 'nc_ga_token', $json->token, $expiration );
		update_option( 'nc_ga_refresh_token', $json->refresh );
		delete_option( 'nc_ga_token_error' );

		return $json->token;

	}//end refresh_access_token()

	/**
	 * Helper function that obtains the views from Google Analytics using the
	 * token in WordPress option.
	 *
	 * @return array The views from Google Analytics.
	 */
	public function get_views() {
		$settings = Nelio_Content_Settings::instance();

		$ga_token = get_transient( 'nc_ga_token' );
		if ( false === $ga_token ) {
			// Get a new valid Google Analytics token.
			$ga_token = $this->refresh_ga_token();
		}//end if

		$data = array(
			'method'    => 'GET',
			'timeout'   => 30,
			'sslverify' => ! nc_does_api_use_proxy(),
			'headers'   => array(
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
				'authorization' => 'Bearer ' . $ga_token,
			),
		);

		$url      = 'https://www.googleapis.com/analytics/v3/management/accountSummaries?max-results=1000';
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			return false;
		}//end if

		$response = json_decode( $response['body'] );

		if ( isset( $response->error ) ) {
			return false;
		}//end if

		return $response;
	}//end get_views()

	/**
	 * Checks whether the statistics of the given post needs to be updated or not.
	 *
	 * @param  integer $post_id the post.
	 * @return boolean          whether the statistics of the post need to be
	 *                          updated or not.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function needs_to_be_updated( $post_id ) {

		$last_update = get_post_meta( $post_id, '_nc_last_update', true );

		// If the custom field has no value, the post needs to be updated.
		if ( empty( $last_update ) ) {
			return true;
		}//end if

		$publication_date = get_post_time( 'U', true, $post_id );
		$now              = time();

		// Less than 1 hour since last update means no update required.
		if ( $now - $last_update < 60 * 60 ) {
			return false;
		}//end if

		// Update posts published less than 1 week ago.
		if ( $now - $publication_date < 7 * 24 * 60 * 60 ) {
			return true;
		}//end if

		// Update other posts if they were last updated more than one day ago.
		if ( $now - $last_update > 24 * 60 * 60 ) {
			return true;
		} else {
			return false;
		}//end if

	}//end needs_to_be_updated()

	/**
	 * Helper function that saves post engagement statistic values.
	 *
	 * @param  integer $post_id the post whose engagement has to be saved.
	 * @param  array   $engagement the engagement values to be saved.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function save_engagement( $post_id, $engagement ) {

		// Properly compute total value.
		$aux = 0;
		foreach ( $engagement as $key => $value ) {
			if ( -1 !== $value ) {
				$aux += $value;
			} else {
				$aux += intval( get_post_meta( $post_id, '_nc_engagement_' . $key, true ) );
			}//end if
		}//end foreach

		$engagement['total'] = $aux;

		// Save.
		foreach ( $engagement as $key => $value ) {
			if ( 'comments' !== $key && -1 !== $value ) {
				update_post_meta( $post_id, '_nc_engagement_' . $key, $value );
			}//end if
		}//end foreach
	}//end save_engagement()

	/**
	 * Helper function that saves post pageviews.
	 *
	 * @param  integer $ga_view_id the id of the Google Analytics view.
	 * @param  integer $post_id    the post whose pageviews have to be saved.
	 * @param  array   $pageviews  the pageview values to be saved.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function save_pageviews( $ga_view_id, $post_id, $pageviews ) {
		if ( $ga_view_id ) {

			if ( -1 === $pageviews['total'] ) {
				return;
			}//end if

			update_post_meta( $post_id, '_nc_pageviews_total_' . $ga_view_id, $pageviews['total'] );

			// Process and update traffic percentages from social networks.
			$value = get_post_meta( $post_id, '_nc_pageviews_data', true );
			if ( ! is_array( $value ) ) {
				$value = [];
			}//end if

			// Filter empty data.
			$pageviews = array_filter( $pageviews, [ $this, 'has_proper_value' ] );

			if ( isset( $value[ $ga_view_id ] ) ) {
				$current_values = $value[ $ga_view_id ];
				$pageviews      = wp_parse_args( $pageviews, $current_values );
			}//end if

			$value[ $ga_view_id ] = $pageviews;

			update_post_meta( $post_id, '_nc_pageviews_data', $value );

		}//end if
	}//end save_pageviews()

	/**
	 * Whether the value is a valid one.
	 *
	 * @param integer $val The value.
	 * @return boolean       True if the value is valid.
	 */
	private function has_proper_value( $val ) {
		return -1 !== $val;
	}//end has_proper_value()

	/**
	 * Helper function that, given a certain number, returns an easier-to-read
	 * string by humans.
	 *
	 * @param integer $number    the number to humanize.
	 * @param integer $precision the number of decimal cyphers we want.
	 *
	 * @return string the simplified representation of the input number.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function human_number( $number, $precision = 1 ) {

		if ( -1 === $number ) {
			return '–';
		}//end if

		$units = [ '', 'k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y' ];
		$step  = 1000;
		$i     = 0;
		while ( ( $number / $step ) > 0.9 ) {
			$number = $number / $step;
			$i++;
		}//end while

		if ( floor( $number ) >= 100 ) {
			$number = number_format_i18n( $number, 0 );
		} elseif ( floor( $number ) * 10 !== floor( $number * 10 ) ) {
			$number = number_format_i18n( $number, $precision );
		} else {
			$number = number_format_i18n( $number, 0 );
		}//end if

		return $number . $units[ $i ];
	}//end human_number()

	/**
	 * Helper function that, given a certain number and a total value, returns the
	 * percentage of that number with respect to the total in an easier-to-read
	 * string by humans.
	 *
	 * @param integer $number    the number.
	 * @param integer $total     the total.
	 *
	 * @return string the simplified representation of the percentage of the
	 *                input number with respect to the total.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function human_percentage( $number, $total ) {

		if ( 0 === $total ) {
			return '0';
		}//end if

		if ( -1 === $number || -1 === $total ) {
			return '–';
		}//end if

		$percentage = ( $number * 100 ) / $total;

		if ( 0 === $percentage ) {
			return '0';
		}//end if

		if ( $percentage < 10 ) {
			return number_format_i18n( $percentage, 1 );
		}//end if

		return number_format_i18n( $percentage, 0 );

	}//end human_percentage()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in Twitter.
	 *
	 * @param int $post_id the ID of the post we’re interested in.
	 *
	 * @return integer the analytics of the given URL in Twitter.
	 *
	 * @since  2.0.0
	 * @access private
	 */
	private function get_twitter_count( $post_id ) {

		$previous_count = absint( get_post_meta( $post_id, '_nc_engagement_twitter', true ) );
		if ( ! nc_is_subscribed() ) {
			return $previous_count;
		}//end if

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

		$path = sprintf(
			'/site/%s/post/%d/analytics',
			nc_get_site_id(),
			$post_id
		);

		$response = wp_remote_request( nc_get_api_url( $path, 'wp' ), $data );
		if ( is_wp_error( $response ) ) {
			return $previous_count;
		}//end if

		$body = json_decode( $response['body'], true );
		if ( ! isset( $body['twitter'] ) ) {
			return $previous_count;
		}//end if

		return absint( $body['twitter'] );

	}//end get_twitter_count()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in Facebook.
	 *
	 * @param string $url the URL whose analytics has to be found.
	 *
	 * @return integer the analytics of the given URL in Facebook.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_facebook_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_get(
			'https://graph.facebook.com/?id=' . rawurlencode( $url ),
			array(
				'sslverify' => false, // Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of Facebook total activities for requested URL.
		if ( ! isset( $json['share']['share_count'] ) ) {
			return 0;
		}//end if

		$value = absint( $json['share']['share_count'] );
		return $value;

	}//end get_facebook_count()

	/**
	 * Helper function that, given a certain URL, finds its social share
	 * count in Pinterest.
	 *
	 * @param  string $url the URL whose analytics has to be found.
	 *
	 * @return integer the analytics of the given URL in Pinterest.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_pinterest_count( $url ) {

		// Retrieves data with HTTP GET method for current URL.
		$json_string = wp_remote_get(
			'https://api.pinterest.com/v1/urls/count.json?url=' . rawurlencode( $url ),
			array(
				'sslverify' => false, // Disable checking SSL certificates.
			)
		);

		if ( is_wp_error( $json_string ) ) {
			return -1;
		}//end if

		// Retrives only body from previous HTTP GET request.
		$json_string = wp_remote_retrieve_body( $json_string );
		$json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $json_string );

		// Convert body data to JSON format.
		$json = json_decode( $json_string, true );

		// Get count of Pinterest Shares for requested URL.
		if ( ! isset( $json['count'] ) ) {
			return -1;
		}//end if

		$value = intval( $json['count'] );
		return $value;

	}//end get_pinterest_count()

	/**
	 * Helper function that, given a certain URL, finds its pageviews
	 * count and referred trafic from social media in Google Analytics.
	 *
	 * @param  integer $ga_view_id the identifier of Google Analytics view.
	 * @param  integer $post_id    the identifier of the post.
	 * @param  string  $url        the URL whose analytics has to be found.
	 * @param  string  $start_date the starting date from which look at data.
	 *
	 * @return array the analytics of the given URL in Google Analytics.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function get_ga_data( $ga_view_id, $post_id, $url, $start_date ) {

		$path = preg_replace( '/^https?:\/\/[^\/]+/', '', $url );
		if ( ! $path ) {
			$path = '/';
		}//end if

		/**
		 * Modifies the list of paths in which we can find a given post.
		 *
		 * @param array $paths   an array with one or more paths in which the post can be found.
		 * @param int   $post_id the ID of the post.
		 *
		 * @since 1.3.0
		 */
		$paths = apply_filters( 'nelio_content_analytics_post_paths', [ $path ], $post_id );

		$ga_token = get_transient( 'nc_ga_token' );
		if ( false === $ga_token ) {
			// Get a new valid Google Analytics token.
			$ga_token = $this->refresh_ga_token();
		}//end if

		$args = array(
			'method'    => 'POST',
			'sslverify' => false, // Disable checking SSL certificates.
			'headers'   => array(
				// Setup content type to JSON.
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $ga_token,
			),
			// Setup POST options to Google API.
			'body'      => wp_json_encode(
				array(
					'reportRequests' => array(
						array(
							'viewId'                 => $ga_view_id,
							'dimensions'             => array(
								array( 'name' => 'ga:pagePath' ),
							),
							'metrics'                => array(
								array( 'expression' => 'ga:pageviews' ),
							),
							'dimensionFilterClauses' => array(
								array(
									'filters' => array(
										array(
											'operator'    => 'IN_LIST',
											'dimensionName' => 'ga:pagePath',
											'expressions' => $paths,
										),
									),
								),
							),
							'dateRanges'             => array(
								array(
									'startDate' => $start_date,
									'endDate'   => 'today',
								),
							),
						),
						array(
							'viewId'                 => $ga_view_id,
							'dimensions'             => array(
								array( 'name' => 'ga:pagePath' ),
								array( 'name' => 'ga:socialNetwork' ),
							),
							'metrics'                => array(
								array( 'expression' => 'ga:pageviews' ),
							),
							'dimensionFilterClauses' => array(
								array(
									'operator' => 'AND',
									'filters'  => array(
										array(
											'operator'    => 'IN_LIST',
											'dimensionName' => 'ga:pagePath',
											'expressions' => $paths,
										),
										array(
											'operator'    => 'IN_LIST',
											'dimensionName' => 'ga:socialNetwork',
											'expressions' => array(
												'Twitter',
												'Facebook',
												'LinkedIn',
												'Instagram',
												'Pinterest',
												'Tumblr',
											),
										),
									),
								),
							),
							'dateRanges'             => array(
								array(
									'startDate' => $start_date,
									'endDate'   => 'today',
								),
							),
						),
					),
				)
			),
		);

		$result = get_post_meta( $post_id, '_nc_pageviews_data', true );
		if ( empty( $result ) || ! isset( $result[ $ga_view_id ] ) ) {
			$result = [];
		} else {
			$result = $result[ $ga_view_id ];
		}//end if

		$defaults = array_combine( self::$pageview_metrics, array_fill( 0, count( self::$pageview_metrics ), -1 ) );
		$result   = wp_parse_args( $result, $defaults );

		// Retrieves JSON with HTTP POST method for current URL.
		$json_string = wp_remote_post( 'https://analyticsreporting.googleapis.com/v4/reports:batchGet', $args );
		if ( is_wp_error( $json_string ) ) {
			return $result;
		}//end if

		$json = json_decode( $json_string['body'] );
		foreach ( $result as &$value ) {
			$value = -1 === $value ? 0 : $value;
		}//end foreach

		// Get pageviews.
		if ( ! isset( $json->reports[0]->data->totals[0]->values[0] ) ) {
			return $result;
		}//end if

		$result['total'] = intval( $json->reports[0]->data->totals[0]->values[0] );

		// Pageviews referred by social networks.
		if ( ! isset( $json->reports[1]->data->rows ) ) {
			return $result;
		}//end if

		$rows = $json->reports[1]->data->rows;
		foreach ( $rows as $row ) {

			if ( ! isset( $row->metrics[0]->values[0] ) ) {
				continue;
			}//end if

			if ( ! isset( $row->dimensions[1] ) ) {
				continue;
			}//end if

			$value = intval( $row->metrics[0]->values[0] );
			switch ( $row->dimensions[1] ) {
				case 'Twitter':
					$result['twitter'] = $value;
					break;

				case 'Facebook':
					$result['facebook'] = $value;
					break;

				case 'LinkedIn':
					$result['linkedin'] = $value;
					break;

				case 'Instagram':
					$result['instagram'] = $value;
					break;

				case 'Pinterest':
					$result['pinterest'] = $value;
					break;

				case 'Tumblr':
					$result['tumblr'] = $value;
					break;

				default:
					break;
			}//end switch
		}//end foreach

		return $result;

	}//end get_ga_data()

	/**
	 * Helper function that gets a valid token to access Google Analytcs.
	 * See https://developers.google.com/identity/protocols/OAuth2WebServer#refresh
	 *
	 * @return string the token to access Google Analytics.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function refresh_ga_token() {

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
					'code' => get_option( 'nc_ga_refresh_token', '' ),
				)
			),
		);

		$url      = nc_get_api_url( '/ga/token/refresh', 'wp' );
		$response = wp_remote_request( $url, $data );

		if ( is_wp_error( $response ) ) {
			update_option( 'nc_ga_token_error', true );
			return;
		}//end if

		$json = json_decode( $response['body'] );
		if ( ! isset( $json->token ) ) {
			update_option( 'nc_ga_token_error', true );
			return;
		}//end if

		$expiration = $json->expiration - ( 10 * MINUTE_IN_SECONDS );
		set_transient( 'nc_ga_token', $json->token, $expiration );
		delete_option( 'nc_ga_token_error' );

		return $json->token;

	}//end refresh_ga_token()

}//end class
