<?php
/**
 * This file contains the class that defines REST API endpoints for
 * working with posts managed by Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/rest
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

defined( 'ABSPATH' ) || exit;

class Nelio_Content_Post_REST_Controller extends WP_REST_Controller {

	/**
	 * The single instance of this class.
	 *
	 * @since  2.0.0
	 * @access protected
	 * @var    Nelio_Content_Post_REST_Controller
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Post_REST_Controller the single instance of this class.
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
			'/calendar/posts',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_calendar_posts' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'from' => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'to'   => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/search',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'search_posts' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'per_page' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'page'     => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'status'   => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => nc_compose( 'sanitize_text_field', 'trim' ),
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [ $this, 'create_post' ],
					'permission_callback' => [ $this, 'check_if_user_can_create_post' ],
					'args'                => array(
						'authorId'  => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'title'     => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'dateValue' => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'timeValue' => array(
							'required'          => false,
							'type'              => 'time',
							'validate_callback' => 'nc_is_time',
						),
						'type'      => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_valid_post_type',
						),
						'category'  => array(
							'required'          => false,
							'type'              => 'string',
							'validate_callback' => [ $this, 'get_category_id_from_category_name' ],
							'sanitize_callback' => [ $this, 'get_category_id_from_category_name' ],
						),
						'reference' => array(
							'required'          => false,
							'type'              => 'URL',
							'validate_callback' => 'nc_is_url',
						),
						'comment'   => array(
							'required'          => false,
							'type'              => 'string',
							'sanitize_callback' => 'sanitize_textarea_field',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => [ $this, 'get_post' ],
					'permission_callback' => 'nc_can_current_user_use_plugin',
					'args'                => array(
						'id'  => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'aws' => array(
							'required'          => false,
							'type'              => 'none',
							'sanitize_callback' => '__return_true',
						),
					),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'update_post' ],
					'permission_callback' => [ $this, 'check_if_user_can_edit_post' ],
					'args'                => array(
						'id'           => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'authorId'     => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'title'        => array(
							'required'          => true,
							'type'              => 'string',
							'validate_callback' => 'nc_is_not_empty',
							'sanitize_callback' => 'sanitize_text_field',
						),
						'dateValue'    => array(
							'required'          => false,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
						'timeValue'    => array(
							'required'          => false,
							'type'              => 'time',
							'validate_callback' => 'nc_is_time',
						),
						'postCategory' => array(
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
			'/post/(?P<id>[\d]+)/reschedule',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'reschedule_post' ],
					'permission_callback' => [ $this, 'check_if_user_can_edit_post' ],
					'args'                => array(
						'id'  => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
						'day' => array(
							'required'          => true,
							'type'              => 'date',
							'validate_callback' => 'nc_is_date',
						),
					),
				),
			)
		);

		register_rest_route(
			nelio_content()->rest_namespace,
			'/post/(?P<id>[\d]+)/unschedule',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'unschedule_post' ],
					'permission_callback' => [ $this, 'check_if_user_can_edit_post' ],
					'args'                => array(
						'id' => array(
							'required'          => true,
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
			'/post/(?P<id>[\d]+)/trash',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => [ $this, 'trash_post' ],
					'permission_callback' => [ $this, 'check_if_user_can_edit_post' ],
					'args'                => array(
						'id' => array(
							'required'          => true,
							'type'              => 'number',
							'validate_callback' => 'nc_can_be_natural_number',
							'sanitize_callback' => 'absint',
						),
					),
				),
			)
		);

	}//end register_routes()

	public function check_if_user_can_create_post( $request ) {

		if ( nc_can_current_user_manage_plugin() ) {
			return true;
		}//end if

		$post_type = get_post_type_object( $request['type'] );
		return current_user_can( $post_type->cap->create_posts );

	}//end check_if_user_can_create_post()

	public function check_if_user_can_edit_post( $request ) {

		$post_id   = $request['id'];
		$post_type = get_post_type( $post_id );
		if ( empty( $post_type ) ) {
			return false;
		} //end if

		if ( ! nc_is_valid_post_type( $post_type ) ) {
			return false;
		} //end if

		if ( nc_can_current_user_manage_plugin() ) {
			return true;
		}//end if

		$post_type = get_post_type_object( $post_type );
		return current_user_can( $post_type->cap->edit_posts, $post_id );

	}//end check_if_user_can_edit_post()

	public function get_category_id_from_category_name( $name ) {
		if ( empty( $name ) ) {
			return false;
		} //end if

		$categories = get_categories( array( 'hide_empty' => false ) );
		foreach ( $categories as $category ) {
			if ( $category->slug === $name ) {
				return $category->term_id;
			} //end if
		} //end foreach

		return false;
	}//end get_category_id_from_category_name()

	/**
	 * Returns the requested post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_post( $request ) {

		$post = get_post( $request['id'] );
		if ( is_wp_error( $post ) ) {
			return $post;
		} //end if

		if ( empty( $post ) ) {
			return new WP_Error(
				'post-not-found',
				_x( 'Post not found.', 'text', 'nelio-content' )
			);
		} //end if

		$post_helper = Nelio_Content_Post_Helper::instance();

		return isset( $request['aws'] ) && $request['aws']
			? new WP_REST_Response( $post_helper->post_to_aws_json( $post ), 200 )
			: new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end get_post()

	/**
	 * Gets all posts in given date period.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response.
	 */
	public function get_calendar_posts( $request ) {

		global $post;
		$from = $request->get_param( 'from' );
		$to   = $request->get_param( 'to' );

		// Load some settings.
		$settings           = Nelio_Content_Settings::instance();
		$enabled_post_types = $settings->get( 'calendar_post_types' );

		$args = array(
			'date_query'     => array(
				'after'     => $from,
				'before'    => $to,
				'inclusive' => true,
			),
			'posts_per_page' => -1, // phpcs:ignore
			'orderby'        => 'date',
			'order'          => 'desc',
			'post_type'      => $enabled_post_types,
			'post_status'    => 'any',
		);

		$query       = new WP_Query( $args );
		$post_helper = Nelio_Content_Post_Helper::instance();

		$result = [];
		while ( $query->have_posts() ) {
			$query->the_post();

			if ( '0000-00-00 00:00:00' === $post->post_date_gmt ) {
				continue;
			}//end if

			$aux = $post_helper->post_to_json( $post );
			if ( ! empty( $aux ) ) {
				array_push( $result, $aux );
			}//end if
		}//end while

		return new WP_REST_Response( $result, 200 );

	}//end get_calendar_posts()

	/**
	 * Creates a new post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function create_post( $request ) {

		$author_id     = $request->get_param( 'authorId' );
		$title         = $request->get_param( 'title' );
		$date          = $request->get_param( 'dateValue' ) ?: false;
		$time          = $request->get_param( 'timeValue' ) ?: false;
		$post_type     = $request->get_param( 'type' );
		$post_category = $request->get_param( 'category' );

		$reference = $request->get_param( 'reference' );
		$comment   = $request->get_param( 'comment' );

		/**
		 * Modifies the title that will be used in the given post.
		 *
		 * This filter is called right before the post is saved in the database.
		 *
		 * @param string $title the new post title.
		 *
		 * @since 1.0.0
		 */
		$title = trim( apply_filters( 'nelio_content_calendar_create_post_title', $title ) );
		if ( empty( $title ) ) {
			$title = _x( 'No Title', 'text', 'nelio-content' );
		} //end if

		// Create new post.
		$post_data = array(
			'post_title'  => $title,
			'post_author' => $author_id,
			'post_type'   => $post_type,
		);
		if ( $date && $time ) {
			$post_data['post_date']     = "$date $time:00";
			$post_data['post_date_gmt'] = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( "$date $time:00" ) ) );
		} else {
			$post_data['post_date_gmt'] = '0000-00-00 00:00:00';
		}//end if

		$post_id = wp_insert_post( $post_data );
		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post could not be created.', 'text', 'nelio-content' )
			);
		}//end if
		$this->trigger_save_post_action( $post_id, true );

		if ( ! empty( $post_category ) ) {
			wp_set_post_categories( $post_id, $post_category );
		}//end if

		if ( ! empty( $reference ) ) {
			$ref = nc_create_reference( $reference );
			nc_suggest_post_reference( $post_id, $ref->ID, get_current_user_id() );
			wp_update_post( $ref->ID, array( 'post_status' => 'nc_pending' ) );
		}//end if

		if ( nc_is_subscribed() ) {
			$this->add_comment( $post_id, $comment );
		}//end if

		$post = get_post( $post_id ); // phpcs:ignore
		if ( ! $post || is_wp_error( $post ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post was successfully created, but could not be retrieved.', 'text', 'nelio-content' )
			);
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end create_post()

	/**
	 * Updates a post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function update_post( $request ) {

		$post_id   = $request['id'];
		$author_id = $request->get_param( 'authorId' );
		$title     = $request->get_param( 'title' );
		$date      = $request->get_param( 'dateValue' ) ?: false;
		$time      = $request->get_param( 'timeValue' ) ?: false;

		/**
		 * Modifies the title that will be used in the given post.
		 *
		 * This filter is called right before the post is updated and saved in the database.
		 *
		 * @param string $title   the new post title.
		 * @param int    $post_id the ID of the post we're updating.
		 *
		 * @since 1.0.0
		 */
		$title = trim( apply_filters( 'nelio_content_calendar_update_post_title', $title, $post_id ) );
		if ( empty( $title ) ) {
			$title = _x( 'No Title', 'text', 'nelio-content' );
		} //end if

		$post = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$datetime  = "$date $time:00";
		$post_data = array(
			'ID'          => $post_id,
			'post_title'  => $title,
			'post_author' => $author_id,
			'edit_date'   => true,
		);
		if ( $date && $time ) {
			$post_data['post_date']     = "$date $time:00";
			$post_data['post_date_gmt'] = get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( "$date $time:00" ) ) );
		} else {
			$post_data['post_date_gmt'] = '0000-00-00 00:00:00';
		}//end if

		$aux = wp_update_post( $post_data );
		if ( is_wp_error( $aux ) ) {
			return new WP_Error(
				'post-not-updated',
				sprintf(
					/* translators: a post ID */
					_x( 'Post %s could not be updated.', 'text', 'nelio-content' ),
					$post_id
				)
			);
		}//end if
		$this->trigger_save_post_action( $post_id, false );

		$post = get_post( $post_id ); // phpcs:ignore
		if ( ! $post || is_wp_error( $post ) ) {
			return new WP_Error(
				'internal-error',
				_x( 'Post was successfully updated, but could not be retrieved.', 'text', 'nelio-content' )
			);
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end update_post()

	/**
	 * Search posts.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 * @return WP_REST_Response The response
	 */
	public function search_posts( $request ) {

		$query = $request->get_param( 'query' );
		$args  = array(
			'per_page' => $request->get_param( 'per_page' ) ?: 10,
			'page'     => $request->get_param( 'page' ) ?: 1,
			'status'   => $request->get_param( 'status' ) ?: 'publish',
		);

		$data = $this->search_wp_posts( $query, $args );
		return new WP_REST_Response( $data, 200 );

	}//end search_posts()

	/**
	 * Reschedules the post to the given date.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function reschedule_post( $request ) {

		$post_id = $request['id'];
		$post    = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$day = $request->get_param( 'day' );
		if ( empty( $day ) ) {
			return new WP_Error(
				'missing-date',
				_x( 'New day is missing.', 'text', 'nelio-content' )
			);
		}//end if

		$time = '10:00';
		if ( '0000-00-00 00:00:00' !== $post->post_date_gmt ) {
			$time = date( 'H:i:s', strtotime( $post->post_date ) );
		}//end if

		wp_update_post(
			array(
				'ID'            => $post_id,
				'post_date'     => $day . ' ' . $time,
				'post_date_gmt' => get_gmt_from_date( date( 'Y-m-d H:i:s', strtotime( $day . ' ' . $time ) ) ),
				'edit_date'     => true,
			)
		);
		$this->trigger_save_post_action( $post_id, false );

		$post        = get_post( $post_id ); // phpcs:ignore
		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end reschedule_post()

	/**
	 * Unschedules the post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function unschedule_post( $request ) {

		$post_id = $request['id'];
		$post    = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$post->post_date_gmt = '0000-00-00 00:00:00';
		wp_update_post( $post );
		$this->trigger_save_post_action( $post_id, false );

		$post_helper = Nelio_Content_Post_Helper::instance();
		return new WP_REST_Response( $post_helper->post_to_json( $post ), 200 );

	}//end unschedule_post()

	/**
	 * Trashes the post.
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response The response
	 */
	public function trash_post( $request ) {

		$post_id = $request['id'];
		$post    = $this->maybe_get_post( $post_id );
		if ( is_wp_error( $post ) ) {
			return $post;
		}//end if

		$result = wp_trash_post( $post_id );
		if ( is_wp_error( $result ) ) {
			return $result;
		}//end if
		$this->trigger_save_post_action( $post_id, false );

		return new WP_REST_Response( true, 200 );

	}//end trash_post()

	private function trigger_save_post_action( $post_id, $creating ) {
		/** This filter is documented in includes/utils/class-nelio-content-post-saving.php */
		do_action( 'nelio_content_save_post', $post_id, $creating );
	}//end trigger_save_post_action()

	private function search_wp_posts( $query, $args ) {

		$page     = $args['page'];
		$per_page = $args['per_page'];
		$status   = $args['status'];

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		$posts = [];
		if ( 1 === $page ) {
			$posts = $this->search_wp_post_by_id_or_url( $query, $post_types );
		}//end if

		$args = array(
			'post_title__like' => $query,
			'post_type'        => $post_types,
			'order'            => 'desc',
			'orderby'          => 'date',
			'posts_per_page'   => $per_page,
			'paged'            => $page,
		);

		if ( 'nc_unscheduled' !== $status ) {
			$args['post_status'] = $status;
		} else {
			$args['post_status'] = 'any';
			$args['date_query']  = array(
				'column'    => 'post_date_gmt',
				'before'    => '0000-00-00',
				'inclusive' => true,
			);
		}//end if

		add_filter( 'posts_where', [ $this, 'add_title_filter_to_wp_query' ], 10, 2 );
		$wp_query = new WP_Query( $args );
		remove_filter( 'posts_where', [ $this, 'add_title_filter_to_wp_query' ], 10, 2 );

		$post_helper = Nelio_Content_Post_Helper::instance();
		while ( $wp_query->have_posts() ) {

			$wp_query->the_post();

			if ( get_the_ID() === $post_id ) {
				continue;
			}//end if

			global $post;
			array_push(
				$posts,
				$post_helper->post_to_json( $post )
			);

		}//end while

		wp_reset_postdata();

		$data = array(
			'results'    => $posts,
			'pagination' => array(
				'more'  => $page < $wp_query->max_num_pages,
				'pages' => $wp_query->max_num_pages,
			),
		);

		return $data;

	}//end search_wp_posts()

	private function search_wp_post_by_id_or_url( $id_or_url, $post_types ) {

		if ( ! absint( $id_or_url ) && ! filter_var( $id_or_url, FILTER_VALIDATE_URL ) ) {
			return [];
		}//end if

		$post_id = $id_or_url;
		if ( ! absint( $id_or_url ) ) {
			$post_id = url_to_postid( $id_or_url );
		}//end if

		$post = get_post( $post_id );
		if ( ! $post || is_wp_error( $post ) ) {
			return [];
		}//end if

		if ( ! in_array( $post->post_type, $post_types, true ) ) {
			return [];
		}//end if

		if ( ! in_array( $post->post_status, [ 'publish', 'draft' ], true ) ) {
			return [];
		}//end if

		$post_helper = Nelio_Content_Post_Helper::instance();
		return [ $post_helper->post_to_json( $post ) ];

	}//end search_wp_post_by_id_or_url()

	/**
	 * A filter to search posts based on their title.
	 *
	 * This function modifies the posts query so that we can search posts based
	 * on a term that should appear in their titles.
	 *
	 * @param string   $where    The where clause, as it's originally defined.
	 * @param WP_Query $wp_query The $wp_query object that contains the params
	 *                           used to build the where clause.
	 *
	 * @return string the query.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function add_title_filter_to_wp_query( $where, $wp_query ) {

		global $wpdb;

		$search_term = $wp_query->get( 'post_title__like' );
		if ( $search_term ) {
			$search_term = $wpdb->esc_like( $search_term );
			$search_term = ' \'%' . $search_term . '%\'';
			$where       = $where . ' AND ' . $wpdb->posts . '.post_title LIKE ' . $search_term;
		}//end if

		return $where;

	}//end add_title_filter_to_wp_query()

	private function maybe_get_post( $post_id ) {

		if ( empty( $post_id ) ) {
			return new WP_Error(
				'missing-post-id',
				_x( 'Post ID is missing.', 'text', 'nelio-content' )
			);
		}//end if

		$post = get_post( $post_id ); // phpcs:ignore
		if ( is_wp_error( $post ) || empty( $post ) ) {
			return new WP_Error(
				'post-not-found',
				sprintf(
					/* translators: a post ID */
					_x( 'Post %s not found.', 'text', 'nelio-content' ),
					$post_id
				)
			);
		}//end if

		return $post;

	}//end maybe_get_post()

	private function add_comment( $post_id, $comment ) {

		$comment = trim( $comment );
		if ( empty( $comment ) ) {
			return;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$data     = array(
			'method'    => 'POST',
			'timeout'   => 30,
			'sslverify' => ! $settings->get( 'uses_proxy' ),
			'headers'   => array(
				'Authorization' => 'Bearer ' . nc_generate_api_auth_token(),
				'accept'        => 'application/json',
				'content-type'  => 'application/json',
			),
			'body'      => wp_json_encode(
				array(
					'id'       => nc_uuid(),
					'authorId' => get_current_user_id(),
					'postId'   => $post_id,
					'postType' => get_post_type( $post_id ),
					'comment'  => $comment,
				)
			),
		);

		$url = sprintf(
			nc_get_api_url( '/site/%s/comment', 'wp' ),
			nc_get_site_id()
		);

		wp_remote_request( $url, $data );

	}//end add_comment()

}//end class
