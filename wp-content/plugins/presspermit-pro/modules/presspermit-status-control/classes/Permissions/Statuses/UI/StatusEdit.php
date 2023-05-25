<?php
namespace PublishPress\Permissions\Statuses\UI;



/**
 * Edit Custom Post Status admin panel
 */

class StatusEdit 
{
    function __construct() {
        // This script executes on admin.php plugin page load (called by Dashboard\DashboardFilters::actMenuHandler)
        //
        
        $this->display();
    }

    private function display() {
        $url = apply_filters('presspermit_conditions_base_url', 'admin.php');

        if ($wp_http_referer = presspermit_REQUEST_var('wp_http_referer')) {
            $wp_http_referer = esc_url_raw($wp_http_referer);
        } elseif ($http_referer = presspermit_SERVER_var('HTTP_REFERER')) {
            if (!strpos(esc_url_raw($http_referer), 'page=presspermit-status-new')) {
                $wp_http_referer = esc_url_raw($http_referer);
            }

            $wp_http_referer = remove_query_arg(['update', 'edit', 'delete_count'], stripslashes($wp_http_referer));
        } else {
            $wp_http_referer = '';
        }
        ?>

        <?php
        $attribute = 'post_status';
        $attrib_type = presspermit_REQUEST_key('attrib_type');

        if (!current_user_can('pp_define_post_status') && (!$attrib_type || !current_user_can("pp_define_{$attrib_type}")))
            wp_die(esc_html__('You are not permitted to do that.', 'presspermit-pro'));

        if (!$status = presspermit_REQUEST_var('status')) {
            wp_die('No status specified.');
        }

        global $wp_post_statuses;
        $status_obj = (!empty($wp_post_statuses[$status])) ? $wp_post_statuses[$status] : false;

        if (!$status_obj)
            wp_die('Status does not exist.');

        if ($status_obj->private)
            $attrib_type = 'private';
        elseif (!empty($status_obj->moderation))
            $attrib_type = 'moderation';

        $admin = presspermit()->admin();

        if ((!presspermit_empty_POST() || presspermit_is_GET('update')) && empty($admin->errors)) :
            $url = add_query_arg(['page' => 'presspermit-statuses', 'attrib_type' => $attrib_type], admin_url(apply_filters('presspermit_role_usage_base_url', 'admin.php')));
            ?>
            <div id="message" class="updated">
                <p><strong><?php esc_html_e('Status updated.', 'presspermit-pro') ?>&nbsp;</strong>
                    <?php if ($wp_http_referer) : ?>
                        <a href="<?php echo esc_url($url); ?>"><?php esc_html_e('Back to Statuses', 'presspermit-pro'); ?></a>
                    <?php endif; ?>
                </p></div>
        <?php endif; ?>

        <?php
        if (!empty($admin->errors) && is_wp_error($admin->errors)) : ?>
            <div class="error">
            <?php 
            foreach($admin->errors->get_error_messages() as $msg) {
                echo '<p>' . esc_html($msg) . '</p>';
            }
            ?>
            </div>
        <?php endif; ?>

            <div class="wrap pressshack-admin-wrapper" id="condition-profile-page">
                <header>
                <?php \PublishPress\Permissions\UI\PluginPage::icon(); ?>
                <h1><?php echo esc_html(__('Edit Post Status', 'presspermit-pro'));
                    ?></h1>
                </header>

                <form action="" method="post" id="editcondition" name="editcondition"
                    class="pp-admin" <?php do_action('presspermit_condition_create_form_tag'); ?>>
                    <input name="action" type="hidden" value="update"/>
                    <input name="pp_attribute" type="hidden" value="<?php echo esc_attr($attribute); ?>"/>
                    <input name="attrib_type" type="hidden" value="<?php echo esc_attr($attrib_type); ?>"/>
                    <?php wp_nonce_field('pp-update-condition_' . $status) ?>

                    <?php if ($wp_http_referer) : ?>
                        <input type="hidden" name="wp_http_referer" value="<?php echo esc_url($wp_http_referer); ?>"/>
                    <?php endif; ?>

                    <?php
                    require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/UI/StatusAdmin.php');
                    StatusAdmin::status_edit_ui($status, compact('attrib_type'));
                    ?>

                    <?php
                    do_action('presspermit_edit_condition_ui', $attrib_type, $attribute, $status);
                    ?>

                    <?php
                    submit_button(PWP::__wp('Update'), 'primary large pp-submit');
                    ?>

                </form>

                <?php 
                presspermit()->admin()->publishpressFooter();
                ?>
            </div>
        <?php
    }
}
