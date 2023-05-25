<?php
namespace PublishPress\Permissions\FileAccess;

class RuleFlush
{
    public static function requestedFileRuleFlush()
    {
        if (defined('PRESSPERMIT_LIMIT_HTACCESS_REQUESTED_REGEN')) {
            return;
        }

        if ($key = presspermit()->getOption('file_filtering_regen_key')) {
            if (presspermit_is_GET('key', $key)) {  // user must store their own non-null key before this will work
                self::flushAllFileRules(['echo' => true]);
            } else
                esc_html_e('Invalid argument.', 'presspermit');
        } else
            esc_html_e('Please configure File Filtering options!', 'presspermit');

        exit(0);
    }

    public static function flushAllFileRules($args=[])
    {
        global $wpdb;

        $blog_id = get_current_blog_id();

        $blog_ids = (is_multisite()) ? $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs ORDER BY blog_id") : [1];
        $orig_blog_id = $blog_id;

        foreach ($blog_ids as $id) {
            if (is_multisite()) {
                switch_to_blog($id);
            }

            if ($id == $orig_blog_id) {
                FileAccess::flushFileRules(['regenerate_keys' => true]);

                if (!empty($args['echo'])) {
                    esc_html_e("File attachment access keys and rewrite rules were regenerated for this site (" . get_bloginfo() . ")");
                    echo '<br /><br />';
                }
            } else {
                $wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key = '_rs_file_key'");
                update_option('presspermit_file_rules_expired', true);
                update_option('presspermit_regenerate_file_keys', true);
            }
        }

        if (is_multisite()) {
            switch_to_blog($orig_blog_id);

            if (!empty($args['echo'])) {
                esc_html_e("File attachment access keys and rewrite rules for other sites will be regenerated at next access.", 'presspermit');
            }
        }
    }

    // remove rules from every .htaccess file in the wp-MU "files" folders
    public static function clearAllFileRules()
    {
        global $wpdb;

        $blog_id = get_current_blog_id();

        $blog_ids = (is_multisite()) ? $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs ORDER BY blog_id") : [1];
        $orig_blog_id = $blog_id;

        foreach ($blog_ids as $id) {
            if (is_multisite()) {
                switch_to_blog($id);
            }

            $uploads = FileAccess::getUploadInfo();
            $htaccess_path = trailingslashit($uploads['basedir']) . '.htaccess';
            if (file_exists($htaccess_path)) {
                require_once(PRESSPERMIT_FILEACCESS_CLASSPATH . '/RewriteRules.php');
                RewriteRules::insertWithMarkers($htaccess_path, 'Press Permit', '');
            }
        }

        if (is_multisite()) {
            switch_to_blog($orig_blog_id);
        }
    }
}
