<?php
namespace PublishPress\Permissions\Statuses\UI\Dashboard;

class BulkEdit
{
    public static function bulk_edit_posts($unused = null)
    {
        global $wpdb;

        if (!$post_id = presspermit_REQUEST_int('post')) {
            return;
        }

        $post_IDs = array_map('intval', (array) $post_id);

        $status = presspermit_REQUEST_key('_status_sub');

        if ('-1' === $status)
            return;

        require_once(PRESSPERMIT_STATUSES_CLASSPATH . '/ItemSave.php');

        $updated = $locked = $skipped = [];
        foreach ($post_IDs as $post_ID) {
            if (wp_check_post_lock($post_ID)) {
                $locked[] = $post_ID;
                continue;
            }

            \PublishPress\Permissions\Statuses\ItemSave::propagate_post_visibility($post_ID, $status);

            $updated[] = $post_ID;
        }

        return ['updated' => $updated, 'skipped' => $skipped, 'locked' => $locked];
    }
}
