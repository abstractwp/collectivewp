<?php
namespace PublishPress\Permissions;

class Teaser
{
    private $teased_posts = [];

    private static $instance = null;

    public static function instance() {
        if ( is_null(self::$instance) ) {
            self::$instance = new Teaser();
        }

        return self::$instance;
    }

    private function __construct() 
    {
        // function access by other modules
        add_filter('presspermit_teased_posts', [$this, 'getTeasedPosts'], 99);
        add_filter('presspermit_teaser_hidden_statuses', [$this, 'fltGetHiddenStatuses'], 99, 2);
        add_action('presspermit_teaser_init_template', [__CLASS__, 'initTemplate']);
    }

    public static function initTemplate()
    {
        require_once(PRESSPERMIT_TEASER_CLASSPATH . '/PostsTeaser.php');
        return new Teaser\PostsTeaser();
    }

    function getTeasedPosts($unused_arg = null) 
    {
        return $this->teased_posts;
    }

    function setTeasedPost($post_id)
    {
        $this->teased_posts[$post_id] = true;
    }

    function isTeaser($id = '', $source_name = 'post')
    {
        if (empty($this->teased_posts) || (is_home() && is_single())) {
            return false;
        }

        if (!$id && ('post' == $source_name)) {
            global $post;

            if (empty($post) || empty($post->ID))
                return false;

            $id = $post->ID;
        }

        return (isset($this->teased_posts[$id]));
    }

    function fltGetHiddenStatuses($statuses, $post_type)
    {
        return self::getHiddenStatuses($post_type);
    }

    public static function getHiddenStatuses($post_type)
    {
        $pp = presspermit();

        if (!$pp->getTypeOption('tease_public_posts_only', $post_type))
            return [];

        $hide_stati = get_post_stati(['private' => true]);

        if ($pp->getOption('teaser_hide_custom_private_only'))
            $hide_stati = array_diff($hide_stati, ['private']);

        return $hide_stati;
    }

    public static function noTeaseTypes()
    {
        return apply_filters('presspermit_teaser_no_tease_types', ['topic' => 'topic', 'reply' => 'reply', 'tribe_events' => 'tribe_events']);
    }
}
