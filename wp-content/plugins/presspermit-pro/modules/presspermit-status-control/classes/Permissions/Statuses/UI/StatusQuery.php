<?php
namespace PublishPress\Permissions\Statuses\UI;

class StatusQuery
{
    var $attribute;
    var $attrib_type;

    /**
     * List of found group ids
     *
     * @access private
     * @var array
     */
    var $results;

    /**
     * Total number of found groups for the current query
     *
     * @access private
     * @var int
     */
    var $total_groups = 0;

    /**
     * PHP5 constructor
     *
     * @param string|array $args The query variables
     * @return WP_Group_Query
     */
    function __construct($attribute, $attrib_type, $query = null)
    {
        $this->attribute = $attribute;
        $this->attrib_type = $attrib_type;

        $this->query_vars = wp_parse_args($query, [
            'blog_id' => get_current_blog_id(),
            'include' => [],
            'exclude' => [],
            'search' => '',
            'orderby' => 'login',
            'order' => 'ASC',
            'offset' => '', 'number' => '',
            'count_total' => true,
            'fields' => 'all',
        ]);

        $this->prepare_query();
        $this->query();

    }

    function prepare_query()
    {
    }

    /**
     * Execute the query, with the current variables
     *
     * @since 3.1.0
     * @access private
     */
    function query()
    {
        $attributes = PPS::attributes();

        $args = [$this->attrib_type => true];
        $this->results = get_post_stati($args, 'object');

        foreach (array_keys($this->results) as $cond) {
            if (!empty($attributes->attributes['post_status']->conditions[$cond]->metacap_map))
                $this->results[$cond]->metacap_map = $attributes->attributes['post_status']->conditions[$cond]->metacap_map;

            if (!empty($attributes->attributes['post_status']->conditions[$cond]->cap_map))
                $this->results[$cond]->cap_map = $attributes->attributes['post_status']->conditions[$cond]->cap_map;
        }

        foreach ($this->results as $index => $row) {
            $this->results[$index]->builtin = !empty($row->_builtin);
        }

        // list in moderation order, but next status children below their parent
        if ('moderation' == $this->attrib_type) {
            $this->results = PPS::orderStatuses($this->results);
        } else {
            $this->results = presspermit()->admin()->orderTypes($this->results, ['order_property' => 'label']);
        }

        $this->total_groups = count($this->results);
    }

    // obsolete
    function get_search_sql($string, $cols, $wild = false)
    {
        return '';
    }

    /**
     * Return the list of groups
     *
     * @access public
     *
     * @return array
     */
    function get_results()
    {
        return $this->results;
    }

    /**
     * Return the total number of groups for the current query
     *
     * @access public
     *
     * @return array
     */
    function get_total()
    {
        return $this->total_groups;
    }
}
