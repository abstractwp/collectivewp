<?php
/**
 * @package     PublishPress\ChecklistsPro
 * @author      PublishPress <help@publishpress.com>
 * @copyright   copyright (C) 2019 PublishPress. All rights reserved.
 * @license     GPLv2 or later
 * @since       1.0.0
 */

namespace PublishPress\ChecklistsPro;

abstract class HooksAbstract
{
    const ACTION_CHECKLIST_LOAD_ADDONS = 'publishpress_checklists_load_addons';

    const ACTION_CHECKLIST_ENQUEUE_SCRIPTS = 'publishpress_checklists_enqueue_scripts';

    const ACTION_ADMIN_ENQUEUE_SCRIPTS = 'admin_enqueue_scripts';

    const ACTION_CHECKLISTS_REGISTER_SETTINGS = 'publishpress_checklists_register_settings_before';

    const FILTER_MODULES_DIRS = 'publishpress_checklists_module_dirs';

    const FILTER_WOOCOMMERCE_DEFAULT_OPTIONS = 'ppchpro_woocommerce_default_options';

    const FILTER_POST_TYPE_REQUIREMENTS = 'publishpress_checklists_post_type_requirements';

    const FILTER_LOCALIZED_DATA = 'ppchpro_localized_data';

    const FILTER_VALIDATE_MODULE_SETTINGS = 'publishpress_checklists_validate_module_settings';

    const FILTER_DISPLAY_BRANDING = 'publishpress_checklist_display_branding';
}
