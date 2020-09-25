<?php
/**
 * This file contains a class with some post-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/helpers
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements post-related helper functions.
 */
class Nelio_Content_Post_Helper {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	/**
	 * This function returns the suggested and external references of the post.
	 *
	 * @param integer|WP_Post $post_id The post whose reference we want or its ID.
	 * @param string          $type    Optional. Type of references to pull. Accepted values: `all` | `included` | `suggested`. Default: `all`.
	 *
	 * @return array an array with two lists: _suggested_ and _included_ references.
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function get_references( $post_id, $type = 'all' ) {

		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		}//end if

		if ( empty( $post_id ) ) {
			return [];
		}//end if

		$included_ids  = nc_get_post_reference( $post_id, 'included' );
		$suggested_ids = nc_get_post_reference( $post_id, 'suggested' );
		if ( 'included' === $type ) {
			$reference_ids = $included_ids;
		} elseif ( 'suggested' === $type ) {
			$reference_ids = $suggested_ids;
		} else {
			$reference_ids = array_values( array_unique( array_merge( $included_ids, $suggested_ids ) ) );
		} //end if

		$references = array_map(
			function( $ref_id ) use ( $post_id ) {
				$reference = new Nelio_Content_Reference( $ref_id );
				$meta      = nc_get_suggested_reference_meta( $post_id, $ref_id );
				if ( ! empty( $meta ) ) {
					$reference->mark_as_suggested( $meta['advisor'], $meta['date'] );
				}//end if
				return $reference->json_encode();
			},
			$reference_ids
		);

		return $references;

	}//end get_references()

	/**
	 * This function returns a list with the domains that shouldn't be considered
	 * as references.
	 *
	 * @return array an array with the external references
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function get_non_reference_domains() {

		/**
		 * List of domain names that shouldn't be considered as external references.
		 *
		 * @param array domains list of domain names that shouldn't be considered as
		 *                      external references. It accepts the star (*) char as
		 *                      a wildcard.
		 *
		 * @since 1.3.4
		 */
		return apply_filters(
			'nelio_content_non_reference_domains',
			array(
				'bing.*',
				'*.bing.*',
				'flickr.com',
				'giphy.com',
				'google.*',
				'*.google.*',
				'linkedin.com',
				'unsplash.com',
				'twitter.com',
				'facebook.com',
			)
		);

	}//end get_non_reference_domains()

	/**
	 * Modifies the metas so that we know whether the post can be auto shared or not.
	 *
	 * @param int     $post_id Post ID.
	 * @param boolean $enabled whether the post can be auto shared or not.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function enable_auto_share( $post_id, $enabled ) {

		if ( $enabled ) {
			delete_post_meta( $post_id, '_nc_exclude_from_auto_share' );
			update_post_meta( $post_id, '_nc_include_in_auto_share', true );
		} else {
			delete_post_meta( $post_id, '_nc_include_in_auto_share' );
			update_post_meta( $post_id, '_nc_exclude_from_auto_share', true );
		}//end if

	}//end enable_auto_share()

	/**
	 * Returns whether the post can be auto shared or not.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return boolean whether the post can be auto shared or not.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function is_auto_share_enabled( $post_id ) {

		$explicitly_included = ! empty( get_post_meta( $post_id, '_nc_include_in_auto_share', true ) );
		if ( $explicitly_included ) {
			return true;
		}//end if

		$explicitly_excluded = ! empty( get_post_meta( $post_id, '_nc_exclude_from_auto_share', true ) );
		if ( $explicitly_excluded ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		return 'include-in-auto-share' === $settings->get( 'auto_share_default_mode' );

	}//end is_auto_share_enabled()

	/**
	 * Sets users to follow specified post.
	 *
	 * @param int   $post_id     ID of the post.
	 * @param array $suggestions URLs of suggested references.
	 * @param array $included    URLs of included references.
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function update_post_references( $post_id, $suggestions, $included ) {

		// 1. SUGGESTED REFERENCES
		$suggestions     = wp_list_pluck( array_map( 'nc_create_reference', $suggestions ), 'ID' );
		$old_suggestions = nc_get_post_reference( $post_id, 'suggested' );

		$new_suggestions = array_diff( $suggestions, $old_suggestions );
		foreach ( $new_suggestions as $ref_id ) {
			nc_suggest_post_reference( $post_id, $ref_id, get_current_user_id() );
		} //end foreach

		$invalid_suggestions = array_diff( $old_suggestions, $suggestions );
		foreach ( $invalid_suggestions as $ref_id ) {
			nc_discard_post_reference( $post_id, $ref_id );
		} //end foreach

		// 2. INCLUDED REFERENCES
		$included     = wp_list_pluck( array_map( 'nc_create_reference', $included ), 'ID' );
		$old_included = nc_get_post_reference( $post_id, 'included' );

		$new_included = array_diff( $included, $old_included );
		foreach ( $new_included as $ref_id ) {
			nc_add_post_reference( $post_id, $ref_id );
		} //end foreach

		$invalid_included = array_diff( $old_included, $included );
		foreach ( $invalid_included as $ref_id ) {
			nc_delete_post_reference( $post_id, $ref_id );
		} //end foreach
	}//end update_post_references()

	/**
	 * Sets users to follow specified post.
	 *
	 * @param int   $post_id ID of the post.
	 * @param array $users   User IDs that follow the post.
	 *
	 * @return boolean true on success and false on failure
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function save_post_followers( $post_id, $users ) {

		if ( ! is_array( $users ) ) {
			$users = [];
		}//end if

		$users = array_values( array_filter( array_unique( array_map( 'absint', $users ) ) ) );
		return nc_update_post_meta_array( $post_id, '_nc_following_users', $users );

	}//end save_post_followers()

	/**
	 * This function creates a ncselect2-ready object with (a) the current post
	 * in the loop or (b) the post specified in `$post_id`.
	 *
	 * @param WP_Post|integer $post The post we want to stringify (or its ID).
	 *
	 * @return array a ncselect2-ready object with (a) the current post in the
	 *               loop or (b) the post specified in `$post_id`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function post_to_json( $post ) {

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}//end if

		if ( is_wp_error( $post ) || ! $post ) {
			return false;
		}//end if

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$result    = array(
			'author'       => absint( $post->post_author ),
			'authorName'   => $this->get_the_author( $post ),
			'categories'   => $this->get_the_categories( $post, 'slug' ),
			'date'         => $this->get_post_time( $post, false ),
			'editLink'     => $this->get_edit_post_link( $post ),
			'excerpt'      => $this->get_the_excerpt( $post ),
			'followers'    => $this->get_post_followers( $post ),
			'id'           => $post->ID,
			'imageId'      => $this->get_post_thumbnail_id( $post ),
			'imageSrc'     => $this->get_post_thumbnail( $post, false ),
			'images'       => $this->get_images( $post ),
			'permalink'    => $this->get_permalink( $post ),
			'statistics'   => $analytics->get_post_stats( $post->ID ),
			'status'       => $post->post_status,
			'thumbnailSrc' => $this->get_featured_thumb( $post ),
			'tags'         => $this->get_tags( $post ),
			'title'        => $this->get_the_title( $post ),
			'type'         => $post->post_type,
			'typeName'     => $this->get_post_type_name( $post ),
			'viewLink'     => get_permalink( $post ),
		);

		return $result;

	}//end post_to_json()

	/**
	 * This function creates an AWS-ready post object.
	 *
	 * @param integer $post_id The ID of the post we want to stringify.
	 *
	 * @return array an AWS-ready post object.
	 *
	 * @since  1.4.5
	 * @access public
	 */
	public function post_to_aws_json( $post_id ) {

		$result = $this->post_to_json( $post_id );
		if ( ! $result ) {
			return false;
		} //end if

		$post = get_post( $post_id );

		unset( $result['followers'] );
		unset( $result['statistics'] );

		$result = array_merge(
			$result,
			array(
				'categories'          => $this->get_the_categories( $post, 'name' ),
				'content'             => $this->get_the_content( $post ),
				'date'                => $result['date'] ?: 'none',
				'excludedFromReshare' => ! $this->is_auto_share_enabled( $post_id ),
				'featuredImage'       => $this->get_post_thumbnail( $post, 'none' ),
				'isAutoShareEnabled'  => $this->is_auto_share_enabled( $post_id ),
				'references'          => $this->get_external_references( $post ),
				'timezone'            => nc_get_timezone(),
				'networkImages'       => array_reduce(
					[ 'facebook', 'googleplus', 'instagram', 'linkedin', 'pinterest', 'twitter', 'tumblr', 'gmb' ],
					function( $carry, $network ) use ( $post_id ) {
						/**
						 * Sets the exact image that should be used when sharing the post on a certain network.
						 *
						 * Notice that not all messages that Nelio Content generates will contain an image.
						 * This filter only overwrites the shared image on those messages that contain one.
						 *
						 * @param boolean|string $image   The image that should be used. Default: `false` (i.e. “none”).
						 * @param int            $post_id The post that’s about to be shared.
						 *
						 * @since 1.4.5
						 */
						$carry[ $network ] = apply_filters( "nelio_content_{$network}_featured_image", false, $post_id );
						return $carry;
					},
					[]
				),
			)
		);

		return $result;

	}//end post_to_aws_json()

	/**
	 * This function returns whether the given post has changed since the last update or not.
	 *
	 * @param integer $post_id the post ID.
	 *
	 * @return boolean whether the post has changed since the last update or not.
	 *
	 * @since  1.6.8
	 * @access public
	 */
	public function has_post_changed_since_last_sync( $post_id ) {

		$new_hash = $this->get_post_hash( $post_id );
		$old_hash = get_post_meta( $post_id, '_nc_sync_hash', true );

		return $new_hash && $new_hash !== $old_hash;

	}//end has_post_changed_since_last_sync()

	/**
	 * This function adds a custom meta so that we know that the post, as is right now, has been synched with AWS.
	 *
	 * @param integer $post_id the post ID.
	 *
	 * @since  1.6.8
	 * @access public
	 */
	public function mark_post_as_synched( $post_id ) {

		$hash = $this->get_post_hash( $post_id );
		if ( $hash ) {
			update_post_meta( $post_id, '_nc_sync_hash', $hash );
		}//end if

	}//end mark_post_as_synched()

	/**
	 * Returns the list of post followers for the given post
	 *
	 * @param int|WP_Post $post_id the ID of the post whose followers we want.
	 *
	 * @return array the list of post followers for the given post
	 *
	 * @since  2.0.0
	 * @access public
	 */
	public function get_post_followers( $post_id ) {

		if ( $post_id instanceof WP_Post ) {
			$post_id = $post_id->ID;
		} //end if

		if ( empty( $post_id ) ) {
			return [];
		}//end if

		$follower_ids = get_post_meta( $post_id, '_nc_following_users', false );
		if ( ! is_array( $follower_ids ) ) {
			$follower_ids = [];
		} //end if

		return array_values( array_unique( array_map( 'absint', $follower_ids ) ) );

	}//end get_post_followers()

	private function get_post_hash( $post_id ) {

		$post = $this->post_to_aws_json( $post_id );
		if ( ! $post ) {
			return false;
		}//end if

		unset( $post['content'] );
		$post = array_map(
			function( $value ) {
				if ( is_array( $value ) ) {
					sort( $value );
				}//end if
				return $value;
			},
			$post
		);

		$post['date'] = substr( $post['date'], 0, strlen( 'YYYY-MM-DDThh:mm' ) );

		$encoded_post = wp_json_encode( $post );
		if ( empty( $encoded_post ) ) {
			return false;
		}//end if

		return md5( $encoded_post );

	}//end get_post_hash()

	private function find_root_category( $term_id, $categories = false ) {

		if ( ! $categories ) {
			$categories = get_categories( array( 'hide_empty' => false ) );
		}//end if

		foreach ( $categories as $cat ) {
			if ( $cat->term_id === $term_id ) {
				if ( 0 === $cat->parent ) {
					return $cat;
				} else {
					return $this->find_root_category( $cat->parent, $categories );
				}//end if
			}//end if
		}//end foreach

		return false;

	}//end find_root_category()

	private function get_the_author( $post ) {

		return get_the_author_meta( 'display_name', $post->post_author );

	}//end get_the_author()

	private function get_the_categories( $post, $mode ) {

		$aux                  = get_the_category( $post->ID );
		$processed_categories = [];
		$categories           = [];

		foreach ( $aux as $cat ) {

			$root_cat = $this->find_root_category( $cat->term_id );
			if ( $root_cat && ! in_array( $root_cat->term_id, $processed_categories, true ) ) {
				array_push( $processed_categories, $root_cat->term_id );
				array_push(
					$categories,
					array(
						'name' => $root_cat->name,
						'slug' => $root_cat->slug,
					)
				);
			}//end if
		}//end foreach

		if ( 'name' === $mode ) {
			return array_map(
				function( $category ) {
					return preg_replace( '/ /', '', ucwords( $category['name'] ) );
				},
				$categories
			);
		} //end if

		return array_map(
			function( $category ) {
				return $category['slug'];
			},
			$categories
		);

	}//end get_the_categories()

	private function get_post_thumbnail( $post, $default ) {

		$aux      = Nelio_Content_External_Featured_Image_Helper::instance();
		$settings = Nelio_Content_Settings::instance();

		$featured_image = $aux->get_external_featured_image( $post->ID );

		if ( empty( $featured_image ) && $this->get_post_thumbnail_id( $post ) ) {
			$featured_image = wp_get_attachment_url( $this->get_post_thumbnail_id( $post ) );
		}//end if

		$auto_feat_image = $settings->get( 'auto_feat_image' );
		if ( empty( $featured_image ) && 'disabled' !== $auto_feat_image ) {
			$featured_image = $aux->get_auto_featured_image( $post->ID, $auto_feat_image );
		}//end if

		if ( empty( $featured_image ) ) {
			$featured_image = $default;
		}//end if

		return $featured_image;

	}//end get_post_thumbnail()

	private function get_featured_thumb( $post ) {

		$aux      = Nelio_Content_External_Featured_Image_Helper::instance();
		$settings = Nelio_Content_Settings::instance();

		$featured_thumb = $aux->get_external_featured_image( $post->ID );

		if ( empty( $featured_thumb ) && $this->get_post_thumbnail_id( $post ) ) {
			$featured_thumb = wp_get_attachment_thumb_url( $this->get_post_thumbnail_id( $post ) );
		}//end if

		$position = $settings->get( 'auto_feat_image' );
		if ( empty( $featured_thumb ) && 'disabled' !== $position ) {
			$featured_thumb = $aux->get_auto_featured_image( $post->ID, $position );
		}//end if

		if ( empty( $featured_thumb ) ) {
			$featured_thumb = Nelio_Content()->plugin_url . '/assets/dist/images/default-featured-image-thumbnail.png';
		}//end if

		return $featured_thumb;

	}//end get_featured_thumb()

	private function get_post_thumbnail_id( $post ) {

		$post_thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
		if ( empty( $post_thumbnail_id ) ) {
			$post_thumbnail_id = 0;
		}//end if

		return $post_thumbnail_id;

	}//end get_post_thumbnail_id()

	private function get_post_type_name( $post ) {

		$post_type_name = _x( 'Post', 'text (default post type name)', 'nelio-content' );
		$post_type      = get_post_type_object( $post->post_type );
		if ( ! empty( $post_type ) && isset( $post_type->labels ) && isset( $post_type->labels->singular_name ) ) {
			$post_type_name = $post_type->labels->singular_name;
		}//end if

		return $post_type_name;

	}//end get_post_type_name()

	private function get_the_title( $post ) {

		/**
		 * Modifies the title of the post.
		 *
		 * @param string $title   the title.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		$title = apply_filters( 'nelio_content_post_title', apply_filters( 'the_title', $post->post_title, $post->ID ), $post->ID );

		return html_entity_decode( wp_strip_all_tags( $title ), ENT_HTML5 );

	}//end get_the_title()

	private function get_the_excerpt( $post ) {

		if ( ! empty( $post->post_excerpt ) ) {
			$excerpt = $post->post_excerpt;
		} else {
			$excerpt = '';
		}//end if

		/**
		 * Modifies the excerpt of the post.
		 *
		 * @param string $excerpt the excerpt.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		$excerpt = apply_filters( 'nelio_content_post_excerpt', $excerpt, $post->ID );

		return html_entity_decode( wp_strip_all_tags( $excerpt ), ENT_HTML5 );

	}//end get_the_excerpt()

	private function get_post_time( $post, $default ) {

		$timezone = date_default_timezone_get();
		date_default_timezone_set( 'UTC' ); // phpcs:ignore

		$date = ' ' . $post->post_date_gmt;
		if ( strpos( $date, '0000-00-00' ) ) {
			$date = $default;
		} else {
			$date = get_post_time( 'c', true, $post );
		}//end if

		date_default_timezone_set( $timezone ); // phpcs:ignore

		return $date;

	}//end get_post_time()

	private function get_edit_post_link( $post ) {

		$link = get_edit_post_link( $post->ID, 'default' );
		if ( empty( $link ) ) {
			$link = '';
		}//end if

		return $link;

	}//end get_edit_post_link()

	private function get_permalink( $post ) {

		$permalink = get_permalink( $post );
		if ( 'publish' !== $post->post_status ) {
			$aux              = clone $post;
			$aux->post_status = 'publish';
			if ( empty( $aux->post_name ) ) {
				$aux->post_name = sanitize_title( $aux->post_title, $aux->ID );
			}//end if
			$aux->post_name = wp_unique_post_slug( $aux->post_name, $aux->ID, 'publish', $aux->post_type, $aux->post_parent );
			$permalink      = get_permalink( $aux );
		}//end if

		/**
		 * Filters the permalink that will be used when sharing the post on social media.
		 *
		 * @param string $permalink the post permalink.
		 * @param id     $post_id   the post ID.
		 *
		 * @since 1.3.6
		 */
		$permalink = apply_filters( 'nelio_content_post_permalink', $permalink, $post->ID );

		return $permalink;

	}//end get_permalink()

	private function extract_urls( $content ) {

		// Extract all the URLs.
		preg_match_all(
			'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
			$content,
			$matches
		);

		if ( count( $matches ) > 0 ) {
			return $matches[0];
		} else {
			return [];
		}//end if

	}//end extract_urls()

	private function is_external_reference( $url, $non_ref_domains ) {

		// Internal URLs are not external.
		if ( 0 === strpos( $url, get_home_url() ) ) {
			return false;
		}//end if

		// Discard any URL that is an external reference.
		foreach ( $non_ref_domains as $pattern ) {
			if ( preg_match( $pattern, $url ) ) {
				return false;
			}//end if
		}//end if

		return true;

	}//end is_external_reference()

	private function get_tags( $post ) {

		if ( 'post' !== $post->post_type ) {
			return [];
		}//end if

		$tags = get_the_tags( $post->ID );
		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			$tags = wp_list_pluck( $tags, 'name' );
		} else {
			$tags = [];
		}//end if

		return array_map(
			function( $tag ) {
				return preg_replace( '/ /', '', ucwords( $tag ) );
			},
			$tags
		);

	}//end get_tags()

	private function get_the_content( $post ) {

		$aux = Nelio_Content_Public::instance();
		remove_filter( 'the_content', [ $aux, 'remove_share_blocks' ], 99 );
		return apply_filters( 'the_content', $post->post_content );

	}//end get_the_content()

	private function get_images( $post ) {

		$content = $this->get_the_content( $post );
		preg_match_all( '/<img[^>]+>/i', $content, $matches );

		$result = [];
		foreach ( $matches[0] as $img ) {
			$url = $this->get_url_from_image_tag( $img );
			if ( $url ) {
				array_push( $result, $url );
			}//end if
		}//end foreach

		shuffle( $result );
		return array_slice( $result, 0, 10 );

	}//end get_images()

	private function get_url_from_image_tag( $img ) {
		/**
		 * HTML attributes that might contain the actual URL in an image tag.
		 *
		 * @param array $attributes list of attributes. Default: `[ 'src', 'data-src' ]`.
		 *
		 * @since 2.0.0
		 */
		$attributes = apply_filters( 'nelio_content_url_attributes_in_image_tag', [ 'src', 'data-src' ] );

		$attributes = implode( '|', $attributes );
		preg_match_all( '/(' . $attributes . ')=("[^"]*"|\'[^\']*\')/i', $img, $aux );

		if ( count( $aux ) <= 2 ) {
			return false;
		}//end if

		$urls = array_map(
			function( $url ) {
				return substr( $url, 1, strlen( $url ) - 2 );
			},
			$aux[2]
		);

		foreach ( $urls as $url ) {
			if ( preg_match( '/^https?:\/\//', $url ) ) {
				return $url;
			} //end if
		} //end foreach

		return false;
	}//end get_url_from_image_tag()

	private function get_external_references( $post ) {

		$result     = [];
		$references = $this->get_references( $post, 'all' );

		$non_ref_domains = $this->get_non_reference_domains();
		$count           = count( $non_ref_domains );
		for ( $i = 0; $i < $count; ++$i ) {
			$pattern               = $non_ref_domains[ $i ];
			$pattern               = str_replace( '.', '\\.', $pattern );
			$pattern               = preg_replace( '/\*$/', '[^\/]+', $pattern );
			$pattern               = str_replace( '*', '[^\/]*', $pattern );
			$pattern               = '/^[^:]+:\/\/[^\/]*' . $pattern . '/';
			$non_ref_domains[ $i ] = $pattern;
		}//end for

		$external_references = array_values(
			array_filter(
				$references,
				function( $reference ) use ( &$non_ref_domains ) {
					return $this->is_external_reference( $reference['url'], $non_ref_domains );
				}
			)
		);

		return array_values(
			array_map(
				function( $reference ) {
					return array(
						'url'     => $reference['url'],
						'author'  => $reference['author'],
						'title'   => $reference['title'],
						'twitter' => $reference['twitter'],
					);
				},
				$external_references
			)
		);
	}//end get_external_references()

}//end class
