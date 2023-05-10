<?php
/**
 * @package     PublishPress\Puvlish
 * @author      PublishPress <help@publishpress.com>
 * @copyright   Copyright (C) 2018 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro\WooCommerce\Requirement;


use PublishPress\ChecklistsPro\Factory;
use PublishPress\ChecklistsPro\HooksAbstract;
use stdClass;
use WPPF\Plugin\ServicesAbstract;
use WPPF\WP\HooksHandlerInterface;

class Backorder extends Base
{
    /**
     * The name of the requirement, in a slug format
     *
     * @var string
     */
    public $name = 'backorder';

    /**
     * @var HooksHandlerInterface
     */
    private $hooksHandler;

    /**
     * @inheritDoc
     */
    public function __construct($module, $post_type)
    {
        parent::__construct($module, $post_type);

        $container = Factory::getContainer();

        $this->hooksHandler = $container->get(ServicesAbstract::HOOKS_HANDLER);
    }

    /**
     * Method to initialize the Requirement, adding filters and actions to
     * interact with the Add-on.
     *
     * @return void
     */
    public function init()
    {
        parent::init();

        $this->hooksHandler->addFilter(HooksAbstract::FILTER_LOCALIZED_DATA, [$this, 'localize_data']);
    }

    /**
     * Initialize the language strings for the instance
     *
     * @return void
     */
    public function init_language()
    {
        $this->lang['label_settings'] = esc_html__('Check the "Allow backorders?" box', 'publishpress-checklists-pro');
        $this->lang['label_yes']      = esc_html__('Allow backorders', 'publishpress-checklists-pro');
        $this->lang['label_notify']   = esc_html__('Allow backorders, but notify', 'publishpress-checklists-pro');
        $this->lang['label_no']       = esc_html__('Do not allow backorders', 'publishpress-checklists-pro');
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

        $backorder = $this->get_option('backorder');

        // Register in the requirements list
        $requirements[$this->name] = [
            'status'    => $this->get_current_status($post, $backorder),
            'label'     => $this->lang['label_' . $backorder],
            'value'     => $backorder,
            'rule'      => $this->get_option_rule(),
            'is_custom' => false,
            'type'      => $this->type,
        ];

        return $requirements;
    }

    /**
     * Returns the current status of the requirement.
     *
     * @param stdClass $post
     * @param mixed $option_value
     *
     * @return mixed
     */
    public function get_current_status($post, $option_value)
    {
        $product = $this->get_product($post->ID);

        $allow = $product->get_backorders();

        return $allow === $option_value;
    }

    /**
     * Get the HTML for the setting field for the specific post type.
     *
     * @param string $css_class
     *
     * @return string
     */
    public function get_setting_field_html($css_class = '')
    {
        $post_type = esc_attr($this->post_type);
        $css_class = esc_attr($css_class);

        $backorder = $this->get_option('backorder');

        $option_name_backorder = $this->name . '_backorder';

        // Get the field markup
        $html = sprintf(
            '<select type="text" " id="%s" name="%s" value="%s">',
            "{$post_type}-{$this->module->slug}-{$option_name_backorder}",
            "{$this->module->options_group_name}[{$option_name_backorder}][{$post_type}]",
            $backorder
        );

        $html .= sprintf(
            '<option value="%s" %s>%s</option>',
            'no',
            $backorder === 'no' ? 'selected="selected"' : '',
            esc_html__('Do not allow', 'publishpress-checklists-pro')
        );

        $html .= sprintf(
            '<option value="%s" %s>%s</option>',
            'yes',
            $backorder === 'yes' ? 'selected="selected"' : '',
            esc_html__('Allow', 'publishpress-checklists-pro')
        );

        $html .= sprintf(
            '<option value="%s" %s>%s</option>',
            'notify',
            $backorder === 'notify' ? 'selected="selected"' : '',
            esc_html__('Allow, but notify customer', 'publishpress-checklists-pro')
        );

        $html .= '</select>';

        return $html;
    }

    /**
     * Add data to the javascript script
     *
     * @param array $data
     *
     * @return array
     */
    public function localize_data($data)
    {
        $data['backorder'] = $this->get_option('backorder');

        return $data;
    }
}
