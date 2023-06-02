<?php
namespace PublishPress\Permissions\Statuses\UI\Dashboard;

class PostEditSubmitMetabox
{
    /**
     *  Classic Editor Post Submit Metabox: HTML
     */
    public static function post_submit_meta_box($post, $args = [])
    {
        $is_administrator = presspermit()->isContentAdministrator();
        $type_obj = get_post_type_object($post->post_type);
        $post_status = apply_filters('presspermit_editor_ui_status', $post->post_status, $post, $args);

        if ('auto-draft' == $post_status)
            $post_status = 'draft';

        if (!$post_status_obj = get_post_status_object($post_status)) {
            $post_status_obj = get_post_status_object('draft');
        }

        $moderation_statuses = PWP::getPostStatuses(['moderation' => true, 'internal' => false, 'post_type' => $post->post_type], 'object');
        unset($moderation_statuses['future']);

        if (!$is_administrator) {
            $moderation_statuses = PPS::filterAvailablePostStatuses($moderation_statuses, $post->post_type, $post->post_status);
        }

        $moderation_statuses = apply_filters('presspermit_available_moderation_statuses', $moderation_statuses, $moderation_statuses, $post);

        // Don't exclude the current status, regardless of other arguments
        $_args = ['include_status' => $post_status_obj->name];

        $default_by_sequence = presspermit()->getOption('moderation_statuses_default_by_sequence');

        if (!empty($post_status_obj->status_parent)) {
            if ($default_by_sequence) {
                // If current status is a workflow branch child, only offer other statuses in that branch
                $_args['status_parent'] = $post_status_obj->status_parent;
            }
        } elseif ($status_children = PPS::getStatusChildren($post_status_obj->name, $moderation_statuses)) {
            if ($default_by_sequence) {
                // If current status is a workflow branch parent, only offer other statuses in that branch
                $moderation_statuses = array_merge([$post_status_obj->name => $post_status_obj], $status_children);
            }
        } else {
            // If current status is in main workflow with no branch children, only display other main workflow statuses 
            $_args['status_parent'] = '';
        }

        $moderation_statuses = PPS::orderStatuses($moderation_statuses, $_args);

        $can_publish = current_user_can($type_obj->cap->publish_posts);

        $_args = compact('is_administrator', 'type_obj', 'post_status_obj', 'can_publish', 'moderation_statuses');
        $_args = array_merge($args, $_args);  // in case args passed into metabox are needed within static calls in the future
        ?>
        <div class="submitbox" id="submitpost">

            <div id="minor-publishing">
                <div id="minor-publishing-actions">
                    <div id="save-action">
                        <?php self::post_save_button($post, $_args); ?>
                    </div>
                    <div id="preview-action">
                        <?php self::post_preview_button($post, $_args); ?>
                    </div>
                    <div class="clear"></div>
                </div><?php // minor-publishing-actions ?>

                <div id="misc-publishing-actions">
                    <div class="misc-pub-section">
                        <?php self::post_status_display($post, $_args); ?>
                    </div>
                    <div class="misc-pub-section " id="visibility">
                        <?php self::post_visibility_display($post, $_args); ?>
                    </div>

                    <?php if ($type_obj->hierarchical) :
                        $attributes = PPS::attributes();
                        $ch_visibility = $attributes->getItemCondition('post', 'force_visibility', ['assign_for' => 'children', 'id' => $post->ID]);
                        ?>
                        <div class="misc-pub-section<?php if (!$ch_visibility) echo ' hide-if-js'; ?>"
                             id="ch_visibility"
                             title="<?php printf(esc_attr(__('force visibility of all sub%s', 'presspermit-pro')), esc_html(strtolower($type_obj->labels->name))); ?>">
                            <?php
                            $_args['ch_visibility'] = $ch_visibility;
                            self::subpost_visibility_display($post, $_args); ?>
                        </div>
                    <?php endif; ?>

                    <?php do_action('post_submitbox_misc_sections'); ?>

                    <?php
                    if (!empty($args['args']['revisions_count'])) :
                        $revisions_to_keep = wp_revisions_to_keep($post);
                        ?>
                        <div class="misc-pub-section num-revisions">
                            <?php
                            if ($revisions_to_keep > 0 && $revisions_to_keep <= $args['args']['revisions_count']) {
                                echo '<span title="' . esc_attr(sprintf(__('Your site is configured to keep only the last %s revisions.'),
                                        number_format_i18n($revisions_to_keep))) . '">';
                                printf(esc_html__('Revisions: %s'), '<b>' . (int) number_format_i18n($args['args']['revisions_count']) . '+</b>');
                                echo '</span>';
                            } else {
                                printf(esc_html__('Revisions: %s'), '<b>' . (int) number_format_i18n($args['args']['revisions_count']) . '</b>');
                            }
                            ?>
                            <a class="hide-if-no-js"
                               href="<?php echo esc_url(get_edit_post_link($args['args']['revision_id'])); ?>"><?php _ex('Browse', 'revisions'); ?></a>
                        </div>
                    <?php
                    endif;
                    ?>

                    <?php
                    if ($can_publish) : // Contributors don't get to choose the date of publish
                        ?>
                        <div class="misc-pub-section curtime misc-pub-section-last">
                            <?php self::post_time_display($post, $_args); ?>
                        </div>
                    <?php endif; ?>

                    <?php do_action('post_submitbox_misc_actions', $post); ?>
                </div> <?php // misc-publishing-actions ?>

                <div class="clear"></div>
            </div> <?php // minor-publishing ?>

            <div id="major-publishing-actions">
                <?php do_action('post_submitbox_start', $post); ?>
                <div id="delete-action">
                    <?php // PP: no change from WP core
                    if (current_user_can("delete_post", $post->ID)) {
                        if (!EMPTY_TRASH_DAYS)
                            $delete_text = PWP::__wp('Delete Permanently');
                        else
                            $delete_text = PWP::__wp('Move to Trash');
                        ?>
                        <a class="submitdelete deletion"
                           href="<?php echo get_delete_post_link($post->ID); ?>"><?php echo esc_html($delete_text); ?></a><?php
                    } ?>
                </div>

                <div id="publishing-action">
                    <?php self::post_publish_ui($post, $_args); ?>
                </div>
                <div class="clear"></div>
            </div> <?php // major-publishing-actions ?>

        </div> <?php // submitpost ?>

        <?php
    } // end function post_submit_meta_box()


    /*
     *  Classic Editor Post Submit Metabox: Post Save Button HTML
     */
    public static function post_save_button($post, $args)
    {
        $post_status_obj = $args['post_status_obj'];
        ?>
        <?php
        // @todo: confirm we don't need a hidden save button when current status is private */
        if (!$post_status_obj->public && !$post_status_obj->private && !$post_status_obj->moderation && ('future' != $post_status_obj->name)) :
            if (!empty($post_status_obj->labels->update)) {
                $save_as = $post_status_obj->labels->update;
            } else {
                $post_status_obj = get_post_status_object('draft');
                $save_as = $post_status_obj->labels->save_as;
            }
            ?>
            <input type="submit" name="save" id="save-post" value="<?php echo esc_attr($save_as) ?>"
                   tabindex="4" class="button button-highlighted"/>
        <?php elseif ($post_status_obj->moderation) :
            if (apply_filters('presspermit_display_save_as_button', true, $post, $args)):?>
            <input type="submit" name="save" id="save-post" value="<?php echo esc_attr($post_status_obj->labels->save_as) ?>"
                   tabindex="4" class="button button-highlighted"/>
            <?php 
            endif;
            ?>
        <?php else : ?>
            <input type="submit" name="save" id="save-post" value="<?php esc_attr_e('Save'); ?>"
                   class="button button-highlighted" style="display:none"/>
        <?php endif; ?>

        <span class="spinner" style="margin:2px 2px 0"></span>
        <?php
    }

    /**
     *  Classic Editor Post Submit Metabox: Post Preview Button HTML
     */
    public static function post_preview_button($post, $args)
    {
        if (empty($args['post_status_obj'])) return;

        if ($type_obj = get_post_type_object($post->post_type)) {
            if (empty($type_obj->public) && empty($type_obj->publicly_queryable)) {
                return;
            }
        }

        $post_status_obj = $args['post_status_obj'];
        ?>
        <?php
        if ($post_status_obj->public) {
            $preview_link = esc_url(get_permalink($post->ID));
            $preview_button = PWP::__wp('Preview Changes');
            $preview_title = '';
        } else {
            $preview_link = esc_url(apply_filters(
                'preview_post_link', 
                add_query_arg('preview', 'true', get_permalink($post->ID)),
                $post
            ));
            
            $preview_button = apply_filters('presspermit_preview_post_label', PWP::__wp('Preview'));
            $preview_title = apply_filters('presspermit_preview_post_title', '');
        }
        ?>
        <a class="preview button" href="<?php echo esc_url($preview_link); ?>" target="wp-preview" id="post-preview"
           tabindex="4" title="<?php echo esc_attr($preview_title);?>"><?php echo esc_html($preview_button); ?></a>
        <input type="hidden" name="wp-preview" id="wp-preview" value=""/>
        <?php
    }

    /**
     *  Classic Editor Post Submit Metabox: Post Status Dropdown HTML
     */
    public static function post_status_display($post, $args)
    {
        $defaults = ['post_status_obj' => false, 'can_publish' => false, 'moderation_statuses' => []];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        ?>
        <label for="post_status"><?php echo esc_html(PWP::__wp('Status:')); ?></label>
        <?php
        $post_status = $post_status_obj->name;
        ?>
        <span id="post-status-display">
        <?php
        if ($post_status_obj->private)
            echo esc_html(PWP::__wp('Privately Published'));
        elseif ($post_status_obj->public)
            echo esc_html(PWP::__wp('Published'));
        elseif (!empty($post_status_obj->labels->caption))
            echo esc_html($post_status_obj->labels->caption);
        else
            echo esc_html($post_status_obj->label);
        ?>
        </span>&nbsp;
        <?php
        // multiple moderation stati are selectable or a single non-current moderation stati is selectable
        $select_moderation = (count($moderation_statuses) > 1 || ($post_status != key($moderation_statuses)));

        if ($post_status_obj->public || $post_status_obj->private || $can_publish || $select_moderation) { ?>
            <a href="#post_status"
               <?php if ($post_status_obj->private || ($post_status_obj->public && 'publish' != $post_status)) { ?>style="display:none;"
               <?php } ?>class="edit-post-status hide-if-no-js" tabindex='4'><?php echo esc_html(PWP::__wp('Edit')) ?></a>
            <?php
            if (current_user_can('pp_create_groups')) :
                $url = admin_url("admin.php?page=presspermit-groups");
                ?>
                <span style="float:right; margin-top: -5px;">
                <a href="<?php echo esc_url($url); ?>" class="visibility-customize pp-submitbox-customize" target="_blank">
                <span class="dashicons dashicons-groups" title="<?php esc_attr_e('Define Permission Groups'); ?>" alt="<?php esc_attr_e('groups', 'presspermit');?>"></span>
                </a>
            </span>
            <?php endif; ?>

            <div id="post-status-select" class="hide-if-js">
                <input type="hidden" name="hidden_post_status" id="hidden_post_status"
                       value="<?php echo esc_attr($post_status); ?>"/>
                <select name='post_status' id='post_status' tabindex='4' autocomplete='off'>

                    <?php if ($post_status_obj->public || $post_status_obj->private || ('future' == $post_status)) : ?>
                        <option <?php selected(true, true); ?> value='publish'>
                        <?php echo esc_html($post_status_obj->labels->caption) ?>
                        </option>
                    <?php endif; ?>

                    <?php
                    foreach ($moderation_statuses as $_status => $_status_obj) : ?>
                        <option <?php selected($post_status, $_status); ?> value='<?php echo esc_attr($_status) ?>'>
                        <?php echo esc_html($_status_obj->labels->caption) ?>
                        </option>
                    <?php endforeach ?>

                    <?php
                    $draft_status_obj = get_post_status_object('draft');
                    ?>
                    <option <?php selected($post_status, 'draft'); ?> value='draft'>
                    <?php echo esc_html($draft_status_obj->label) ?>
                    </option>

                </select>
                <a href="#post_status" class="save-post-status hide-if-no-js button"><?php echo esc_html(PWP::__wp('OK')); ?></a>
                <a href="#post_status" class="cancel-post-status hide-if-no-js"><?php echo esc_html(PWP::__wp('Cancel')); ?></a>
                <?php
                if (('draft' == $post_status_obj->name || $post_status_obj->moderation) 
                && (current_user_can('pp_define_post_status') || current_user_can('pp_define_moderation'))
                ) {
                    if (PPS::publishpressStatusesActive($post->post_type)) {
                        $url = admin_url('admin.php?action=add-new&page=pp-modules-settings&module=pp-custom-status-settings');
                    } else {
                        $url = admin_url('admin.php?page=presspermit-statuses&amp;attrib_type=moderation');
                    }
                    echo "<br /><a href='" . esc_url($url) . "' class='pp-postsubmit-add-privacy' target='_blank'>" . esc_html__('add workflow status', 'presspermit-pro') . '</a>';
                }
                ?>
            </div>

        <?php } // endif status editable
    }

    /**
     *  Classic Editor Post Submit Metabox: Post Visibility HTML
     */
    public static function post_visibility_display($post, $args)
    {
        $defaults = ['is_administrator' => false, 'type_obj' => false, 'post_status_obj' => false, 'can_publish' => false];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $attributes = PPS::attributes();

        echo esc_html(PWP::__wp('Visibility:')); ?>
        <span id="post-visibility-display"><?php

            if ('future' == $post_status_obj->name) {  // indicate eventual visibility of scheduled post
                if (!$vis_status = get_post_meta($post->ID, '_scheduled_status', true))
                    $vis_status = 'publish';

                $vis_status_obj = get_post_status_object($vis_status);
            } else {
                $vis_status = $post_status_obj->name;
                $vis_status_obj = $post_status_obj;
            }

            if ($vis_status_obj->private) {
                $visibility = $vis_status;
                $post->post_password = '';
                $visibility_trans = $vis_status_obj->labels->visibility;
            } elseif (!empty($post->post_password)) {
                $visibility = 'password';
                $visibility_trans = PWP::__wp('Password protected');
            } elseif ('publish' == $vis_status) {
                $post->post_password = '';
                $visibility = 'public';

                if (('post' == $post->post_type || post_type_supports($post->post_type, 'sticky')) && is_sticky($post->ID)) {
                    $visibility_trans = PWP::__wp('Public, Sticky');
                } else {
                    $visibility_trans = PWP::__wp('Public');
                }
            } elseif ($vis_status_obj->public) {
                $post->post_password = '';
                $visibility = $vis_status;

                if (('post' == $post->post_type || post_type_supports($post->post_type, 'sticky')) && is_sticky($post->ID)) {
                    $visibility_trans = sprintf(__('%s, Sticky', 'presspermit-pro'), $vis_status_obj->label);
                } else {
                    $visibility_trans = $vis_status_obj->labels->visibility;
                }
            } else {
                $visibility = 'public';
                $visibility_trans = PWP::__wp('Public');
            }

            echo esc_html($visibility_trans); ?>
        </span>

        <?php if ($can_publish) { ?>
        <a href="#visibility" class="edit-visibility hide-if-no-js"><?php echo esc_html(PWP::__wp('Edit')); ?></a>

        <div id="post-visibility-select" class="hide-if-js">
            <input type="hidden" name="hidden_post_password" id="hidden-post-password" value="<?php echo esc_attr($post->post_password); ?>"/>
            <?php if (post_type_supports($post->post_type, 'sticky')): ?>
                <input type="checkbox" style="display:none" name="hidden_post_sticky" id="hidden-post-sticky" value="sticky" <?php checked(is_sticky($post->ID)); ?> />
            <?php endif; ?>
            <input type="hidden" name="hidden_post_visibility" id="hidden-post-visibility" value="<?php echo esc_attr($visibility); ?>"/>

            <input type="radio" name="visibility" id="visibility-radio-public" value="public" <?php checked($visibility, 'public'); ?> /> 
                <label for="visibility-radio-public" class="selectit"><?php echo esc_html(PWP::__wp('Public')); ?></label><br/>

            <?php
            if ((($post->post_type == 'post') || post_type_supports($post->post_type, 'sticky')) && current_user_can('edit_others_posts')) : ?>
                <span id="sticky-span">
                    <input id="sticky" name="sticky" type="checkbox" value="sticky" <?php checked(is_sticky($post->ID)); ?> tabindex="4"/> 
                    <label for="sticky" class="selectit"><?php echo esc_html(PWP::__wp('Stick this to the front page')) ?></label><br/>
                </span>
            <?php endif; ?>

            <input type="radio" name="visibility" id="visibility-radio-password" value="password" <?php checked($visibility, 'password'); ?> /> 
            <label for="visibility-radio-password" class="selectit"><?php echo esc_html(PWP::__wp('Password protected')); ?></label><br/>

            <span id="password-span"><label for="post_password"><?php echo esc_html(PWP::__wp('Password:')); ?></label>
            <input type="text" name="post_password" id="post_password" value="<?php echo esc_attr($post->post_password); ?>"/><br/>
            </span>

            <?php if ($_status_obj = get_post_status_object('private')) : ?>
                <input type="radio" name="visibility" id="visibility-radio-private" value="private" <?php checked($visibility, 'private'); ?> /> 
                <label for="visibility-radio-private" class="selectit"><?php echo esc_html($_status_obj->label) ?></label>
                <br/>
            <?php endif; ?>

            <?php
            if (!PPS::privacyStatusesDisabled()) :
                $i = 0;
                $pvt_stati = presspermit()->admin()->orderTypes(
                    PWP::getPostStatuses(
                        ['private' => true, 'post_type' => $post->post_type], 
                        'object'
                    ),
                    ['order_property' => 'label']
                );
                
                foreach ($pvt_stati as $_status => $status_obj) :
                    $i++;

                    if ('private' == $_status)
                        continue;

                    if (!$is_administrator) {
                        if (empty($type_obj->cap->set_posts_status)) {
                            $set_status_cap = $type_obj->cap->publish_posts;
                        } else {
                            $_caps = $attributes->getConditionCaps(
                                $type_obj->cap->set_posts_status, 
                                $post->post_type, 
                                'post_status', 
                                $_status
                            );

                            if (!$set_status_cap = reset($_caps)) {
                                $set_status_cap = $type_obj->cap->set_posts_status;
                            }
                        }

                        if (!current_user_can($set_status_cap))
                            continue;
                    }
                    ?>
                    <input type="radio" name="visibility" class="pvt-custom" id="visibility-radio-<?php echo esc_attr($_status) ?>" value="<?php echo esc_attr($_status) ?>" <?php checked($visibility, $_status); ?> />
                    <label for="visibility-radio-<?php echo esc_attr($_status) ?>" class="selectit"><?php echo esc_html($status_obj->label) ?></label>

                    <?php
                    if ($i == count($pvt_stati)) {
                        if ((current_user_can('pp_define_post_status') || current_user_can('pp_define_privacy'))) {
                            $url = admin_url('admin.php?page=presspermit-statuses&amp;attrib_type=private');
                            echo "<a href='" . esc_url($url) . "' class='pp-postsubmit-add-privacy' target='_blank'>" . esc_html__('define privacy types', 'presspermit-pro') . '</a>';
                        }
                    }
                    ?>
                    <br/>
                <?php
                endforeach;

            endif; // custom privacy statuses enabled
            ?>
            <?php if ($type_obj->hierarchical) : ?>
                <p>
        <span id="pp-propagate-privacy-span">
            <input id="pp-propagate-privacy" name="pp-propagate-privacy" class="pp-submitbox-customize " type="checkbox" value="1"/>
            <label for="pp-propagate-privacy" class="selectit">
            <?php printf(
                esc_html__('mirror selection to %1$s sub%2$s%3$s', 'presspermit-pro'), 
                '<a href="#child-visibility" class="pp-edit-ch-visibility">', 
                esc_html(strtolower($type_obj->labels->name)), 
                '</a>'
                ); 
            ?>
            </label>
        </span>
            </p>
            <?php endif; ?>
            <p>
                <a href="#visibility" class="save-post-visibility hide-if-no-js button"><?php echo esc_html(PWP::__wp('OK')); ?></a>
                <a href="#visibility" class="cancel-post-visibility hide-if-no-js"><?php echo esc_html(PWP::__wp('Cancel')); ?></a>
            </p>
        </div>
    <?php }
    }

    /**
     *  Classic Editor Post Submit Metabox: Subpost Visibility HTML
     */
    public static function subpost_visibility_display($post, $args)
    {
        $defaults = ['is_administrator' => false, 'type_obj' => false, 'can_publish' => false, 'ch_visibility' => ''];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $attributes = PPS::attributes();

        printf(esc_html(_x('Sub%1$s %2$s: ', 'restriction_attribute', 'presspermit-pro')), esc_html(strtolower($type_obj->labels->singular_name)), esc_html(PWP::__wp('Visibility'))); ?>
        <span id="ch_post-visibility-display"><?php

            if ($ch_visibility) {
                $child_status_obj = get_post_status_object($ch_visibility);
                $visibility_trans = (!empty($child_status_obj->labels)) ? $child_status_obj->labels->visibility : '';
            } else {
                $visibility_trans = esc_html__('(manual)', 'presspermit-pro');
            }

            echo esc_html($visibility_trans); ?></span>
        <?php
        if (!$can_publish) {
            return;
        } ?>

        <a href="#ch_visibility" class="ch_edit-visibility hide-if-no-js"><?php echo esc_html(PWP::__wp('Edit')); ?></a>

        <div id="ch_post-visibility-select" class="hide-if-js item-condition-select ">
            <input type="hidden" name="ch_hidden_post_visibility" id="ch_hidden-post-visibility" value="<?php echo esc_attr($ch_visibility); ?>"/>

            <input type="radio" name="ch_visibility" id="ch_visibility-radio-manual" value="" <?php checked($ch_visibility, ''); ?> /> 
            <label for="ch_visibility-radio-manual" class="selectit" title="<?php esc_attr_e('visibility of subpages set individually', 'presspermit-pro'); ?>">
            <?php esc_html_e('(manual)', 'presspermit-pro'); ?>
            </label><br/>

            <input type="radio" name="ch_visibility" id="ch_visibility-radio-publish" value="publish" <?php checked($ch_visibility, 'publish'); ?> /> 
            <label for="ch_visibility-radio-publish" class="selectit" title="<?php esc_attr_e('visibility of subpages set individually', 'presspermit-pro'); ?>">
            <?php echo esc_html(PWP::__wp('Public')); ?>
            </label><br/>

            <?php if ($_status_obj = get_post_status_object('private')) : ?>
                <input type="radio" name="ch_visibility" id="ch_visibility-radio-private" value="private" <?php checked($ch_visibility, 'private'); ?> /> 
                <label for="ch_visibility-radio-private" class="selectit"><?php echo esc_html($_status_obj->label) ?>
                </label>
                <br/>
            <?php endif; ?>

            <?php
			if (presspermit()->getOption('privacy_statuses_enabled')):
                $pvt_stati = presspermit()->admin()->orderTypes(PWP::getPostStatuses(['private' => true, 'post_type' => $post->post_type], 'object'), ['order_property' => 'label']);
                
                foreach ($pvt_stati as $_status => $_status_obj) :
                    if ('private' == $_status)
                        continue;

                    if (!$is_administrator) {
                        if (empty($type_obj->cap->set_posts_status)) {
                            $set_status_cap = $type_obj->cap->publish_posts;
                        } else {
                            $cond_caps = $attributes->getConditionCaps($type_obj->cap->set_posts_status, $post->post_type, 'post_status', $_status);
                            if (!$set_status_cap = reset($cond_caps)) {
                                $set_status_cap = $type_obj->cap->set_posts_status;
                            }
                        }

                        if (!current_user_can($set_status_cap))
                            continue;
                    }
                    ?>
                    <input type="radio" name="ch_visibility" class="pvt-custom" id="ch_visibility-radio-<?php echo esc_attr($_status) ?>" value="<?php echo esc_attr($_status) ?>" <?php checked($ch_visibility, $_status); ?> /> 
                    <label for="ch_visibility-radio-<?php echo esc_attr($_status) ?>" class="selectit"><?php echo esc_html($_status_obj->label) ?>
                    </label>
                    <br/>
                <?php
                endforeach; 
            endif;    
            ?>
            <p>
                <a href="#child-visibility" class="ch_save-post-visibility hide-if-no-js button"><?php echo esc_html(PWP::__wp('OK')); ?></a>
            </p>
        </div>
        <?php
    }

    /**
     *  Classic Editor Post Submit Metabox: Post Time Display HTML
     */
    public static function post_time_display($post, $args)
    {
        global $action;

        if (empty($args['post_status_obj'])) return;

        $post_status_obj = $args['post_status_obj'];
        ?>
        <span id="timestamp">
        <?php
        // translators: Publish box date formt, see http://php.net/date
        $datef = PWP::__wp('M j, Y @ G:i');

        if (0 != $post->ID) {
            $published_stati = get_post_stati(['public' => true, 'private' => true], 'names', 'or');
            $date = date_i18n($datef, strtotime($post->post_date));

            if ('future' == $post_status_obj->name) { // scheduled for publishing at a future date
                printf(esc_html__('Scheduled for: %s%s%s'), '<strong>', esc_html($date), '</strong>');

            } elseif (in_array($post_status_obj->name, $published_stati)) { // already published
                printf(esc_html__('Published on: %s%s%s'), '<strong>', esc_html($date), '</strong>');

            } elseif (in_array($post->post_date_gmt, [constant('PRESSPERMIT_MIN_DATE_STRING'), '0000-00-00 00:00:00'])) { // draft, 1 or more saves, no date specified
                echo apply_filters(
                    'presspermit_post_editor_immediate_caption', 
                    sprintf(
                        esc_html__('Publish %simmediately%s'),
                        '<strong>',
                        '</strong>'
                    ),
                    $post
                );

            } elseif (time() < strtotime($post->post_date_gmt . ' +0000')) { // draft, 1 or more saves, future date specified
                printf(esc_html__('Schedule for: %s%s%s'), '<strong>', esc_html($date), '</strong>');

            } else { // draft, 1 or more saves, date specified
                printf(esc_html__('Publish on: %s%s%s'), '<strong>', esc_html($date), '</strong>');
            }
        } else { // draft (no saves, and thus no date specified)
            printf(
                esc_html__('Publish %simmediately%s'),
                '<strong>',
                '</strong>'
            );
        }
        ?></span>
        <a href="#edit_timestamp" class="edit-timestamp hide-if-no-js" tabindex='4'><?php echo esc_html(PWP::__wp('Edit')) ?></a>
        <div id="timestampdiv" class="hide-if-js"><?php touch_time(($action == 'edit'), 1, 4); ?></div>
        <?php
    }

    /**
     *  Classic Editor Post Submit Metabox: Post Publish Button HTML
     */
    public static function post_publish_ui($post, $args)
    {
        $defaults = ['post_status_obj' => false, 'can_publish' => false, 'moderation_statuses' => []];
        $args = array_merge($defaults, $args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }
        ?>
        <span class="spinner"></span>

        <?php
        if ((!$post_status_obj->public && !$post_status_obj->private && ('future' != $post_status_obj->name))) {
            $status_obj = PPS::defaultStatusProgression($post);

            if (!empty($status_obj->public) || !empty($status_obj->private)) :
                if (!empty($post->post_date_gmt) && time() < strtotime($post->post_date_gmt . ' +0000')) :
                    $future_status_obj = get_post_status_object('future');
                    ?>
                    <input name="original_publish" type="hidden" id="original_publish" value="<?php echo esc_attr($future_status_obj->labels->publish) ?>"/>
                    <input name="publish" type="submit" class="button button-primary button-large" id="publish" tabindex="5" accesskey="p" value="<?php echo esc_attr($future_status_obj->labels->publish) ?>"/>
                <?php
                else :
                    $publish_status_obj = get_post_status_object('publish');
                    ?>
                    <input name="original_publish" type="hidden" id="original_publish" value="<?php echo esc_attr($publish_status_obj->labels->publish) ?>"/>
                    <input name="publish" type="submit" class="button button-primary button-large" id="publish" tabindex="5" accesskey="p" value="<?php echo esc_attr($publish_status_obj->labels->publish) ?>"/>
                <?php
                endif;
            else :
                echo '<input name="pp_submission_status" type="hidden" id="pp_submission_status" value="' . esc_attr($status_obj->name) . '" />';
                ?>
                <input name="original_publish" type="hidden" id="original_publish" value="<?php echo esc_attr($status_obj->labels->publish) ?>"/>
                <input name="publish" type="submit" class="button button-primary button-large" id="publish" tabindex="5" accesskey="p" value="<?php echo esc_attr($status_obj->labels->publish) ?>"/>
            <?php
            endif;
        } else { ?>
            <input name="original_publish" type="hidden" id="original_publish" value="<?php echo esc_attr(PWP::__wp('Update')); ?>"/>
            <input name="save" type="submit" class="button button-primary button-large" id="publish" tabindex="5" accesskey="p" value="<?php echo esc_attr(PWP::__wp('Update')); ?>"/>
            <?php
        }
    }
}
