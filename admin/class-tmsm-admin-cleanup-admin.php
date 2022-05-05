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

	/**
	 * All the provided Hotfix does is to hook into the wp_update_attachement_metadata() call and making sure that the data provided for the
	 * meta-value thumb does not contain any parts making path traversal possible. Thus, no security relevant files can be deleted
	 *
	 * @link https://blog.ripstech.com/2018/wordpress-file-delete-to-code-execution/
	 * @since    1.0.7
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function rips_unlink_tempfix( $data ) {
		if( isset($data['thumb']) ) {
			$data['thumb'] = basename($data['thumb']);
		}
		return $data;
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
	 * Jetpack disable Just In Time
	 *
	 * @return bool
	 */
	public function jetpack_just_in_time_msgs(){
		return false;
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
			'admin.php?page=wc-reports&tab=customers&report=customer_list'
		);
	}

	/**
	 *  Mailjet: Move admin menu to submenu of Settings
	 */
	public function menu_mailjet(){

		if ( defined('MAILJET_VERSION')) {
			global $current_user;
			if ( user_can( $current_user, 'manage_options' ) ) {
				add_submenu_page( 'options-general.php',
					__( 'Change your mailjet settings', 'wp-mailjet' ),
					__( 'Mailjet', 'wp-mailjet' ),
					'read',
					'mailjet_settings_page',
					''
				);
			}
			remove_menu_page('mailjet_settings_page');

		}

	}



	/**
	 * Smush: Move admin menu to submenu of Settings
	 *
	 * @since    1.0.8
	 */
	public function menu_smush() {

		if(class_exists('WpSmushitAdmin')){
			global $current_user;
			add_submenu_page( 'options-general.php',
				esc_html__( "Smush", "wp-smushit" ),
				esc_html__( "Smush", "wp-smushit" ),
				'manage_options',
				'smush',
				''
			);
			remove_menu_page('smush');
		}
	}

	/**
	 * Kinsta: Move admin menu to submenu of Settings
	 *
	 * @since    1.0.8
	 */
	public function menu_kinsta() {
		if(defined('KINSTAMU_VERSION')){

			add_submenu_page( 'options-general.php',
				'Kinsta',
				'Kinsta',
				'manage_options',
				'kinsta-tools',
				''
			);

			remove_menu_page('kinsta-tools');
		}
	}

	/**
	 * CRMPerks: Remove admin menu
	 *
	 * @since    1.1.3
	 */
	public function menu_crmperks() {
		if(class_exists('vxg_freshdesk')){

			remove_menu_page('vx-addons');
		}
	}

	/**
	 * OptinMonster: Move admin menu to submenu of Settings
	 *
	 * @since    1.1.3
	 */
	public function menu_optinmonster() {
		if(class_exists('OMAPI')){

			add_submenu_page( 'options-general.php',
				'OptinMonster',
				'OptinMonster',
				'manage_options',
				'optin-monster-api-settings',
				''
			);

			remove_menu_page('optin-monster-api-settings');
		}
	}

	/**
	 * Post Expirator: Move admin menu to submenu of Settings
	 *
	 * @since    1.4.9
	 */
	public function menu_postexpirator() {

		$publishpressfuture = PostExpirator_Display::getInstance();

		add_submenu_page( 'options-general.php',
			__('Post Expirator', 'post-expirator'),
			__('Post Expirator', 'post-expirator'),
			'manage_options',
			'publishpress-future',
			array($publishpressfuture, 'settings_tabs')
		);
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
		$menu[ $menu_item ][4] = 'menu-top menu-icon-generic';
		$menu[ $menu_item ][6] = 'dashicons-cart';
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
	 * Rename Theme Panel menu to Ocean
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
	 * Reorder some plugins
	 *
	 * @param array $menu_order
	 *
	 * @return array
	 */
	function menu_order(array $menu_order){

		$new_positions = array(
			'rank-math' => 100,  // Rank Math
			'complianz' => 500,  // Complianz
			'jetpack' => 200,  // Jetpack
			'gf_edit_forms' => 8, // Gravity Forms
			'leadin_user_guide' => 100, // Gravity Forms
		);
		// helper function to move an element inside an array
		function move_element(&$array, $a, $b) {
			$out = array_splice($array, $a, 1);
			array_splice($array, $b, 0, $out);
		}
		// traverse through the new positions and move
		// the items if found in the original menu_positions
		foreach( $new_positions as $value => $new_index ) {
			if( $current_index = array_search( $value, $menu_order ) ) {
				move_element($menu_order, $current_index, $new_index);
			}
		}

		return $menu_order;
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
			} elseif ( ( in_array( 'shop_manager', $user->roles ) || in_array( 'shop_order_manager', $user->roles) ) &&  class_exists( 'woocommerce' ) ) {
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
		global $woocommerce;
		if ( ! empty( $woocommerce ) && version_compare( $woocommerce->version, '4.5', '<' ) ) {
			$roles = wp_get_current_user()->roles;
			if ( is_array( $roles ) && isset( $roles[0] ) && $roles[0] == 'shop_order_manager' ):
				echo '<style type="text/css">';
				echo '#adminmenu #toplevel_page_woocommerce {display: none !important;}';
				echo '</style>';
			endif;
		}

	}

	/**
	 * Reports menu for Advanced Order Export For WooCommerce
	 *
	 * @since  1.0.4
	 * @access public
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
	 * OceanWP: Disables Dashboard Widget News
	 */
	public function oceanwp_news_enabled(){
		return true;
	}

	/**
	 * Disable WooCommerce dashboard widget
	 *
	 * @since 1.1.0
	 */
	public function woocommerce_remove_dashboard_widgets(){
		remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal' );
		remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal' );
	}

	/**
	 * Admin Body Class: Add Role
	 *
	 * @param string    $classes An array of classes
	 *
	 * @return string Returned classes
	 */
	public function admin_body_class_role( $classes ) {
		$current_user = new WP_User(get_current_user_id());
		$user_role = array_shift($current_user->roles);
		$classes .= ' role-'. $user_role;
		return $classes;
	}

	/**
	 * Filters the interval for redirecting the user to the admin email confirmation screen.
	 * If `0` (zero) is returned, the user will not be redirected.
	 *
	 * @param int Interval time (in seconds).
	 *
	 * @return int
	 */
	public function admin_email_check_interval( $interval ) {
		$interval = 0;
		return $interval;
	}

	/**
	 * Filters the default post display states used in the posts list table.
	 *
	 * @param string[] $post_states An array of post display states.
	 * @param WP_Post  $post        The current post object.
	 *
	 * @return array
	 * @throws Exception
	 */
	public function display_post_states_expire( array $post_states, WP_Post $post ){

		$date_string = __( '%1$s at %2$s', 'tmsm-admin-cleanup' );

		if(!empty($post_states['scheduled'])){

			$date = sprintf(
				$date_string,
				date_i18n( get_option( 'date_format' ), strtotime( $post->post_date ) ),
				date_i18n( get_option( 'time_format' ), strtotime( $post->post_date ) )
			);
			$post_states['scheduled'] = sprintf(_x( 'Scheduled on %s', 'post status', 'tmsm-admin-cleanup' ), $date);
		}


		if($expiration_date_label = $this->expiration_date_label( $post->ID )){
			if(!empty($post_states['scheduled'])){
				$post_states['scheduled'] .=' , '.$expiration_date_label;
			}
			else{
				$post_states['scheduled'] = $expiration_date_label;
			}
		}


		return $post_states;
	}

	/**
	 * Get the expiration date label
	 *
	 * @param int $post_id
	 *
	 *
	 * @return string
	 * @throws Exception
	 */
	private function expiration_date_label( int $post_id): string {

		$label = '';
		if ( function_exists( 'postexpirator_add_column_page' ) ) {
			$date_format = sprintf( __( '%1$s at %2$s', 'tmsm-admin-cleanup' ), get_option( 'date_format' ), get_option( 'time_format' ) );
			$expirationdate_timestamp = get_post_meta( $post_id, '_expiration-date', true );

			if ( ! empty( $expirationdate_timestamp ) ) {

				$date_in_local_timezone = get_date_from_gmt( date( 'Y-m-d H:i:s', $expirationdate_timestamp ) );

				$seconds_since_local_1_jan_1970 = (new DateTime( $date_in_local_timezone, new DateTimeZone( 'UTC' ) ))->getTimestamp();


				$label = sprintf( _x( 'Expires on %s', 'post status', 'tmsm-admin-cleanup' ), date_i18n( $date_format, $seconds_since_local_1_jan_1970 ) );
			}

		}
		return $label;
	}



	/**
	 * Polylang: Display a country flag or the name of the language as a "post state"
	 *
	 * @param array   $post_states An array of post display states.
	 * @param WP_Post $post        The current post object.
	 *
	 * @return array A filtered array of post display states.
	 */
	public function polylang_display_post_states_language( $post_states, $post ) {
		if ( function_exists( 'pll_get_post_language' ) ) {
			if ( ! empty( pll_get_post_language( $post->ID, 'flag' ) ) ) {
				$post_states['polylang'] = pll_get_post_language( $post->ID, 'flag' );
			}
		}
		return $post_states;
	}

	/**
	 * Fix for Elementor template conditions not compatible with Polylang
	 *
	 * @link https://github.com/polylang/polylang/issues/152#issuecomment-320602328
	 *
	 * @param WP_Query $query
	 */
	function polylang_elementor_library_conditions_parse_query( $query ) {
		if ( is_admin() && ! empty( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'elementor_library'
		     && ! empty( $query->query_vars['meta_key'] ) && $query->query_vars['meta_key'] === '_elementor_conditions' ) {
			$query->set( 'lang', '' );
		}
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
	 * Gravity Forms: Add custom settings to all forms.
	 *
	 * @param array $fields
	 * @param array $form
	 *
	 * @hooked gform_form_settings_fields
	 *
	 * @return array
	 */
	public function gravityforms_add_custom_settings(array $fields, array $form): array
	{

		// Legal Notice field
		$fields['form_basics']['fields']['legal_notice'] = [
			'name'       => 'legal_notice',
			'type'       => 'textarea',
			'allow_html' => true,
			'tooltip'    => gform_tooltip( 'legal_notice', '', true ),
			'label'      => __( 'Legal Notice', 'tmsm-admin-cleanup' ),
		];

		return $fields;
	}

	/**
	 * Gravity Forms: Adds custom tooltip to the backend fields
	 *
	 * @param array $tooltips
	 *
	 * @hooked gform_tooltips
	 *
	 * @return array
	 */
	function gravityforms_add_custom_tooltips(array $tooltips): array
	{
		$tooltips['legal_notice'] = __('Legal Notice is displayed below the submit form button.', 'tmsm-admin-cleanup');
		return $tooltips;
	}

	/**
	 * Gravity Forms: Form Filter with the Legal Notice
	 *
	 * @param string $form_string
	 * @param array $form
	 *
	 * @hooked gform_get_form_filter
	 *
	 * @return string
	 */
	function gravityforms_form_filter(string $form_string, array $form): string
	{
		$legal_notice = '<div class="gform_legalnotice">' . rgar($form, 'legal_notice') . '</div>';
		$form_string = str_replace('</form>', $legal_notice . '</form>', $form_string);

		return $form_string;
	}

	/**
	 * Hide WooCommerce menu for shop_order_manager
	 *
	 * @since  1.0.4
	 * @access public
	 */
	public function woocommerce_hide_menu()
	{
		global $woocommerce;
		if (!empty($woocommerce) && version_compare($woocommerce->version, '4.5', '<')) {
			$roles = wp_get_current_user()->roles;
			if (is_array($roles) && isset($roles[0]) && $roles[0] == 'shop_order_manager'):
				echo '<style type="text/css">';
				echo '#adminmenu #toplevel_page_woocommerce {display: none !important;}';
				echo '</style>';
			endif;
		}

	}

	/**
	 * WooCommerce: Disable dashboard widget
	 *
	 * @since 1.1.0
	 */
	public function woocommerce_remove_dashboard_widgets()
	{
		remove_meta_box('woocommerce_dashboard_recent_reviews', 'dashboard', 'normal');
		remove_meta_box('woocommerce_dashboard_status', 'dashboard', 'normal');
	}

	/**
	 * WooCommerce: Hide add-ons menus (Marketplace & My Subscriptions)
	 *
	 * @return bool
	 * @since 1.4.8
	 *
	 * @return bool
	 */
	public function woocommerce_show_addons_page(){
		return false;
	}

	/**
	 * Disable Connect your store to WooCommerce.com to receive extensions updates and support admin notice
	 *
	 * @since 1.1.0
	 *
	 * @return bool
	 */
	public function woocommerce_helper_suppress_admin_notices(){
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
	 * WooCommerce: on product admin page, create a button leading to product report
	 *
	 * @param WP_Post $post The post being edited.
	 * @since 1.1.11
	 *
	 * @param WP_Post $post The post being edited.
	 */
	function woocommerce_product_report_button( $post ) {
		if ( get_post_type($post) == 'product' ) {
			$product = wc_get_product($post->ID);

			$link             = add_query_arg( 'page', 'wc-reports', admin_url( 'admin.php' ) );
			$link             = add_query_arg( 'tab', 'orders', $link );
			$link             = add_query_arg( 'report', 'sales_by_product', $link );
			$link             = add_query_arg( 'range', 'year', $link );
			$link             = add_query_arg( 'product_ids', [$product->get_id()], $link );
			?>

			<div class="misc-pub-section curtime misc-pub-curtime misc-pub-section-product-report">
				<span id="timestamp">
					<?php
					printf( __( 'Total sales: %s', 'tmsm-admin-cleanup' ), '<b>' . number_format_i18n( $product->get_total_sales() ) . '</b>' );
					?>
				</span>
				<a href="<?php echo $link;?>" class="" role="button"><span aria-hidden="true"><?php _e( 'View report', 'tmsm-admin-cleanup' ); ?></span> <span class="screen-reader-text"><?php _e( 'View report', 'tmsm-admin-cleanup' ); ?></span></a>

			</div>
			<?php
		}
	}

	/**
	 * This action can be used to modify the object further before it is created - it will be passed by reference.
	 *
	 * @param WC_Product $duplicate
	 * @param WC_Product $product
	 *
	 */
	public function woocommerce_product_duplicate_before_save( WC_Product $duplicate, WC_Product $product){

		$last_year_long = date( 'Y' ) - 1;
		$last_year_short = substr( $last_year_long,-2);
		$current_year_long =  date( 'Y' );
		$current_year_short = substr( $current_year_long,-2);

		// Check if the product to be duplicated has a year in the product name
		if ( substr( $product->get_name(), - 4 ) == ( $last_year_long ) ) {

			// Replace the last year with the new year
			$duplicate->set_name( sprintf( esc_html__( '%s%s', 'woocommerce' ), substr( $product->get_name(), 0, - 4 ), $current_year_long ) );

		}

		// Product SKU has last year long
		if ( substr( $product->get_sku( 'edit' ), - 4 ) == ( $last_year_long ) ) {
			// Replace the last year with the new year
			$duplicate->set_sku( wc_product_generate_unique_sku( 0, sprintf( esc_html__( '%s%s', 'woocommerce' ), substr( $product->get_sku( 'edit' ), 0, - 4 ), $current_year_long ) ) );
		}

		// Product SKU has last year short
		if ( substr( $product->get_sku( 'edit' ), - 2 ) == ( $last_year_short ) ) {
			// Replace the last year with the new year
			$duplicate->set_sku( wc_product_generate_unique_sku( 0, sprintf( esc_html__( '%s%s', 'woocommerce' ), substr( $product->get_sku( 'edit' ), 0, - 2 ), $current_year_short ) ) );
		}

	}

	/**
	 * WooCommerce: customize email subject for customer processing email
	 *
	 * @param string   $subject
	 * @param WC_Order $order
	 * @param WC_Email $email
	 *
	 * @return string
	 */
	public function woocommerce_email_subject_customer_processing_order( string $subject, WC_Order $order, WC_Email $email ) {

		// Order has local pickup
		if ( $order->has_shipping_method( 'local_pickup' ) ) {
			$subject = __( 'Your {site_title} order has been received!', 'tmsm-admin-cleanup' );
			$subject = $email->format_string( $subject );
		}

		return $subject;
	}

	/**
	 * WooCommerce: customize email heading for customer processing email
	 *
	 * @param string   $heading
	 * @param WC_Order $order
	 * @param WC_Email $email
	 *
	 * @return string
	 */
	public function woocommerce_email_heading_customer_processing_order( string $heading, WC_Order $order, WC_Email $email ) {

		// Order has local pickup
		if ( $order->has_shipping_method( 'local_pickup' ) ) {
			$heading = __( 'Thank you for your order', 'tmsm-admin-cleanup' );
			$heading = $email->format_string( $heading );
		}

		return $heading;
	}

	/**
	 * WooCommerce: customize email additional content for customer processing email
	 *
	 * @param string   $additional_content
	 * @param WC_Order $order
	 * @param WC_Email $email
	 *
	 * @return string
	 */
	public function woocommerce_email_additional_content_customer_processing_order( string $additional_content, WC_Order $order, WC_Email $email ) {

		// Order has local pickup
		if ( $order->has_shipping_method( 'local_pickup' ) ) {
			$additional_content = '';
		}

		return $additional_content;
	}

	/**
	 * WooCommerce: customize email subject for customer completed email
	 *
	 * @param string   $subject
	 * @param WC_Order $order
	 * @param WC_Email $email
	 *
	 * @return string
	 */
	public function woocommerce_email_subject_customer_completed_order( string $subject, WC_Order $order, WC_Email $email ) {

		// Order has local pickup
		if ( $order->has_shipping_method( 'local_pickup' ) ) {
			//$subject = __( 'Your {site_title} order is available for local pickup', 'tmsm-admin-cleanup' );
			$subject = __( 'Your {site_title} order is now complete', 'tmsm-admin-cleanup' );
		}
		else{
			// Order is virtual
			if ( $order->needs_shipping_address() == false){
				$subject = __( 'Your {site_title} order is now complete', 'tmsm-admin-cleanup' );
			}
		}

		$subject = $email->format_string( $subject );

		return $subject;
	}

	/**
	 * WooCommerce: customize email heading for customer completed email
	 *
	 * @param string   $heading
	 * @param WC_Order $order
	 * @param WC_Email $email
	 *
	 * @return string
	 */
	public function woocommerce_email_heading_customer_completed_order( string $heading, WC_Order $order, WC_Email $email ) {

		// Order has local pickup
		if ( $order->has_shipping_method( 'local_pickup' ) ) {
			//$heading = __( 'Your {site_title} order is available for local pickup', 'tmsm-admin-cleanup' );
			$heading = __( 'Thank you for your order', 'tmsm-admin-cleanup' );
		}
		else{
			// Order is virtual
			if ( $order->needs_shipping_address() == false){
				$heading = __( 'Thank you for your order', 'tmsm-admin-cleanup' );
			}
		}

		$heading = $email->format_string( $heading );

		return $heading;
	}

	/**
	 * WooCommerce: customize email additional content for customer completed email
	 *
	 * @param string   $additional_content
	 * @param WC_Order $order
	 * @param WC_Email $email
	 *
	 * @return string
	 */
	public function woocommerce_email_additional_content_customer_completed_order( string $additional_content, WC_Order $order, WC_Email $email ) {

		// Order has local pickup
		if ( $order->has_shipping_method( 'local_pickup' ) ) {
			$additional_content = '';
		}
		else{
			// Order is virtual
			if ( $order->needs_shipping_address() == false){
				$additional_content = '';
			}
		}

		return $additional_content;
	}

	/**
	 * WooCommerce: Add Web Hook Order Paid
	 *
	 * @param array $topic_hooks Existing topic hooks.
	 *
	 * @return array
	 */
	function woocommerce_webhook_topic_hooks_order_paid( $topic_hooks ) {
		$new_hooks = array(
			'order.paid' => array(
				'woocommerce_payment_complete',
			),
		);
		return array_merge( $topic_hooks, $new_hooks );
	}

	/**
	 * WooCommerce: Add Web Hook Topic Paid
	 *
	 * @param array $topic_events Existing topic hooks.
	 *
	 * @return array
	 */
	function woocommerce_valid_webhook_events_paid( $topic_events ) {
		$new_events = array(
			'paid',
		);
		return array_merge( $topic_events, $new_events );
	}

	/**
	 * WooCommerce: Add Web Hook Order Paid i18n
	 *
	 * @param array $topics Array of topics with the i18n proper name.
	 *
	 * @return array
	 */
	function woocommerce_webhook_topics_order_paid( $topics ) {
		$new_topics = array(
			'order.paid' => __( 'Order paid', 'tmsm-admin-cleanup' ),
		);
		return array_merge( $topics, $new_topics );
	}


	/**
	 * WooCommerce: Adds the order processing count to the menu.
	 */
	public function woocommerce_menu_order_count() {
		global $submenu;

		if ( isset( $submenu['edit.php?post_type=shop_order'] ) ) {
			unset( $submenu['edit.php?post_type=shop_order'][0] );
			$order_count = wc_processing_order_count();

			foreach ( $submenu['edit.php?post_type=shop_order'] as $key => $menu_item ) {

				if ( 0 === strpos( $menu_item[0], _x( 'Orders', 'Admin menu name', 'woocommerce' ) ) ) {
					$submenu['edit.php?post_type=shop_order'][ $key ][0] .= ' <span class="awaiting-mod update-plugins count-' . esc_attr( $order_count ) . '"><span class="processing-count">' . number_format_i18n( $order_count ) . '</span></span>'; // WPCS: override ok.
					break;
				}
			}
		}
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
		$title = __('Order Receipt', 'tmsm-admin-cleanup');
		if(!empty($document->order_id)){
			$title .= ' ('.$document->order_id.')';
		}
		return $title;
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

			if ( !in_array($order->get_status(), self::wpo_wcpdf_allowed_statuses()) ) {
				unset( $order_ids[$key] );
			}
		}
		return $order_ids;
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: Allowed Statuses
	 *
	 * @return array
	 */
	static function wpo_wcpdf_allowed_statuses()
	{
		return array('completed', 'processing', 'processed', 'on-hold', 'pending', 'preparation');
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: Style PDF with CSS
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
		.shop-address{font-size:10px}
		#footer{font-size:10px}
		.order-number th, .order-number td{font-weight: bold}
		.order-details thead th {
			background-color: #666;
			border-color: #666;
		}
		table.totals tr.order_total * {
			border-color: #666;
		}
		.invoice-number{display: none; !important}
		.invoice-date{display: none; !important}
		';
		return $css;
	}

	/**
	 * WooCommerce PDF Invoices: display used coupons
	 *
	 * @param $type
	 * @param WC_Order $order
	 */
	function wpo_wcpdf_after_customer_notes( $type, $order){
		if($order->get_coupon_codes()){
			echo '<h3>';
			_e( 'Used coupons', 'tmsm-admin-cleanup' );
			echo '</h3>';
			echo implode("-", $order->get_coupon_codes());
		}
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: Remove Action button
	 *
	 * @param array    $listing_actions
	 * @param WC_Order $order
	 *
	 * @return array
	 */
	public function wpo_wcpdf_listing_actions( array $listing_actions, WC_Order $order){

		if(!empty($listing_actions['invoice'])){
			if(!in_array($order->get_status(), self::wpo_wcpdf_allowed_statuses() ) ){
				unset($listing_actions['invoice']);
			}
		}

		return $listing_actions;
	}

	/**
	 * WooCommerce PDF Invoices & Packing Slips: Allowed Statuses
	 *
	 * @return array
	 */
	static function wpo_wcpdf_allowed_statuses(){
		return array( 'completed', 'processing', 'processed', 'on-hold', 'pending', 'preparation' );
	}

	/**
	 * ACF Disable Autocomplete
	 */
	function acf_input_disable_autocomplete() {

		?>
		<script type="text/javascript">
          (function($) {
            $('.acf-input-wrap input, .acf-input textarea').attr('autocomplete', 'disableacf');
          })(jQuery);
		</script>
		<?php

	}

	/**
	 * Elementor: Shortcode with sanitized name as hint
	 *
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function elementor_admin_columns_content( $column_name, $post_id ) {
		if ( 'shortcode' === $column_name ) {
			// %s = shortcode, %d = post_id
			$post = get_post($post_id);
			$slug = get_post_field( 'post_name', $post );

			$shortcode = esc_attr( sprintf( '[%s id="%d" hint="%s"]', 'elementor-template', $post_id, $slug ) );
			printf( '<input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="%s" />', $shortcode );
		}
	}

	/**
	 * YoastSEO: do not copy title and metadesc
	 *
	 * @since 1.0.9
	 *
	 * @param mixed  $value Meta value
	 * @param string $key   Meta key
	 * @param string $lang  Language of target
	 * @return mixed
	 */
	public function yoast_translate_meta( $value, $key, $lang ) {
		if ( '_yoast_wpseo_title' === $key || '_yoast_wpseo_metadesc' === $key ) {
			$value = null;
		}
		return $value;
	}

	/**
	 * Prevent update notification for specific plugin
	 *
	 * @param $value
	 *
	 * @return mixed
	 */
	function site_transient_update_plugins_disable_specific( $value ) {

		$plugins_to_disable = [
			'github-updater/github-updater.php',
			'backupwordpress/backupwordpress.php',
		];

		if ( isset( $value ) && is_object( $value ) ) {
			foreach ( $plugins_to_disable as $plugin ) {
				if ( isset( $value->response[ $plugin ] ) ) {
					unset( $value->response[ $plugin ] );
				}
			}
		}
		return $value;
	}

	/**
	 * Core Updates: disable wp_version_check single event creation
	 * @since    1.5.0
	 *
	 * @param null|bool|WP_Error $pre       Value to return instead. Default null to continue adding the event.
	 * @param stdClass           $event     {
	 *                                      An object containing an event's data.
	 *
	 * @type string              $hook      Action hook to execute when the event is run.
	 * @type int                 $timestamp Unix timestamp (UTC) for when to next run the event.
	 * @type string|false        $schedule  How often the event should subsequently recur.
	 * @type array               $args      Array containing each separate argument to pass to the hook's callback function.
	 * @type int                 $interval  The interval time in seconds for the schedule. Only present for recurring events.
	 * }
	 *
	 * @param bool               $wp_error  Whether to return a WP_Error on failure.
	 */
	function pre_schedule_event_disableversioncheck( $pre, $event, $wp_error ) {

		if ( $event->hook === 'wp_version_check' && $event->schedule === false ) {
			return new WP_Error(
				'pre_schedule_event_false',
				__( 'Disabling wp_version_check single event.' )
			);
		}

		return $pre;
	}

}
