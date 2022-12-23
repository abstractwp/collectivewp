<?php
namespace PublishPress\Capabilities;

class EditorFeaturesCustomConfig {
    private static $instance = null;

    public static function instance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new EditorFeaturesCustomConfig();
        }

        return self::$instance;
    }

    function __construct() {
        require_once (dirname(CME_FILE) . '/includes-pro/features/custom.php');
        EditorFeaturesCustom::instance();

        add_action('pp_capabilities_features_classic_after_table', [$this, 'customAddFormClassic']);
        add_action('pp_capabilities_features_gutenberg_after_table', [$this, 'customAddForm']);
    }

    public function customAddFormClassic() {
        ?>
        <table class="editor-features-custom editor-features-classic-custom" <?php if (empty($_REQUEST['ppc-tab']) || ('classic' != $_REQUEST['ppc-tab'])):?>style="display:none"<?php endif;?>>

        <tr class="ppc-menu-row parent-menu ppc-add-custom-row-header">
            <td>
                <p class="cme-subtext"><?php _e('You can remove other elements from the editor screen by adding their IDs or classes below:', 'capsman-enhanced');?>
                </p>
            </td>
        </tr>

        <tr class="ppc-add-custom-row-body">
            <td>

                <div class="left">
                <?php esc_html_e('Label', 'capsman-enhanced'); ?> <font color="red">*</font>
                <input class="ppc-feature-classic-new-name" type="text"/>
                <small><?php esc_html_e('Enter the name/label to identify the custom element on this screen.',
                        'capsman-enhanced'); ?></small>
                </div>

                <div class="right">
                <?php esc_html_e('Element IDs or Classes', 'capsman-enhanced'); ?> <font color="red">*</font>
                <div><textarea class="ppc-feature-classic-new-ids"></textarea>
                    <input class="ppc-feature-submit-form-nonce" type="hidden"
                           value="<?php echo esc_attr(wp_create_nonce('ppc-custom-feature-nonce')); ?>"/>
                    <button type="button" class="ppc-feature-classic-new-submit button button-secondary"><?php echo esc_html__('Add','capsman-enhanced'); ?></button>
                    <span class="ppc-feature-post-loader spinner"></span>
                </div>
                <div>
                    <small><?php esc_html_e('IDs or classes to hide. Separate multiple values by comma (.custom-item-one, .custom-item-two, #new-item-id).',
                            'capsman-enhanced'); ?></small>
                </div>
                </div>
                <div class="ppc-post-features-note"></div>
            </td>
        </tr>

        </table>
        <?php
    }

    public function customAddForm() {
        ?>
        <table class="editor-features-custom editor-features-gutenberg-custom" <?php if (!empty($_REQUEST['ppc-tab']) && ('gutenberg' != $_REQUEST['ppc-tab'])):?>style="display:none"<?php endif;?>>
        <tr class="ppc-menu-row parent-menu ppc-add-custom-row-header">
            <td>
                <p class="cme-subtext"><?php _e('You can remove other elements from the editor screen by adding their IDs or classes below:', 'capsman-enhanced');?>
                </p>
            </td>
        </tr>

        <tr class="ppc-add-custom-row-body">
            <td>

                <div class="left">
                <?php esc_html_e('Label', 'capsman-enhanced'); ?> <font color="red">*</font>
                <input class="ppc-feature-gutenberg-new-name" type="text"/>
                <small><?php esc_html_e('Enter the name/label to identify the custom element on this screen.',
                        'capsman-enhanced'); ?></small>
                </div>

                <div class="right">
                <?php esc_html_e('Element IDs or Classes', 'capsman-enhanced'); ?> <font color="red">*</font>
                <div><textarea class="ppc-feature-gutenberg-new-ids"></textarea>
                    <input class="ppc-feature-submit-form-nonce" type="hidden"
                            value="<?php echo esc_attr(wp_create_nonce('ppc-custom-feature-nonce')); ?>"/>
                    <button type="button" class="ppc-feature-gutenberg-new-submit button button-secondary"><?php esc_html_e('Add','capsman-enhanced'); ?></button>
                    <span class="ppc-feature-post-loader spinner"></span>
                </div>
                <div>
                    <small><?php esc_html_e('IDs or classes to hide. Separate multiple values by comma (.custom-item-one, .custom-item-two, #new-item-id).',
                            'capsman-enhanced'); ?></small>
                </div>
                </div>
                <div class="ppc-post-features-note"></div>

            </td>
        </tr>
        </table>
        <?php
    }

    /**
     * Submit new item for editor feature ajax callback.
     *
     * @since 2.1.1
     */
    public static function addByAjax()
    {
        $response['status']  = 'error';
        $response['message'] = __('An error occured!', 'capabilities-pro');
        $response['content'] = '';

        $def_post_types = apply_filters('pp_capabilities_feature_post_types', ['post', 'page']);

        $custom_label   = isset($_POST['custom_label']) ? sanitize_text_field($_POST['custom_label']) : '';
        $custom_element = isset($_POST['custom_element']) ? sanitize_textarea_field($_POST['custom_element']) : '';
        $action         = isset($_POST['action']) ? sanitize_key($_POST['action']) : '';
        $security       = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if (!wp_verify_nonce($security, 'ppc-custom-feature-nonce')) {
            $response['message'] = __('Invalid action. Reload this page and try again if occured in error.', 'capabilities-pro');
        } elseif (empty(trim($custom_label)) || empty(trim($custom_element))) {
            $response['message'] = __('All fields are required.', 'capabilities-pro');
        } else {
            $element_id = uniqid(true);
            if ($action === 'ppc_submit_feature_gutenberg_by_ajax') {
                $data_parent       = 'gutenberg';
                $data_name_prefix  = 'capsman_feature_restrict_';
                $data              = EditorFeaturesCustom::getData();
                $data[$element_id] = ['label' => $custom_label, 'elements' => $custom_element];
                update_option('ppc_feature_post_gutenberg_custom_data', $data);
            } elseif ($action === 'ppc_submit_feature_classic_by_ajax') {
                $data_parent       = 'classic';
                $data_name_prefix  = 'capsman_feature_restrict_classic_';
                $data              = EditorFeaturesCustom::getClassicData();
                $data[$element_id] = ['label' => $custom_label, 'elements' => $custom_element];
                update_option('ppc_feature_post_classic_custom_data', $data);
            }

            if (!empty($action)) {
                $response['message'] = __('New custom item added. Save changes to apply restrictions.', 'capabilities-pro');
                $response['status']  = 'success';
                $response_content    = [
                    'custom_label'      => $custom_label,
                    'custom_element'    => $custom_element,
                    'element_id'        => $element_id,
                    'data_parent'       => $data_parent,
                    'delete_text'       => esc_html__('Delete', 'capabilities-pro'),
                    'data_parent'       => $data_parent,
                    'data_name_prefix'   => $data_name_prefix
                ];

                $response['content'] = $response_content;
            }
        }

        wp_send_json($response);
    }

    /**
     * Delete custom added post features item ajax callback.
     *
     * @since 2.1.1
     */
    public static function deleteByAjax()
    {
        $response = [];
        $response['status']  = 'error';
        $response['message'] = __('An error occured!', 'capsman-enhanced');
        $response['content'] = '';

        $delete_id     = isset($_POST['delete_id']) ? sanitize_key($_POST['delete_id']) : '';
        $delete_parent = isset($_POST['delete_parent']) ? sanitize_key($_POST['delete_parent']) : '';
        $security      = isset($_POST['security']) ? sanitize_key($_POST['security']) : '';

        if (!wp_verify_nonce($security, 'ppc-custom-feature-nonce')) {
            $response['message'] = __('Invalid action. Reload this page and try again if occured in error.',
                'capsman-enhanced');
        } elseif (empty(trim($delete_id)) || empty(trim($delete_parent))) {
            $response['message'] = __('Invalid request!.', 'capsman-enhanced');
        } else {

            if ($delete_parent === 'gutenberg') {
                $data = EditorFeaturesCustom::getData();
                if (array_key_exists($delete_id, $data)) {
                    unset($data[$delete_id]);
                    update_option('ppc_feature_post_gutenberg_custom_data', $data);
                }
            } elseif ($delete_parent === 'classic') {
                $data = EditorFeaturesCustom::getClassicData();
                if (array_key_exists($delete_id, $data)) {
                    unset($data[$delete_id]);
                    update_option('ppc_feature_post_classic_custom_data', $data);
                }
            }

            if (!empty($delete_parent)) {
                $response['status']  = 'success';
                $response['message'] = __('Selected item deleted successfully', 'capsman-enhanced');
            }
        }

        wp_send_json($response);
    }
}
