<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/nicomollet
 * @since      1.0.0
 *
 * @package    Tmsm_Admin_Cleanup
 * @subpackage Tmsm_Admin_Cleanup/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Tmsm_Admin_Cleanup
 * @subpackage Tmsm_Admin_Cleanup/admin
 * @author     Nicolas Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Admin_Cleanup_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/tmsm-admin-cleanup-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/tmsm-admin-cleanup-admin.js', array( 'jquery' ), $this->version, true );

		$translation_array = array(
			'urls' => [
				'export_orders' => 'admin.php?page=wc-order-export'
			],
			'strings' => [
				'export_orders' => __( 'Export', 'woocommerce' )
			],
			'export_orders' => (class_exists( 'WC_Order_Export_Admin' )),

		);
		wp_localize_script( $this->plugin_name, 'tmsm_admin_cleanup_i18n', $translation_array );

	}

	/*
	 * Remove Dashboard Meta Boxes
	 */
	public function remove_dashboard_boxes(){
		remove_meta_box('e-dashboard-overview', 'dashboard', 'normal'); // Elementor
		remove_meta_box('dashboard_primary', 'dashboard', 'normal');
		remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	}

	/**
	 * Filter by PDF in Medias
	 *
	 * @param $post_mime_types
	 *
	 * @return mixed
	 */
	function post_mime_types_pdf( $post_mime_types ) {
		$post_mime_types['application/pdf'] = array( __( 'PDFs', 'tmsm-admin-cleanup' ), __( 'Manage PDFs', 'tmsm-admin-cleanup' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
		return $post_mime_types;
	}

	/**
	 * Remove WP menu in admin toolbar
	 */
	function remove_wp_logo_from_admin_bar()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}

	/**
	 * Customers menu item in WooCommerce (Orders)
	 *
	 * @since  1.0.4
	 * @access public
	 */
	public function menu_customers() {
		add_submenu_page(
			'woocommerce',
			__( 'Customers', 'woocommerce' ),
			__( 'Customers', 'woocommerce' ),
			'list_users',
			'users.php?role=customer&orderby=id&order=desc'
		);
	}

	/**
	 *  Mailjet: Move admin menu to submenu of Settings
	 */
	public function menu_mailjet(){

		global $current_user;
		if(user_can($current_user, 'manage_options')){
			add_submenu_page( 'options-general.php',
				__( 'Change your mailjet settings', 'wp-mailjet' ),
				__( 'Mailjet', 'wp-mailjet' ),
				'read',
				'wp_mailjet_options_top_menu',
				'manage_options'
			);
		}

		remove_menu_page('wp_mailjet_options_top_menu');
	}

	/**
	 *  Pricing & Discounts: Move admin menu to submenu of Products
	 */
	public function menu_discounts(){

		if(class_exists('RP_WCDPD')){
			add_submenu_page(
				'edit.php?post_type=product',
				__('Pricing & Discounts', 'rp_wcdpd'),
				__('Pricing & Discounts', 'rp_wcdpd'),
				RP_WCDPD::get_admin_capability(),
				'rp_wcdpd_settings',
				array('RP_WCDPD_Settings', 'print_settings_page')
			);
		}
		remove_submenu_page('woocommerce', 'rp_wcdpd_settings');
	}

	/**
	 * Rename WooCommerce menu to Orders
	 */
	public function menu_woocommerce() {
		global $menu;
		$menu_item = self::recursive_array_search( 'WooCommerce', $menu );
		if ( ! $menu_item ) {
			return;
		}
		$menu[ $menu_item ][0] = __( 'Orders', 'woocommerce' );
	}

	/**
	 * Rename BackWPup menu to Backup
	 */
	public function menu_backwpup() {
		global $menu;
		$menu_item = self::recursive_array_search( 'BackWPup', $menu );
		if ( ! $menu_item ) {
			return;
		}
		$menu[$menu_item][0] = __('Backups', 'backwpup');
	}

	/**
	 * Rename BackWPup menu to Backup
	 */
	public function menu_ocean() {
		global $menu;
		$menu_item = self::recursive_array_search( 'Theme Panel', $menu );
		if ( ! $menu_item ) {
			return;
		}
		$menu[$menu_item][0] = __('Ocean', 'tmsm-frontend-optimizations');
	}

	/**
	 * Shop Managers: redirect to orders
	 *
	 * @param $redirect_to
	 * @param $request
	 * @param $user
	 *
	 * @return string
	 */
	public function redirect_shop_managers( $redirect_to, $request, $user ) {

		$redirect_to_orders = admin_url( 'edit.php?post_type=shop_order' );

		//is there a user to check?
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			// Default redirect for admins
			if ( in_array( 'administrator', $user->roles ) || in_array( 'editor', $user->roles ) || in_array( 'contributor', $user->roles )
			     || in_array( 'author', $user->roles )
			) {
				return $redirect_to;
			} elseif ( in_array( 'shop_manager', $user->roles ) || in_array( 'shop_order_manager', $user->roles ) ) {
				// Redirect shop_manager and shop_order_manager to the orders page
				return $redirect_to_orders;
			} else {
				// Default redirect for other roles
				return $redirect_to;
			}
		} else {
			// Default redirect for no role
			return $redirect_to;
		}
	}



	/**
	 * Hide WooCommerce menu for shop_order_manager
	 *
	 * @since  1.0.4
	 * @access public
	 */
	public function hide_woocommerce() {
		$roles = wp_get_current_user()->roles;
		if ( is_array( $roles ) && isset( $roles[0] ) && $roles[0] == 'shop_order_manager' ):
			echo '<style type="text/css">';
			echo '#adminmenu #toplevel_page_woocommerce {display: none !important;}';
			echo '</style>';
		endif;
	}

	/**
	 * Reports menu for Advanced Order Export For WooCommerce
	 *
	 * @since  1.0.4
	 * @access public
	 * @deprecated
	 */
	public function order_export() {

		if ( class_exists( 'WC_Order_Export_Admin' ) ):
			add_submenu_page(
				'edit.php?post_type=shop_order',
				__( 'Export Orders', 'woo-order-export-lite' ),
				__( 'Export Orders', 'woo-order-export-lite' ),
				'view_woocommerce_reports',
				'admin.php?page=wc-order-export'

			);
		endif;

	}

	/**
	 * Registered column for display
	 *
	 * @since  1.0.4
	 * @access public
	 */
	public function users_columns( $columns ) {
		$columns['registered'] = __( 'Registered', 'tmsm-admin-cleanup' );

		return $columns;
	}

	/**
	 * Handles the registered date column output.
	 *
	 * This uses the same code as column_registered, which is why
	 * the date isn't filterable.
	 *
	 * @param $value
	 * @param $column_name
	 * @param $user_id
	 *
	 * @return bool|int|string
	 */
	public function users_custom_column( $value, $column_name, $user_id ) {

		global $mode;
		$mode = empty( $_REQUEST['mode'] ) ? 'list' : $_REQUEST['mode'];


		if ( 'registered' != $column_name ) {
			return $value;
		} else {
			$user = get_userdata( $user_id );

			if ( is_multisite() && ( 'list' == $mode ) ) {
				$formated_date = __( 'Y/m/d', 'tmsm-admin-cleanup' );
			} else {
				$formated_date = __( 'Y/m/d g:i:s a', 'tmsm-admin-cleanup' );
			}
			$registerdate = mysql2date( $formated_date, $user->user_registered );

			return $registerdate;
		}
	}

	/**
	 * Makes the column sortable
	 *
	 * @since  1.0.4
	 * @access public
	 */
	public function users_sortable_columns( $columns ) {
		$custom = array(
			// meta column id => sortby value used in query
			'registered' => 'id',
		);

		return wp_parse_args( $custom, $columns );
	}


	/**
	 * Recursive array search
	 *
	 * @param $needle
	 * @param $haystack
	 *
	 * @return bool|int|string
	 */
	private function recursive_array_search( $needle, $haystack ) {
		foreach ( $haystack as $key => $value ) {
			$current_key = $key;
			if (
				$needle === $value
				|| (
					is_array( $value )
					&& self::recursive_array_search( $needle, $value ) !== false
				)
			) {
				return $current_key;
			}
		}

		return false;
	}

	/**
	 * Polylang: Display a country flag or the name of the language as a "post state"
	 *
	 * @param array    $post_states An array of post display states.
	 * @param \WP_Post $post        The current post object.
	 *
	 * @return array A filtered array of post display states.
	 */
	public function polylang_display_post_states_language( $post_states, $post ) {
		if ( function_exists( 'pll_get_post_language' ) ) {
			$post_states['polylang'] = pll_get_post_language( $post->ID, 'flag' );
		}
		return $post_states;
	}

	/**
	 * Empty WP Rocket cache on save product
	 *
	 * @param $product
	 */
	public function wprocket_empty_cache_on_save_product($product){
		// clear cache of the default domain
		if(function_exists('rocket_clean_domain')){
			rocket_clean_domain();
		}
	}

	/**
	 * WP Rocket : redefine plugin name
	 *
	 * @return string
	 */
	public function wprocket_name(){
		return __( 'Cache', 'tmsm-admin-cleanup' );
	}

	/**
	 * Content Blocks: Public or not
	 *
	 * @return bool
	 */
	public function content_block_post_type_public(){
		return true;
	}

	/**
	 * Gravity Forms: Label Visibility
	 *
	 * @return bool
	 */
	public function gravityforms_label_visibility(){
		return true;
	}

	/**
	 * Remove tour guide
	 *
	 * @since 1.0.7
	 *
	 * @return bool
	 */
	public function woocommerce_enable_admin_help_tab(){
		return false;
	}

	/**
	 * WooCommerce: Rename order statuses
	 *
	 * @param $statuses
	 *
	 * @return array
	 */
	public function woocommerce_rename_order_statuses_processing($statuses){

		$statuses['wc-processing'] = _x( 'Paid', 'Order status', 'tmsm-admin-cleanup' );

		return $statuses;
	}

	/**
	 * Order date format
	 *
	 * @param $date_format
	 *
	 * @return mixed
	 */
	function woocommerce_admin_order_date_format($date_format){

		$date_format = __( 'M j, Y', 'tmsm-admin-cleanup' );
		return $date_format;
	}

	/**
	 * WooCommerce: Rename order statuses in views filters
	 *
	 * @param $views array
	 *
	 * @return array
	 */
	public function woocommerce_rename_views_filters_processing($views){
		foreach($views as &$view){
			$view = str_replace(_x( 'Processing', 'Order status', 'woocommerce' ), _x( 'Paid', 'Order status', 'tmsm-admin-cleanup' ), $view);
		}
		return $views;
	}

	/**
	 * WooCommerce: Rename order statuses in bulk actions
	 *
	 * @param array $actions
	 *
	 * @return array
	 */
	public function woocommerce_rename_bulk_actions_processing(array $actions){
		$actions['mark_processing'] = __( 'Mark paid', 'tmsm-admin-cleanup' );

		return $actions;
	}

	/**
	 * WooCommerce: sort views
	 *
	 * @param $views
	 *
	 * @return mixed
	 */
	public function woocommerce_orders_sort_views($views){

		$sorted_keys_default = ['all', 'wc-failed', 'wc-refunded', 'wc-cancelled', 'wc-pending', 'wc-on-hold', 'wc-processing', 'wc-completed', 'wc-processed'];
		$sorted_keys = [];

		foreach($sorted_keys_default as $sorted_key){
			if(isset($views[$sorted_key])){
				array_push($sorted_keys, $sorted_key );
			}
		}

		$views = array_merge(array_flip($sorted_keys), $views);

		return $views;
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: "Invoice" changed to "Order Receipt"
	 *
	 * @param string $title
	 * @param \\WPO\\WC\\PDF_Invoices\\Documents\\Order_Document $document
	 *
	 * @return string
	 */
	public function wpo_wcpdf_invoice_title($title, $document){
		return __('Order Receipt', 'tmsm-admin-cleanup'). ' ('.$document->order_id.')';
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: limit export orders to statuses: completed, processing, processed
	 *
	 * @param $order_ids
	 * @param $document_type
	 *
	 * @return mixed
	 */
	function wpo_wcpdf_process_order_ids_paid( $order_ids, $document_type ) {
		foreach ($order_ids as $key => $order_id) {
			$order = wc_get_order( $order_id );
			$allowed_statuses = array( 'completed', 'processing', 'processed' );

			if ( !in_array($order->get_status(), $allowed_statuses) ) {
				unset( $order_ids[$key] );
			}
		}
		return $order_ids;
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: style PDF with CSS
	 *
	 * @param $css
	 * @param $document
	 *
	 * @return string
	 */
	function wpo_wcpdf_template_styles($css, $document){
		$css .= '
		body{font-size: 11pt; color: #666}
		.wc-item-meta{font-size: 11pt;}
		.invoice-number th, .invoice-number td{font-weight: bold}
		.order-details thead th {
			background-color: #666;
			border-color: #666;
		}
		table.totals tr.order_total * {
			border-color: #666;
		}
		';
		return $css;
	}
}
