<?php
namespace PublishPress\Permissions\Statuses\DB;

require_once(PRESSPERMIT_STATUSES_ABSPATH . '/db-config.php');

class DatabaseSetup
{
    public function __construct($last_db_ver)
    {
        if (MULTISITE) {
            add_action('switch_blog', [__CLASS__, 'multisite_db_support']);
        }

        self::updateSchema($last_db_ver);
    }

    private static function updateSchema($last_db_ver)
    {
        global $wpdb;

        $charset_collate = '';

        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        // note: dbDelta requires two spaces after PRIMARY KEY, no spaces between KEY columns

        // Conditions table def 
        //
        // This table is used to store "child post visibility" per-post. It does not pertain to post status registration. 
        // Some of the schema is carryover from PP < 2.0, where it had a broader purpose.
        $tabledefs = "CREATE TABLE $wpdb->pp_conditions (
         assignment_id bigint(20) NOT NULL auto_increment,
         attribute varchar(32) NOT NULL default '',
         condition_name varchar(32) NOT NULL default '',
         scope enum('site', 'term', 'object') NOT NULL,
         item_source varchar(32) NOT NULL,
         item_id bigint(20) NOT NULL,
         assign_for enum('item', 'children') NOT NULL default 'item',
         mode enum('set', 'force', 'default') NOT NULL default 'set',
         inherited_from bigint(20) NOT NULL default '0',
            PRIMARY KEY  (assignment_id),
            KEY pp_item_condition (scope,assign_for,mode,attribute,condition_name,item_source,item_id),
            KEY pp_item_cond_assign (scope,assign_for,mode,attribute,condition_name,item_source,item_id,inherited_from,assignment_id) )
            $charset_collate
        ;
        ";

        require_once(PRESSPERMIT_CLASSPATH . '/DB/DatabaseSetup.php');
                
        // apply all table definitions
        \PublishPress\Permissions\DB\DatabaseSetup::dbDelta($tabledefs);
    }

    public static function multisite_db_support()
    {
        require_once(PRESSPERMIT_STATUSES_ABSPATH . '/db-config.php');
    }
}
