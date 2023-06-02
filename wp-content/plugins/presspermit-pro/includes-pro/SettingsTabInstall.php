<?php

namespace PublishPress\Permissions\UI;

use PublishPress\Permissions\Factory;

class SettingsTabInstall
{
    public function __construct()
    {
        @load_plugin_textdomain('presspermit-pro-hints', false, dirname(plugin_basename(PRESSPERMIT_PRO_FILE)) . '/languages');

        add_filter('presspermit_option_tabs', [$this, 'optionTabs'], 90);
        add_filter('presspermit_section_captions', [$this, 'sectionCaptions']);
        add_filter('presspermit_option_captions', [$this, 'optionCaptions']);
        add_filter('presspermit_option_sections', [$this, 'optionSections']);

        add_action('presspermit_install_options_ui', [$this, 'optionsUI']);
    }

    public function optionTabs($tabs)
    {
        $tabs['install'] = esc_html__('License', 'press-permit-core');
        return $tabs;
    }

    public function sectionCaptions($sections)
    {
        $new = [
            'key' => esc_html__('License Key', 'press-permit-core'),
            'version' => esc_html__('Version', 'press-permit-core'),
            'help' => PWP::__wp('Help'),
        ];

        $key = 'key';
        $sections[$key] = (isset($sections[$key])) ? array_merge($sections[$key], $new) : $new;
        return $sections;
    }

    public function optionCaptions($captions)
    {
        $opt = [
            'key' => esc_html__('settings', 'press-permit-core'),
            'help' => esc_html__('settings', 'press-permit-core'),
        ];

        return array_merge($captions, $opt);
    }

    public function optionSections($sections)
    {
        $new = [
            'key' => ['edd_key'],
            'beta_updates' => ['beta_updates'],
            'help' => ['no_option'],
        ];

        $key = 'install';
        $sections[$key] = (isset($sections[$key])) ? array_merge($sections[$key], $new) : $new;
        return $sections;
    }

    public function optionsUI()
    {
        $pp = presspermit();

        $ui = SettingsAdmin::instance();
        $tab = 'install';

        require_once(PRESSPERMIT_PRO_ABSPATH . '/includes-pro/library/Factory.php');
        $container      = \PublishPress\Permissions\Factory::get_container();
        $licenseManager = $container['edd_container']['license_manager'];

        $use_network_admin = $this->useNetworkUpdates();
        $suppress_updates = $use_network_admin && !is_super_admin();

        $section = 'key'; // --- UPDATE KEY SECTION ---
        if (!empty($ui->form_options[$tab][$section]) && !$suppress_updates) : ?>
            <tr>
                <td scope="row" colspan="2">
                    <?php

                    global $activated;

                    $id = 'edd_key';

                    if (!defined('PRESSPERMIT_LICENSE_KEY_NO_AUTOREFRESH')) {
                        if (!get_transient('presspermit-refresh-update-info')) {
                            $pp->keyStatus(true);
                            set_transient('presspermit-refresh-update-info', true, 86400);
                        }
                    }

                    $opt_val = $pp->getOption($id);

                    if (!is_array($opt_val) || count($opt_val) < 2) {
                        $activated = false;
                        $expired = false;
                        $key = '';
                        $opt_val = [];
                    } else {
                        $activated = !empty($opt_val['license_status']) && ('valid' == $opt_val['license_status']);
                        $expired = $opt_val['license_status'] && ('expired' == $opt_val['license_status']);
                    }

                    if (isset($opt_val['expire_date']) && is_date($opt_val['expire_date'])) {
                        $date = new \DateTime(date('Y-m-d H:i:s', strtotime($opt_val['expire_date'])), new \DateTimezone('UTC'));
                        $date->setTimezone(new \DateTimezone('America/New_York'));
                        $expire_date_gmt = $date->format("Y-m-d H:i:s");
                        $expire_days = intval((strtotime($expire_date_gmt) - time()) / 86400);
                    } else {
                        unset($opt_val['expire_date']);
                    }
                    ?>

                    <div class="pp-key-wrap">

                    <?php if ($expired && (!empty($key))) : ?>
                    
                        <span class="pp-key-expired"><?php esc_html_e("Key Expired", 'press-permit-core') ?></span>
                        <input name="<?php echo esc_attr($id); ?>" type="text" id="<?php echo esc_attr($id); ?>" style="display:none" />
                        <button type="button" id="activation-button" name="activation-button"
                                class="button-secondary"><?php esc_html_e('Deactivate Key', 'press-permit-core'); ?></button>
                    <?php else : ?>
                        <div class="pp-key-label" style="float:left">
                            <span class="pp-key-active" <?php if (!$activated) echo 'style="display:none;"';?>><?php esc_html_e("Key Activated", 'press-permit-core') ?></span>
                            <span class="pp-key-inactive" <?php if ($activated) echo 'style="display:none;"';?>><?php esc_html_e("License Key", 'press-permit-core') ?></span>
                        </div>

                            <input name="<?php echo esc_attr($id); ?>" type="text" placeholder="<?php echo esc_attr('(please enter publishpress.com key)', 'press-permit-pro');?>" id="<?php echo esc_attr($id); ?>"
                                   maxlength="40" <?php if ($activated) echo ' style="display:none"'; ?> />
                        
                            <button type="button" id="activation-button" name="activation-button"
                                    class="button-secondary"><?php if (!$activated) echo esc_html__('Activate Key', 'press-permit-core'); else echo esc_html__('Deactivate Key', 'press-permit-core'); ?></button>
                    <?php endif; ?>

                        <img id="pp_support_waiting" class="waiting" style="display:none;position:relative"
                             src="<?php echo esc_url(admin_url('images/wpspin_light.gif')) ?>" alt=""/>

                        <div class="pp-key-refresh" style="display:inline">
                            &bull;&nbsp;&nbsp;<a href="https://publishpress.com/checkout/purchase-history/"
                                                       target="_blank"><?php esc_html_e('review your account info', 'press-permit-core'); ?></a>
                        </div>
                    </div>

                    <?php if ($activated) : ?>
                        <?php if ($expired) : /* @todo: replace this text with EDD Integration equivalent */ ?>
                            <div class="pp-key-hint-expired">
                                <span class="pp-key-expired pp-key-warning"> <?php _e('Note: Renewal does not require deactivation. If you do deactivate, re-entry of the license key will be required.', 'presspermit-pro'); ?></span>
                            </div>
                        <?php elseif ($pp->getOption('display_hints')) : ?>
                            <div class="pp-key-hint">
                            <span class="pp-subtext"> <?php SettingsAdmin::echoStr('key-deactivation');?></span>
                            </div>
                        <?php endif; ?>

                    <?php elseif (!$expired) : ?>
                        <div class="pp-key-hint">
                        </div>
                    <?php endif ?>

                    <?php
                    $class = 'activating';

                    if (!$expired && !empty($opt_val['expire_date'])) {
                        if ($expire_days >= 30) {
                            $class = "activating hidden";
                        }
                    } elseif ($activated) {
                        $class = "activating hidden";
                    }

                    // @todo: replace these strings with EDD Integration equivalents

                    ?>
                    <div id="activation-status" class="<?php echo esc_attr($class);?>">
                        <?php

                    if ($expired) {
                        printf(
                            'Your license key has expired. For continued priority support, %splease renew%s.',
                            '<a href="admin.php?page=presspermit-settings&amp;pp_renewal=1">',
                            '</a>'
                        );
                    } elseif (!empty($opt_val['expire_date'])) {
                        if ($expire_days == 1) {
                            printf(
                                'Your license key will expire today. For updates and priority support, %splease renew%s.',
                                '<a href="admin.php?page=presspermit-settings&amp;pp_renewal=1">',
                                '</a>'
                            );
                        } elseif ($expire_days < 30) {
                            printf(
                                'Your license key (for plugin updates) will expire in %d day(s). For updates and priority support, %splease renew%s.',
                                (int) $expire_days,
                                '<a href="admin.php?page=presspermit-settings&amp;pp_renewal=1">',
                                '</a>'
                            );
                        }
                    } elseif (!$activated) {
                        printf(
                            'For updates to Permissions Pro, activate your %sPublishPress license key%s.',
                            '<a href="https://publishpress.com/pricing/">',
                            '</a>'
                        );
                    }
                    ?>
                    </div>

                    <div class="pp-settings-caption" style="display:none;">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=presspermit-settings'));?>"><?php esc_html_e('reload module info', 'press-permit-core'); ?></a>
                    </div>

                    <?php if ($expired) : ?>
                        <div id="activation-error" class="error">
                        <?php printf(
                            'Your license key has expired. For continued priority support, %splease renew%s.',
                            '<a href="admin.php?page=presspermit-settings&amp;pp_renewal=1">',
                            '</a>'
                        );
                        ?>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php

            do_action('presspermit_support_key_ui');
            self::footer_js($activated, $expired);
        endif; // any options accessable in this section

        $section = 'version'; // --- VERSION SECTION ---
        ?>
            <tr>
                <td colspan="2">

                    <?php
                    $update_info = [];

                    if (!$suppress_updates) {
                        $wp_plugin_updates = get_site_transient('update_plugins');
                        if (
                            $wp_plugin_updates && isset($wp_plugin_updates->response[plugin_basename(PRESSPERMIT_PRO_FILE)])
                            && !empty($wp_plugin_updates->response[plugin_basename(PRESSPERMIT_PRO_FILE)]->new_version)
                            && version_compare($wp_plugin_updates->response[plugin_basename(PRESSPERMIT_PRO_FILE)]->new_version, PRESSPERMIT_PRO_VERSION, '>')
                        ) {
                            $do_info_link = true;
                        }
                    }

                    ?>
                    <p>
                        <?php 
                        if (!empty($do_info_link)) {
                            $slug = 'presspermit-pro';

                            $_url = "plugin-install.php?tab=plugin-information&plugin=$slug&section=changelog&TB_iframe=true&width=600&height=800";
                            $info_url = ($use_network_admin) ? network_admin_url($_url) : admin_url($_url);

                            printf(
                                esc_html__('Permissions Pro Version: %1$s %2$s', 'press-permit-core'), 
                                esc_html(PRESSPERMIT_PRO_VERSION),

                                "&nbsp;<span class='update-message'> &bull;&nbsp;&nbsp;<a href='" . esc_url($info_url) . "' class='thickbox'>"
                                . sprintf(esc_html__('view %s&nbsp;details', 'press-permit-core'), esc_html($wp_plugin_updates->response[plugin_basename(PRESSPERMIT_PRO_FILE)]->new_version))
                                . '</a></span>'
                            ); 
                        } else {
                            printf(esc_html__('Permissions Pro Version: %1$s %2$s', 'press-permit-core'), esc_html(PRESSPERMIT_PRO_VERSION), '');
                        }

                        if (current_user_can('update_plugins')) :
                            $url = wp_nonce_url(
                                admin_url("admin.php?page=presspermit-settings&presspermit_refresh_updates=1"),
                                'presspermit_refresh_updates'
                            );
                            ?>

                            &nbsp;&nbsp;&bull;&nbsp;&nbsp;<a href="<?php echo esc_url($url);?>"><?php esc_html_e('update check / install', 'press-permit-core'); ?></a>
                        <?php endif; ?>

                        <br/>
                        <span style="display:none"><?php printf(esc_html__("Database Schema Version: %s", 'press-permit-core'), esc_html(PRESSPERMIT_DB_VERSION)); ?><br/></span>
                    </p>

                    <p>
                    <?php
                    global $wp_version;
                    printf(esc_html__("WordPress Version: %s", 'press-permit-core'), esc_html($wp_version));
                    ?>
                    </p>
                    <p>
                    <?php printf(esc_html__("PHP Version: %s", 'press-permit-core'), esc_html(phpversion())); ?>
                    </p>
                </td>
            </tr>
        <?php

    } // end function optionsUI()

    private static function footer_js($activated, $expired)
    {
        // Remove translation wrappers for these strings because they will be replaced by equivalent wordpress-edd-license-integration calls
        $vars = [
            'activated' => ($activated || !empty($expired)) ? true : false,
            'expired' => !empty($expired),
            'activateCaption' => 'Activate Key',
            'deactivateCaption' => 'Deactivate Key',
            'noConnectCaption' => 'The request could not be processed due to a connection failure.',
            'noEntryCaption' => 'Please enter the license key shown on your order receipt.',
            'errCaption' => 'An unidentified error occurred.',
            'keyStatus' => json_encode([
                'deactivated' => 'The key has been deactivated.',
                'valid' => 'The key has been activated.',
                'expired' => 'The key has expired.',
                'invalid' => 'The key is invalid.',
                'retry' => 'The key could not be activated. Please retry.'
            ]),
            'activateURL' => wp_nonce_url(admin_url(''), 'wp_ajax_pp_activate_key'),
            'deactivateURL' => wp_nonce_url(admin_url(''), 'wp_ajax_pp_deactivate_key'),
            'refreshURL' => wp_nonce_url(admin_url(''), 'wp_ajax_pp_refresh_version'),
        ];

        wp_localize_script('presspermit-settings', 'ppSettings', $vars);
    }

    private function useNetworkUpdates()
    {
        return false; //(is_multisite() && (is_network_admin() || PWP::isNetworkActivated() || PWP::isMuPlugin()));
    }
} // end class
