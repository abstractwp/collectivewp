<?php
namespace PublishPress\Capabilities;

class AdminFeaturesBlockUrl {
    private static $instance = null;

    public static function instance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new AdminFeaturesBlockUrl();
        }

        return self::$instance;
    }

    function __construct() {
        //add block by url to admin features element
        add_filter('pp_capabilities_admin_features_elements', [$this, 'blockUrlElements'], 50);
        //add block by url section icon
        add_filter('pp_capabilities_admin_features_icons', [$this, 'blockUrlSectionIcon']);
        //add form element to admin features table bottom
        add_action('pp_capabilities_admin_features_blockedbyurl_before_subsection_tr', [$this, 'blockUrlAddForm']);
        //ajax handler for url block new entry submission
        add_action('wp_ajax_ppc_submit_feature_blocked_url_by_ajax', [$this, 'blockUrlNewEntryAjaxHandler']);
        //ajax handler for deleting blocked url item
        add_action('wp_ajax_ppc_delete_feature_blocked_url_item_by_ajax', [$this, 'blockUrlDeleteItemAjaxHandler']);
        //block access to url pages
        add_action('ppc_admin_feature_restriction', [$this, 'blockUrlRestrictPages']);
    }

    /**
     * Fetch admin features blocked url options.
     *
     * @return mixed
     *
     * @since 2.3.1
     */
    public static function getData()
    {
        $data = (array)get_option('ppc_admin_feature_block_url_custom_data');
        $data = array_filter($data);

        return $data;
    }


    /**
     * Admin features icon filter
     *
     * @param array $icons admin features screen elements
     *
     * @return array $icons updated icon list
     *
     * @since 2.3.1
     */
    function blockUrlSectionIcon($icons) {

        $icons['blockedbyurl']     = 'admin-links';

        return $icons;
    }


    /**
     * Block by url admin features element filter
     *
     * @param array $element admin features screen elements
     *
     * @return array
     *
     * @since 2.3.1
     */
    function blockUrlElements($elements) {
        $data = self::getData();
        $added_element = [];

        if (count($data) > 0) {
            foreach ($data as $name => $restrict_data) {
                $added_element[$name] = [
                    'label'          => $restrict_data['label'],
                    'action'         => 'ppc_blocked_url',
                    'elements'       => $restrict_data['elements'],
                    'custom_element' => true,
                    'button_class'   => 'ppc-custom-features-url-delete red-pointer',
                    'button_data_id' => $name,
                    'element_label'  => $restrict_data['label'],
                    'element_items'  => self::cleanCustomUrl($restrict_data['elements']),
                ];
            }
        }

        $elements[__('Blocked by URL', 'capabilities-pro')] = $added_element;

        return $elements;
    }



    /**
     * Add form element to admin features table bottom
     *
     * @since 2.3.1
     */
    public function blockUrlAddForm() {
        ?>
        <tr class="ppc-menu-row child-menu blockedbyurl">
            <td colspan="2" class="form-td">
                <table class="editor-features-custom admin-features-block-url-form">
                <tr class="ppc-menu-row parent-menu ppc-add-custom-row-header">
                    <td>
                        <p class="cme-subtext"><?php esc_html_e('Enter URL to be blocked by role:', 'capabilities-pro');?>
                        </p>
                        </h4>
                    </td>
                </tr>

                <tr class="ppc-add-custom-row-body">
                    <td>
                        <div class="left">
                        <?php esc_html_e('Label', 'capabilities-pro'); ?> <font color="red">*</font>
                        <input class="ppc-feature-block-url-new-name" type="text"/>
                        <small><?php esc_html_e('Enter the name/label to identify the element under Blocked by URL section of this screen.',
                                'capabilities-pro'); ?></small>
                        </div>

                        <div class="right">
                        <?php esc_html_e('URLs', 'capabilities-pro'); ?> <font color="red">*</font>
                        <div><textarea class="ppc-feature-block-url-new-link"></textarea>
                            <input class="ppc-feature-submit-form-nonce" type="hidden"
                                    value="<?php echo esc_attr(wp_create_nonce('ppc-custom-feature-nonce')); ?>"/>
                            <button type="button" class="ppc-feature-block-url-new-submit button button-secondary"><?php esc_html_e('Add', 'capabilities-pro'); ?></button>
                            <span class="ppc-feature-post-loader spinner"></span>
                        </div>
                        <div>
                            <small><?php
                                $sample_url_one = admin_url('plugins.php');
                                $sample_url_two = admin_url('profile.php');
                                printf(esc_html__('Separate multiple urls by comma. (e.g, %1$s, %2$s).',
                                    'capabilities-pro'), esc_url_raw($sample_url_one), esc_url_raw($sample_url_two)); ?></small>
                        </div>
                        </div>
                        <div class="ppc-post-features-note"></div>

                    </td>
                </tr>
                </table>
            </td>
        </tr>
        <?php
    }

    /**
     * Ajax handler for deleting blocked url item
     *
     * @since 3.3.1
     */
    public static function blockUrlNewEntryAjaxHandler()
    {
        $response['status']  = 'error';
        $response['message'] = __('An error occured!', 'capabilities-pro');
        $response['content'] = '';

        $custom_label   = isset($_POST['custom_label']) ? sanitize_text_field($_POST['custom_label']) : '';
        $custom_link    = isset($_POST['custom_link']) ? sanitize_textarea_field($_POST['custom_link']) : '';
        $security       = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if (!wp_verify_nonce($security, 'ppc-custom-feature-nonce')) {
            $response['message'] = __('Invalid action. Reload this page and try again.', 'capabilities-pro');
        } elseif (empty(trim($custom_label)) || empty(trim($custom_link))) {
            $response['message'] = __('All fields are required.', 'capabilities-pro');
        } else {
            $element_id       = uniqid(true);
            $data             = self::getData();
            $data[$element_id]= ['label' => $custom_label, 'elements' => $custom_link];
            update_option('ppc_admin_feature_block_url_custom_data', $data);

            $response['message'] = __('New custom item added successfully', 'capabilities-pro');
            $response['status']  = 'success';


            $response_content    = '<tr class="ppc-menu-row child-menu ppc-menu-overlay-item blockedbyurl">

                <td class="restrict-column ppc-menu-checkbox">
                    <input id="check-item-'. $element_id .'" class="check-item" type="checkbox" name="capsman_disabled_admin_features[]" checked value="ppc_blocked_url||' . $element_id . '">
                </td>
                <td class="menu-column ppc-menu-item">

                    <label for="check-item-'. $element_id .'">
                        <span class="menu-item-link">
                        <strong> &mdash;  ' . $custom_label . ' <small class="entry">(' . self::cleanCustomUrl($custom_link) . ')</small> &nbsp;
                        <span class="ppc-custom-features-url-delete red-pointer" data-id="' . $element_id . '"><small>(' . __('Delete', 'capabilities-pro') . ')</small></span> </strong></span>
                    </label>
                </td>
            </tr>';

            $response['content'] = $response_content;
        }

        wp_send_json($response);
    }

    /**
     * Delete custom added post features item ajax callback.
     *
     * @since 2.1.1
     */
    public static function blockUrlDeleteItemAjaxHandler()
    {
        $response = [];
        $response['status']  = 'error';
        $response['message'] = __('An error occured!', 'capabilities-pro');
        $response['content'] = '';

        $delete_id     = isset($_POST['delete_id']) ? sanitize_key($_POST['delete_id']) : '';
        $security      = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if (!wp_verify_nonce($security, 'ppc-custom-feature-nonce')) {
            $response['message'] = __('Invalid action. Reload this page and try again.', 'capabilities-pro');
        } elseif (empty(trim($delete_id))) {
            $response['message'] = __('Invalid request!.', 'capabilities-pro');
        } else {
            $data = self::getData();
            if (array_key_exists($delete_id, $data)) {
                unset($data[$delete_id]);
                update_option('ppc_admin_feature_block_url_custom_data', $data);
            }
            $response['status']  = 'success';
            $response['message'] = __('Selected item deleted successfully', 'capabilities-pro');
        }

        wp_send_json($response);
    }

    /**
     * Block access to url pages
     *
     * @param array $disabled_elements
     *
     * @since 3.3.1
     */
    public static function blockUrlRestrictPages($disabled_elements)
    {
        if(!is_admin()){//this feature block is only restricted to admin area
            return;
        }

        //return if it's ajax request
        if (defined('DOING_AJAX') && DOING_AJAX) {
            return;
        }

        //Block people from locking themselves out of "Admin Features" #278
        if (current_user_can('manage_options') && isset($_GET['page']) && $_GET['page'] === 'pp-capabilities-admin-features') {
            return;
        }

        //get element related to block url alone
        $data_key = 'ppc_blocked_url';
        $ppc_blocked_url    = array_filter(
			$disabled_elements,
			function($value, $key) use ($data_key) {return strpos($value, $data_key) === 0;}, ARRAY_FILTER_USE_BOTH
		);

        if(count($ppc_blocked_url) > 0){
            $data = self::getData();
            $blocked_urls = [];
            foreach($ppc_blocked_url as $blocked_element){
                $blocked_element = str_replace($data_key.'||', '', $blocked_element);
                if (array_key_exists($blocked_element, $data)) {
                    $blocked_urls[] = explode (",", $data[$blocked_element]['elements']);//merge multiple url into array
                }
            }

            if ($blocked_urls) {
	            //merge all array into one
	            $blocked_urls = call_user_func_array('array_merge', $blocked_urls);

	            //trim any excess white space in the array values
	            $blocked_urls = array_map('trim', $blocked_urls);

                $page_access_forbidden = false;
                foreach($blocked_urls as $blocked_url){
                    $current_url_data = self::parsePageUrlData(self::currentPageUrl());
                    $blocked_url_data = self::parsePageUrlData($blocked_url);
                    $interceptions = array_intersect($current_url_data, $blocked_url_data);

                    //Stop users from adding characters to avoid URL blocks #279
                    if (array_values($blocked_url_data) === array_values($interceptions)) {
                        $page_access_forbidden = true;
                    }
                }

	            //block access to current page if part of
	            if ($page_access_forbidden){
	                add_action('init', function() {
	                    $forbidden = esc_attr__('You do not have permission to access this page.', 'capabilities-pro');
	                    wp_die(esc_html($forbidden));
	                }, 1);
                }
            }

        }
    }


    /**
     * Parse URL and return it data
     *
     * @param string  $url
     * @return array $url_data
     */
    public static function parsePageUrlData($url)
    {
        $parts = parse_url($url);
        $url_data = [];

        if (isset($parts['host'])) {
            $url_data[] = $parts['host'];
        }

        if (isset($parts['path'])) {
            $url_data[] = $parts['path'];
        }

        if (isset($parts['query'])) {
            $url_data = array_merge($url_data, explode("&", $parts['query']));
        }

        return $url_data;
    }

    /**
     * Clean custom URL by removing website link from it
     *
     * @param string $urls
     *
     * @return string
     *
     * @since 3.3.1
     */
    public static function cleanCustomUrl($urls)
    {
        $home_url = home_url();

        return str_replace($home_url, '', $urls);
    }


    /**
     * Rereive current page url
     *
     * @return string
     *
     * @since 3.3.1
     */
    public static function currentPageUrl()
    {
        if (!empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
            return esc_url_raw((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        } else {
            return admin_url('');
        }
    }

}
