<?php
/**
 * This file contains the setting for selecting which post types can be managed
 * using Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents a the setting for selecting which post types can be
 * managed using Nelio Content.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.1.0
 */
class Nelio_Content_Calendar_Post_Type_Setting extends Nelio_Content_Abstract_React_Setting {

	public function __construct() {
		parent::__construct( 'calendar_post_types', 'CalendarPostTypeSetting' );
	}//end __construct()

	// @Overrides
	protected function get_field_attributes() {
		$post_types = array_map(
			function( $name ) {
				$post_type = get_post_type_object( $name );
				return array(
					'value' => $post_type->name,
					'label' => $post_type->labels->singular_name,
				);
			},
			$this->get_post_types()
		);

		return array(
			'postTypes' => $post_types,
		);
	}//end get_field_attributes()

	// @Implements
	public function sanitize( $input ) { // phpcs:ignore

		$value = sanitize_text_field( $input[ $this->name ] );
		$value = explode( ',', $value );

		$value = array_values(
			array_filter(
				$value,
				function( $pt ) {
					return get_post_type_object( $pt );
				}
			)
		);

		if ( empty( $value ) ) {
			$value = [ 'post' ];
		} //end if

		$input[ $this->name ] = $value;
		return $input;

	}//end sanitize()

	private function get_post_types() {

		$default_types = [ 'post', 'page' ];
		$other_types   = get_post_types(
			array(
				'public'   => true,
				'_builtin' => false,
			)
		);

		return array_values( array_unique( array_merge( $default_types, $other_types ) ) );

	}//end get_post_types()

}//end class
