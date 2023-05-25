jQuery(document).ready(function ($) {
    $('#sync_posts_to_users').on('change', function () {
        $('#sync_posts_to_users_settings, #sync_posts_to_users_apply_permissions').toggle($(this).is(':checked'));
        $('div.pp-sync-permissions-hint').toggle($(this).is(':checked'));
    });
    $('td.pp-sync-now input[type="checkbox"]').on('change', function () {
        $('input.pp-sync-now-button').show();
    });
    $('#sync_posts_to_users_settings input.sync-enable-type').on('change', function () {
        $(this).closest('tr').find('td.pp-toggle,input.ppp-parent-field').toggle($(this).is(':checked'));
        if ($(this).is(':checked')) {
            $('#sync_posts_to_users_settings th').parent().show();
            if ($(this).closest('td').hasClass('pp-hierarchical-type')) {
                $('th.pp-sync-parent').show();
                $(this).closest('tr').find('td.pp-sync-parent').show();
                $('#sync_posts_to_users_settings tr td:visible span.pp-sync-parent').show();
            }
        } else {
            if (!$('#sync_posts_to_users_settings td:visible').length) {
                $('#sync_posts_to_users_settings th').parent().hide();
            }
        }
        $(this).closest('tr').find('td select, td input[type=text]').attr('disabled', !$(this).is(':checked'));
    });
    $('a.ppp-suggest').on('click', function () {
        $(this).closest('td').find('input.ppp-text-field-buffer').val($(this).closest('td').find('input.ppp-text-field').val());
        $(this).closest('td').find('input.ppp-text-field').hide();
        $(this).closest('td').find('select.ppp-suggestion').val($(this).closest('td').find('select.ppp-suggestion option:first').val());
        if ($(this).closest('td').find("select.ppp-suggestion option[value='" + $(this).closest('td').find('input.ppp-text-field').val() + "']").length > 0) {
            $(this).closest('td').find('select.ppp-suggestion').val($(this).closest('td').find('input.ppp-text-field').val()).show();
        }
        $(this).closest('td').find('select.ppp-suggestion').show();
        $(this).closest('td').find('input.ppp-text-field').attr('value', $(this).closest('td').find('select.ppp-suggestion').val());
        $(this).hide();
    });
    $('a.ppp-cancel').on('click', function () {
        $(this).closest('td').find('select.ppp-suggestion').hide();
        $(this).closest('td').find('input.ppp-text-field').attr('value', $(this).closest('td').find('input.ppp-field-buffer').val());
        $(this).closest('td').find('input.ppp-text-field').show();
        $(this).hide();
        $(this).closest('td').find('a.ppp-suggest').show();
    });
    $('select.ppp-suggestion').on('click', function () {
        if ($(this).val() == '(other)') {
            $(this).closest('td').find('a.ppp-cancel').trigger('click');
        } else {
            $(this).siblings('input.ppp-text-field').attr('value', $(this).val());
        }
    });
});