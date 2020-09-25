<?php
/**
 * This file contains REST endpoints to work with analytics.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Analytics_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Author_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Analytics_REST_Controller the single instance of this class.
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
			'/analytics/top-posts',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_top_posts' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'author'       => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'from'         => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'to'           => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'postCategory' => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => [ $this, 'category_slug_to_id' ],
						),
						'postType'     => array(
							'required' => false,
							'type'     => 'string',
						),
						'sortBy'       => array(
							'required' => false,
							'type'     => 'string',
						),
						'page'         => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'perPage'      => array(
							'required'          => false,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/refresh-access-token',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'refresh_access_token' ],
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'code' => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => nc_compose( 'sanitize_text_field', 'trim' ),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/views',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_views' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/post',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_post_ids_to_update' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'page'   => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'period' => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_text_field',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/analytics/post/(?P<id>[\d]+)/update',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_post_analytics' ],
					'permission_callback' => 'nc_can_current_user_manage_plugin',
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

	}//end register_routes()

	/**
	 * Returns the list of top posts that match the search criteria.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_top_posts( $request ) {

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		// Get the author id.
		$author_id = isset( $request['author'] ) ? $request['author'] : 0;

		// Get the time interval.
		$first_day = $this->get_top_posts_arg( $request, 'from' ) ?: false;
		$last_day  = $this->get_top_posts_arg( $request, 'to' ) ?: false;
		$last_day  = $last_day ? "{$last_day} 23:59:59" : $last_day;

		// Post type and category.
		$post_types = $this->get_top_posts_arg( $request, 'postType' ) ?: false;
		$post_types = empty( $post_types ) ? $enabled_post_types : [ $post_types ];

		$category = $this->get_top_posts_arg( $request, 'postCategory' ) ?: false;

		// Sort by criterion.
		$ranking_field = $this->get_top_posts_arg( $request, 'sortBy' ) ?: false;
		$ranking_field = 'pageviews' === $ranking_field ? '_nc_pageviews_total' : '_nc_engagement_total';

		// Pagination.
		$posts_per_page = isset( $request['perPage'] ) ? $request['perPage'] : 10;
		$page           = isset( $request['page'] ) ? $request['page'] : 1;

		$args = array(
			'paged'          => $page,
			'posts_per_page' => $posts_per_page,
			'author'         => $author_id,
			'meta_key'       => $ranking_field, // slow query ok
			'orderby'        => 'meta_value_num',
			'order'          => 'desc',
			'post_type'      => $post_types,
			'date_query'     => array(
				'after'     => $first_day,
				'before'    => $last_day,
				'inclusive' => true,
			),
		);

		if ( ! empty( $category ) ) {
			$args['category__in'] = $category;
		}//end if

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$result    = $analytics->get_paginated_posts( $args );
		return new WP_REST_Response( $result, 200 );

	}//end get_top_posts()

	private function get_top_posts_arg( $request, $name ) {
		if ( ! isset( $request[ $name ] ) ) {
			return false;
		} //end if
		return $request[ $name ];
	}//end get_top_posts_arg()

	/**
	 * Refreshes Google Analytics’ access token.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function refresh_access_token( $request ) {

		$analytics = Nelio_Content_Analytics_Helper::instance();

		$code  = $request['code'];
		$token = $analytics->refresh_access_token( $code );

		if ( empty( $token ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Unable to retrieve Google Analytics token.', 'text', 'nelio-content' )
			);
		}//end if

		return new WP_REST_Response( true, 200 );

	}//end refresh_access_token()

	/**
	 * Returns a list with all available views in the currently authenticated profile.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_views() {

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$views     = $analytics->get_views();

		if ( empty( $views ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Unable to retrieve Google Analytics views.', 'text', 'nelio-content' )
			);
		}//end if

		return new WP_REST_Response( $views, 200 );

	}//end get_views()

	/**
	 * Returns a list of IDs of posts that require updating.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_post_ids_to_update( $request ) {

		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$page   = $request['page'];
		$period = $request['period'];
		$ppp    = 10;

		$args = array(
			'paged'          => $page,
			'post_status'    => 'publish',
			'posts_per_page' => $ppp,
			'orderby'        => 'date',
			'order'          => 'desc',
			'post_type'      => $enabled_post_types,
		);

		if ( 'month' === $period || 'year' === $period ) {
			$args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => '1 ' . $period . ' ago',
				),
			);
		}//end if

		$query  = new WP_Query( $args );
		$result = array(
			'ids'   => wp_list_pluck( $query->posts, 'ID' ),
			'more'  => $page < $query->max_num_pages,
			'total' => absint( $query->found_posts ),
			'ppp'   => $ppp,
		);
		wp_reset_postdata();

		return new WP_REST_Response( $result, 200 );

	}//end get_post_ids_to_update()

	/**
	 * Updates the analytics of all posts included in the current period.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function update_post_analytics( $request ) {
		$analytics = Nelio_Content_Analytics_Helper::instance();
		$analytics->update_statistics( $request['id'] );
		return new WP_REST_Response( true, 200 );
	}//end update_post_analytics()

	public function category_slug_to_id( $name ) {
		$categories = get_categories( array( 'hide_empty' => false ) );
		foreach ( $categories as $category ) {
			if ( $category->slug === $name ) {
				return $category->term_id;
			}//end if
		}//end foreach
		return false;
	}//end category_slug_to_id()

}//end class
