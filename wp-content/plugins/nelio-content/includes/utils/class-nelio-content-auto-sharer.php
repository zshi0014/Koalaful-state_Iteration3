<?php
/**
 * This file shares content automatically.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements all the functions used for sharing content automatically on social media.
 */
class Nelio_Content_Auto_Sharer {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.3.0
	 * @access protected
	 * @var    int
	 */
	const MAX_SHARES_PER_DAY = 30;

	/**
	 * The single instance of this class.
	 *
	 * @since  1.3.0
	 * @access protected
	 * @var    Nelio_Content_Auto_Sharer
	 */
	protected static $_instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Auto_Sharer the single instance of this class.
	 *
	 * @since  1.3.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	public function init() {
		add_action( 'init', [ $this, 'enable_cron_tasks' ] );
	}//end init()

	/**
	 * Adds WordPress cron tasks to generate social messages automatically.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function enable_cron_tasks() {

		$time     = sprintf(
			'%02d:%02d:00',
			wp_rand( 0, 4 ),
			wp_rand( 0, 59 )
		);
		$today    = date( 'Y-m-d', time() ) . 'T' . $time;
		$tomorrow = strtotime( $today . ' +1 day' );

		// Regular CRON task
		$task_name = 'nelio_content_social_automations_schedule_week';
		add_action( $task_name, [ $this, 'schedule_week' ] );
		if ( ! wp_next_scheduled( $task_name ) ) {
			wp_schedule_event( $tomorrow, 'daily', $task_name );
		}//end if

		// Task for resetting social messages
		add_action( 'nelio_content_social_automations_reset_social_messages', [ $this, 'schedule_week' ] );

	}//end enable_cron_tasks()


	/**
	 * This function schedules a new cron task to re-generate all automatic messages.
	 *
	 * @since  1.5.15
	 * @access public
	 */
	public function reset() {

		delete_option( 'nc_reshare_last_day' );
		wp_schedule_single_event( time(), 'nelio_content_social_automations_reset_social_messages', [ time() ] );

	}//end reset()

	/**
	 * This function generates social messages for the next two weeks.
	 *
	 * @since  1.3.0
	 * @access public
	 */
	public function schedule_week() {

		// Get the day in which the current week ends.
		$now   = time();
		$today = date( 'Y-m-d', $now );

		$end_of_week = get_option( 'start_of_week', 0 ) - 1;
		if ( 0 > $end_of_week ) {
			$end_of_week = 6;
		}//end if

		$days_to_end_of_week = $end_of_week - date( 'w', $now );
		if ( 0 > $days_to_end_of_week ) {
			$days_to_end_of_week = 7 + $days_to_end_of_week;
		}//end if

		$end_of_week = $today;
		if ( $days_to_end_of_week ) {
			$end_of_week = date( 'Y-m-d', strtotime( $today . ' +' . $days_to_end_of_week . ' days' ) );
		}//end if

		// Get the last day we have to schedule.
		$last_day_to_schedule = date( 'Y-m-d', strtotime( $end_of_week . ' +7 days' ) );

		// Schedule all required days.
		$last_day_scheduled = get_option( 'nc_reshare_last_day', $today );

		if ( strcmp( $last_day_scheduled, $today ) < 0 ) {
			$last_day_scheduled = $today;
		}//end if

		$guard = 0;
		while ( strcmp( $last_day_scheduled, $last_day_to_schedule ) < 0 ) {

			$posts = $this->get_posts_for_resharing( 7 );

			if ( empty( $posts ) ) {
				return;
			}//end if

			$aux = count( $posts );
			while ( $aux < 7 ) {
				shuffle( $posts );
				array_push( $posts, $posts[0] );
			}//end while

			$posts_per_day = $this->array_split( $posts, 7 );
			foreach ( $posts_per_day as $posts ) {

				$last_day_scheduled = date( 'Y-m-d', strtotime( $last_day_scheduled . ' +1 day' ) );
				$this->schedule_day( $last_day_scheduled, $posts );

				if ( $last_day_scheduled === $last_day_to_schedule ) {
					break;
				}//end if
			}//end foreach

			++$guard;
			if ( 3 < $guard ) {
				break;
			}//end if
		}//end while

		update_option( 'nc_reshare_last_day', $last_day_scheduled );

	}//end schedule_week()

	/**
	 * This function requests the cloud to generate all the messages for a given
	 * day, using the given list of posts.
	 *
	 * @param string day   day to schedule.
	 * @param array  posts list of posts used to "fill" the day.
	 *
	 * @since  1.3.0
	 * @access public
	 */
	public function schedule_day( $day, $posts ) {

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
					'day'   => $day,
					'posts' => $posts,
				)
			),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/social/auto', 'wp' ),
			nc_get_site_id()
		);
		wp_remote_request( $url, $data );

	}//end schedule_day()

	/**
	 * This function retrieves the list of posts to share on a week.
	 *
	 * @param int $num_of_days Optional. Default: 1.
	 *
	 * @return array the list of posts to share on a week.
	 *
	 * @since  1.3.0
	 * @access private
	 */
	private function get_posts_for_resharing( $num_of_days ) {

		$post_helper = Nelio_Content_Post_Helper::instance();

		$result       = [];
		$list_of_args = $this->get_list_of_args();

		$total_posts     = $num_of_days * self::MAX_SHARES_PER_DAY;
		$posts_per_block = ceil( $total_posts / count( $list_of_args ) );

		foreach ( $list_of_args as $index => $args ) {

			$args['post__not_in'] = wp_list_pluck( $result, 'id' );

			if ( $index < count( $list_of_args ) - 1 ) {
				$args['posts_per_page'] = $posts_per_block;
			} else {
				$args['posts_per_page'] = $total_posts - count( $result );
			}//end if

			$query = new WP_Query( $args );

			while ( $query->have_posts() ) {
				$query->the_post();
				$aux = $post_helper->post_to_aws_json( get_the_ID() );
				unset( $aux['content'] );
				array_push( $result, $aux );
			}//end while

			wp_reset_postdata();

		}//end foreach

		shuffle( $result );
		return $result;

	}//end get_posts_for_resharing()

	/**
	 * This function prepares the list of WPDB args to select which posts will be auto shared.
	 *
	 * @return array the list of WPDB args to select which posts will be auto shared.
	 *
	 * @since  1.3.0
	 * @access private
	 */
	private function get_list_of_args() {

		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		if ( 'include-in-auto-share' === $settings->get( 'auto_share_default_mode' ) ) {
			$auto_share_meta_query = array(
				'key'     => '_nc_exclude_from_auto_share',
				'compare' => 'NOT EXISTS',
			);
		} else {
			$auto_share_meta_query = array(
				'key'     => '_nc_include_in_auto_share',
				'compare' => 'EXISTS',
			);
		}//end if

		// 1. Get top posts by pageviews.
		$meta_name  = '_nc_pageviews_total_' . $settings->get( 'google_analytics_view' );
		$meta_value = $this->get_meta_value_threshold( $meta_name );

		$pageview_args = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'orderby'     => 'rand(' . time() . ')',
			'meta_query'  => array( // slow query ok
				'relation' => 'AND',
				array(
					'key'     => $meta_name,
					'value'   => $meta_value,
					'compare' => '>=',
				),
				$auto_share_meta_query,
			),
		);

		// 2. Get top posts by shares.
		$meta_name  = '_nc_engagement_total';
		$meta_value = $this->get_meta_value_threshold( $meta_name );

		$engagement_args = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'orderby'     => 'rand(' . time() . ')',
			'meta_query'  => array( // slow query ok
				'relation' => 'AND',
				array(
					'key'     => $meta_name,
					'value'   => $meta_value,
					'compare' => '>=',
				),
				$auto_share_meta_query,
			),
		);

		// 3. Get posts from last three months.
		$three_months_args = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'orderby'     => 'rand(' . time() . ')',
			'date_query'  => array(
				array(
					'column' => 'post_date_gmt',
					'before' => '1 month ago',
				),
				array(
					'column' => 'post_date_gmt',
					'after'  => '3 months ago',
				),
			),
			'meta_query'  => array( // slow query ok
				$auto_share_meta_query,
			),
		);

		// 4. Get posts from last year.
		$last_year_args = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'orderby'     => 'rand(' . time() . ')',
			'date_query'  => array(
				array(
					'column' => 'post_date_gmt',
					'before' => '3 month ago',
				),
				array(
					'column' => 'post_date_gmt',
					'after'  => '1 year ago',
				),
			),
			'meta_query'  => array( // slow query ok
				$auto_share_meta_query,
			),
		);

		// 5. Get posts from last two years.
		$two_year_args = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'orderby'     => 'rand(' . time() . ')',
			'date_query'  => array(
				array(
					'column' => 'post_date_gmt',
					'before' => '1 year ago',
				),
				array(
					'column' => 'post_date_gmt',
					'after'  => '2 years ago',
				),
			),
			'meta_query'  => array( // slow query ok
				$auto_share_meta_query,
			),
		);

		// 6. Get other posts.
		$default_args = array(
			'post_status' => 'publish',
			'post_type'   => $enabled_post_types,
			'orderby'     => 'rand(' . time() . ')',
			'meta_query'  => array( // slow query ok
				$auto_share_meta_query,
			),
		);

		return array(
			$pageview_args,
			$engagement_args,
			$three_months_args,
			$last_year_args,
			$two_year_args,
			$default_args,
		);

	}//end get_list_of_args()

	/**
	 * This function returns the value of a given meta.
	 *
	 * @param string $meta_name the meta whose value we want.
	 *
	 * @return int the value of the given meta.
	 *
	 * @since  1.3.0
	 * @access private
	 */
	private function get_meta_value_threshold( $meta_name ) {

		$meta_value = 1;

		// Get number of pages.
		$args          = array(
			'posts_per_page' => 1,
			'meta_key'       => $meta_name, // slow query ok
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
		);
		$query         = new WP_Query( $args );
		$num_top_posts = $query->max_num_pages;
		wp_reset_postdata();

		// Get the "threshold" post
		$last_good_post = min( $num_top_posts, 250 );
		$args['paged']  = $last_good_post;
		$query          = new WP_Query( $args );
		if ( $query->have_posts() ) {
			$query->the_post();
			$meta_value = get_post_meta( get_the_ID(), $meta_name, true );
		}//end if
		wp_reset_postdata();

		if ( 0 >= $meta_value ) {
			return 1;
		} else {
			return $meta_value;
		}//end if

	}//end get_meta_value_threshold()

	/**
	 * Splits the array into multiple parts.
	 *
	 * @param array $array the array to split.
	 * @param int   $parts the number of parts.
	 *
	 * @return array an array of "parts" elements, each of which will be a
	 *               subarray of the original array.
	 *
	 * @since 1.3.0
	 * @access private
	 */
	private function array_split( $array, $parts = 1 ) {

		if ( 1 >= $parts ) {
			return $array;
		}//end if

		$index  = 0;
		$result = array_fill( 0, $parts, [] );
		$max    = ceil( count( $array ) / $parts );
		foreach ( $array as $v ) {
			if ( count( $result[ $index ] ) >= $max ) {
				++$index;
			}//end if
			array_push( $result[ $index ], $v );
		}//end foreach

		return $result;

	}//end array_split()

}//end class
