<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * The admin-specific functionality of the plugin.
 */
class Nelio_Content_Admin {

	protected static $instance;

	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		add_action( 'init', [ $this, 'init_pages' ], 9999 );

		add_action( 'admin_menu', [ $this, 'create_menu' ] );
		add_action( 'admin_bar_menu', [ $this, 'add_calendar_in_admin_bar' ], 99 );

		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ], 5 );
		add_action( 'admin_enqueue_scripts', [ $this, 'maybe_enqueue_editor_dialog_styles' ], 99 );
		add_action( 'admin_enqueue_scripts', [ $this, 'maybe_enqueue_media_scripts' ], 99 );
		add_filter( 'option_page_capability_nelio-content_group', [ $this, 'get_settings_capability' ] );

	}//end init()

	public function create_menu() {

		$capability =
			nc_can_current_user_use_plugin()
				? 'read'
				: 'invalid-capability';

		add_menu_page(
			'Nelio Content',
			'Nelio Content',
			$capability,
			'nelio-content',
			null,
			$this->get_plugin_icon(),
			25
		);

	}//end create_menu()

	public function add_calendar_in_admin_bar() {

		global $wp_admin_bar;
		$original_blog_id = get_current_blog_id();

		foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {

			if ( is_multisite() ) {
				switch_to_blog( $blog->userblog_id );
			}//end if

			if ( ! nc_get_site_id() || ! nc_can_current_user_use_plugin() ) {
				continue;
			}//end if

			$wp_admin_bar->add_node(
				array(
					'parent' => is_multisite()
						? 'blog-' . get_current_blog_id()
						: 'site-name',
					'id'     => 'nelio-content-calendar-blog-' . get_current_blog_id(),
					'title'  => _x( 'Calendar', 'text (menu)', 'nelio-content' ),
					'href'   => admin_url( 'admin.php?page=nelio-content' ),
				)
			);

		}//end foreach

		if ( is_multisite() ) {
			switch_to_blog( $original_blog_id );
		}//end if

	}//end add_calendar_in_admin_bar()


	public function init_pages() {

		if ( ! nelio_content()->is_ready() ) {
			$page = new Nelio_Content_Welcome_Page();
			$page->init();
			return;
		}//end if

		if ( nc_use_editorial_calendar_only() ) {
			$page = new Nelio_Content_Calendar_Page();
			$page->init();

			$page = new Nelio_Content_Settings_Page();
			$page->init();

			return;
		}//end if

		$page = new Nelio_Content_Calendar_Page();
		$page->init();

		$page = new Nelio_Content_Edit_Post_Page();
		$page->init();

		$page = new Nelio_Content_Feeds_Page();
		$page->init();

		$page = new Nelio_Content_Analytics_Page();
		$page->init();

		$page = new Nelio_Content_Account_Page();
		$page->init();

		$page = new Nelio_Content_Settings_Page();
		$page->init();

		$page = new Nelio_Content_Help_Page();
		$page->init();

		$page = new Nelio_Content_Plugin_List_Page();
		$page->init();

	}//end init_pages()

	public function register_assets() {

		$url  = nelio_content()->plugin_url;
		$path = nelio_content()->plugin_path;

		$scripts = array(
			'nelio-content-components',
			'nelio-content-data',
			'nelio-content-date',
			'nelio-content-networks',
			'nelio-content-post-quick-editor',
			'nelio-content-social-message-editor',
			'nelio-content-task-editor',
			'nelio-content-utils',
		);

		foreach ( $scripts as $script ) {
			$file_without_ext = preg_replace( '/^nelio-content-/', '', $script );
			nc_register_script_with_auto_deps( $script, $file_without_ext, true );
		}//end foreach

		wp_register_style(
			'nelio-content-components',
			$url . '/assets/dist/css/components.css',
			[ 'wp-admin', 'wp-components' ],
			nc_get_script_version( 'components' )
		);

		$post_helper     = Nelio_Content_Post_Helper::instance();
		$plugin_settings = array(
			'feeds'  => get_option( 'nc_feeds', [] ),
			'now'    => date( 'c' ),
			'plugin' => array(
				'authenticationToken' => nc_generate_api_auth_token(),
				'isSubscribed'        => nc_is_subscribed(),
				'isAnalyticsEnabled'  => $this->is_analytics_enabled(),
				'isGAConnected'       => $this->is_ga_connected(),
				'limits'              => nc_get_site_limits(),
				'nonReferenceDomains' => $post_helper->get_non_reference_domains(),
			),
			'site'   => array(
				'adminUrl'       => admin_url(),
				'homeUrl'        => home_url(),
				'id'             => nc_get_site_id(),
				'isMultiAuthor'  => $this->is_multi_author(),
				'language'       => nc_get_language(),
				'timezone'       => nc_get_timezone(),
				'postCategories' => $this->get_post_categories(),
				'postTypes'      => $this->get_post_types(),
			),
			'user'   => array(
				'id'                     => get_current_user_id(),
				'pluginPermission'       => nc_can_current_user_manage_plugin() ? 'manage' : 'use',
				'postTypeCapabilities'   => $this->get_post_type_capabilities(),
				'socialEditorPermission' => $this->get_social_editor_permission(),
				'taskEditorPermission'   => $this->get_task_editor_permission(),
			),
		);

		$script = <<<EOS
wp.data.dispatch( "nelio-content/data" ).initPluginSettings( %s );
wp.data.dispatch( "nelio-content/data" ).loadSocialProfiles();
wp.data.dispatch( "nelio-content/data" ).loadSocialTemplates();
setInterval( function() {
	wp.data.dispatch( "nelio-content/data" ).setUtcNow( new Date().toISOString() );
}, %d );
EOS;

		wp_add_inline_script(
			'nelio-content-data',
			sprintf(
				$script,
				wp_json_encode( $plugin_settings ),
				30 * MINUTE_IN_SECONDS * 1000
			)
		);

	}//end register_assets()

	public function maybe_enqueue_media_scripts() {

		if ( wp_script_is( 'nelio-content-components' ) ) {
			wp_enqueue_media();
		}//end if

	}//end maybe_enqueue_media_scripts()

	public function maybe_enqueue_editor_dialog_styles() {

		$url   = nelio_content()->plugin_url;
		$files = [ 'post-quick-editor', 'social-message-editor', 'task-editor' ];
		foreach ( $files as $file ) {
			if ( wp_script_is( "nelio-content-{$file}", 'queue' ) ) {
				wp_enqueue_style(
					"nelio-content-{$file}",
					"{$url}/assets/dist/css/{$file}.css",
					[ 'nelio-content-components' ],
					nc_get_script_version( $file )
				);
			}//end if
		}//end foreach

	}//end maybe_enqueue_editor_dialog_styles()

	public function get_settings_capability() {
		return nc_can_current_user_manage_plugin() ? 'read' : 'invalid-capability';
	}//end get_settings_capability()

	private function get_plugin_icon() {

		$svg_icon_file = nelio_content()->plugin_path . '/assets/dist/images/logo.svg';
		if ( ! file_exists( $svg_icon_file ) ) {
			return false;
		}//end if

		return 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( $svg_icon_file ) ); // phpcs:ignore

	}//end get_plugin_icon()

	private function is_analytics_enabled() {
		$settings = Nelio_Content_Settings::instance();
		return ! empty( $settings->get( 'use_analytics' ) );
	}//end is_analytics_enabled()

	private function is_ga_connected() {
		$settings = Nelio_Content_Settings::instance();
		$ga_view  = $settings->get( 'google_analytics_view' );
		return ! empty( $ga_view );
	}//end is_ga_connected()

	private function get_post_types() {

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		if ( empty( $post_types ) ) {
			$post_types = [ 'post' ];
		}//end if

		$post_types = array_values( array_filter( array_map( // phpcs:ignore
			function ( $name ) {

				$type = get_post_type_object( $name );
				if ( ! $type || is_wp_error( $type ) ) {
					return false;
				}//end if

				return array(
					'name'   => $type->name,
					'labels' => array(
						'edit'     => $type->labels->edit_item,
						'new'      => $type->labels->add_new_item,
						'plural'   => $type->labels->name,
						'singular' => $type->labels->singular_name,
					),
				);

			},
			$post_types
		) ) ); // phpcs:ignore

		usort(
			$post_types,
			function ( $a, $b ) {
				if ( $a['labels']['singular'] < $b['labels']['singular'] ) {
					return -1;
				}//end if
				if ( $a['labels']['singular'] > $b['labels']['singular'] ) {
					return 1;
				}//end if
				return 0;
			}
		);

		return array_combine(
			wp_list_pluck( $post_types, 'name' ),
			$post_types
		);

	}//end get_post_types()

	private function get_post_categories() {

		$post_types = wp_list_pluck( $this->get_post_types(), 'name' );
		if ( ! in_array( 'post', $post_types, true ) ) {
			return [];
		}//end if

		$categories = get_categories( array( 'hide_empty' => false ) );
		$categories = array_values( array_filter( array_map( // phpcs:ignore
			function ( $category ) {

				if ( $category->parent > 0 ) {
					return false;
				}//end if

				return array(
					'name'  => $category->slug,
					'label' => $category->name,
				);

			},
			$categories
		) ) ); // phpcs:ignore

		usort(
			$categories,
			function ( $a, $b ) {
				if ( $a['label'] < $b['label'] ) {
					return -1;
				}//end if
				if ( $a['label'] > $b['label'] ) {
					return 1;
				}//end if
				return 0;
			}
		);

		return array_combine(
			wp_list_pluck( $categories, 'name' ),
			$categories
		);

	}//end get_post_categories()

	private function is_multi_author() {

		$authors = get_users(
			array(
				'who'    => 'authors',
				'number' => 2,
			)
		);
		return 1 < count( $authors );

	}//end is_multi_author()

	private function get_post_type_capabilities() {
		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		if ( nc_can_current_user_manage_plugin() ) {
			$capabilities = [];
			foreach ( $post_types as $name ) {
				$capabilities[ $name ] = [ 'read', 'edit-own', 'edit-others', 'create' ];
			}//end foreach
			return $capabilities;
		}//end if

		$capabilities = [];
		foreach ( $post_types as $name ) {
			$type = get_post_type_object( $name );
			if ( empty( $type ) || is_wp_error( $type ) ) {
				continue;
			}//end if

			$capabilities[ $name ] = array_values( array_filter( // phpcs:ignore
				array(
					current_user_can( $type->cap->read ) ? 'read' : false,
					current_user_can( $type->cap->edit_posts ) ? 'edit-own' : false,
					current_user_can( $type->cap->edit_others_posts ) ? 'edit-others' : false,
					current_user_can( $type->cap->create_posts ) ? 'create' : false,
				)
			) ); // phpcs:ignore
		}//end foreach

		return $capabilities;
	}//end get_post_type_capabilities()

	private function get_social_editor_permission() {
		$permission = 'none';
		if ( nc_can_current_user_use_plugin() ) {
			$permission = 'post-type';
		}//end if
		if ( nc_can_current_user_manage_plugin() ) {
			$permission = 'all';
		}//end if

		/**
		 * Filters the required permission for the user to be able to edit social messages.
		 *
		 * Possible values are:
		 *
		 * - `all`: the user can edit any social message
		 * - `post-type`: the user can edit social messages related to a post type they can edit or social messages assigned to them
		 * - `none`: the user can’t edit any social message
		 *
		 *
		 * @param string $permission the required permisison. Possibe values are:
		 * @param int    $user_id    current user id
		 *
		 * @since 2.0.0
		 */
		$new_permission = apply_filters( 'nelio_content_social_editor_permission', $permission, get_current_user_id() );

		if ( in_array( $new_permission, [ 'all', 'post-type', 'none' ] ) ) {
			$permission = $new_permission;
		}//end if

		if ( ! nc_is_subscribed() && 'all' === $new_permission ) {
			$permission = 'post-type';
		}//end if

		return $permission;
	}//end get_social_editor_permission()

	private function get_task_editor_permission() {
		if ( ! nc_is_subscribed() ) {
			return 'none';
		} //end if

		$permission = 'none';
		if ( nc_can_current_user_use_plugin() ) {
			$permission = 'post-type';
		}//end if
		if ( nc_can_current_user_manage_plugin() ) {
			$permission = 'all';
		}//end if

		/**
		 * Filters the required permission for the user to be able to edit tasks.
		 *
		 * Possible values are:
		 *
		 * - `all`: the user can edit any task
		 * - `post-type`: the user can edit tasks related to a post type they can edit or tasks assigned to them
		 * - `none`: the user can’t edit any tasks
		 *
		 *
		 * @param string $permission the required permisison. Possibe values are:
		 * @param int    $user_id    current user id
		 *
		 * @since 2.0.0
		 */
		$new_permission = apply_filters( 'nelio_content_task_editor_permission', $permission, get_current_user_id() );

		if ( in_array( $new_permission, [ 'all', 'post-type', 'none' ] ) ) {
			$permission = $new_permission;
		}//end if

		return $permission;
	}//end get_task_editor_permission()

}//end class
