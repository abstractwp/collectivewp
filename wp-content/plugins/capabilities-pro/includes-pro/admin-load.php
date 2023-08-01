<?php
namespace PublishPress\Capabilities;

/*
 * PublishPress Capabilities Pro
 * 
 * Admin execution controller: menu registration and other filters and actions that need to be loaded for every wp-admin URL
 * 
 * This module should not include full functions related to our own plugin screens.  
 * Instead, use these filter and action handlers to load other classes when needed.
 * 
 */
class AdminFiltersPro {
    function __construct() {
        add_action('init', [$this, 'versionInfoRedirect'], 1);
        add_action('admin_init', [$this, 'loadUpdater']);

        add_action('admin_enqueue_scripts', [$this, 'adminScripts']);

        // Editor Features: Custom Items
        add_action('admin_init', [$this, 'initPostFeatureCustom']);

        $this->initPostFeatureCustom();

        add_action('pp_capabilities_editor_features', [$this, 'editorFeaturesUI']);
        add_action('wp_ajax_ppc_submit_feature_gutenberg_by_ajax', [$this, 'ajaxFeaturesRestrictCustomItem']);
        add_action('wp_ajax_ppc_submit_feature_classic_by_ajax', [$this, 'ajaxFeaturesRestrictCustomItem']);
        add_action('wp_ajax_ppc_delete_custom_post_features_by_ajax', [$this, 'ajaxFeaturesClearCustomItem']);

        // Editor Features: Metaboxes
        add_action('admin_head', [$this, 'initPostFeatureMetaboxes'], 999);

        add_action('publishpress-caps_manager-load', [$this, 'CapsManagerLoad']);
        add_action('admin_print_styles', array($this, 'adminStyles'));

        add_action('admin_init', [$this, 'settingsUI']);
        add_filter('pp_capabilities_settings_options', [$this, 'settingsOption']);

        add_action('publishpress-caps_manager-load', [$this, 'loadStatusesUI']);

        add_action('publishpress-caps_process_update', [$this, 'updateOptions']);

        add_filter('pp_capabilities_sub_menu_lists', [$this, 'actCapabilitiesSubmenus'], 10, 2);

        add_filter('pp_capabilities_dashboard_features', [$this, 'addProFeaturestoDashboard']);

        //set admin menu and sub menu in 'adminmenu' to support custom menu and also get menu correct order
        add_action('adminmenu', [$this, 'setCapabilitiesAdminMenu']);
        //translation
        add_action('init', [$this, 'register_textdomain']);

        //Frontend features pages field
        add_action('pp_capabilities_frontend_features_pages', [$this, 'frontendFeaturesPagesField']);

        //Frontend features post types field
        add_action('pp_capabilities_frontend_features_metabox_post_types', [$this, 'frontendFeaturesPostTypesField']);
    }

	public function register_textdomain() {
        //load pro domain
        $domain       = 'capabilities-pro';
        $mofile_custom = sprintf('%s-%s.mo', $domain, get_user_locale());
        $locations = [
        trailingslashit(WP_LANG_DIR . '/' . $domain),
        trailingslashit(WP_LANG_DIR . '/loco/plugins/'),
        trailingslashit(WP_LANG_DIR),
        trailingslashit(plugin_dir_path(CME_FILE) . 'languages'),
            ];

        // Try custom locations in WP_LANG_DIR.
        foreach ($locations as $location) {
            if (load_textdomain($domain, $location . $mofile_custom)) {
                break;
            }
        }

        //load free domain
        $domain       = 'capsman-enhanced';
        $mofile_custom = sprintf('%s-%s.mo', $domain, get_user_locale());
        $locations = [
        trailingslashit(WP_LANG_DIR . '/' . $domain),
        trailingslashit(WP_LANG_DIR . '/loco/plugins/'),
        trailingslashit(WP_LANG_DIR),
        trailingslashit(plugin_dir_path(CME_FILE) . 'languages'),
            ];

        // Try custom locations in WP_LANG_DIR.
        foreach ($locations as $location) {
            if (load_textdomain($domain, $location . $mofile_custom)) {
                break;
            }
        }
}

    public function adminScripts() {
        global $capsman;

        $url = plugins_url( '', CME_FILE );

        wp_register_style('pp_capabilities_pro_admin', $url . '/includes-pro/common/css/admin.css', false, PUBLISHPRESS_CAPS_VERSION);
        wp_enqueue_style('pp_capabilities_pro_admin');

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.dev' : '';
        $url .= "/includes-pro/common/js/admin{$suffix}.js";
        wp_enqueue_script( 'pp_capabilities_pro_admin', $url, array('jquery'), PUBLISHPRESS_CAPS_VERSION, true );
    }

    public function settingsUI() {
        require_once(dirname(__FILE__).'/settings-ui.php');
        new Pro_Settings_UI();
    }

    public function settingsOption($settings_options) {

        $settings_options[] = 'cme_display_branding';
        $settings_options[] = 'cme_custom_status_control';
        $settings_options[] = 'cme_admin_menus_restriction_priority';

        return $settings_options;
    }

    function actCapabilitiesSubmenus($sub_menu_pages, $cme_fakefunc) {

        if (!$cme_fakefunc) {
            //add admin menu after profile features menu
            $profile_features_offset = array_search('profile-features', array_keys($sub_menu_pages));
            $profile_features_menu   = [];
            $profile_features_menu['admin-menus'] = [
                'title'             => __('Admin Menus', 'capabilities-pro'),
                'capabilities'      => (is_multisite() && is_super_admin()) ? 'read' : 'manage_capabilities_admin_menus',
                'page'              => 'pp-capabilities-admin-menus',
                'callback'          => [$this, 'ManageAdminMenus'],
                'dashboard_control' => true,
            ];

            $sub_menu_pages = array_merge(
                array_slice($sub_menu_pages, 0, $profile_features_offset),
                $profile_features_menu,
                array_slice($sub_menu_pages, $profile_features_offset, null)
            );
        }

        return $sub_menu_pages;
    }

    function addProFeaturestoDashboard($features) {

            //add admin menu after profile features menu
            $profile_features_offset = array_search('profile-features', array_keys($features));
            $admin_menu_feature   = [];
            $admin_menu_feature['admin-menus'] = [
                'label'        => esc_html__('Admin Menus', 'capabilities-pro'),
                'description'  => esc_html__('Admin Menus allows you to block access to admin menu links.', 'capabilities-pro'),
            ];

            $features = array_merge(
                array_slice($features, 0, $profile_features_offset),
                $admin_menu_feature,
                array_slice($features, $profile_features_offset, null)
            );

        return $features;
    }

    /**
	 * Manages admin menu permission
	 *
	 * @hook add_management_page
	 * @return void
	 */
	function ManageAdminMenus ()
	{
        global $capsman;

		if ((!is_multisite() || !is_super_admin()) && !current_user_can('administrator') && !current_user_can('manage_capabilities_admin_menus')) {
            // TODO: Implement exceptions.
		    wp_die('<strong>' . esc_html__('You do not have permission to manage menu restrictions.', 'capabilities-pro') . '</strong>');
		}

		$capsman->generateNames();
		$roles = array_keys($capsman->roles);

		if ( ! isset($capsman->current) ) {
			if (empty($_POST) && !empty($_REQUEST['role'])) {
                $capsman->set_current_role(sanitize_key($_REQUEST['role']));
			}
		}

		if (!isset($capsman->current) || !get_role($capsman->current)) {
			$capsman->current = $capsman->get_last_role();
		}

		if ( ! in_array($capsman->current, $roles) ) {
			$capsman->current = array_shift($roles);
		}

		$ppc_admin_menu_reload = '0';

		if (!empty($_SERVER['REQUEST_METHOD']) && ('POST' == $_SERVER['REQUEST_METHOD']) && isset($_POST['ppc-admin-menu-role']) && !empty($_REQUEST['_wpnonce'])) {
            if (!wp_verify_nonce(sanitize_key($_REQUEST['_wpnonce']), 'pp-capabilities-admin-menus')) {
                wp_die('<strong>' . esc_html__('You do not have permission to manage menu restrictions.', 'capabilities-pro') . '</strong>');
            } else {
                $menu_role = sanitize_key($_POST['ppc-admin-menu-role']);

                $capsman->set_current_role($menu_role);

                //set role admin menu
                $admin_menu_option = !empty(get_option('capsman_admin_menus')) ? get_option('capsman_admin_menus') : [];

                $admin_menu_option[$menu_role] = isset($_POST['pp_cababilities_disabled_menu']) ? array_map('sanitize_text_field', $_POST['pp_cababilities_disabled_menu']) : '';

                //set role admin child menu
                $admin_child_menu_option = !empty(get_option('capsman_admin_child_menus')) ? get_option('capsman_admin_child_menus') : [];
                $admin_child_menu_option[sanitize_key($_POST['ppc-admin-menu-role'])] = isset($_POST['pp_cababilities_disabled_child_menu']) ? array_map('sanitize_text_field', $_POST['pp_cababilities_disabled_child_menu']) : '';

                update_option('capsman_admin_menus', $admin_menu_option, false);
                update_option('capsman_admin_child_menus', $admin_child_menu_option, false);

                //set reload option for menu reflection if user is updating own role
                if(in_array($_POST['ppc-admin-menu-role'], wp_get_current_user()->roles)){
                	$ppc_admin_menu_reload = '1';
                }

                ak_admin_notify(__('Settings updated.', 'capabilities-pro'));
            }
		}

		include ( dirname(__FILE__) . '/admin-menus.php' );
	}

    function versionInfoRedirect() {
        if (!empty($_REQUEST['publishpress_caps_refresh_updates']) && current_user_can('activate_plugins')) { // not a security issue, but prevent status refresh by CSRF
            check_admin_referer('publishpress_caps_refresh_updates');
            
            publishpress_caps_pro()->keyStatus(true);
            set_transient('publishpress-caps-refresh-update-info', true, 86400);

            delete_site_transient('update_plugins');
            delete_option('_site_transient_update_plugins');

            $opt_val = get_option('cme_edd_key');
            if (is_array($opt_val) && !empty($opt_val['license_key'])) {
                $plugin_slug = basename(CME_FILE, '.php'); // 'capabilities-pro';
                $plugin_relpath = basename(dirname(CME_FILE)) . '/' . basename(CME_FILE);
                $license_key = $opt_val['license_key'];
                $beta = false;

                delete_option(md5($plugin_slug . $license_key . $beta));
                delete_option('edd_api_request_' . md5($plugin_slug . $license_key . $beta));
                delete_option(md5('edd_plugin_' . sanitize_key($plugin_relpath) . '_' . $beta . '_version_info'));
            }

            wp_update_plugins();

            if (current_user_can('update_plugins')) {
                $url = admin_url('admin.php?page=pp-capabilities-settings&publishpress_caps_refresh_done=1');
                $url = wp_nonce_url($url, 'publishpress_caps_refresh_updates');
                wp_redirect(esc_url_raw($url));
                exit;
            }
        }

        if (!empty($_REQUEST['amp;publishpress_caps_refresh_done']) && !empty($_REQUEST['amp;_wpnonce'])) {
            $_REQUEST['publishpress_caps_refresh_done'] = (int) $_REQUEST['amp;publishpress_caps_refresh_done'];
            $_REQUEST['_wpnonce'] = sanitize_key($_REQUEST['amp;_wpnonce']);
        }

        if (!empty($_REQUEST['publishpress_caps_refresh_done']) && empty($_POST)) {
            check_admin_referer('publishpress_caps_refresh_updates');

            if (current_user_can('activate_plugins')) {
                $url = admin_url('update-core.php');
                wp_redirect(esc_url_raw($url));
                exit;
            }
        }
    }

    function CapsManagerLoad() {
        require_once(dirname(__FILE__).'/manager-ui.php');
        new ManagerUI();
    }

    function loadUpdater() {
        require_once(PUBLISHPRESS_CAPS_ABSPATH . '/includes-pro/library/Factory.php');
        $container = \PublishPress\Capabilities\Factory::get_container();
        return $container['edd_container']['update_manager'];
    }

    function adminStyles() {
        global $plugin_page;

        if (!empty($plugin_page) && (0 == strpos('pp-capabilities', $plugin_page))) {
            wp_enqueue_style('publishpress-caps-pro', plugins_url( '', CME_FILE ) . '/includes-pro/pro.css', [], PUBLISHPRESS_CAPS_VERSION);
            wp_enqueue_style('publishpress-caps-status-caps', plugins_url( '', CME_FILE ) . '/includes-pro/status-caps.css', [], PUBLISHPRESS_CAPS_VERSION);

            add_thickbox();
        }
    }

    function loadStatusesUI() {
        if (Pro::customStatusPermissionsAvailable() && get_option('cme_custom_status_control')) {
            require_once(dirname(__FILE__).'/admin.php');
            new CustomStatusCapsUI();
        }
    }

    function updateCapabilitiesOptions() {
        check_admin_referer('capsman-general-manager');
        update_option('cme_custom_status_control', (int) !empty($_REQUEST['cme_custom_status_control']));
    }

    function updateOptions() {
        $this->updateCapabilitiesOptions();

        update_option('cme_display_branding', (int) !empty($_REQUEST['cme_display_branding']));
    }

    function editorFeaturesUI() {
        require_once (dirname(__FILE__) . '/features/config/metaboxes-config.php');
        new EditorFeaturesMetaboxesConfig();

        require_once (dirname(__FILE__) . '/features/config/custom-config.php');
        EditorFeaturesCustomConfig::instance();

        ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            jQuery(document).ready(function ($) {

                $('.editor-features-tab').click(function (e) {
                    e.preventDefault();

                    $('.editor-features-custom').hide();
                    var elem = $(this).attr('data-tab') + '-custom';
                    $(elem).show();
                });
            });
            /* ]]> */
        </script>
        <?php
    }

    /**
     * Capture metaboxes for post features
     *
     * @param array $post_types Post type.
     * @param array $elements All elements.
     * @param array $post_disabled All disabled post type element.
     *
     * @since 2.1.1
     */
    function initPostFeatureMetaboxes()
    {
        $screen = get_current_screen();

        if ($screen && !empty($screen->base) && ($screen->base == 'post')) {
            require_once (dirname(__FILE__) . '/features/config/metaboxes-config.php');
            $features_metaboxes = new EditorFeaturesMetaboxesConfig();
            $features_metaboxes->capturePostFeatureMetaboxes($screen->post_type);
        }
    }

    function initPostFeatureCustom() {
        require_once (dirname(__FILE__) . '/features/config/custom-config.php');
        EditorFeaturesCustomConfig::instance();
    }

    /**
     * Ajax callback to add restriction for a custom editor features item.
     *
     * @since 2.1.1
     */
    function ajaxFeaturesRestrictCustomItem()
    {
        require_once (dirname(__FILE__) . '/features/config/custom-config.php');
        EditorFeaturesCustomConfig::addByAjax();
    }

    /**
     * Ajax callback to delete custom-added editor features item restriction.
     *
     * @since 2.1.1
     */
    function ajaxFeaturesClearCustomItem()
    {
        require_once (dirname(__FILE__) . '/features/config/custom-config.php');
        EditorFeaturesCustomConfig::deleteByAjax();
    }

    /**
     * Set admin menu and sub menu in 'adminmenu' to support custom menu and also get menu correct order
     * 
     * I initially set global item here but it has the following limitations:
     * - Restricted menu are not showing in 'Admin menu Restrictions' screen if admin restrict his role
     * - On the pp_capabilities_admin_menu_permission() function, custom menu are not available there
     * 
     * So, storing the data in adminmenu that has complete data is our best option so far.
     *
     * @since 2.3.1
     */
    function setCapabilitiesAdminMenu()
    {
        global $menu, $submenu, $ppc_global_menu, $ppc_global_submenu;

        $ppc_global_menu    = $menu;
        $ppc_global_submenu = $submenu;

        // we only want to update complete menu and on capablities page where menu is not restricted
        if ( current_user_can('manage_capabilities_admin_menus') && isset($_GET['page']) && $_GET['page'] === 'pp-capabilities-admin-menus') 
        {
            if ( get_option('ppc_admin_menus_menu') !== $menu) {//save menu
                update_option('ppc_admin_menus_menu', $menu);
            }
            if ( get_option('ppc_admin_menus_submenu') !== $submenu) {//save submenu
                update_option('ppc_admin_menus_submenu', $submenu);
            }
        }
    }

    /**
     * Frontend features pages field
     *
     * @since 2.9.0
     */
    function frontendFeaturesPagesField()
    {
        $options = [
          'homepage'      => esc_html__('Homepage', 'capabilities-pro'),
          'archive_pages' => esc_html__('Archive Pages', 'capabilities-pro'),
          'single_pages'  => esc_html__('Single Pages', 'capabilities-pro')
        ];
        ?>
        <select class="frontend-element-new-element-pages chosen-cpt-select frontendelements-form-pages" data-placeholder="<?php esc_attr_e('Select pages...', 'capabilities-pro'); ?>" multiple>
            <?php foreach ($options as $value => $label) : ?>
                <option value="<?php echo esc_attr($value); ?>">
                    <?php echo esc_html($label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br />
        <small>
            <?php esc_html_e('You can select page types where this element will be added.', 'capabilities-pro'); ?>
        </small>
    <?php 
    }

    /**
     * Frontend features post types field
     *
     * @since 2.9.0
     */
    function frontendFeaturesPostTypesField()
    { 
        ?>
        <select class="frontend-element-new-element-post-types chosen-cpt-select frontendelements-form-post-types"
            data-placeholder="<?php esc_attr_e('Select post types...', 'capabilities-pro'); ?>"
            multiple>
            <?php foreach (get_post_types(['public' => true], 'objects') as $name => $post_type) :
                if ($name === 'attachment') {
                    continue;
                } ?>
                <option
                    value="<?php echo esc_attr($name); ?>">
                    <?php echo esc_html($post_type->label); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br />
         <small>
            <?php esc_html_e('This will add a metabox on the post editing screen. You can use this feature to add body classes only for that post.', 'capabilities-pro'); ?>
        </small>
    <?php
    }
}