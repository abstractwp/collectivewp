<?php
/**
 * @package     PublishPress\\WooCommerce
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\WooCommerce\Requirement;


use PublishPress\ChecklistsPro\Factory;
use stdClass;
use WPPF\Helper\MathInterface;
use WPPF\Plugin\ServicesAbstract;

class BaseFloatRange extends Base
{

    /**
     * @var MathInterface
     */
    private $mathHelper;

    /**
     * @inheritDoc
     */
    public function __construct($module, $post_type)
    {
        parent::__construct($module, $post_type);

        $container = Factory::getContainer();

        $this->mathHelper = $container->get(ServicesAbstract::MATH_HELPER);
    }

    /**
     * Validates the option group, making sure the values are sanitized.
     *
     * @param array $new_options
     *
     * @return array
     */
    public function filter_settings_validate($new_options)
    {
        $new_options = parent::filter_settings_validate($new_options);

        // $index = $this->name . '_value';
        // if ( isset( $new_options[ $index ][ $this->post_type ] ) ) {
        // 	// Identify the decimal separator
        // 	//$separator = preg_match( '/,', $new_options[ $index ][ $this->post_type ])

        // 	$new_options[ $index ][ $this->post_type ] = filter_var(
        // 		$new_options[ $index ][ $this->post_type ],
        // 		FILTER_SANITIZE_NUMBER_FLOAT
        // 	);
        // }

        // // Make sure we don't have 0 as value if enabled
        // if ( empty( $new_options[ $index ][ $this->post_type ] ) && static::VALUE_YES === $new_options[ $this->name ][ $this->post_type ] ) {
        // 	$new_options[ $index ][ $this->post_type ] = 1;
        // }

        return $new_options;
    }

    /**
     * Add the requirement to the list to be displayed in the metabox.
     *
     * @param array $requirements
     * @param stdClass $post
     *
     * @return array
     */
    public function filter_requirements_list($requirements, $post)
    {
        // Check if it is a compatible post type. If not, ignore this requirement.
        if (($post->post_type !== $this->post_type)
            || !$this->is_enabled()) {
            return $requirements;
        }

        $min = $this->mathHelper->sanitizeFloat($this->get_option('min'));
        $max = $this->mathHelper->sanitizeFloat($this->get_option('max'));

        // Check if both values are empty, to skip
        if (empty($min) && empty($max)) {
            return $requirements;
        }

        // Register in the requirements list
        $requirements[$this->name] = [
            'status'    => $this->get_current_status($post, [$min, $max]),
            'label'     => $this->get_label_for_values($min, $max),
            'value'     => [$min, $max],
            'rule'      => $this->get_option_rule(),
            'is_custom' => false,
            'type'      => $this->type,
        ];

        return $requirements;
    }

    /**
     * Returns the relative label for the checklist according to
     * the min and max params.
     *
     * @param float $min
     * @param float $max
     *
     * @return string
     */
    protected function get_label_for_values($min, $max)
    {
        $label = '';

        // Both same value = exact
        if ($min == $max) {
            $label = sprintf(
                _n(
                    $this->lang['label_exact_singular'],
                    $this->lang['label_exact_plural'],
                    $min,
                    'publishpress-checklists-pro'
                ),
                $min
            );
        }

        // Min not empty, max empty or < min = only min
        if (!empty($min) && ($max < $min)) {
            $label = sprintf(
                _n($this->lang['label_min_singular'], $this->lang['label_min_plural'], $min, 'publishpress-checklists-pro'),
                $min
            );
        }

        // Min not empty, max not empty and > min = both min and max
        if (!empty($min) && ($max > $min)) {
            $label = sprintf(
                esc_html__($this->lang['label_between'], 'publishpress-checklists-pro'),
                $min,
                $max
            );
        }

        // Min empty, max not empty and > min = only max
        if (empty($min) && ($max > $min)) {
            $label = sprintf(
                _n($this->lang['label_max_singular'], $this->lang['label_max_plural'], $max, 'publishpress-checklists-pro'),
                $max
            );
        }

        return $label;
    }

    /**
     * Get the HTML for the setting field for the specific post type.
     *
     * @return string
     */
    public function get_setting_field_html($css_class = '')
    {
        $post_type = esc_attr($this->post_type);
        $css_class = esc_attr($css_class);

        // Get the min value
        $min = $this->mathHelper->sanitizeFloat($this->get_option('min'));
        $max = $this->mathHelper->sanitizeFloat($this->get_option('max'));

        // Make sure to do not display 0 as value in the fields
        if (empty($min)) {
            $min = '';
        }

        if (empty($max)) {
            $max = '';
        }

        // Make sure to do not display max, if less than min
        if ($max < $min) {
            $max = '';
        }

        // Option names
        $option_name_min = $this->name . '_min';
        $option_name_max = $this->name . '_max';

        // Get the field markup for min value
        $min_field = sprintf(
            '<input type="text" " id="%s" name="%s" value="%s" class="pp-checklists-small-input pp-checklist-float" />',
            "{$post_type}-{$this->module->slug}-{$option_name_min}",
            "{$this->module->options_group_name}[{$option_name_min}][{$post_type}]",
            $min
        );

        // Get the field markup for max value
        $max_field = sprintf(
            '<input type="text" " id="%s" name="%s" value="%s" class="pp-checklists-small-input pp-checklist-float" />',
            "{$post_type}-{$this->module->slug}-{$option_name_max}",
            "{$this->module->options_group_name}[{$option_name_max}][{$post_type}]",
            $max
        );

        $html = sprintf(
            $this->lang['label_settings_params'],
            $min_field,
            $max_field
        );

        return $html;
    }
}
