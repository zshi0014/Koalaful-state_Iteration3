<?php
/**
 * Helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/utils/functions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * Checks if the current user can manage the account or not.
 *
 * @return boolean whether the current user can manage the account or not.
 *
 * @since 2.0.0
 */
function nc_can_current_user_manage_account() {
	if ( ! nc_can_current_user_manage_plugin() ) {
		return false;
	} //end if

	if ( ! function_exists( 'current_user_can' ) ) {
		return false;
	}//end if

	$can_manage = current_user_can( 'manage_options' );

	/**
	 * Filters whether the user can manage the account or not.
	 *
	 * @param boolean $can_manage whether the user can or can’t manage the account.
	 * @param id      $user_id    user id.
	 *
	 * @since 2.0.0
	 */
	$can_manage = apply_filters( 'nelio_content_can_user_manage_account', $can_manage, get_current_user_id() );
	return ! empty( $can_manage );
}//end nc_can_current_user_manage_account()

/**
 * Checks if the current user can manage the plugin or not.
 *
 * @return boolean whether the current user can manage the plugin or not.
 *
 * @since 2.0.0
 */
function nc_can_current_user_manage_plugin() {
	if ( ! nc_can_current_user_use_plugin() ) {
		return false;
	} //end if

	if ( ! function_exists( 'current_user_can' ) ) {
		return false;
	}//end if

	$can_manage = current_user_can( 'edit_others_posts' );

	/**
	 * Filters whether the current user can or can’t manage the plugin.
	 *
	 * @param boolean $can_manage whether the user can or can’t manage the plugin.
	 * @param id      $user_id    user id.
	 *
	 * @since 2.0.0
	 */
	$can_manage = apply_filters( 'nelio_content_can_user_manage_plugin', $can_manage, get_current_user_id() );
	return ! empty( $can_manage );
}//end nc_can_current_user_manage_plugin()

/**
 * Checks if the current user can use the plugin or not.
 *
 * @return boolean whether the current user can use the plugin or not.
 *
 * @since 2.0.0
 */
function nc_can_current_user_use_plugin() {

	if ( ! function_exists( 'current_user_can' ) ) {
		return false;
	}//end if

	/**
	 * Short-circuits the user’s ability to use the plugin.
	 *
	 * If set to `true`, the plugin won’t have access to the plugin. Otherwise, it’ll depend on their capabilities.
	 *
	 * @param boolean $revoke_access whether the user shouldn’t have access to the plugin.
	 * @param id      $user_id       the user.
	 *
	 * @since 2.0.0
	 */
	if ( apply_filters( 'nelio_content_revoke_plugin_access_to_user', false, get_current_user_id() ) ) {
		return false;
	} //end if

	$settings = Nelio_Content_Settings::instance();
	if ( ! $settings->are_ready() ) {
		return false;
	}//end if

	$can_use    = false;
	$post_types = $settings->get( 'calendar_post_types' );

	foreach ( $post_types as $name ) {
		if ( $can_use ) continue;
		$type = get_post_type_object( $name );
		if ( empty( $type ) || is_wp_error( $type ) ) {
			continue;
		}//end if
		$can_use = current_user_can( $type->cap->edit_posts );
	}//end if

	return ! empty( $can_use );

}//end nc_can_current_user_use_plugin()

/**
 * Generates a title for our settings screen.
 *
 * @param string $title the title of the section.
 * @param string $icon  a Dashicon identifier.
 *
 * @return string the title of the section.
 *
 * @since 2.0.0
 */
function nc_make_settings_title( $title, $icon ) {
	if ( empty( $icon ) ) {
		return $title;
	}//end if

	return sprintf(
		'<span class="dashicons dashicons-%s"></span> %s',
		$icon,
		$title
	);
}//end nc_make_settings_title()

/**
 * Registers a script loading the dependencies automatically.
 *
 * @param string  $handle    the script handle name.
 * @param string  $file_name the JS name of a script in $plugin_path/assets/dist/js/. Don't include the extension or the path.
 * @param boolean $footer    whether the script should be included in the footer or not.
 *
 * @since 2.0.0
 */
function nc_register_script_with_auto_deps( $handle, $file_name, $footer ) {

	$asset = array(
		'dependencies' => [],
		'version'      => nelio_content()->plugin_version,
	);

	if ( file_exists( nelio_content()->plugin_path . "/assets/dist/js/$file_name.asset.php" ) ) {
		$asset = include nelio_content()->plugin_path . "/assets/dist/js/$file_name.asset.php";
	}//end if

	// NOTE. Bug fix with @wordpress/core-data package.
	$asset['dependencies'] = array_map(
		function( $dep ) {
			return str_replace( 'wp-coreData', 'wp-core-data', $dep );
		},
		$asset['dependencies']
	);

	wp_register_script(
		$handle,
		nelio_content()->plugin_url . "/assets/dist/js/$file_name.js",
		$asset['dependencies'],
		$asset['version'],
		$footer
	);

	wp_set_script_translations( $handle, 'nelio-content' );

}//end nc_register_script_with_auto_deps()

/**
 * Returns the script version if available. If it isn’t, it defaults to the plugin’s version.
 *
 * @param string $file_name the JS name of a script in $plugin_path/assets/dist/js/. Don't include the extension or the path.
 *
 * @return string the version of the given script or the plugin’s version if the former wasn’t be found.
 *
 * @since 2.0.0
 */
function nc_get_script_version( $file_name ) {
	if ( ! file_exists( nelio_content()->plugin_path . "/assets/dist/js/$file_name.asset.php" ) ) {
		return nelio_content()->plugin_version;
	}//end if
	$asset = include nelio_content()->plugin_path . "/assets/dist/js/$file_name.asset.php";
	return $asset['version'];
}//end nc_get_script_version()

/**
 * Enqueues a script loading the dependencies automatically.
 *
 * @param string  $handle    the script handle name.
 * @param string  $file_name the JS name of a script in $plugin_path/assets/dist/js/. Don't include the extension or the path.
 * @param boolean $footer    whether the script should be included in the footer or not.
 *
 * @since 2.0.0
 */
function nc_enqueue_script_with_auto_deps( $handle, $file_name, $footer ) {

	nc_register_script_with_auto_deps( $handle, $file_name, $footer );
	wp_enqueue_script( $handle );

}//end nc_enqueue_script_with_auto_deps()

/**
 * Given an ordered list of keys, returns the value of the first key that
 * has a value in the array.
 *
 * @param array        $array       A list of key-value pairs.
 * @param array|string $key_options An ordered list of keys.
 * @param mixed        $default     Optional. The value that has to be
 *                         returned if none of the given keys appear in
 *                         the given array. Default: `false`.
 *
 * @return mixed The value of the first key in `$key_options` that appears
 *               in $array.
 *
 * @since 1.0.0
 */
function nc_get_first_option( $array, $key_options, $default = false ) {

	if ( ! is_array( $key_options ) ) {
		$key_options = [ $key_options ];
	}//end if

	foreach ( $key_options as $key ) {

		if ( isset( $array[ $key ] ) ) {
			return $array[ $key ];
		}//end if
	}//end foreach

	return $default;

}//end nc_get_first_option()

/**
 * This function makes sure that a certain pair of meta key and value for a
 * given posts exists only once in the database.
 *
 * @param string $post_id    the post ID related to the given meta.
 * @param string $meta_key   the meta key.
 * @param mixed  $meta_value the meta value.
 *
 * @return integer the meta ID, false otherwise.
 *
 * @since 1.0.0
 */
function nc_add_post_meta_once( $post_id, $meta_key, $meta_value ) {

	delete_post_meta( $post_id, $meta_key, $meta_value );
	return add_post_meta( $post_id, $meta_key, $meta_value );

}//end nc_add_post_meta_once()

/**
 * This function makes sure that only the values in the array of meta values
 * exists in the database for the given post and meta key (one row per value).
 *
 * @param string $post_id     the post ID related to the given meta.
 * @param string $meta_key    the meta key.
 * @param array  $meta_values the meta values.
 *
 * @return boolean true on success, false otherwise.
 *
 * @since 1.4.2
 */
function nc_update_post_meta_array( $post_id, $meta_key, $meta_values ) {

	$previous_values = get_post_meta( $post_id, $meta_key, false );

	$values_to_delete = array_diff( $previous_values, $meta_values );
	$values_to_save   = array_diff( $meta_values, $previous_values );

	foreach ( $values_to_delete as $value ) {
		if ( ! delete_post_meta( $post_id, $meta_key, $value ) ) {
			return false;
		}//end if
	}//end foreach

	foreach ( $values_to_save as $value ) {
		if ( ! add_post_meta( $post_id, $meta_key, $value, false ) ) {
			return false;
		}//end if
	}//end foreach

	return true;

}//end nc_update_post_meta_array()

/**
 * This function returns the timezone/UTC offset used in WordPress.
 *
 * @return string the meta ID, false otherwise.
 *
 * @since 1.0.0
 */
function nc_get_timezone() {

	$timezone_string = get_option( 'timezone_string', '' );
	if ( ! empty( $timezone_string ) ) {

		if ( 'UTC' === $timezone_string ) {
			return '+00:00';
		} else {
			return $timezone_string;
		}//end if
	}//end if

	$utc_offset = get_option( 'gmt_offset', 0 );

	if ( $utc_offset < 0 ) {
		$utc_offset_no_dec = ceil( $utc_offset );
		$result            = sprintf( '-%02d', absint( $utc_offset_no_dec ) );
	} else {
		$utc_offset_no_dec = floor( $utc_offset );
		$result            = sprintf( '+%02d', absint( $utc_offset_no_dec ) );
	}//end if

	if ( $utc_offset == $utc_offset_no_dec ) { // phpcs:ignore
		$result .= ':00';
	} else {
		$result .= ':30';
	}//end if

	return $result;

}//end nc_get_timezone()

/**
 * This function returns the two-letter locale used in WordPress.
 *
 * @return string the two-letter locale used in WordPress.
 *
 * @since 1.0.0
 */
function nc_get_language() {

	// Language of the blog.
	$lang = get_option( 'WPLANG' );
	if ( empty( $lang ) ) {
		$lang = 'en_US';
	}//end if

	// Convert into a two-char string.
	if ( strpos( $lang, '_' ) > 0 ) {
		$lang = substr( $lang, 0, strpos( $lang, '_' ) );
	}//end if

	return $lang;

}//end nc_get_language()

/**
 * Returns whether this site is a staging site (based on its URL) or not.
 *
 * @return boolean Whether this site is a staging site or not.
 *
 * @since 1.4.0
 */
function nc_is_staging() {

	/**
	 * List of URLs (or keywords) used to identify a staging site.
	 *
	 * If `home_url` matches one of the given values, the current site will
	 * be considered as a staging site.
	 *
	 * @param array $urls list of staging URLs (or fragments). Default: `[ 'staging' ]`.
	 *
	 * @since 1.4.0
	 */
	$staging_urls = apply_filters( 'nelio_content_staging_urls', [ 'staging' ] );
	foreach ( $staging_urls as $staging_url ) {
		if ( strpos( home_url(), $staging_url ) !== false ) {
			return true;
		}//end if
	}//end foreach

	return false;

}//end nc_is_staging()

/**
 * Returns whether the plugin should only be used for its editorial calendar
 * features or not.
 *
 * @return boolean Whether the plugin should only be used for its editorial
 *                 calendar features or not.
 *
 * @since 1.6.1
 */
function nc_use_editorial_calendar_only() {
	/**
	 * Filters whether the user is only interested in using the editorial calendar.
	 *
	 * @param boolean $only_calendar Default: `false`.
	 *
	 * @since 1.6.1
	 */
	return apply_filters( 'nelio_content_use_editorial_calendar_only', false );
}//end nc_use_editorial_calendar_only()

/**
 * Generates a unique ID.
 *
 * @return string unique ID.
 *
 * @since 2.0.0
 */
function nc_uuid() {

	$data    = random_bytes( 16 );
	$data[6] = chr( ord( $data[6] ) & 0x0f | 0x40 );
	$data[8] = chr( ord( $data[8] ) & 0x3f | 0x80 );

	return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );

}//end nc_uuid()

/**
 * Composes all the arguments into a single function.
 *
 * @param function $functions List of functions to compose.
 *
 * @return function the compositon of all its arguments (from left to right).
 *
 * @since 2.0.0
 */
function nc_compose( ...$functions ) {
	return function( $arg ) use ( &$functions ) {
		return array_reduce(
			$functions,
			function ( $res, $fn ) {
				return call_user_func_array( $fn, [ $res ] );
			},
			$arg
		);
	};
}//end nc_compose()
