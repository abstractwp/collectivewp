<?php
namespace PublishPress\Permissions\Statuses\UI;

class PublishPressSettings
{
    public static function scripts() 
    {
        ?>
        <script type="text/javascript">
            /* <![CDATA[ */
            jQuery(document).ready(function ($) {
                <?php
                if ($term_id = presspermit_REQUEST_var('term-id')) {
                    global $publishpress;
                    if ($status_term = $publishpress->custom_status->get_custom_status_by('id', $term_id)) {
                        $url = admin_url("admin.php?page=presspermit-status-edit&action=edit&status={$status_term->slug}");
                    }
                }
                if (empty($url)) {
                    $url = admin_url('admin.php?page=presspermit-statuses&attrib_type=moderation');
                }

                ?>
                $('div.pp-module-settings form').first().after(
                    '<div class="pp-extra-heading pp-statuses-other-config"><h4><?php esc_html_e('Additional Status Settings:', 'presspermit-pro');?></h4>'
                    + '<ul><li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Require Custom Permissions per-Status', 'presspermit-pro');?></a></li>'
                    + '<li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Workflow Order, Branching, Button Labels', 'presspermit-pro');?></a></li>'
                    + '<li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Set Post Types per-Status', 'presspermit-pro');?></a></li></ul>'
                    + '</div><?php 
                    if ((!PPS::publishpressStatusesActive())) {
                        echo '<br /><div class="update-nag">' . esc_html__('Please enable the Status dropdown for Gutenberg compatibility.', 'presspermit-pro') . '</div>';
                    }
                    ?>');

                <?php
                $url = admin_url("admin.php?page=presspermit-statuses&attrib_type=moderation");

                ?>
                $('#capabilities_groups ul').last().after(
                    '<div class="pp-extra-heading pp-statuses-other-config"><h4><?php esc_html_e('Additional Status Settings:', 'presspermit-pro');?></h4>'
                    + '<ul><li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Require Custom Permissions per-Status', 'presspermit-pro');?></a></li>'
                    + '<li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Workflow Order, Branching, Button Labels', 'presspermit-pro');?></a></li>'
                    + '<li><a href="<?php echo esc_url($url);?>"><?php esc_html_e('Set Post Types per-Status', 'presspermit-pro');?></a></li></ul>'
                    + '</div><div><?php printf(esc_html__('%sNote%s: "Change post status" capabilities can be overriden by %Editing Permissions enabled for specific post types%s.', 'presspermit-pro'), '<strong>', '</strong>', '<a href="' . esc_url_raw($url) . '">', '</a>');

                    if (!PPS::publishpressStatusesActive()) {
                        $_url = admin_url('admin.php?page=pp-modules-settings&module=pp-custom-status-settings');
                        echo '<br /><div class="update-nag">' . sprintf(esc_html__('Please %senable the Status dropdown%s for Gutenberg compatibility.', 'presspermit-pro'), '<a href="' . esc_url($_url) . '" style="text-decoration:underline">', '</a>') . '</div>';
                    }
                    ?></div>');
            
            });
            /* ]]> */
        </script>
        <style type="text/css">
            div.pp-statuses-other-config {
                background-color: white;
                padding: 2px 2px 2px 10px;
                margin-top: 25px;
            }

            div.pp-statuses-other-config h4 {
                margin-top: 5px;
                margin-bottom: 6px;
                padding-bottom: 0
            }

            div.pp-statuses-other-config ul {
                list-style-type: disc;
                list-style-position: outside;
                margin: 0 0 0 2em
            }

            div.pp-statuses-other-config a, div.pp-statuses-other-config a:visited {
                text-decoration: underline;
            }
        </style>
    <?php
    }
}