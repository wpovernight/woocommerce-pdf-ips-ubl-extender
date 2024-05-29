<?php
/**
 * Plugin Name:          PDF Invoices & Packing Slips for WooCommerce - UBL Extender
 * Requires Plugins:     woocommerce-pdf-invoices-packing-slips
 * Plugin URI:           https://wpovernight.com/downloads/woocommerce-pdf-invoices-packing-slips-bundle/
 * Description:          UBL Extender add-on for  PDF Invoices & Packing Slips for WooCommerce plugin.
 * Version:              1.0.0
 * Author:               WP Overnight
 * Author URI:           https://wpovernight.com
 * License:              GPLv3
 * License URI:          https://opensource.org/licenses/gpl-license.php
 * Text Domain:          woocommerce-pdf-ips-ubl-extender
 * WC requires at least: 3.3
 * WC tested up to:      8.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! class_exists( 'WPO_WCPDF_UBL_Extender' ) ) {

	class WPO_WCPDF_UBL_Extender {

		public         $version = '1.0.0';
		private static $_instance;

		/**
		 * Plugin instance
		 * 
		 * @return object
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			include_once 'vendor/autoload.php';
			
			// Declare HPOS compatibility.
			add_action( 'before_woocommerce_init', array( $this, 'custom_order_tables_compatibility' ) );
			
			// Add custom handlers.
			add_filter( 'wpo_wc_ubl_document_format', array( $this, 'add_handlers' ) );
		}
		
		/**
		 * Add HPOS compatibility
		 * 
		 * @return void
		 */
		public function custom_order_tables_compatibility(): void {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}
		
		/**
		 * Inserts a new UBL handler into the format array before or after a specific key.
		 *
		 * @param array  $format       The original format array.
		 * @param string $new_key      The key of the new handler.
		 * @param array  $new_handler  The new handler to insert.
		 * @param string $position_key The key before or after which the new handler should be inserted.
		 * @param string $position     'before' or 'after' to specify where to insert the new handler relative to the position key.
		 *
		 * @return array The format array with the new handler inserted.
		 */
		private function insert_handler_at_key( array $format, string $new_key, array $new_handler, string $position_key, string $position = 'after' ): array {
			$result = array();
			
			foreach ( $format as $key => $value ) {
				if ( $key === $position_key && 'before' === $position ) {
					$result[ $new_key ] = $new_handler;
				}
				
				$result[ $key ] = $value;
				
				if ( $key === $position_key && 'after' === $position ) {
					$result[ $new_key ] = $new_handler;
				}
			}
			
			return $result;
		}
		
		/**
		 * Add custom handlers
		 * 
		 * @param array $format The original format array.
		 * 
		 * @return array The format array with the new handler inserted.
		 */
		public function add_handlers( array $format ): array {
			$custom_handler = array(
				'enabled' => true,
				'handler' => \WPO\WC\UBL\Extender\Handlers\CustomHandler::class,
			);
			
			$format = $this->insert_handler_at_key( $format, 'customhandler', $custom_handler, 'issuedate', 'after' ); // Insert after 'issuedate'
			
			return $format;
		}

	}

}

function WPO_WCPDF_UBL_Extender() {
	return WPO_WCPDF_UBL_Extender::instance();
}
add_action( 'plugins_loaded', 'WPO_WCPDF_UBL_Extender', 99 );