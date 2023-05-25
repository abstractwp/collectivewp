<?php
namespace PublishPress\Permissions\SyncPosts\UI;

use \PublishPress\Permissions\UI\SettingsAdmin as SettingsAdmin;

/**
 * PressPermit Sync Settings
 *
 * @package PressPermit
 * @author Kevin Behrens
 * @copyright Copyright (c) 2019, PublishPress
 * 
 */

class SettingsTabSyncPosts
{
    var $bbp_teaser_disabled = false;

    function __construct()
    {
        add_filter('presspermit_option_tabs', [$this, 'optionTabs'], 14);

        add_filter('presspermit_section_captions', [$this, 'sectionCaptions']);
        add_filter('presspermit_option_captions', [$this, 'optionCaptions']);
        add_filter('presspermit_option_sections', [$this, 'optionSections'], 20);

        add_action('presspermit_sync_posts_options_ui', [$this, 'optionsUI']);
    }

    function optionTabs($tabs)
    {
        $tabs['sync_posts'] = esc_html__('Sync Posts', 'presspermit-pro');
        return $tabs;
    }

    function sectionCaptions($sections)
    {
        // Sync Posts tab
        $new = [
            'sync_posts' =>             esc_html__('Synchronize Posts', 'presspermit-pro'),
        ];

        $key = 'sync_posts';
        $sections[$key] = (isset($sections[$key])) ? array_merge($sections[$key], $new) : $new;

        return $sections;
    }

    function optionCaptions($captions)
    {
        $opt = [];

        $opt['sync_posts_to_users'] =                   esc_html__('Synchronize Posts to Users', 'presspermit-pro');
        $opt['sync_posts_to_users_apply_permissions'] = esc_html__('Grant Author Permissions', 'presspermit-pro');
        $opt['sync_posts_to_users_types'] =             esc_html__('Post Types', 'presspermit-pro');
        $opt['sync_posts_to_users_post_field'] =        esc_html__('Match Post Field', 'presspermit-pro');
        $opt['sync_posts_to_users_user_field'] =        esc_html__('Match User Field', 'presspermit-pro');
        $opt['sync_posts_to_users_role'] =              esc_html__('User Role', 'presspermit-pro');
        $opt['sync_posts_to_users_post_parent'] =       esc_html__('Parent Post', 'presspermit-pro');

        return array_merge($captions, $opt);
    }

    function optionSections($sections)
    {
        // Sync Posts tab
        $new = [
            'sync_posts' => [
                'sync_posts_to_users',
                'sync_posts_to_users_apply_permissions',
                'sync_posts_to_users_types',
                'sync_posts_to_users_post_field',
                'sync_posts_to_users_user_field',
                'sync_posts_to_users_role',
                'sync_posts_to_users_post_parent'
            ],
        ];

        $tab = 'sync_posts';
        $sections[$tab] = (isset($sections[$tab])) ? array_merge($sections[$tab], $new) : $new;

        return $sections;
    }

    function optionsUI()
    {
        $ui = \PublishPress\Permissions\UI\SettingsAdmin::instance(); 
        $tab = 'sync_posts';

        $pp = presspermit();

        $section = 'sync_posts';                                    // --- SYNC POSTS SECTION ---
        if (!empty($ui->form_options[$tab][$section])) : ?>
                <tr>
                    <th scope="row"><?php echo esc_html($ui->section_captions[$tab][$section]); ?></th>
                    <td>

                        <?php
                        $ret = $ui->optionCheckbox('sync_posts_to_users', $tab, $section, true, '');
                        $main_enable = !empty($ret['val']);

                        $skip_post_types = apply_filters(
                            'presspermit_disabled_sync_posts_to_users_types', 
                            ['block', 'attachment', 'forum', 'topic', 'reply']
                        );

                        $option_names = [
                            'sync_posts_to_users_types', 
                            'sync_posts_to_users_post_field', 
                            'sync_posts_to_users_user_field', 
                            'sync_posts_to_users_role', 
                            'sync_posts_to_users_post_parent'
                        ];
                        
                        $ui->all_otype_options = array_merge($ui->all_otype_options, $option_names);
                        $opt_values = [];

                        $titles = [];
                        $titles['sync_posts_to_users_post_field'] = '';
                        $titles['sync_posts_to_users_user_field'] = '';
                        $titles['sync_posts_to_users_user_field_text'] = '';
                        $titles['sync_posts_to_users_role'] = '';
                        $titles['sync_posts_to_users_post_parent'] = '';
                        $titles['suggestions'] = '';

                        foreach(array_keys($titles) as $string_id) {
                            $titles[$string_id] = SettingsAdmin::getStr($string_id);
                        }

                        $suggested_values = [];

                        $all_post_types = get_post_types([], 'object');

                        $private_types = SyncPosts::getAllowedPrivatePostTypes();
                        foreach ($all_post_types as $post_type => $post_type_obj) {
                            if (empty($post_type_obj->public) && empty($post_type_obj->show_ui) && !in_array($post_type, $private_types, true)) {
                                unset($all_post_types[$post_type]);
                            }
                        }

                        // retrieve stored values, blending in defaults and stripping out disabled types
                        foreach ($option_names as $option_name) {
                            $defaults = array_fill_keys(array_keys($all_post_types), '');
                            $stored_settings = $ui->getOptionArray($option_name);

                            if (!defined('PPP_DISABLE_METAKEY_SUGGESTIONS')) {
                                if ('sync_posts_to_users_post_field' == $option_name) {
                                    if (!empty($opt_values['sync_posts_to_users_types'])) {
                                        // query suggested postmeta keys
                                        if (defined('presspermit_sync_posts_SHOW_ALL_META_KEYS')) {
                                            $key_like = false;
                                        } else {
                                            $key_like = (array)apply_filters(
                                                'presspermit_sync_posts_to_users_postmeta_keylike', 
                                                [
                                                    '%email%', 
                                                    '%phone%', 
                                                    '%mobile%', 
                                                    '%cell%', 
                                                    '%$skype%', 
                                                    '%twitter%', 
                                                    '%_tlink%', 
                                                    '%facebook%', 
                                                    '%fcbk%', 
                                                    '%linked%', 
                                                    '%youtube%', 
                                                    '%google%', 
                                                    '%instagram%', 
                                                    '%github%', 
                                                    '%user%', 
                                                    '%_id'
                                                ]
                                            );
                                        }

                                        $suggested_values[$option_name] = $this->get_suggested_meta_keys(
                                            array_keys($opt_values['sync_posts_to_users_types']), 
                                            $key_like
                                        );

                                        foreach ($suggested_values[$option_name] as $post_type => $suggestions) {
                                            // re-order suggestions with email keys first
                                            $_email_suggestions = [];
                                            $_twitter_suggestions = [];
                                            $_user_suggestions = [];
                                            $_other_suggestions = [];
                                            foreach ($suggestions as $k => $val) {
                                                if (false !== strpos($k, 'email')) {
                                                    $_email_suggestions[$k] = $val;
                                                } elseif (false !== strpos($k, 'twitter') || false !== strpos($k, '_tlink') 
                                                || false !== strpos($k, 'facebook') || false !== strpos($k, 'fcbk') 
                                                || false !== strpos($k, 'linked') || false !== strpos($k, 'github') 
                                                || false !== strpos($k, 'youtube') || false !== strpos($k, 'google') 
                                                || false !== strpos($k, 'instagram')
                                                ) {
                                                    $_twitter_suggestions[$k] = $val;
                                                } elseif (false !== strpos($k, 'phone') || false !== strpos($k, 'mobile') 
                                                || false !== strpos($k, 'cell') || false !== strpos($k, 'skype') 
                                                || false !== strpos($k, 'user')
                                                ) {
                                                    $_user_suggestions[$k] = $val;
                                                } else {
                                                    $_other_suggestions[$k] = $val;
                                                }
                                            }

                                            $suggested_values[$option_name][$post_type] = array_merge(
                                                $_email_suggestions, $_twitter_suggestions, $_user_suggestions, $_other_suggestions
                                            );
                                            
                                            $suggestions = $suggested_values[$option_name][$post_type];
                                            reset($suggestions);

                                            $first = key($suggestions);

                                            if (false !== strpos($first, 'email')) {
                                                $defaults[$post_type] = $first;
                                            } else {
                                                $defaults[$post_type] = 'post_title';
                                            }

                                            // default cleared value back to first suggestion
                                            if (empty($stored_settings[$post_type])) {
                                                unset($stored_settings[$post_type]);

                                                // ...and store that default immediately
                                                $arr = $pp->getOption($option_name);
                                                $arr[$post_type] = $defaults[$post_type];

                                                $pp->updateOption($option_name, $arr);
                                            }
                                        }
                                    }
                                }
                            }

                            if ('sync_posts_to_users_post_field' == $option_name) {
                                $defaults = array_merge($defaults, [
                                    'jv_team_members' => 'jv_team_email_address',
                                    'staff-member' => '_ikcf_email',
                                    'emd_employee' => 'emd_employee_email',
                                    'staff' => 'staffer_staff_email',
                                    'team' => 'email',
                                    'team_mf' => 'contact_email',
                                    'team_manager' => 'tm_emailid',
                                ]);
                                $defaults = (array)apply_filters('presspermit_sync_posts_to_users_default_post_field', $defaults);

                                foreach ($defaults as $_post_type => $val) {
                                    if (!$val) continue;

                                    $suggested_values['sync_posts_to_users_post_field'][$_post_type][$val] = true;
                                }

                                $post_field_defaults = $defaults;
                            } elseif ('sync_posts_to_users_user_field' == $option_name) {
                                $defaults = (array)apply_filters('presspermit_sync_posts_to_users_default_user_field', []);
                            }

                            // if post field is defaulting to an email metakey, default user field to user_email
                            if ('sync_posts_to_users_user_field' == $option_name) {
                                foreach ($post_field_defaults as $_post_type => $val) {
                                    if ($val && (false !== strpos($val, 'email') || false !== strpos($val, 'e-mail'))) {
                                        $defaults[$_post_type] = 'user_email';
                                    }
                                }
                            }

                            // add enabled types whose settings have never been stored
                            $opt_values[$option_name] = array_merge($defaults, $stored_settings);

                            // skip stored types that are not enabled
                            $opt_values[$option_name] = array_intersect_key($opt_values[$option_name], $all_post_types);

                            $opt_values[$option_name] = array_diff_key($opt_values[$option_name], array_fill_keys($skip_post_types, true));

                            if ('sync_posts_to_users_types' == $option_name) {
                                $any_types_enabled = false;
                                foreach ($opt_values[$option_name] as $enabled) {
                                    if ($enabled) $any_types_enabled = true;
                                }
                            }
                        }

                        $style = ($ui->getOption('sync_posts_to_users')) ? '' : 'display:none';
                        $header_style = ($any_types_enabled) ? '' : 'display:none';
                        $header_style_copy = ($any_types_enabled) ? 'nostyle' : 'display:none';

                        global $wp_roles;
                        $roles = $wp_roles->get_names();
                        uasort($roles, 'strnatcasecmp');

                        $main_check_title = SettingsAdmin::getStr('sync_title_main_checkbox');
                        
                        $sync_existing_title = SettingsAdmin::getStr('sync_existing_title');

                        $post_field_captions = [
                            'post_title' => __('Post Title', 'presspermit-pro'), 
                            'post_name' => __('Post slug', 'presspermit-pro')
                        ];

                        $user_field_captions = [
                            'display_name' => __('User Display Name', 'presspermit-pro'), 
                            'user_email' => __('User email', 'presspermit-pro'), 
                            'user_login' => __('User login', 'presspermit-pro'), 
                            'user_nicename' => __('User nicename', 'presspermit-pro')
                        ];

                        $any_hierarchical = false;

                        $ordered_post_types = array_intersect_key($all_post_types, $opt_values['sync_posts_to_users_types']);

                        foreach ($ordered_post_types as $post_type => $type_obj) {
                            $ordered_post_types[$post_type] = (isset($type_obj->labels->name)) 
                            ? $type_obj->labels->name 
                            : $post_type;

                            if (!empty($type_obj->hierarchical) && !empty($opt_values['sync_posts_to_users_types'][$post_type])) {
                                $any_hierarchical = true;
                            }
                        }
                        uasort($ordered_post_types, 'strnatcasecmp');
                        ?>

                        <table id="sync_posts_to_users_settings" class='agp-vtight_input agp-rlabel' style='<?php echo esc_attr($style); ?>'>
                            <tr style='<?php echo esc_attr($header_style); ?>' test='<?php echo esc_attr($header_style_copy); ?>'>
                                <th class="pp-enable"><?php esc_html_e('Sync to New Users', 'presspermit-pro'); ?></th>
                                <th class="pp-sync-now"><?php esc_html_e('Sync to Current Users', 'presspermit-pro'); ?></th>
                                <th class="pp-sync-role"><?php esc_html_e('Role to Sync', 'presspermit-pro'); ?></th>
                                <th class="pp-sync-post-field"><?php esc_html_e('Post Match Field', 'presspermit-pro'); ?></th>
                                <th class="pp-sync-equals"></th>
                                <th class="pp-sync-user-field"><?php esc_html_e('User Match Field', 'presspermit-pro'); ?></th>

                                <th class="pp-sync-parent" <?php if (!$any_hierarchical) echo 'style="display:none;"'; ?>>
                                <?php esc_html_e('Parent', 'presspermit-pro'); ?>
                                </th>
                            </tr>
                            <?php

                            foreach ($ordered_post_types as $object_type => $post_type_label) :
                                if ('attachment' == $object_type) continue;

                                $type_enabled = $opt_values['sync_posts_to_users_types'][$object_type];

                                $type_style = ($type_enabled) ? '' : 'display:none';

                                $option_name = 'sync_posts_to_users_types';
                                $id = $option_name . '-' . $object_type;
                                $name = "{$option_name}[$object_type]";
                                ?>
                                <tr class="pp-<?php echo esc_attr($object_type); ?>">
                                    <td style="white-space:nowrap" class="rlabel pp-sync-type<?php if (is_post_type_hierarchical($object_type)) echo ' pp-hierarchical-type'; ?>">
                                        <label for='<?php echo esc_attr($id); ?>' title='<?php echo esc_attr($object_type); ?>'><?php echo esc_html($ordered_post_types[$object_type]); ?></label>
                                        &nbsp;
                                        <?php
                                        $checked = ($type_enabled) ? ' checked ' : '';
                                        ?>
                                        <input name='<?php echo esc_attr($name); ?>' type='hidden' value='0' />
                                        &nbsp;<label for="<?php echo esc_attr($id); ?>">
                                        <input type="checkbox" class="sync-enable-type" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="1" <?php echo esc_attr($checked); ?> <?php echo esc_html($main_check_title); ?> />
                                    </td>

                                    <td class="pp-sync-now pp-toggle" style='<?php echo esc_attr($type_style); ?>'>
                                        <?php
                                        $id = 'sync_posts_to_users_existing' . '-' . $object_type;
                                        $name = "sync_posts_to_users_existing[$object_type]";
                                        ?>
                                        <label for="<?php echo esc_attr($id); ?>">
                                        <input type="checkbox" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="1" title='<?php echo esc_attr($sync_existing_title); ?>' />
                                    </td>

                                    <?php
                                        $option_name = 'sync_posts_to_users_role';
                                        $id = $option_name . '-' . $object_type;
                                        $name = "{$option_name}[$object_type]";
                                        $disabled = ($type_enabled) ? '' : ' disabled ';
                                        $title = $titles[$option_name];
                                        $setting = (isset($opt_values[$option_name][$object_type])) ? $opt_values[$option_name][$object_type] : '';
                                        ?>
                                    <td class="pp-toggle" style='<?php echo esc_attr($type_style); ?>'>
                                        <select name='<?php echo esc_attr($name); ?>' id='<?php echo esc_attr($id); ?>' class='pp-suggestion' title='<?php echo esc_attr($title); ?>' <?php echo esc_attr($disabled); ?> autocomplete='off'>
                                            <option value=''><?php esc_html_e('please select...', 'presspermit-pro'); ?></option>
                                            <option value='(any)' <?php if ('(any)' == $setting) echo ' selected=selected'; ?>><?php esc_html_e('(all roles)', 'presspermit-pro'); ?></option>
                                            <?php
                                            foreach ($roles as $role_name => $role_display) :
                                                $selected = ($setting === $role_name) ? ' selected ' : '';
                                                ?>
                                                <option value='<?php echo esc_attr($role_name); ?>' <?php echo esc_attr($selected); ?>><?php echo esc_html($role_display); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td class="pp-toggle pp-sync-post-field" style='<?php echo esc_attr($type_style); ?>'>
                                        <?php
                                        $option_name = 'sync_posts_to_users_post_field';
                                        $id = $option_name . '-' . $object_type;
                                        $name = "{$option_name}[$object_type]";
                                        $disabled = ($type_enabled) ? '' : ' disabled ';
                                        $title = $titles[$option_name];

                                        // default to post_title field
                                        $setting = (!empty($opt_values[$option_name][$object_type])) ? $opt_values[$option_name][$object_type] : 'post_title';

                                        $show_dropdown = (!$setting || in_array($setting, ['post_title', 'post_name'], true) || !empty($suggested_values['sync_posts_to_users_post_field'][$object_type][$setting]));

                                        $show_hint = false;
                                        if (empty($suggested_values['sync_posts_to_users_post_field'][$object_type]) && $ui->display_hints) {
                                            $_posts = (array)wp_count_posts($object_type);
                                            unset($_posts['trash']);
                                        }

                                        $style = ($show_dropdown) ? '' : 'display:none;';

                                        unset($suggested_values[$option_name][$object_type]['post_title']);
                                        ?>
                                        <select class="pp-hint ppp-suggestion pp-no-hide" title='<?php echo esc_attr($title); ?>' style='<?php echo esc_attr($style); ?>' autocomplete="off">
                                            <?php
                                            if (!empty($suggested_values[$option_name][$object_type])) :
                                                foreach (array_keys($suggested_values[$option_name][$object_type]) as $meta_key) :
                                                    $selected = ($meta_key == $setting) ? ' selected ' : '';
                                                    ?>
                                                    <option value='<?php echo esc_attr($meta_key); ?>' <?php echo esc_attr($selected); ?>>
                                                    <?php if (isset($post_field_captions[$meta_key])) echo esc_html($post_field_captions[$meta_key]); else echo esc_html($meta_key);?>
                                                    </option>
                                                <?php
                                            endforeach;
                                        endif;

                                        $selected = (('post_title' == $setting) || !$setting) ? ' selected ' : '';
                                        ?>
                                            <option value='post_title' <?php echo esc_attr($selected); ?>><?php echo esc_html($post_field_captions['post_title']); ?></option>
                                            <?php $selected = (('post_name' == $setting)) ? ' selected ' : ''; ?>
                                            <option value='post_name' <?php echo esc_attr($selected); ?>><?php echo esc_html($post_field_captions['post_name']); ?></option>

                                            <?php $selected = (empty($suggested_values[$option_name][$object_type][$setting]) && !in_array($setting, array_keys($post_field_captions), true)) ? ' selected ' : ''; ?>
                                            <option value='(other)' <?php echo esc_attr($selected); ?>>
                                            <?php esc_html_e('(other)', 'presspermit-pro'); ?>
                                            </option>
                                        </select>

                                        <?php
                                        $style = ($show_dropdown) ? 'display:none' : '';
                                        ?>
                                        <input name="<?php echo esc_attr($name); ?>" type="text" id="<?php echo esc_attr($id);?>" class="ppp-text-field" value="<?php echo esc_attr($setting); ?>" title='<?php echo esc_attr($title); ?>' style='<?php echo esc_attr($style); ?>' <?php echo esc_attr($disabled); ?> />
                                        <input type='hidden' value='<?php echo esc_attr($setting); ?>' class='ppp-field-buffer' />
                                        <?php
                                        $title = $titles['suggestions'];
                                        ?>
                                        <a href="javascript:void(0)" class="ppp-suggest" title='<?php echo esc_attr($title); ?>' style='<?php echo esc_attr($style); ?>'><?php esc_html_e('select...', 'presspermit-pro'); ?></a>
                                        <a href="javascript:void(0)" class="ppp-cancel" style="display:none"><?php esc_html_e('cancel', 'presspermit-pro'); ?></a>
                                    </td>

                                    <td class="pp-toggle pp-sync-equals" style='<?php echo esc_attr($type_style); ?>'>
                                        <span>=</span>
                                    </td>

                                    <?php
                                    $option_name = 'sync_posts_to_users_user_field';
                                    $id = $option_name . '-' . $object_type;
                                    $name = "{$option_name}[$object_type]";
                                    $disabled = ($type_enabled) ? '' : ' disabled ';
                                    $title = $titles[$option_name];
                                    
                                    $setting = (!empty($opt_values[$option_name][$object_type])) 
                                    ? $opt_values[$option_name][$object_type] 
                                    : 'display_name';
                                    
                                    $show_dropdown = (!$setting || in_array($setting, array_keys($user_field_captions), true));
                                    $style = ($show_dropdown) ? '' : 'display:none;';
                                    ?>
                                    <td class="pp-toggle pp-sync-user-field" style='<?php echo esc_attr($type_style); ?>'>
                                        <select name='<?php echo esc_attr($name); ?>' id='<?php echo esc_attr($id); ?>' class='ppp-suggestion' style='<?php echo esc_attr($style); ?>' title='<?php echo esc_attr($title); ?>' <?php echo esc_attr($disabled);?> autocomplete='off'>
                                            <?php foreach ($user_field_captions as $field_name => $caption) : ?>
                                                <option value='<?php echo esc_attr($field_name); ?>' <?php if ($field_name == $setting) echo 'selected=selected'; ?>>
                                                <?php echo esc_html($caption); ?>
                                                </option>
                                            <?php endforeach; ?>

                                            <option value='(other)' <?php if (!in_array($setting, array_keys($user_field_captions), true)) echo 'selected=selected'; ?>>
                                            <?php esc_html_e('(other)', 'presspermit-pro'); ?>
                                            </option>
                                        </select>

                                        <?php
                                        $style = ($show_dropdown) ? 'display:none' : '';
                                        ?>
                                        <input name="<?php echo esc_attr($name); ?>" type="text" class="ppp-text-field" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($setting); ?>" title="<?php echo esc_attr($titles['sync_posts_to_users_user_field_text']); ?>" style='<?php echo esc_attr($style); ?>' <?php echo esc_attr($disabled); ?> />
                                        <input type='hidden' value='<?php echo esc_attr($setting); ?>' class='ppp-field-buffer' />
                                        <?php
                                        $title = $titles['suggestions'];
                                        ?>
                                        <a href="javascript:void(0)" class="ppp-suggest" title='<?php echo esc_attr($title); ?>' style='<?php echo esc_attr($style); ?>'><?php esc_html_e('select...', 'presspermit-pro'); ?></a>
                                        <a href="javascript:void(0)" class="ppp-cancel" style="display:none"><?php esc_html_e('cancel', 'presspermit-pro'); ?></a>

                                    <?php
                                    $type_style = ($type_enabled && $any_hierarchical) ? '' : 'display:none';
                                    ?>
                                    <td class="pp-sync-parent" style='<?php echo esc_attr($type_style); ?>'>
                                        <?php
                                        $post_type_object = get_post_type_object($object_type);
                                        if (empty($post_type_object) || empty($post_type_object->hierarchical)) : ?>
                                            <span class="pp-sync-parent" <?php if (!$any_hierarchical) echo 'style="display:none;"'; ?> title="<?php SettingsAdmin::echoStr('sync_title_not_hierarchical'); ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php /* (previously output 'n/a' here) */?></span>
                                        <?php else :
                                        $option_name = 'sync_posts_to_users_post_parent';
                                        $id = $option_name . '-' . $object_type;
                                        $name = "{$option_name}[$object_type]";
                                        $disabled = ($type_enabled) ? '' : ' disabled ';
                                        $title = $titles[$option_name];
                                        $setting = (!empty($opt_values[$option_name][$object_type])) ? $opt_values[$option_name][$object_type] : '0';
                                        ?>
                                            <input name="<?php echo esc_attr($name); ?>" type="text" class="ppp-parent-field" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($setting); ?>" title='<?php echo esc_attr($title); ?>' <?php echo esc_attr($disabled); ?> />
                                        <?php endif;
                                    ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            <tr>
                                <td colspan="3" style="text-align:right">
                                    <input type="submit" name="presspermit_submit" class="button-primary pp-sync-now-button" style="display:none" value="<?php esc_attr_e('Sync to Selected Role', 'presspermit'); ?>" title='<?php echo esc_attr($sync_existing_title); ?>' />
                                </td>
                                <td colspan="3"></td>
                                <td class="pp-sync-parent"></td>
                            </tr>
                        </table>

        </div>

        <?php
        $style = ($ui->getOption('sync_posts_to_users')) ? '' : 'display:none';
        ?>
        <div id="sync_posts_to_users_apply_permissions" style='<?php echo esc_attr($style); ?>'>
            <?php
            $ui->optionCheckbox('sync_posts_to_users_apply_permissions', $tab, $section, true, '');
            ?>
        </div>

        <?php
        if (SyncPosts::userSyncLoaded() && !empty(SyncPosts::userSync()->log)) :
            $sync_executed = true;
            ?>
            <div class="activating pp-sync-results">
                <h3><?php esc_html_e('Synchronization Results:', 'presspermit-pro'); ?></h3>
                <ul>
                    <?php foreach (SyncPosts::userSync()->log as $entry) : ?>
                        <li><?php echo esc_html($entry); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif;
    ?>

        <?php if ($ui->display_hints) : ?>
            <br />
            <div class="activating pp-hint pp-sync-permissions-hint" <?php if (!$ui->getOption('sync_posts_to_users')) echo 'style="display:none;"'; ?>>
                <h4>
                    <?php 
                    SettingsAdmin::echoStr('sync_explanation');
                    ?>
                </h4>

                <a href="javascript:void(0)" class="pp-more"><?php esc_html_e('Read more...', 'presspermit-pro'); ?></a>
                <ul class="pp-more" style="display:none">
                    <li>
                        <?php 
                        SettingsAdmin::echoStr('sync_team_staff_plugins');
                        ?>
                    </li>
                    <li>
                        <?php 
                        SettingsAdmin::echoStr('sync_post_user_match_fields');
                        ?>
                    </li>
                    <li>
                        <?php 
                        SettingsAdmin::echoStr('sync_new_post_creation'); 
                        ?>
                    </li>
                    <li>
                        <?php 
                        SettingsAdmin::echoStr('sync_grant_author_permissions'); 
                        ?>
                        <ol>
                            <li>
                            <?php 
                            SettingsAdmin::echoStr('sync_permissions_filtering_enable'); 
                            ?>
                            </li>
                            
                            <li>
                            <?php
                            SettingsAdmin::echoStr('sync_role_supplemented_author_caps'); 
                            ?>
                            </li>
                        </ol>
                    </li>
                    <li>
                        <?php 
                        SettingsAdmin::echoStr('sync_developer_note_api'); 
                        ?>
                    </li>
                    <li>
                        <?php 
                        SettingsAdmin::echoStr('sync_plugin_compat_disclaimer'); 
                        ?>
                        
                        <ul>
                            <li class="pp-strikethru"><span title="<?php SettingsAdmin::echoStr('sync_not_fully_compatible'); ?>"><?php echo 'Team Members (WP Darko)'; ?></span></li>
                            <li class="pp-strikethru"><span title="<?php SettingsAdmin::echoStr('sync_not_fully_compatible'); ?>"><?php echo 'Team Builder (smartshop)'; ?></span></li>
                            <li class="pp-strikethru"><span title="<?php SettingsAdmin::echoStr('sync_not_fully_compatible'); ?>"><?php echo 'Team Dazzler'; ?></span></li>
                        </ul>
                    </li>
            </div>
        <?php endif; ?>

        </td>
        </tr>
    <?php
    endif; // any options accessable in this section
    } // end function optionsUI

    private function get_suggested_meta_keys($post_types, $key_like = '%email%', $count_limit = 10000)
    {
        global $wpdb;

        if (!$post_types) return [];

        // if a post type has too many posts, don't risk the overhead of scanning for meta keys
        foreach ($post_types as $post_type) {
            $num_posts = (array)wp_count_posts($post_type);
            if (array_sum($num_posts) > $count_limit) {
                $post_types = array_diff($post_types, (array)$post_type);
            }
        }

        if (false === $key_like) {
            $skip_meta_keys = (array)apply_filters(
                'presspermit_sync_posts_to_users_skip_meta_keys', 
                ['_edit_last', 
                '_edit_lock', 
                '_pp_is_autodraft', 
                '_pp_last_parent', 
                '_wp_attached_file', 
                '_wp_attachment_metadata', 
                '_wp_desired_post_slug', 
                '_wp_page_template', 
                '_wp_trash_meta_status', 
                '_wp_trash_meta_time', 
                '_yoast_wpseo_content_score', 
                '_yoast_wpseo_primary_category'
                ]
            );
            
            $meta_key_csv = implode("','", array_map('sanitize_key', $skip_meta_keys));
            $key_like_clause = " AND meta_key NOT IN ('$meta_key_csv')";
        } else {
            $key_like_clause = ' AND (';
            $or = '';
            foreach ($key_like as $like) {
                // work around parenthesis getting converted to braced UID
                $like = str_replace('%', '&', $like);

                if ($or) {
                    $key_like_clause .= $wpdb->prepare("OR meta_key LIKE '%s'", $like);
                } else {
                    $key_like_clause .= $wpdb->prepare("meta_key LIKE '%s'", $like);
                }

                $key_like_clause = str_replace('&', '%', $key_like_clause);
                $or = ' OR ';
            }
            $key_like_clause .= ' )';
        }

        $type_csv = implode("','", array_map('sanitize_key', $post_types));

        $results = $wpdb->get_results(
            "SELECT DISTINCT(pm.meta_key), p.post_type 
            FROM $wpdb->postmeta AS pm
            INNER JOIN $wpdb->posts AS p ON p.ID = pm.post_id
            WHERE p.post_type IN ('$type_csv')
            $key_like_clause
            ORDER BY meta_id DESC"
        );

        // AND meta_key NOT RegExp '(^[_0-9].+$)' 
        // AND meta_key NOT RegExp '(^[0-9]+$)'

        $skip_keys = apply_filters('presspermit_suggested_meta_keys_skip', ['_thumbnail_id']);

        $meta_keys = [];
        foreach ($results as $row) {
            if (in_array($row->meta_key, $skip_keys, true)) continue;

            $meta_keys[$row->post_type][$row->meta_key] = true;
        }

        return $meta_keys;
    }
} // end class
?>

<script type="text/javascript">
    /* <![CDATA[ */
    jQuery(document).ready(function($) {
        $('#pp_settings_form a.pp-more').on('click', function()
        {
            $(this).hide().siblings('ul.pp-more,div.pp-more,p.pp-more').show();
        });
    });
    /* ]]> */
</script>