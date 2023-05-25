<?php
namespace PublishPress\Permissions;

class SyncHooksAdmin
{
    function __construct()
    {
        add_action('presspermit_options_ui', [$this, 'optionsUI']);
        add_action('admin_enqueue_scripts', [$this, 'act_scripts']);
    }

    function optionsUI()
    {
        require_once(PRESSPERMIT_SYNC_CLASSPATH . '/UI/SettingsTabSyncPosts.php');
        new SyncPosts\UI\SettingsTabSyncPosts();
    }

    function act_scripts()
    {
        if ('presspermit-settings' == presspermitPluginPage()) {
            $urlpath = plugins_url('', PRESSPERMIT_SYNC_FILE);
            wp_enqueue_style('presspermit-sync-settings', $urlpath . '/common/css/settings.css', [], PRESSPERMIT_SYNC_VERSION);

            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '-dev' : '';
            wp_enqueue_script('presspermit-sync-settings', $urlpath . "/common/js/settings{$suffix}.js", ['jquery'], PRESSPERMIT_SYNC_VERSION);
        }
    }
}
