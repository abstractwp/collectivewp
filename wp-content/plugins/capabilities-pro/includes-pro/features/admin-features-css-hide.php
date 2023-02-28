<?php
namespace PublishPress\Capabilities;

class AdminFeaturesCssHide {
    private static $instance = null;

    public static function instance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new AdminFeaturesCssHide();
        }

        return self::$instance;
    }

    function __construct() {
        //add hide css element to admin features element
        add_filter('pp_capabilities_admin_features_elements', [$this, 'cssHideElements'], 50);
        //add hide css section icon
        add_filter('pp_capabilities_admin_features_icons', [$this, 'cssHideSectionIcon']);
        //add form css hide element to admin features table bottom
        add_action('pp_capabilities_admin_features_hidecsselement_before_subsection_tr', [$this, 'cssHideAddForm']);
        //ajax handler for hide css new entry submission
        add_action('wp_ajax_ppc_submit_feature_css_hide_by_ajax', [$this, 'cssHideNewEntryAjaxHandler']);
        //ajax handler for deleting css hide item
        add_action('wp_ajax_ppc_delete_feature_css_hide_item_by_ajax', [$this, 'cssHideDeleteItemAjaxHandler']);
        //Add hidden css element styles to admin pages
        add_action('ppc_admin_feature_restriction', [$this, 'cssHideAddStyles']);
    }

    /**
     * Fetch admin features css hide options.
     *
     * @return mixed
     *
     * @since 2.3.1
     */
    public static function getData()
    {
        $data = (array)get_option('ppc_admin_feature_css_hide_custom_data');
        $data = array_filter($data);

        return $data;
    }


    /**
     * Add hide css section icon
     *
     * @param array $icons admin features screen elements
     *
     * @return array $icons updated icon list
     *
     * @since 2.3.1
     */
    function cssHideSectionIcon($icons) {

        $icons['hidecsselement']     = 'hidden';

        return $icons;
    }


    /**
     * Add hide css element to admin features element
     *
     * @param array $element admin features screen elements
     *
     * @return array
     *
     * @since 2.3.1
     */
    function cssHideElements($elements) {
        $data = self::getData();
        $added_element = [];

        if (count($data) > 0) {
            foreach ($data as $name => $restrict_data) {
                $added_element[$name] = [
                    'label'          => $restrict_data['label'],
                    'action'         => 'ppc_hidden_css',
                    'elements'       => $restrict_data['elements'],
                    'custom_element' => true,
                    'button_class'   => 'ppc-custom-features-css-delete red-pointer',
                    'button_data_id' => $name,
                    'element_label'  => $restrict_data['label'],
                    'element_items'  => $restrict_data['elements'],
                ];
            }
        }

        $elements[esc_html__('Hide CSS Element', 'capabilities-pro')] = $added_element;

        return $elements;
    }



    /**
     * Add form css hide element to admin features table bottom
     *
     * @since 2.3.1
     */
    public function cssHideAddForm() {
        ?>
        <tr class="ppc-menu-row child-menu hidecsselement">
            <td colspan="2" class="form-td">
                <table class="editor-features-custom admin-features-css-hide-form">
                <tr class="ppc-menu-row parent-menu ppc-add-custom-row-header">
                    <td>
                        <p class="cme-subtext"><?php esc_html_e('You can remove other elements from admin area by adding their IDs or classes below:', 'capabilities-pro');?>
                        </p>
                        </h4>
                    </td>
                </tr>

                <tr class="ppc-add-custom-row-body">
                    <td>
                        <div class="left">
                        <?php esc_html_e('Label', 'capabilities-pro'); ?> <font color="red">*</font>
                        <input class="ppc-feature-css-hide-new-name" type="text"/>
                        <small><?php esc_html_e('Enter the name/label to identify the custom element on this screen.',
                                'capabilities-pro'); ?></small>
                        </div>

                        <div class="right">
                        <?php esc_html_e('Element IDs or Classes', 'capabilities-pro'); ?> <font color="red">*</font>
                        <div><textarea class="ppc-feature-css-hide-new-element"></textarea>
                            <input class="ppc-feature-submit-form-nonce" type="hidden"
                                    value="<?php echo esc_attr(wp_create_nonce('ppc-custom-feature-nonce')); ?>"/>
                            <button type="button" class="ppc-feature-css-hide-new-submit button button-secondary"><?php esc_html_e('Add', 'capabilities-pro'); ?></button>
                            <span class="ppc-feature-post-loader spinner"></span>
                        </div>
                        <div>
                            <small><?php esc_html_e('IDs or classes to hide. Separate multiple values by comma (.custom-item-one, .custom-item-two, #new-item-id).',
                                'capabilities-pro'); ?></small>
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
     * Ajax handler for hide css new entry submission
     *
     * @since 3.3.1
     */
    public static function cssHideNewEntryAjaxHandler()
    {
        $response['status']  = 'error';
        $response['message'] = esc_html__('An error occured!', 'capabilities-pro');
        $response['content'] = '';

        $custom_label   = isset($_POST['custom_label']) ? sanitize_text_field($_POST['custom_label']) : '';
        $custom_element = isset($_POST['custom_element']) ? sanitize_textarea_field($_POST['custom_element']) : '';
        $security       = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if (!wp_verify_nonce($security, 'ppc-custom-feature-nonce')) {
            $response['message'] = esc_html__('Invalid action. Reload this page and try again.', 'capabilities-pro');
        } elseif (empty(trim($custom_label)) || empty(trim($custom_element))) {
            $response['message'] = esc_html__('All fields are required.', 'capabilities-pro');
        } else {
            $element_id       = uniqid(true);
            $data             = self::getData();
            $data[$element_id]= ['label' => $custom_label, 'elements' => $custom_element];
            update_option('ppc_admin_feature_css_hide_custom_data', $data);


            $response['message'] = esc_html__('New CSS element added. Save changes to apply restrictions.', 'capabilities-pro');
            $response['status']  = 'success';


            $response_content    = '<tr class="ppc-menu-row child-menu ppc-menu-overlay-item hidecsselement">

                <td class="restrict-column ppc-menu-checkbox">
                    <input id="check-item-'. esc_attr($element_id) .'" class="check-item" type="checkbox" name="capsman_disabled_admin_features[]" checked value="ppc_hidden_css||' . esc_attr($element_id) . '">
                </td>
                <td class="menu-column ppc-menu-item">

                    <label for="check-item-'. esc_attr($element_id) .'">
                        <span class="menu-item-link">
                        <strong> &mdash;  ' . esc_html($custom_label) . ' <small class="entry">(' . esc_html($custom_element) . ')</small> &nbsp;
                        <span class="ppc-custom-features-css-delete red-pointer" data-id="' . esc_attr($element_id) . '"><small>(' . esc_html__('Delete', 'capabilities-pro') . ')</small></span> </strong></span>
                    </label>
                </td>
            </tr>';

            $response['content'] = $response_content;
        }

        wp_send_json($response);
    }

    /**
     * Ajax handler for deleting css hide item.
     *
     * @since 2.1.1
     */
    public static function cssHideDeleteItemAjaxHandler()
    {
        $response = [];
        $response['status']  = 'error';
        $response['message'] = esc_html__('An error occured!', 'capabilities-pro');
        $response['content'] = '';

        $delete_id     = isset($_POST['delete_id']) ? sanitize_key($_POST['delete_id']) : '';
        $security      = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if (!wp_verify_nonce($security, 'ppc-custom-feature-nonce')) {
            $response['message'] = esc_html__('Invalid action. Reload this page and try again.', 'capabilities-pro');
        } elseif (empty(trim($delete_id))) {
            $response['message'] = esc_html__('Invalid request!.', 'capabilities-pro');
        } else {
            $data = self::getData();
            if (array_key_exists($delete_id, $data)) {
                unset($data[$delete_id]);
                update_option('ppc_admin_feature_css_hide_custom_data', $data);
            }
            $response['status']  = 'success';
            $response['message'] = esc_html__('Selected item deleted successfully', 'capabilities-pro');
        }

        wp_send_json($response);
    }

    /**
     * Add hidden css element styles to admin pages
     *
     * @param array $disabled_elements
     *
     * @since 3.3.1
     */
    public static function cssHideAddStyles($disabled_elements)
    {
        global $css_hidden_element;

        if (empty($css_hidden_element)) {
            $css_hidden_element = [];
        }

        if(!is_admin()){//this feature block is only restricted to admin area
            return;
        }

        //get element related to css hide alone
        $data_key = 'ppc_hidden_css';
        $ppc_hidden_css    = array_filter(
			$disabled_elements,
			function($value, $key) use ($data_key) {return strpos($value, $data_key) === 0;}, ARRAY_FILTER_USE_BOTH
		);

        if(count($ppc_hidden_css) > 0){
            $data = self::getData();
            $css_hide = [];
            foreach($ppc_hidden_css as $blocked_element){
                $blocked_element = str_replace($data_key.'||', '', $blocked_element);
                if (array_key_exists($blocked_element, $data)) {
                    $css_elements = explode (",", $data[$blocked_element]['elements']);
                    $multiple_elements = [];
                    foreach ($css_elements as $css_element) {
                        //check if it's a plugin element
                        if (count(explode('/', $css_element)) === 2) {
                            $multiple_elements[] = '[data-plugin="'. trim($css_element) .'"]';
                        } else {
                            $multiple_elements[] = trim($css_element);
                        }
                    }
                    $css_hidden_element[] = $multiple_elements;//merge multiple element into array
                }
            }
            //merge all array into one
            if ($css_hidden_element) {
                $css_hidden_element = call_user_func_array('array_merge', $css_hidden_element);
            }
            ppc_add_inline_style('' . implode(',', $css_hidden_element) . ' {display:none !important;}');
        }
    }

}
