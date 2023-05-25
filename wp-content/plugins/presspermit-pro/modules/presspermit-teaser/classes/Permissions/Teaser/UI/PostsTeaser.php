<?php
namespace PublishPress\Permissions\Teaser\UI;

use \PublishPress\Permissions\UI\SettingsAdmin as SettingsAdmin;

/**
 * PressPermit Custom Post Statuses administration panel.
 *
 */

class PostsTeaser
{
	var $blockEditorActive = true;

    function __construct() {
        add_filter('presspermit_option_tabs', [$this, 'optionTabs'], 7);
        add_filter('presspermit_section_captions', [$this, 'sectionCaptions']);
        add_filter('presspermit_option_captions', [$this, 'optionCaptions']);
        add_filter('presspermit_option_sections', [$this, 'optionSections']);

		$this->blockEditorActive = PWP::isBlockEditorActive();

        // This script executes on admin.php plugin page load (called by Dashboard\DashboardFilters::actMenuHandler)
        //
        $this->display();
    }

    function optionTabs($tabs)
    {
        $tabs['teaser'] = __('Teaser', 'presspermit-pro');
        return $tabs;
    }

    function sectionCaptions($sections)
    {
        $new = [
            'teaser_type' => esc_html__('Teaser Type', 'presspermit-pro'),
            'coverage' => esc_html__('Coverage', 'presspermit-pro'),
            'teaser_text' => esc_html__('Teaser Text', 'presspermit-pro'),
            'redirect' => esc_html__('Redirect', 'presspermit-pro'),
            'options' => esc_html__('Options', 'presspermit-pro'),

            'hidden_content_teaser' => esc_html__('Hidden Content Teaser', 'presspermit-pro'),
        ];
        $key = 'teaser';
        $sections[$key] = (isset($sections[$key])) ? array_merge($sections[$key], $new) : $new;
        return $sections;
    }

    function optionCaptions($captions)
    {
        $opt = [
            'rss_private_feed_mode' => esc_html__('Display mode for readable private posts', 'presspermit-pro'),
            'rss_nonprivate_feed_mode' => esc_html__('Display mode for readable non-private posts', 'presspermit-pro'),
            'feed_teaser' => esc_html__('Feed Replacement Text (use %permalink% for post URL)', 'presspermit-pro'),
            'teaser_hide_thumbnail' => esc_html__('Hide Featured Image when Teaser is applied', 'presspermit-pro'),
            'teaser_hide_custom_private_only' => esc_html__('"Hide Private" settings only apply to custom privacy (Member, Premium, Staff, etc.)', 'presspermit-pro'),
        ];

        return array_merge($captions, $opt);
    }

    function optionSections($sections)
    {
        $new = [
            'teaser_type' => ['use_teaser', 'tease_logged_only'],
            'coverage' => ['teaser_hide_custom_private_only', 'tease_public_posts_only', 'tease_direct_access_only'],
            'menu' => [''],
            'redirect' => ['teaser_redirect_anon', 'teaser_redirect_anon_page', 'teaser_redirect', 'teaser_redirect_page', 'teaser_redirect_custom_login_page_anon', 'teaser_redirect_custom_login_page'],
            'teaser_text' => ['tease_replace_content', 'tease_replace_content_anon', 'tease_prepend_content', 'tease_prepend_content_anon',
                              'tease_append_content', 'tease_append_content_anon', 'tease_prepend_name', 'tease_prepend_name_anon',
                              'tease_append_name', 'tease_append_name_anon', 'tease_replace_excerpt', 'tease_replace_excerpt_anon',
                              'tease_prepend_excerpt', 'tease_prepend_excerpt_anon', 'tease_append_excerpt', 'tease_append_excerpt_anon'],
            'hidden_content_teaser' => ['teaser_hide_custom_private_only'],
            'options' => ['teaser_hide_thumbnail', 'rss_private_feed_mode', 'rss_nonprivate_feed_mode', 'feed_teaser'],
        ];

        $key = 'teaser';
        $sections[$key] = (isset($sections[$key])) ? array_merge($sections[$key], $new) : $new;
        return $sections;
    }

    function getStr($code) {
        return apply_filters('presspermit_admin_get_string', '', $code);
    }

    private function display() {
        echo '<form id="pp_settings_form" action="" method="post">';
        wp_nonce_field('pp-update-options');
        
		// Default active tab
		$current_tab = presspermit_is_REQUEST( 'presspermit_submit' )
			? sanitize_text_field( $_REQUEST['current_tab'] )
			: 'ppp-tab-teaser-type';
        ?>
        <input type="hidden" value="<?php echo $current_tab ?>" id="current_tab" name="current_tab">
        <div class="wrap pressshack-admin-wrapper pp-conditions">
            <header>
                <h1 class="wp-heading-inline">
                    <?php
                    echo esc_html(__('Posts Teaser', 'presspermit-pro'));
                    ?>
                </h1>
            </header>

			<?php
			if ( presspermit_is_REQUEST( 'presspermit_submit' ) || presspermit_is_REQUEST( 'presspermit_submit_redirect') ) :
				// Get new active tab to keep it opened after saving changes
				$current_tab = sanitize_text_field( $_REQUEST['current_tab'] );
				?>
                <div id="message" class="updated">
                    <p>
                        <?php esc_html_e( 'All post teaser settings were updated.', 'presspermit-pro' ); ?>
                    </p>
                </div>
			<?php
			elseif ( presspermit_is_REQUEST( 'presspermit_defaults' ) ) :
                ?>
                <div id="message" class="updated">
                    <p>
                        <?php esc_html_e( 'All post teaser settings were reset to defaults.', 'presspermit-pro' ); ?>
                    </p>
                </div>
         		<?php
			endif;
			?>

            <ul id="publishpress-permissions-teaser-tabs" class="nav-tab-wrapper">
                <li class="nav-tab<?php echo $current_tab === 'ppp-tab-teaser-type' ? ' nav-tab-active' : '' ?>">
                  <a href="#ppp-tab-teaser-type">
                      <?php _e('Teaser Type', 'presspermit-pro') ?>
                  </a>
                </li>

                <li class="nav-tab<?php echo $current_tab === 'ppp-tab-coverage' ? ' nav-tab-active' : '' ?>">
                  <a href="#ppp-tab-coverage">
                      <?php _e('Coverage', 'presspermit-pro') ?>
                  </a>
                </li>

                <li class="nav-tab<?php echo $current_tab === 'ppp-tab-teaser-text' ? ' nav-tab-active' : '' ?>">
                  <a href="#ppp-tab-teaser-text">
                      <?php _e('Teaser Text', 'presspermit-pro') ?>
                  </a>
                </li>

                <li class="nav-tab<?php echo $current_tab === 'ppp-tab-redirect' ? ' nav-tab-active' : '' ?>">
                  <a href="#ppp-tab-redirect">
                      <?php _e('Redirect', 'presspermit-pro') ?>
                  </a>
                </li>

                <li class="nav-tab<?php echo $current_tab === 'ppp-tab-options' ? ' nav-tab-active' : '' ?>">
                  <a href="#ppp-tab-options">
                      <?php _e('Options', 'presspermit-pro') ?>
                  </a>
                </li>
            </ul>

            <div id="pp-teaser">

            <?php
            $pp = presspermit();

            do_action('presspermit_teaser_settings_ui');

            require_once(PRESSPERMIT_CLASSPATH . '/UI/SettingsAdmin.php');
            $ui = SettingsAdmin::instance();
            $tab = 'teaser';

            $ui->all_options = [];

            $ui->tab_captions = apply_filters('presspermit_option_tabs', []);
            $ui->section_captions = apply_filters('presspermit_section_captions', []);
            $ui->option_captions = apply_filters('presspermit_option_captions', []);
            $ui->form_options = apply_filters('presspermit_option_sections', []);

            $ui->display_hints = presspermit()->getOption('display_hints');

            if ($_hidden = apply_filters('presspermit_hide_options', [])) {
                $hidden = [];
                foreach (array_keys($_hidden) as $option_name) {
                    if (!is_array($_hidden[$option_name]) && strlen($option_name) > 3)
                        $hidden[] = substr($option_name, 3);
                }

                foreach (array_keys($ui->form_options) as $tab) {
                    foreach (array_keys($ui->form_options[$tab]) as $section)
                        $ui->form_options[$tab][$section] = array_diff($ui->form_options[$tab][$section], $hidden);
                }
            }



        // --- TEASER TYPE SECTION ---
        $section = 'teaser_type';

        $default_options = apply_filters('presspermit_teaser_default_options', []);

        $opt_available = array_diff_key(array_fill_keys($pp->getEnabledPostTypes(), 0), ['tribe_events' => true]);
        $no_tease_types = Teaser::noTeaseTypes();

        $option_use_teaser = 'tease_post_types';
        $ui->all_otype_options[] = $option_use_teaser;
        $opt_vals = $ui->getOptionArray($option_use_teaser);
        $use_teaser = array_diff_key(array_merge($opt_available, $default_options[$option_use_teaser], $opt_vals), $no_tease_types);

        $option_num_chars = 'teaser_num_chars';
        $ui->all_otype_options[] = $option_num_chars;
        $arr_num_chars = $ui->getOptionArray($option_num_chars);

        if (!empty($ui->form_options[$tab][$section])) : ?>
            <section id="ppp-tab-teaser-type" style="display:<?php echo $current_tab === 'ppp-tab-teaser-type' ? 'block' : 'none' ?>;">
			<p>
            <?php
			if (empty($displayed_teaser_caption)) {
                if ($ui->display_hints) {
                    SettingsAdmin::echoStr('display_teaser');
                    //SettingsAdmin::echoStr('teaser_prefix_suffix_note');
                }

                $displayed_teaser_caption = true;
            }
			?>
			</p>

			<?php
            $option_logged_only = 'tease_logged_only';
            $ui->all_otype_options[] = $option_logged_only;
            $opt_vals = $ui->getOptionArray($option_logged_only);
            $logged_only = array_diff_key(array_merge($opt_available, $default_options[$option_logged_only], $opt_vals), $no_tease_types);

            $use_teaser = array_diff_key(array_intersect_key($use_teaser, array_fill_keys($pp->getEnabledPostTypes(), true)), array_fill_keys(['tribe_events'], true));
            $use_teaser = $pp->admin()->orderTypes($use_teaser, ['item_type' => 'post']);

            $any_teased_types = array_filter($use_teaser);

            echo "<div id='teaser_usage-post'><table class='widefat fixed striped teaser-table'>";
			echo "<thead><tr>";
			echo "<th></th>";
            echo "<th>" . __( 'Teaser Type', 'presspermit-pro' ) . "</th>";
            
            $style = ($any_teased_types) ? '' : " style='display:none;'";
			echo "<th class='pp-teaser-user-application'><span{$style}>" . __( 'User Application', 'presspermit-pro' ) . "<span></th>";
			echo "</tr></thead>";

            // loop through each object type (for current source) to provide a use_teaser checkbox
            foreach ($use_teaser as $object_type => $teaser_setting) {
                if ($type_obj = get_post_type_object($object_type)) {
                    $item_label_singular = $type_obj->labels->singular_name;
                } else {
                    $item_label_singular = $object_type;
                }

                if (is_bool($teaser_setting) || is_numeric($teaser_setting))
                    $teaser_setting = intval($teaser_setting);

                $id = $option_use_teaser . '-' . $object_type;
                $name = "tease_post_types[$object_type]";

                echo "<tr class='teaser-post-type'><th>";

                echo "<label>";
                echo esc_html($item_label_singular);
                echo '</label>';

                echo "</th><td><select name='" . esc_attr($name) . "' id='" . esc_attr($id) . "' class='teaser-$object_type' autocomplete='off'>";

                $captions = apply_filters(
                    'presspermit_teaser_enable_options',
                    [
                        0 => esc_html__("No Teaser", 'presspermit-pro'),
                        1 => esc_html__("Configured Teaser Text", 'presspermit-pro'),
                        'excerpt' => esc_html__("Excerpt as Teaser", 'presspermit-pro'),
                        'more' => esc_html__("Excerpt or pre-More as Teaser", 'presspermit-pro'),
                        'x_chars' => __("Excerpt, pre-More or First X Characters", 'presspermit-pro')
                    ],
                    $object_type,
                    $teaser_setting
                );

				if ($this->blockEditorActive) {
                    unset($captions['more']);
                    $captions['x_chars'] = __("Excerpt or First X Characters", 'presspermit-pro');

                    if ('more' === $teaser_setting) {
                        $teaser_setting = 'x_chars';
                    }
                }

                foreach ($captions as $teaser_option_val => $teaser_caption) {
                    $selected = ($teaser_setting === $teaser_option_val) ? ' selected ' : '';
                    echo "\n\t<option value='" . esc_attr($teaser_option_val) . "'" . esc_attr($selected) . ">" . esc_html($teaser_caption) . "</option>";
                }

                echo '</select>';
                echo "<span style='display:none'>" . esc_html($object_type) . "</span>";

                $id = 'teaser_num_chars-' . $object_type;
                $name = "teaser_num_chars[$object_type]";
                $default_num_chars = (defined('PP_TEASER_NUM_CHARS')) ? PP_TEASER_NUM_CHARS : 50;
                $_setting = (!empty($arr_num_chars[$object_type])) ? $arr_num_chars[$object_type] : $default_num_chars;
                $style = ('x_chars' !== $teaser_setting) ? 'display:none;' : '';
                ?>
                <span class='teaser-num-chars' style='<?php echo esc_attr($style);?>'><input id='<?php echo esc_attr($id);?>' name='<?php echo esc_attr($name);?>' type='input' value='<?php echo esc_attr($_setting);?>' autocomplete='off' title='<?php _e('Number of characters to display', 'press-permit-core');?>' /></span>
                <?php

                do_action('presspermit_teaser_type_row', $object_type, $teaser_setting);

                echo '</td><td>';

				// Checkbox option to skip teaser for anonymous users
                $id = $option_logged_only . '-' . $object_type;
                $name = "tease_logged_only[$object_type]";
                $display = ($teaser_setting) ? '' : "display:none";
                echo "<div class='teaser_vspace' style='" . esc_attr($display) . "'><span>";

                // 'teaser: anonymous, logged or both'
                echo "<label for='" . esc_attr($id) . "_logged'>";
                $checked = (!empty($logged_only[$object_type]) && 'anon' == $logged_only[$object_type]) ? ' checked ' : '';
                echo "<input name='" . esc_attr($name) . "' type='radio' id='" . esc_attr($id) . "_logged' value='anon'" . esc_attr($checked) . " autocomplete='off' />";
                echo "";
                esc_html_e("Anonymous Visitors", 'presspermit-pro');
                echo '</label></span>';

                // Checkbox option to skip teaser for logged in users
                echo "<span style='margin-left: 1em'><label for='" . esc_attr($id) . "_anon'>";
                $checked = (!empty($logged_only[$object_type]) && 'anon' != $logged_only[$object_type]) ? ' checked ' : '';
                echo "<input name='" . esc_attr($name) . "' type='radio' id='" . esc_attr($id) . "_anon' value='1'" . esc_attr($checked) . " autocomplete='off' />";
                echo "";
                esc_html_e("Logged in Users", 'presspermit-pro');
                echo '</label></span>';

                // Checkbox option to do teaser for BOTH logged and anon users
                echo "<span style='margin-left: 1em'><label for='" . esc_attr($id) . "_all'>";
                $checked = (empty($logged_only[$object_type])) ? ' checked ' : '';
                echo "<input name='" . esc_attr($name) . "' type='radio' id='" . esc_attr($id) . "_all' value='0'" . esc_attr($checked) . " autocomplete='off' />";
                echo "";
                esc_html_e("Both", 'presspermit-pro');
                echo '</label></span>';

				echo '</div>';

				echo '</td></tr>';
            }

            echo '</table>';
            ?>
            </div> <?php // teaser_usage-post ?>

            </section>
        <?php
        endif; // any options accessable in this section


        // 2 --- COVERAGE SECTION ---
        $section = 'coverage';

        $default_options = apply_filters('presspermit_teaser_default_options', []);

        if (!empty($ui->form_options[$tab][$section])) : ?>
            <section id="ppp-tab-coverage" style="display:<?php echo $current_tab === 'ppp-tab-coverage' ? 'block' : 'none' ?>;">
            
            <?php
            $style = (!$any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-conditional-headline" <?php echo $style;?>>
            <?php
            if ($ui->display_hints) {
                SettingsAdmin::echoStr('teaser_coverage');
            }
			?>
			</p>
            
            <?php
            $style = ($any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-settings-na" <?php echo $style;?>>
            <?php
            SettingsAdmin::echoStr('teaser_settings_not_applicable');
			?>
            </p>

            <?php
            $opt_available = array_fill_keys($pp->getEnabledPostTypes(), 0);
            $no_tease_types = Teaser::noTeaseTypes();

            $option_hide_private = 'tease_public_posts_only';
            $ui->all_otype_options[] = $option_hide_private;
            $opt_vals = $ui->getOptionArray($option_hide_private);
            $hide_private = array_diff_key(array_merge($opt_available, $default_options[$option_hide_private], $opt_vals), $no_tease_types);
            $hide_private = array_intersect_key($hide_private, array_fill_keys($pp->getEnabledPostTypes(), true));
            //$hide_private = $pp->admin()->orderTypes($hide_private, ['item_type' => 'post']);

            $option_direct_only = 'tease_direct_access_only';
            $ui->all_otype_options[] = $option_direct_only;
            $opt_vals = $ui->getOptionArray($option_direct_only);
            $direct_only = array_diff_key(array_merge($opt_available, $default_options[$option_direct_only], $opt_vals), $no_tease_types);

            $option_hide_links = 'teaser_hide_menu_links_type';
            $ui->all_otype_options[] = $option_hide_links;
            $opt_vals = $ui->getOptionArray($option_hide_links);

            $defaults = (isset($default_options[$option_hide_links])) ? (array) $default_options[$option_hide_links] : [];
            $hide_links = array_diff_key(array_merge($opt_available, $defaults, $opt_vals), $no_tease_types);
            $hide_links = array_intersect_key($hide_links, array_fill_keys($pp->getEnabledPostTypes(), true));

            $style = (!$any_teased_types) ? "style='display:none'" : '';

            echo "<div class='teaser-coverage-post' $style><table class='widefat fixed striped teaser-table'>";

            echo '<thead>';
			echo '<thead><tr class="col-headers">';
            echo '<th></th>';
			echo '<th>' . __('Teaser Application', 'presspermit-pro') . '</th>';
            echo '<th>' . __('Private Posts (if unreadable)', 'presspermit-pro') . '</th>';
            echo '<th>' . __('Nav Menu Links', 'presspermit-pro') . '</th>';
            echo '</tr>';
			echo '</thead>';

            echo '<tbody>';

            // loop through each object type (for current source) to provide a use_teaser checkbox
            foreach ($use_teaser as $object_type => $teaser_setting) {
                $display = (!empty($use_teaser[$object_type])) ? '' : "display:none";

                if ($type_obj = get_post_type_object($object_type)) {
                    $item_label_singular = $type_obj->labels->singular_name;
                } else {
                    $item_label_singular = $object_type;
                }

                $style = (empty($teaser_setting)) ? "style='display:none;'" : '';

                echo "<tr class='teaser-{$object_type}' $style>";
				echo "<th>";
                echo "<label>";
                echo esc_html($item_label_singular);
                echo '</label>';
                echo '</th>';

                // Affected Views
                echo '<td>';

				echo '<label>' . __( 'Teaser Application', 'presspermit-pro' ) . '</label>';

                $id = $option_direct_only . '-' . $object_type;
                $name = "tease_direct_access_only[$object_type]";

                echo "<select name='" . esc_attr($name) . "' id='" . esc_attr($id) . "' autocomplete='off'>";
                $captions = [
                    0 => esc_html__("List and Single view", 'presspermit-pro'),
                    1 => esc_html__("Single view only", 'presspermit-pro'),
                ];

                $stored_setting = isset($direct_only[$object_type]) ? $direct_only[$object_type] : 0;

                foreach ($captions as $teaser_option_val => $teaser_caption) {
                    $selected = ($stored_setting == $teaser_option_val) ? ' selected=selected ' : '';
                    echo "\n\t<option value='" . esc_attr($teaser_option_val) . "'" . esc_attr($selected) . ">" . esc_html($teaser_caption) . "</option>";
                }

                echo '</select>';

                echo '</td>';


                // Private Posts
                echo '<td>';

				echo '<label>' . __( 'Private Posts (if unreadable)', 'presspermit-pro' ) . '</label>';

                $id = $option_hide_private . '-' . $object_type;
                $name = "tease_public_posts_only[$object_type]";

                if ($type_obj = get_post_type_object($object_type))
                    $item_label = $type_obj->labels->name;
                else
                    $item_label = $object_type;

                $teaser_hide_private_types = apply_filters('presspermit_teaser_hide_private_types', []);


                $stored_setting = isset($hide_private[$object_type]) ? $hide_private[$object_type] : 0;

                echo "<select name='" . esc_attr($name) . "' id='" . esc_attr($id) . "' autocomplete='off'>";
                $captions = [
                    '0' => esc_html__("Apply Teaser to Private Posts", 'presspermit-pro'),
                    '1' => esc_html__("Hide Private Posts", 'presspermit-pro'),
                    'custom' => esc_html__("Hide for Custom Visibility", 'presspermit-pro'),
                ];

                foreach ($captions as $teaser_option_val => $teaser_caption) {
                    $selected = ($stored_setting === $teaser_option_val) ? ' selected=selected ' : '';
                    echo "\n\t<option value='" . esc_attr($teaser_option_val) . "'" . esc_attr($selected) . ">" . esc_html($teaser_caption) . "</option>";
                }

                echo '</select>';

                echo '</td>';


                // Nav Menu Links
                echo '<td>';

				echo '<label>' . __( 'Nav Menu Links', 'presspermit-pro' ) . '</label>';

                $id = $option_hide_links . '-' . $object_type;
                $name = "teaser_hide_menu_links_type[$object_type]";

                if ($type_obj = get_post_type_object($object_type))
                    $item_label = $type_obj->labels->name;
                else
                    $item_label = $object_type;

                echo "<select name='" . esc_attr($name) . "' id='" . esc_attr($id) . "' autocomplete='off'>";
                $captions = [
                    0 => esc_html__("Display Teaser Links in Nav Menu", 'presspermit-pro'),
                    1 => esc_html__("No Teaser Links in Nav Menu", 'presspermit-pro'),
                ];

                $stored_setting = !empty($hide_links[$object_type]) ? $hide_links[$object_type] : 0;

                foreach ($captions as $teaser_option_val => $teaser_caption) {
                    $selected = ($stored_setting == $teaser_option_val) ? ' selected ' : '';
                    echo "\n\t<option value='" . esc_attr($teaser_option_val) . "'" . esc_attr($selected) . ">" . esc_html($teaser_caption) . "</option>";
                }

                echo '</select>';
                echo '</td>';

                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            ?>

            <div class="teaser-coverage-nav-menu-terms">
            <h4><?php _e('Nav Menu Links - Disable Teaser for Some Categories / Tags:', 'presspermit-pro');?></h4>

            <?php if ($ui->display_hints) :?>
                <p>
                <?php SettingsAdmin::echoStr('nav_menu_hide_terms_caption');?>
                </p>
            <?php endif;?>

            </div>

            <table class="form-table">
            <tr>
				<th><?php esc_html_e('Taxonomy', 'presspermit-pro');?></th>

	            <?php
	            $tx_setting = $pp->getOption("teaser_hide_links_taxonomy");

	            $_args = (defined('PRESSPERMIT_FILTER_PRIVATE_TAXONOMIES')) ? [] : ['public' => true];
	            $taxonomies = get_taxonomies($_args, 'object');

	            $taxonomies = array_intersect_key($taxonomies, array_fill_keys($pp->getEnabledTaxonomies(), true));

	            $tx_label = '';
                $set_taxonomy = '';

	            $hide_style = ($tx_setting) ? '' : 'display:none;';

	            foreach ($taxonomies as $taxonomy => $tx) {
	                if ($pp->getOption("teaser_hide_links_taxonomy") === $taxonomy) {
                        $set_taxonomy = $taxonomy;
	                    $tx_label = esc_html($tx->labels->singular_name);
	                    break;
	                }
	            }
	            ?>

				<td>
	                <?php
	                $id = "teaser_hide_links_taxonomy";
	                $ui->all_options[] = $id;
	                $_setting = $pp->getOption($id);
	                ?>

	                <select name='<?php echo esc_attr($id); ?>' id='<?php echo esc_attr($id); ?>' autocomplete='off'>
	                <option value=''><?php esc_html_e('select...', 'presspermit'); ?></option>
	                <?php
	                foreach ($taxonomies as $taxonomy => $tx) {
	                    $selected = ($_setting === $taxonomy) ? ' selected ' : '';

	                    if ($tx->labels->singular_name || $selected) {
	                        echo "\n\t<option value='" . esc_attr($taxonomy) . "' " . esc_attr($selected) . ">" . esc_html($tx->labels->singular_name) . "</option>";
	                    }
	                }
	                ?>
	                </select>
	            </td>
			</tr>
			<tr>
	            <th class='pp-hide-terms'>
	                <?php
	                foreach ($taxonomies as $taxonomy => $tx) {
	                    $_hide_style = ($tx_setting && ($taxonomy == $tx_setting)) ? '' : 'display:none;';
	                    ?>
	                    <span class="pp-teaser-tx-label pp-teaser-tx-label-<?php echo $taxonomy;?>" style="<?php echo $_hide_style;?>">
	                    <?php printf(esc_html__('Hide links related to these %s:', 'presspermit-pro'), esc_html($tx->labels->name));
	                    ?>
	                    </span>
	                    <?php
	                }
	                ?>
	            </th>
				<td class='pp-hide-terms' <?php if (!$set_taxonomy) echo "style='display:none'";?>>
	                <?php
	                $id = "teaser_hide_links_term";
	                $ui->all_options[] = $id;
	                $_setting = $pp->getOption($id);
					?>
					<div class="pp-select-dynamic-wrapper">
					  <select class="permissions_select_terms"
					      name="<?php esc_attr_e( $id ) ?>[]"
					      id="<?php esc_attr_e( $id ) ?>"
						  multiple="multiple"
					  >
					    <?php
						if( isset( $_setting ) && is_array( $_setting ) ) :
							foreach( $_setting as $item ) : ?>
						    	<option value="<?php echo (int) $item ?>" selected="selected"><?php echo htmlspecialchars(get_term( (int) $item )->name)?></option>
					    	<?php
							endforeach;
						endif; ?>
					  </select>
					</div>
	            </td>
            </tr>
			</table>

            <?php
            echo '</div>'; // teaser_usage-post
            ?>
            </section>
        <?php
        endif; // any options accessable in this section


        // 3 --- REDIRECT SECTION ---
        $section = 'redirect';

        if (!empty($ui->form_options[$tab][$section])) : ?>
            <section id="ppp-tab-redirect" style="display:<?php echo $current_tab === 'ppp-tab-redirect' ? 'block' : 'none' ?>;">

            <?php
            $style = (!$any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-conditional-headline" <?php echo $style;?>>
            <?php
            if ($ui->display_hints) {
                SettingsAdmin::echoStr('teaser_redirect_page');
            }
            ?>
			</p>

            <?php
            $style = ($any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-settings-na" <?php echo $style;?>>
            <?php
            SettingsAdmin::echoStr('teaser_settings_not_applicable');
			?>
            </p>

            <?php
            $id = "teaser_redirect_anon";
            $id_slug = "teaser_redirect_anon_page";

            $ui->all_options[] = $id;
            if ($_setting = $pp->getOption($id_slug)) {
                if (is_numeric($_setting)) {
                    $_setting = '(select)';
                }
            }

            $ui->all_options[] = $id_slug;
            $redirect_page_id = $pp->getOption($id_slug);

            if ('[login]' == $redirect_page_id) {
                $_setting = '[login]';
            }

            $style = (!$any_teased_types) ? "style='display:none'" : '';
            ?>
            <table class="widefat fixed striped teaser-table pp-teaser-redirect" <?php echo $style;?>>
				<thead>
					<tr>
						<th></th>
						<th><?php _e('Redirection', 'presspermit-pro') ?></th>

						<?php $style = ($any_teased_types) ? '' : "display:none;";?>
                        <th class='pp-teaser-page-selection'><span style="<?php echo esc_attr($style);?>"><?php _e('Page Selection', 'presspermit-pro') ?></span></th>
					</tr>
				</thead>
				<tbody>
                <tr>
                    <th>
                        <label for='<?php echo esc_attr($id); ?>'>
                        <?php esc_html_e('Anonymous Visitors:', 'presspermit-pro');
                        ?>
                        </label>
                    </th>
                    <td>
                        <?php
                        echo "<select name='" . esc_attr($id) . "' id='" . esc_attr($id) . "' class='teaser-redirect-mode' autocomplete='off'>";
                        $captions = [
                            0 => esc_html__("No Redirect", 'presspermit-pro'),
                            '[login]' => esc_html__("Redirect to WordPress Login", 'presspermit-pro'),
                            '(select)' => esc_html__("Redirect to a Custom Page", 'presspermit-pro'),
                        ];

                        foreach ($captions as $teaser_option_val => $teaser_caption) {
                            $selected = ($_setting == $teaser_option_val) ? ' selected ' : '';
                            echo "\n\t<option value='" . esc_attr($teaser_option_val) . "'" . esc_attr($selected) . ">" . esc_html($teaser_caption) . "</option>";
                        }

                        echo '</select>';
                        ?>
                    </td>
                    <td>
                        <?php
                        $style = ('(select)' === $_setting) ? '' : " style='display:none;'";

                        $id = "teaser_redirect_anon_page";
                        ?>
						<div class="pp-select-dynamic-wrapper" <?php echo $style;?>>
							<select class="permissions_select_posts"
									name="<?php esc_attr_e( $id_slug ) ?>"
									id="<?php esc_attr_e( $id_slug ) ?>"
							>
								<?php if( isset( $redirect_page_id ) && ! empty( $redirect_page_id ) ) : ?>
									<option value="<?php echo (int) $redirect_page_id ?>" selected="selected">
										<?php
										echo htmlspecialchars(
											get_the_title( (int) $redirect_page_id )
										)
										?>
									</option>
								<?php endif; ?>
							</select>

                            <?php
                            $id = "teaser_redirect_custom_login_page_anon";
                            $ui->all_options[] = $id;
                            $_setting = $pp->getOption($id);
                            ?>
                            &nbsp;<label style="white-space:nowrap"><input type="checkbox" name="<?php echo $id;?>" <?php if ($_setting) echo 'checked';?> /><?php _e('This is a custom login page', 'press-permit-core');?></label>
						</div>
                    </td>
                </tr>

                <?php
                $id = "teaser_redirect";
                $id_slug = "teaser_redirect_page";

                $ui->all_options[] = $id;
                if ($_setting = $pp->getOption($id_slug)) {
                    if (is_numeric($_setting)) {
                        $_setting = '(select)';
                    }
                }

                $ui->all_options[] = $id_slug;
                $redirect_page_id = $pp->getOption($id_slug);

                if ('[login]' == $redirect_page_id) {
                    $_setting = '[login]';
                }
                ?>
                <tr>
                    <th>
                        <label for='<?php echo esc_attr($id); ?>'>
                        <?php esc_html_e('Logged in Users:', 'presspermit-pro');
                        ?>
                        </label>
                    </th>
                    <td>
                        <?php
                        echo "<select name='" . esc_attr($id) . "' id='" . esc_attr($id) . "' class='teaser-redirect-mode' autocomplete='off'>";
                        $captions = [
                            0 => esc_html__("No Redirect", 'presspermit-pro'),
                            '[login]' => esc_html__("Redirect to WordPress Login", 'presspermit-pro'),
                            '(select)' => esc_html__("Redirect to a Custom Page", 'presspermit-pro'),
                        ];

                        foreach ($captions as $teaser_option_val => $teaser_caption) {
                            $selected = ($_setting == $teaser_option_val) ? ' selected ' : '';
                            echo "\n\t<option value='" . esc_attr($teaser_option_val) . "'" . esc_attr($selected) . ">" . esc_html($teaser_caption) . "</option>";
                        }

                        echo '</select>';
                        ?>
                    </td>
                    <td>
                        <?php
                        $style = ('(select)' === $_setting) ? '' : " style='display:none;'";

                        $id_slug = "teaser_redirect_page";
                        ?>
						<div class="pp-select-dynamic-wrapper" <?php echo $style;?>>
							<select class="permissions_select_posts"
									name="<?php esc_attr_e( $id_slug ) ?>"
									id="<?php esc_attr_e( $id_slug ) ?>"
							>
								<?php if( isset( $redirect_page_id ) && ! empty( $redirect_page_id ) ) : ?>
									<option value="<?php echo (int) $redirect_page_id ?>" selected="selected"><?php echo htmlspecialchars(get_the_title( (int) $redirect_page_id ))?></option>
								<?php endif; ?>
							</select>
                            
                            <?php
                            $id = "teaser_redirect_custom_login_page";
                            $ui->all_options[] = $id;
                            $_setting = $pp->getOption($id);
                            ?>
                            &nbsp;<label style="white-space:nowrap"><input type="checkbox" name="<?php echo $id;?>" <?php if ($_setting) echo 'checked';?> /><?php _e('This is a custom login page', 'press-permit-core');?></label>
						</div>
                    </td>
                </tr>
				</tbody>
            </table>

            </section>
        <?php
        endif; // any options accessable in this section


        // 3 --- TEASER TEXT SECTION ---
        $section = 'teaser_text';

        if (!empty($ui->form_options[$tab][$section])) : ?>
            <section id="ppp-tab-teaser-text" style="display:<?php echo $current_tab === 'ppp-tab-teaser-text' ? 'block' : 'none' ?>;">
            
            <?php
            $style = (!$any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-conditional-headline" <?php echo $style;?>>
            <?php
            if ($ui->display_hints) {
                SettingsAdmin::echoStr('teaser_text');
            }
			?>
			</p>

            <?php
            $style = ($any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-settings-na" <?php echo $style;?>>
            <?php
            SettingsAdmin::echoStr('teaser_settings_not_applicable');
			?>
            </p>

            <?php
            // now draw the teaser replacement / prefix / suffix input boxes
            $user_suffixes = ['_anon', ''];
            $item_actions = [
                'name' => ['prepend', 'append'],
                'content' => ['replace', 'prepend', 'append'],
                'excerpt' => ['replace', 'prepend', 'append']
            ];

            $items_display = [
				'name' => esc_html__( 'name', 'presspermit-pro' ),
				'content' => esc_html__( 'content', 'presspermit-pro' ),
				'excerpt' => esc_html__( 'excerpt', 'presspermit-pro' )
			];

            // first determine all object types
            foreach ($user_suffixes as $anon) {
                foreach ($item_actions as $item => $actions) {
                    foreach ($actions as $action) {
                        $ui->all_options[] = "tease_{$action}_{$item}{$anon}";
                    }
                }
            }

            $style = (!$any_teased_types) ? "style='display:none'" : '';
            ?>
            <div id='teaserdef-post' <?php echo $style;?>>
            <?php
			// Translatable strings
			$trans_headings = [
				'name' => __( 'post title', 'presspermit-pro' ),
				'content' => __( 'post content', 'presspermit-pro' ),
				'excerpt' => __( 'post excerpt', 'presspermit-pro' )
			];

            // separate input boxes to specify teasers for anon users and unpermitted logged in users
            foreach ($user_suffixes as $anon) {
                $user_descript = ($anon) ? __('Anonymous Visitors', 'presspermit-pro') : __('Logged In Users', 'presspermit-pro');

				if( $anon !== '_anon' ) {
					echo '<hr style="margin: 40px 0 50px;" />';
				}

                echo '<h2 class="title">';
                printf(esc_html__('Teaser Text for %s', 'presspermit-pro'), esc_html($user_descript));
                echo '</h2>';

                // items are name, content, excerpt
                foreach ($item_actions as $item => $actions) {

					// Generate "Before post title:" / "After post title:" / "Replace post excerpt with:"
					$actions_display = [
						'replace' => sprintf(
							esc_html__( 'Replace %s with:', 'presspermit-pro' ),
							$trans_headings[$item]
						),
						'prepend' => sprintf(
							esc_html__( 'Before %s:', 'presspermit-pro' ),
							$trans_headings[$item]
						),
						'append' => sprintf(
							esc_html__( 'After %s:', 'presspermit-pro' ),
							$trans_headings[$item]
						)
					];
					?>
					<!--h2 class="title">
						 <?php echo esc_html( $trans_headings[$item] ) ?>
					</h2-->
                    <?php
                    echo '<table class="form-table"><tbody>';

                    // actions are prepend / append / replace
                    foreach ($actions as $action) {
                        $option_name = "tease_{$action}_{$item}{$anon}";
                        if (!$opt_val = $pp->getOption($option_name))
                            $opt_val = '';

                        $ui->all_options[] = $option_name;

                        $id = $option_name;
                        $name = $option_name;

                        echo "<tr><th><label for='" . esc_attr($id) . "'>";
                        echo esc_html($actions_display[$action]);
                        echo '</label>';
                        ?>
						</th>
                        <td>
                            <?php if ('content' == $item) : ?>
								<textarea class="large-text" name="<?php echo esc_attr($name); ?>"
                                            id="<?php echo esc_attr($id); ?>"><?php echo esc_html($opt_val); ?></textarea>
								<?php if (('content' == $item) && ('replace' == $action)) { ?>
									<p class="pp-add-login-form">
										<?php
										printf(
											__( 'Insert a login form by using %s[login_form]%s shortcode.', 'presspermit-pro' ),
											'<a href="#">',
											'</a>'
										);
										?>
									</p>
								<?php } ?>
                            <?php else : ?>
                                <?php $class_name = (in_array($name, ['tease_prepend_name_anon', 'tease_append_name_anon', 'tease_prepend_name', 'tease_append_name'])) ? 'short-text' : 'regular-text';?>
                                <input class="<?php echo esc_attr($class_name); ?>" name="<?php echo esc_attr($name); ?>" type="text" id="<?php echo esc_attr($id); ?>"
                                        value="<?php echo esc_attr($opt_val); ?>"/>
                            <?php endif; ?>

                        </td>
                        </tr>
                        <?php
                    } // end foreach actions

                    echo '</tbody></table>';
                } // end foreach item_actions

            } // end foreach user_suffixes

            do_action('presspermit_teaser_text_ui');

            echo '</div>';

            if (presspermit()->getOption('advanced_options')) :?>
                <?php
                $style = (!$any_teased_types) ? "style='display:none'" : '';
                ?>

                <div id="pp_teaser_text_sample_code" <?php echo $style;?>>
                <hr style="margin: 40px 0 40px 0;">

                <h2 class="title">
					<?php esc_html_e('Type-Specific Teaser Text', 'presspermit-pro'); ?>
				</h2>
				<p>
					<?php
					_e("<strong>Copy</strong> the following code into your theme's <strong>functions.php</strong> file (or some other file which is always executed and not auto-updated). You will need to adjust the 'my_custom_type' identifier and text as desired:", 'presspermit-pro');
					?>
				</p>
				<div class="ppp-code-sample">
					<span>
						<button class="button button-secondary button-small ppp-expand-code" data-expand="closed">
							<span class="ppp-expand-msg">
								<span class="dashicons dashicons-editor-expand"></span>
								<?php esc_attr_e( 'Expand code', 'presspermit-pro' ); ?>
							</span>
							<span class="ppp-collapse-msg" style="display: none;">
								<span class="dashicons dashicons-editor-contract"></span>
								<?php esc_attr_e( 'Collapse code', 'presspermit-pro' ); ?>
							</span>
						</button>
						<button class="button button-secondary button-small ppp-copy-code" data-copy="uncopied">
							<span class="ppp-uncopied-msg">
								<span class="dashicons dashicons-admin-page"></span>
								<?php esc_attr_e( 'Copy', 'presspermit-pro' ); ?>
							</span>
							<span class="ppp-copied-msg" style="display: none;">
								<span class="dashicons dashicons-saved"></span>
								<?php esc_attr_e( 'Code copied', 'presspermit-pro' ); ?>
							</span>
						</button>
					</span>
					<textarea readonly='readonly' class="large-text code">
add_filter( 'presspermit_teaser_text', 'my_custom_teaser_text', 10, 5 );

/*
 * adjustment_type: replace, prefix or suffix
 * post_part: content, excerpt or name
*/
function my_custom_teaser_text( $text, $adjustment_type, $post_part, $post_type, $is_anonymous ) {
	switch ( $post_type ) {
		case 'page':
			if ( ( 'content' == $post_part ) && ( 'replace' == $adjustment_type ) ) {
				if ( $is_anonymous ) { // note: if you put a link or other html tags in the text, be sure to use single quotes
					$text = "Sorry, you don't have access to this page. Please log in or contact an administrator.";
				} else {
					$text = "Sorry, this page requires additional permissions. Please contact an administrator for help.";
				}
			}

			break;

		case 'my_custom_type':
			if ( ( 'content' == $post_part ) && ( 'replace' == $adjustment_type ) ) {
				if ( $is_anonymous ) {  // note: if you put a link or other html tags in the text, be sure to use single quotes
					$text = "Sorry, you don't have access to this custom content. Please log in or contact an administrator.";
				} else {
					$text = "Sorry, this custom content requires additional permissions. Please contact an administrator for help.";
				}
			}

			break;
	}

	return $text;
}</textarea>
			</div>

			<?php if ( is_multisite() ) : ?>
				<p>
					<?php
					_e(
						"To modify default settings network-wide, <strong>copy</strong> the following code into your theme's <strong>functions.php</strong> file (or some other file which is always executed and not auto-updated) and modify as desired:",
						'presspermit'
					);
					?>
				</p>
				<div class="ppp-code-sample">
					<span>
						<button class="button button-secondary button-small ppp-expand-code" data-expand="closed">
							<span class="ppp-expand-msg">
								<span class="dashicons dashicons-editor-expand"></span>
								<?php esc_attr_e( 'Expand code', 'presspermit-pro' ); ?>
							</span>
							<span class="ppp-collapse-msg" style="display: none;">
								<span class="dashicons dashicons-editor-contract"></span>
								<?php esc_attr_e( 'Collapse code', 'presspermit-pro' ); ?>
							</span>
						</button>
						<button class="button button-secondary button-small ppp-copy-code" data-copy="uncopied">
							<span class="ppp-uncopied-msg">
								<span class="dashicons dashicons-admin-page"></span>
								<?php esc_attr_e( 'Copy', 'presspermit-pro' ); ?>
							</span>
							<span class="ppp-copied-msg" style="display: none;">
								<span class="dashicons dashicons-saved"></span>
								<?php esc_attr_e( 'Code copied', 'presspermit-pro' ); ?>
							</span>
						</button>
					</span>
					<textarea readonly='readonly' class="large-text code">
add_filter( 'presspermit_default_options', 'my_presspermit_default_options', 99 );

/*
 * def_options[option_name] = option_value
*/
function my_presspermit_default_options( $def_options ) {
	// option name (array key) corresponds to name attributes of checkboxes, dropdowns and input boxes.  Modify as desired.

	return $def_options;
}</textarea>
				</div>

				<p>
					<?php
					_e(
						"To force the value of a specific setting network-wide, <strong>copy</strong> the following code into your theme's <strong>functions.php</strong> file (or some other file which is always executed and not auto-updated) and modify as desired:",
						'presspermit'
					);
					?>
				</p>
				<div class="ppp-code-sample">
					<span>
						<button class="button button-secondary button-small ppp-expand-code" data-expand="closed">
							<span class="ppp-expand-msg">
								<span class="dashicons dashicons-editor-expand"></span>
								<?php esc_attr_e( 'Expand code', 'presspermit-pro' ); ?>
							</span>
							<span class="ppp-collapse-msg" style="display: none;">
								<span class="dashicons dashicons-editor-contract"></span>
								<?php esc_attr_e( 'Collapse code', 'presspermit-pro' ); ?>
							</span>
						</button>
						<button class="button button-secondary button-small ppp-copy-code" data-copy="uncopied">
							<span class="ppp-uncopied-msg">
								<span class="dashicons dashicons-admin-page"></span>
								<?php esc_attr_e( 'Copy', 'presspermit-pro' ); ?>
							</span>
							<span class="ppp-copied-msg" style="display: none;">
								<span class="dashicons dashicons-saved"></span>
								<?php esc_attr_e( 'Code copied', 'presspermit-pro' ); ?>
							</span>
						</button>
					</span>
					<textarea readonly='readonly' class="large-text code">
add_filter( 'presspermit_default_options', 'my_presspermit_default_options', 99 );

/*
 * def_options[option_name] = option_value
*/
function my_presspermit_default_options( $def_options ) {
	// option name (array key) corresponds to name attributes of checkboxes, dropdowns and input boxes.  Modify as desired.

	return $def_options;
}</textarea>
				</div>
                </div>
			<?php endif;?>
            
			<?php endif;?>

            </section>
        <?php
        endif; // any options accessable in this section


        $section = 'options';                                // --- OPTIONS SECTION ---
        if (!empty($ui->form_options[$tab][$section])) : ?>
            <section id="ppp-tab-options" style="display:<?php echo $current_tab === 'ppp-tab-options' ? 'block' : 'none' ?>;">
            
            <?php
            $style = ($any_teased_types) ? "style='display:none'" : '';
            ?>
            <p class="pp-teaser-settings-na" <?php echo $style;?>>
            <?php
            SettingsAdmin::echoStr('teaser_settings_not_applicable');
			?>
            </p>

            <?php
            $style = (!$any_teased_types) ? "style='display:none'" : '';
            ?>

            <div class="pp-teaser-options" <?php echo $style;?>>
            
			<?php
			if ( in_array(
					'teaser_hide_thumbnail',
					$ui->form_options[$tab][$section],
					true
				)
			) :
			?>
				<h2 class="title">
					<?php
					printf(
						esc_html__( 'Custom Fields', 'presspermit-pro' ),
						esc_html( $user_descript )
					);
					?>
				</h2>
				<table class="form-table">
					<tr>
						<th>
							<?php esc_html_e( 'Hide Featured Image when Teaser is applied:', 'presspermit-pro' ) ?>
						</th>
						<td>
							<?php
							if ( in_array(
									'teaser_hide_thumbnail',
									$ui->form_options[$tab][$section],
									true
								)
							) {
								$ui->optionCheckbox( 'teaser_hide_thumbnail', $tab, $section, '', '', ['display_label' => false] );
							}
							?>
						</td>
					</tr>
				</table>
			<?php endif; ?>
			<h2 class="title">
				<?php
				printf(
					esc_html__( 'RSS', 'presspermit-pro' ),
					esc_html( $user_descript )
				);
				?>
			</h2>
			<p>
				<?php
				if ( $ui->display_hints ) {
					SettingsAdmin::echoStr( 'teaser_block_all_rss' );
				}
				?>
			</p>
			<table class="form-table">

				<?php
				// Display for readable private posts
				if ( in_array( 'rss_private_feed_mode', $ui->form_options[$tab][$section], true ) ) :
					$ui->all_options[] = 'rss_private_feed_mode';
					?>
					<tr>
						<th>
							<?php
							esc_html_e( 'Display for readable private posts:', 'presspermit-pro' );
		                    ?>
						</th>
						<td>
							<?php
							echo '<select name="rss_private_feed_mode" id="rss_private_feed_mode" autocomplete="off">';
							$captions = ['full_content' => esc_html__("Full Content", 'presspermit-pro'), 'excerpt_only' => esc_html__("Excerpt Only", 'presspermit-pro'), 'title_only' => esc_html__("Title Only", 'presspermit-pro')];
							foreach ($captions as $key => $value) {
								$selected = ($ui->getOption('rss_private_feed_mode') == $key) ? ' selected ' : '';
								echo "\n\t<option value='" . esc_attr($key) . "' " . esc_attr($selected) . ">" . esc_html($captions[$key]) . "</option>";
							}
							echo '</select>';
							?>
						</td>
					</tr>
					<?php
				endif;

				// Display for readable non-private posts
				if ( in_array( 'rss_nonprivate_feed_mode', $ui->form_options[$tab][$section], true ) ) :
					$ui->all_options[] = 'rss_nonprivate_feed_mode';
					?>
					<tr>
						<th>
							<?php
							esc_html_e( 'Display for readable non-private posts:', 'presspermit-pro' );
		                    ?>
						</th>
						<td>
							<?php
							echo '<select name="rss_nonprivate_feed_mode" id="rss_nonprivate_feed_mode" autocomplete="off">';
	                        $captions = ['full_content' => esc_html__("Full Content", 'presspermit-pro'), 'excerpt_only' => esc_html__("Excerpt Only", 'presspermit-pro'), 'title_only' => esc_html__("Title Only", 'presspermit-pro')];
	                        foreach ($captions as $key => $value) {
	                            $selected = ($ui->getOption('rss_nonprivate_feed_mode') == $key) ? ' selected ' : '';
	                            echo "\n\t<option value='" . esc_attr($key) . "' " . esc_attr($selected) . ">" . esc_html($captions[$key]) . "</option>";
	                        }
	                        echo '</select>';
							?>
						</td>
					</tr>
					<?php
				endif;

				// Feed Replacement Text
				if ( in_array( 'feed_teaser', $ui->form_options[$tab][$section], true ) ) :
					$id = 'feed_teaser';
					$ui->all_options[] = $id;
					$val = htmlspecialchars($ui->getOption($id));
					?>
					<tr>
						<th>
							<?php
							esc_html_e( 'Feed Replacement Text:', 'presspermit-pro' );
							?>
						</th>
						<td>
							<?php
							echo "<label for='" . esc_attr($id) . "'>";
	                        echo "<textarea name='" . esc_attr($id) . "' class='large-text' id='" . esc_attr($id) . "'>" . esc_html($val) . "</textarea>";
							echo "</label>";
							?>
							<p class="description">
								<?php printf(
									__( 'Use %s for post URL', 'presspermit-pro' ),
									'<code>%permalink%</code>'
								); ?>
							</p>
						</td>
					</tr>
				<?php endif; ?>
			</table>
            
            </div>
            </section>
        <?php
        endif; // any options accessable in this section

        echo "<input type='hidden' name='all_options' value='" . esc_attr(implode(',', $ui->all_options)) . "' />";
        echo "<input type='hidden' name='all_otype_options' value='" . esc_attr(implode(',', $ui->all_otype_options)) . "' />";

        echo "<input type='hidden' name='pp_submission_topic' value='options' />";
        ?>

            <p>
                <input type="submit" name="presspermit_submit" class="button button-primary" value="<?php _e('Save Changes', 'presspermit-pro') ?>">
                <input type="submit" name="presspermit_defaults" class="button button-secondary" value="<?php _e('Revert to Defaults', 'presspermit-pro') ?>" style="float:right;">
            </p>
        </div>
        <?php
        presspermit()->admin()->publishpressFooter();
        ?>
    </div>

    </form>
    <?php

    } // end function display()
}

?>
<script type="text/javascript">
    /* <![CDATA[ */
    jQuery(document).ready(function ($) {
        var ppNavMenuHideLinksTaxonomy = '<?php echo get_option("presspermit_teaser_hide_links_taxonomy", '');?>';

        $('#teaser_usage-post select[name!="topics_teaser"]').on('change', function()
        {
            var otype = $(this).next().html();

            if ($(this).val() != '0') {
                $(this).closest('tr').find('td div.teaser_vspace').show();
                $('div.teaser-coverage-post table tr.' + $(this).attr('class')).show();
            } else {
                $(this).closest('tr').find('td div.teaser_vspace').hide();
                $('div.teaser-coverage-post table tr.' + $(this).attr('class')).hide();
            }

            var ppAnyTeaserTypesEnabled = $('#teaser_usage-post select[name!="topics_teaser"] option:selected[value!=0]').length;

            $('p.pp-teaser-conditional-headline').toggle(ppAnyTeaserTypesEnabled > 0);
            $('p.pp-teaser-settings-na').toggle(ppAnyTeaserTypesEnabled == 0);
            $('div.teaser-coverage-post').toggle(ppAnyTeaserTypesEnabled > 0);
            $('table.pp-teaser-redirect').toggle(ppAnyTeaserTypesEnabled > 0);
            $('#teaserdef-post').toggle(ppAnyTeaserTypesEnabled > 0);
            $('#pp_teaser_text_sample_code').toggle(ppAnyTeaserTypesEnabled > 0);
            $('div.pp-teaser-options').toggle(ppAnyTeaserTypesEnabled > 0);

            $('th.pp-teaser-user-application span').toggle(ppAnyTeaserTypesEnabled > 0);
        });

        $('.pp-add-login-form a').on('click', function()
        {
            var e;
            if (e = $(this).closest('td').find('textarea')) {
                if (-1 == e.val().indexOf('[login_form]')) {
                    e.val(e.val() + '[login_form]');
                }
            }
            return false;
        });

        $('#teaser_redirect_anon').on('click', function()
        {
            $('#teaser_redirect_anon_page').toggle($(this).val() == '(select)');

            if ('(select)' != $(this).val()) {
                $('#teaser_redirect_anon_page').val('');
            }

            var ppAnyRedirectsEnabled = $('#teaser_redirect_anon option:selected[value="(select)"], #teaser_redirect option:selected[value="(select)"]').length;
            $('th.pp-teaser-page-selection span').toggle(ppAnyRedirectsEnabled > 0);
        });

        $('#teaser_redirect').on('click', function()
        {
            $('#teaser_redirect_page').toggle($(this).val() == '(select)');

            if ('(select)' != $(this).val()) {
                $('#teaser_redirect_page').val('');
            }

            var ppAnyRedirectsEnabled = $('#teaser_redirect_anon option:selected[value="(select)"], #teaser_redirect option:selected[value="(select)"]').length;
            $('th.pp-teaser-page-selection span').toggle(ppAnyRedirectsEnabled > 0);
        });

        $('#teaser_hide_links_taxonomy').on('click', function()
        {
            $("span.pp-teaser-tx-label").hide()
            $("span.pp-teaser-tx-label-" + $(this).val()).show()

            var selectedTaxonomy = $(this).val();
            $('#teaser_hide_links_term, .teaser_hide_links_term_desc, td.pp-hide-terms').toggle(selectedTaxonomy != '');

            if ($(this).val() != ppNavMenuHideLinksTaxonomy) {
                $('td.pp-hide-terms select').val(null).trigger('change');
            }

            ppNavMenuHideLinksTaxonomy = selectedTaxonomy;
        });
    });
    /* ]]> */
</script>
