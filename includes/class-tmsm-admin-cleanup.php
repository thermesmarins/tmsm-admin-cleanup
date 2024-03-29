<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/nicomollet
 * @since      1.0.0
 *
 * @package    Tmsm_Admin_Cleanup
 * @subpackage Tmsm_Admin_Cleanup/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Tmsm_Admin_Cleanup
 * @subpackage Tmsm_Admin_Cleanup/includes
 * @author     Nicolas Mollet <nico.mollet@gmail.com>
 */
class Tmsm_Admin_Cleanup
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Tmsm_Admin_Cleanup_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('TMSM_ADMIN_CLEANUP_VERSION')) {
			$this->version = TMSM_ADMIN_CLEANUP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'tmsm-admin-cleanup';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		//$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Tmsm_Admin_Cleanup_Loader. Orchestrates the hooks of the plugin.
	 * - Tmsm_Admin_Cleanup_i18n. Defines internationalization functionality.
	 * - Tmsm_Admin_Cleanup_Admin. Defines all hooks for the admin area.
	 * - Tmsm_Admin_Cleanup_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tmsm-admin-cleanup-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-tmsm-admin-cleanup-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-tmsm-admin-cleanup-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-tmsm-admin-cleanup-public.php';

		$this->loader = new Tmsm_Admin_Cleanup_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Tmsm_Admin_Cleanup_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Tmsm_Admin_Cleanup_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Tmsm_Admin_Cleanup_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999);
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		// Security fixes
		$this->loader->add_filter('wp_update_attachment_metadata', $plugin_admin, 'wp_update_attachment_metadata', 10);

		// Plugin Updates: Disable for some plugins
		$this->loader->add_filter('site_transient_update_plugins', $plugin_admin, 'site_transient_update_plugins_disable_specific', 50, 3);

		// Cores Updates: disable auto updates after wp_version_check
		//remove_action('wp_maybe_auto_update', 'wp_maybe_auto_update');

		// Core Updates: disable wp_version_check single event creation (removed because it prevents core security from being applied with a ttl
		//$this->loader->add_filter( 'pre_schedule_event', $plugin_admin, 'pre_schedule_event_disableversioncheck', 10, 3 );

		// Dashboard
		$this->loader->add_action('admin_init', $plugin_admin, 'remove_dashboard_boxes');
		remove_action('welcome_panel', 'wp_welcome_panel');
		remove_action('admin_notices', 'ocean_theme_is_outdated_admin_notice');
		$this->loader->add_filter('oceanwp_news_enabled', $plugin_admin, 'oceanwp_news_enabled', 10);
		$this->loader->add_action('wp_dashboard_setup', $plugin_admin, 'woocommerce_remove_dashboard_widgets', 10);
		$this->loader->add_filter('admin_body_class', $plugin_admin, 'admin_body_class_role', 10, 1);
		$this->loader->add_filter('admin_email_check_interval', $plugin_admin, 'admin_email_check_interval', 10, 1);


		// Jetpack
		$this->loader->add_filter('jetpack_just_in_time_msgs', $plugin_admin, 'jetpack_just_in_time_msgs');

		// Users
		$this->loader->add_filter('manage_users_columns', $plugin_admin, 'users_columns');
		$this->loader->add_action('manage_users_custom_column', $plugin_admin, 'users_custom_column', 10, 3);
		$this->loader->add_filter('manage_users_sortable_columns', $plugin_admin, 'users_sortable_columns', 10, 1);
		$this->loader->add_action('login_redirect', $plugin_admin, 'redirect_shop_managers', 10, 3);

		// Medias
		$this->loader->add_filter('post_mime_types', $plugin_admin, 'post_mime_types_pdf', 999, 1);

		// Menu
		$this->loader->add_action('wp_before_admin_bar_render', $plugin_admin, 'remove_wp_logo_from_admin_bar', 999);
		$this->loader->add_filter('admin_head', $plugin_admin, 'woocommerce_hide_menu', 999);
		$this->loader->add_filter('admin_head', $plugin_admin, 'menu_woocommerce', 999);
		$this->loader->add_filter('admin_head', $plugin_admin, 'menu_backwpup', 999);
		$this->loader->add_filter('admin_head', $plugin_admin, 'menu_customers', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_mailjet', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_discounts', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_ocean', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_smush', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_kinsta', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_crmperks', 999);
		$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_optinmonster', 999);
		$this->loader->add_filter('menu_order', $plugin_admin, 'menu_order', 999, 1);

		// Polylang
		if (defined('POLYLANG_VERSION')) {
			$this->loader->add_filter('display_post_states', $plugin_admin, 'polylang_display_post_states_language', 10, 2);
			$this->loader->add_action('parse_query', $plugin_admin, 'polylang_elementor_library_conditions_parse_query', 1, 1);
		}

		// WP Rocket
		$this->loader->add_filter('get_rocket_option_wl_plugin_name', $plugin_admin, 'wprocket_name', 10);

		// Content Blocks (formerly Custom Post Widget)
		$this->loader->add_filter('content_block_post_type', $plugin_admin, 'content_block_post_type_public', 10);

		// Gravity Forms
		$this->loader->add_filter( 'gform_enable_field_label_visibility_settings', $plugin_admin, 'gravityforms_label_visibility',
			10 ); // Label Visibility
		$this->loader->add_filter( 'gform_form_settings_fields', $plugin_admin, 'gravityforms_add_custom_settings', 10, 2 );
		$this->loader->add_filter( 'gform_tooltips', $plugin_admin, 'gravityforms_add_custom_tooltips', 10, 1 );
		$this->loader->add_filter( 'gform_get_form_filter', $plugin_admin, 'gravityforms_form_filter', 20, 2 );

		// WooCommerce
		$this->loader->add_filter('woocommerce_mail_callback_params', $plugin_admin, 'woocommerce_mail_callback_params', 20, 2);
		$this->loader->add_filter('woocommerce_show_addons_page', $plugin_admin, 'woocommerce_show_addons_page', 10);
		$this->loader->add_filter('woocommerce_helper_suppress_admin_notices', $plugin_admin, 'woocommerce_helper_suppress_admin_notices', 10);
		$this->loader->add_filter('woocommerce_enable_admin_help_tab', $plugin_admin, 'woocommerce_enable_admin_help_tab');
		$this->loader->add_action('woocommerce_admin_process_product_object', $plugin_admin, 'wprocket_empty_cache_on_save_product');
		$this->loader->add_filter('woocommerce_admin_order_date_format', $plugin_admin, 'woocommerce_admin_order_date_format');
		$this->loader->add_filter('views_edit-shop_order', $plugin_admin, 'woocommerce_rename_views_filters_processing', 50, 1);
		$this->loader->add_filter('wc_order_statuses', $plugin_admin, 'woocommerce_rename_order_statuses_processing', 999, 1);
		$this->loader->add_filter('bulk_actions-edit-shop_order', $plugin_admin, 'woocommerce_rename_bulk_actions_processing', 50, 1);
		$this->loader->add_filter('views_edit-shop_order', $plugin_admin, 'woocommerce_orders_sort_views', 50, 1);
		remove_action('admin_notices', 'woothemes_updater_notice');
		$this->loader->add_action('post_submitbox_misc_actions', $plugin_admin, 'woocommerce_product_report_button', -10, 1);
		$this->loader->add_action('woocommerce_product_duplicate_before_save', $plugin_admin, 'woocommerce_product_duplicate_before_save', 10, 2);
		remove_action('admin_notices', 'woothemes_updater_notice');

		// WooCommerce Emails
		$this->loader->add_filter('woocommerce_email_heading_customer_processing_order', $plugin_admin, 'woocommerce_email_heading_customer_processing_order', 50, 3);
		$this->loader->add_filter('woocommerce_email_subject_customer_processing_order', $plugin_admin, 'woocommerce_email_subject_customer_processing_order', 50, 3);
		$this->loader->add_filter('woocommerce_email_additional_content_customer_processing_order', $plugin_admin, 'woocommerce_email_additional_content_customer_processing_order', 50, 3);

		$this->loader->add_filter('woocommerce_email_heading_customer_completed_order', $plugin_admin, 'woocommerce_email_heading_customer_completed_order', 50, 3);
		$this->loader->add_filter('woocommerce_email_subject_customer_completed_order', $plugin_admin, 'woocommerce_email_subject_customer_completed_order', 50, 3);
		$this->loader->add_filter('woocommerce_email_additional_content_customer_completed_order', $plugin_admin, 'woocommerce_email_additional_content_customer_completed_order', 50, 3);


		// WooCommerce Web Hook Order Paid
		$this->loader->add_filter('woocommerce_webhook_topic_hooks', $plugin_admin, 'woocommerce_webhook_topic_hooks_order_paid', 50, 1);
		$this->loader->add_filter('woocommerce_valid_webhook_events', $plugin_admin, 'woocommerce_valid_webhook_events_paid', 50, 1);
		$this->loader->add_filter('woocommerce_webhook_topics', $plugin_admin, 'woocommerce_webhook_topics_order_paid', 50, 1);
		$this->loader->add_action('admin_head', $plugin_admin, 'woocommerce_menu_order_count');

		// WooCommerce PDF Invoices & Packing Slips
		$this->loader->add_filter('wpo_wcpdf_invoice_title', $plugin_admin, 'wpo_wcpdf_invoice_title', 50, 2);
		$this->loader->add_filter('wpo_wcpdf_process_order_ids', $plugin_admin, 'wpo_wcpdf_process_order_ids_paid', 50, 2);
		$this->loader->add_filter('wpo_wcpdf_template_styles', $plugin_admin, 'wpo_wcpdf_template_styles', 50, 2);
		$this->loader->add_filter('wpo_wcpdf_listing_actions', $plugin_admin, 'wpo_wcpdf_listing_actions', 50, 2);
		$this->loader->add_action('wpo_wcpdf_after_customer_notes', $plugin_admin, 'wpo_wcpdf_after_customer_notes', 100, 2);

		// ACF
		$this->loader->add_action('acf/input/admin_footer', $plugin_admin, 'acf_input_disable_autocomplete');

		// Elementor
		$this->loader->add_action('manage_elementor_library_posts_custom_column', $plugin_admin, 'elementor_admin_columns_content', -10, 2);

		// Yoast
		$this->loader->add_filter('pll_translate_post_meta', $plugin_admin, 'yoast_translate_meta', 50, 3);

		// BackUpWordPress / Fix disk_free_space banned function
		remove_action('admin_init', 'HM\BackUpWordPress\set_server_config_notices');

		// PublishPress Future / Post Expirator
		if (class_exists('PostExpirator_Display')) {
			$publishpressfuture = PostExpirator_Display::getInstance();
			remove_action('admin_menu', array($publishpressfuture, 'add_menu'));
			remove_filter('manage_posts_columns', 'expirationdate_add_column');
			remove_filter('manage_pages_columns', 'expirationdate_add_column_page');
			remove_filter('manage_posts_columns', 'postexpirator_add_column');
			remove_filter('manage_pages_columns', 'postexpirator_add_column_page');

			$this->loader->add_filter('admin_menu', $plugin_admin, 'menu_postexpirator', 999);
			$this->loader->add_filter('display_post_states', $plugin_admin, 'display_post_states_expire', 10, 2);

		}


	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version()
	{
		return $this->version;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Tmsm_Admin_Cleanup_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Tmsm_Admin_Cleanup_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

	}

}
