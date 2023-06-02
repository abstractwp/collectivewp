<?php
namespace PublishPress\Permissions\Statuses;

class AttributesAdmin
{
    // returns array [item_id][condition] = true or (if return_array=true) [ 'inherited_from' => $row->inherited_from ]
    // source_name = item source name (i.e. 'post') 
    //
    public static function getItemCondition($source_name, $attribute, $args = [])
    {
        // Note: propogating conditions are always directly assigned to the child item(s).
        // Use assign_for = 'children' to retrieve condition values that are set for propagation to child items,
        $defaults = ['id' => null, 'object_type' => '', 'assign_for' => 'item', 'default_only' => false, 'inherited_only' => false];
        $args = array_merge($defaults, (array)$args);
        foreach (array_keys($defaults) as $var) {
            $$var = $args[$var];
        }

        $id = (int) $id;
        $object_type = sanitize_key($object_type);

        if ($default_only)
            return null;

        $query_ids = (array)$id;

        $pp = presspermit();
        static $listed_object_conditions;

        if (!isset($listed_object_conditions))
            $listed_object_conditions = [];

        $object_cache_id = md5($source_name . $attribute . serialize($args));

        if (!empty($pp->listed_ids) && empty($listed_object_conditions[$object_cache_id])) {
            foreach (array_keys($pp->listed_ids) as $_type) {
                $query_ids = array_merge($query_ids, array_keys($pp->listed_ids[$_type]));
            }
        } elseif (!empty($listed_object_conditions[$object_cache_id])) {
            if ($results = array_intersect_key($listed_object_conditions[$object_cache_id], array_flip($query_ids))) {
                if (count($results) == count($query_ids))
                    return $results;
            }
        }

        global $wpdb;

        $items = [];

        if ($query_ids) { // don't return all objects
            $query_id_csv = implode("','", array_map('intval', array_unique($query_ids)));
            $id_clause = "AND item_id IN ('$query_id_csv')";
        } else {
            $id_clause = "AND item_id != 0";
        }

        $inherited_clause = (!empty($args['inherited_only'])) ? "AND inherited_from > 0" : '';

        static $all_attrib_conditions;
        if (!isset($all_attrib_conditions))
            $all_attrib_conditions = [];

        $qry = $wpdb->prepare(
            "SELECT attribute, condition_name, item_id, inherited_from FROM $wpdb->pp_conditions"
            . " WHERE scope = 'object' AND assign_for = %s AND item_source = %s $inherited_clause $id_clause", 
            
            $assign_for, 
            $source_name
        );
        
        $qkey = md5($qry);

        if (!isset($all_attrib_conditions[$qkey])) {
            $all_attrib_conditions[$qkey] = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT attribute, condition_name, item_id, inherited_from FROM $wpdb->pp_conditions"
                    . " WHERE scope = 'object' AND assign_for = %s AND item_source = %s $inherited_clause $id_clause", 
                    
                    $assign_for, 
                    $source_name
                )
            );
        }

        if (isset($all_attrib_conditions[$qkey])) {
            foreach ($all_attrib_conditions[$qkey] as $row) {
                if ($attribute == $row->attribute) {
                    $items[$row->item_id][$row->condition_name] = true;
                }
            }

            if (empty($listed_object_conditions[$object_cache_id]))
                $listed_object_conditions[$object_cache_id] = $items;
        }

        return (!is_null($id) && isset($items[$id])) ? key($items[$id]) : null;
    }
}
