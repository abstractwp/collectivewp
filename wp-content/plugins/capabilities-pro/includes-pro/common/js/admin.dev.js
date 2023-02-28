jQuery(document).ready( function($) {
	// -------------------------------------------------------------
	//   Prevent ENTER on input from submitting whole Editor feature settings
	// -------------------------------------------------------------
	$(document).on("keydown", ".ppc-add-custom-row-body input[type='text']", function (event) {
        return event.keyCode !== 13;
      });

      // -------------------------------------------------------------
      //   Submit new item for editor feature
      // -------------------------------------------------------------
      $(document).on("click", ".ppc-feature-gutenberg-new-submit, .ppc-feature-classic-new-submit", function (event) {
        event.preventDefault();
        var ajax_action,
          security,
          custom_label,
          custom_element,
          button = $(this);

        $('.ppc-feature-submit-form-error').remove();
        button.attr('disabled', true);
        $(".ppc-feature-post-loader").addClass("is-active");

        if (button.hasClass('ppc-feature-gutenberg-new-submit')) {
          ajax_action = 'ppc_submit_feature_gutenberg_by_ajax';
          custom_label = button.closest('.ppc-add-custom-row-body').find('.ppc-feature-gutenberg-new-name').val();
          custom_element = button.closest('.ppc-add-custom-row-body').find('.ppc-feature-gutenberg-new-ids').val();
        } else {
          ajax_action = 'ppc_submit_feature_classic_by_ajax';
          custom_label = button.closest('.ppc-add-custom-row-body').find('.ppc-feature-classic-new-name').val();
          custom_element = button.closest('.ppc-add-custom-row-body').find('.ppc-feature-classic-new-ids').val();
        }

        security = button.closest('.ppc-add-custom-row-body').find('.ppc-feature-submit-form-nonce').val();

        var data = {
          'action': ajax_action,
          'security': security,
          'custom_label': custom_label,
          'custom_element': custom_element,
        };

        $.post(ajaxurl, data, function (response) {

          if (response.status == 'error') {
            button.closest('tr').find('.ppc-post-features-note').html('<div class="ppc-feature-submit-form-error" style="color:red;">' + response.message + '</div>');
            $(".ppc-feature-submit-form-error").delay(2000).fadeOut('slow');
          }else {
            var currentTable, tableContent, PostType, checkedElement, activeSelector;

            $('.ppc-editor-features-submit').attr('disabled', false);
            $('.ppc-save-button-warning').remove();

            if (button.hasClass('ppc-feature-gutenberg-new-submit')) {
              activeSelector = '.editor-features-gutenberg';
              $('.ppc-feature-gutenberg-new-name').val('');
              $('.ppc-feature-gutenberg-new-ids').val('');
            } else {
              activeSelector = '.editor-features-classic';
              $('.ppc-feature-classic-new-name').val('');
              $('.ppc-feature-classic-new-ids').val('');
            }

            button.closest('tr').find('.ppc-post-features-note').html('<div class="ppc-feature-submit-form-error" style="color:green;">' + response.message + '</div>');

            $(".ppc-feature-submit-form-error").delay(5000).fadeOut('slow');

            setTimeout(function () {
              $('.ppc-menu-overlay-item').removeClass('ppc-menu-overlay-item');
            }, 5000);
            
            //add element to all post type
            $(activeSelector).each(function () {
              currentTable  = $(this);
              PostType      = currentTable.attr('data-post_type');
              checkedElement = ($('.ppc-capabilities-tab-active').attr('data-slug') === PostType) ? 'checked' : '';
              tableContent = '<tr class="ppc-menu-row parent-menu ppc-menu-overlay-item">' +
                             '<td class="menu-column ppc-menu-item">' +
                             '<span class="gutenberg menu-item-link">' +
                             '<strong><i class="dashicons dashicons-arrow-right"></i>' + response.content.custom_label + ' <small class="entry">(' + response.content.custom_element + ')</small> &nbsp; <span class="ppc-custom-features-delete" data-id="' + response.content.element_id + '" data-parent="' + response.content.data_parent + '"><small>(' + response.content.delete_text + ')</small></span></strong></span></td>' +
                            '<td class="restrict-column ppc-menu-checkbox">' +
                            '<input id="check-item-' + PostType + '-' + response.content.element_id + '" class="check-item" type="checkbox" name="' + response.content.data_name_prefix + PostType + '[]" value="' + response.content.element_id + '" ' + checkedElement + ' />' +
                            '</td></tr>';
                            
              currentTable.find('tr:last').after(tableContent);
            });

          }

          $(".ppc-feature-post-loader").removeClass("is-active");
          button.attr('disabled', false);

        });


      });

      // -------------------------------------------------------------
      //   Delete custom added post features item
      // -------------------------------------------------------------
      $(document).on("click", ".ppc-custom-features-delete", function (event) {
        if (confirm(cmeAdmin.deleteWarning)) {
          var item = $(this);
          var delete_id = item.attr('data-id');
          var delete_parent = item.attr('data-parent');
          var security = $('.ppc-feature-submit-form-nonce').val();

          $("span[data-id='" + delete_id + "']").each(function () {
            $(this).closest('.ppc-menu-row').fadeOut(300);
          });

          var data = {
            'action': 'ppc_delete_custom_post_features_by_ajax',
            'security': security,
            'delete_id': delete_id,
            'delete_parent': delete_parent,
          };

          $.post(ajaxurl, data, function (response) {
            if (response.status == 'error') {
              $("span[data-id='" + delete_id + "']").each(function () {
                $(this).closest('.ppc-menu-row').show();
              });
              alert(response.message);
            }
          });

        }
      });

  	// -------------------------------------------------------------
  	//   Lock Editor Features 'Save changes' button if unsaved custom items exist
  	// -------------------------------------------------------------
  	$(document).on("keyup paste", ".ppc-add-custom-row-body input, .ppc-add-custom-row-body textarea", function (event) {
    	var lock_button = false;
    	$('.ppc-save-button-warning').remove();

    	$('.ppc-add-custom-row-body .left input, .ppc-add-custom-row-body .right textarea').each(function () {
      	if ($(this).val() !== '' && $(this).val().replace(/\s/g, '').length) {
        	lock_button = true;
      	}
    	});

    	if (lock_button) {
      	$(this).closest('form').find('input[type=submit]').attr('disabled', true).after('<span class="ppc-save-button-warning">' + cmeAdmin.saveWarning + '</span>');
    	} else {
      	$(this).closest('form').find('input[type=submit]').attr('disabled', false);
    	}
    });

    // -------------------------------------------------------------
    //   Submit css hide new form entry for admin feature
    // -------------------------------------------------------------
    $(document).on("click", ".ppc-feature-css-hide-new-submit", function (event) {
        event.preventDefault();
        var ajax_action   = 'ppc_submit_feature_css_hide_by_ajax';
          custom_label    = $('.ppc-feature-css-hide-new-name').val(),
          custom_element     = $('.ppc-feature-css-hide-new-element').val(),

          security        = $('.ppc-feature-submit-form-nonce').val(),
          button          = $(this);

        $('.ppc-feature-submit-form-error').remove();
        button.attr('disabled', true);
        $(".ppc-feature-post-loader").addClass("is-active");

        var data = {
          'action': ajax_action,
          'security': security,
          'custom_label': custom_label,
          'custom_element': custom_element,
        };

        $.post(ajaxurl, data, function (response) {

          if (response.status == 'error') {
            button.closest('tr').find('.ppc-post-features-note').html('<div class="ppc-feature-submit-form-error" style="color:red;">' + response.message + '</div>');
            $(".ppc-feature-submit-form-error").delay(2000).fadeOut('slow');
          } else {
            var parent_table = $('.parent-menu.hidecsselement');
            var parent_child = $('.child-menu.hidecsselement');

            $('.ppc-save-button-warning').remove();
            $('.ppc-feature-css-hide-new-name').val('');
            $('.ppc-feature-css-hide-new-element').val('');

            button.closest('tr').find('.ppc-post-features-note').html('<div class="ppc-feature-submit-form-error" style="color:green;">' + response.message + '</div>');
            $(".ppc-feature-submit-form-error").delay(5000).fadeOut('slow');
            setTimeout(function () {
              $('.ppc-menu-overlay-item').removeClass('ppc-menu-overlay-item');
            }, 5000);

            if(parent_child.length > 0){
              $('.child-menu.hidecsselement:last').after(response.content);
            }else{
              parent_table.after(response.content);
            }
          }

          button.closest('form').find('input[type=submit]').attr('disabled', false);
          $(".ppc-feature-post-loader").removeClass("is-active");
          button.attr('disabled', false);

        });
    });



    // -------------------------------------------------------------
    //   Delete css hide item for admin feature
    // -------------------------------------------------------------
    $(document).on("click", ".ppc-custom-features-css-delete", function (event) {
        if (confirm(cmeAdmin.deleteWarning)) {
          var item = $(this);
          var delete_id = item.attr('data-id');
          var security = $('.ppc-feature-submit-form-nonce').val();

          item.closest('.ppc-menu-row').fadeOut(300);

          var data = {
            'action': 'ppc_delete_feature_css_hide_item_by_ajax',
            'security': security,
            'delete_id': delete_id,
          };

          $.post(ajaxurl, data, function (response) {
            if (response.status == 'error') {
              item.closest('.ppc-menu-row').show();
              alert(response.message);
            }
          });

        }
    });

  
    // -------------------------------------------------------------
    //   Submit block url new form entry for admin feature
    // -------------------------------------------------------------
    $(document).on("click", ".ppc-feature-block-url-new-submit", function (event) {
      event.preventDefault();
      var ajax_action   = 'ppc_submit_feature_blocked_url_by_ajax';
        custom_label    = $('.ppc-feature-block-url-new-name').val(),
        custom_link     = $('.ppc-feature-block-url-new-link').val(),
        security        = $('.ppc-feature-submit-form-nonce').val(),
        button          = $(this);

      $('.ppc-feature-submit-form-error').remove();
      button.attr('disabled', true);
      $(".ppc-feature-post-loader").addClass("is-active");

      var data = {
        'action': ajax_action,
        'security': security,
        'custom_label': custom_label,
        'custom_link': custom_link,
      };

      $.post(ajaxurl, data, function (response) {

        if (response.status == 'error') {
          button.closest('tr').find('.ppc-post-features-note').html('<div class="ppc-feature-submit-form-error" style="color:red;">' + response.message + '</div>');
          $(".ppc-feature-submit-form-error").delay(2000).fadeOut('slow');
        } else {
          var parent_table = $('.parent-menu.blockedbyurl');
          var parent_child = $('.child-menu.blockedbyurl');

          $('.ppc-save-button-warning').remove();
          $('.ppc-feature-block-url-new-name').val('');
          $('.ppc-feature-block-url-new-link').val('');

          button.closest('tr').find('.ppc-post-features-note').html('<div class="ppc-feature-submit-form-error" style="color:green;">' + response.message + '</div>');
          $(".ppc-feature-submit-form-error").delay(5000).fadeOut('slow');
          setTimeout(function () {
            $('.ppc-menu-overlay-item').removeClass('ppc-menu-overlay-item');
          }, 5000);
          if(parent_child.length > 0){
            $('.child-menu.blockedbyurl:last').after(response.content);
          }else{
            parent_table.after(response.content);
          }
        }

        button.closest('form').find('input[type=submit]').attr('disabled', false);
        $(".ppc-feature-post-loader").removeClass("is-active");
        button.attr('disabled', false);

      });
  });



  // -------------------------------------------------------------
  //   Delete blocked url item for admin feature
  // -------------------------------------------------------------
  $(document).on("click", ".ppc-custom-features-url-delete", function (event) {
      if (confirm(cmeAdmin.deleteWarning)) {
        var item = $(this);
        var delete_id = item.attr('data-id');
        var security = $('.ppc-feature-submit-form-nonce').val();

        item.closest('.ppc-menu-row').fadeOut(300);

        var data = {
          'action': 'ppc_delete_feature_blocked_url_item_by_ajax',
          'security': security,
          'delete_id': delete_id,
        };

        $.post(ajaxurl, data, function (response) {
          if (response.status == 'error') {
            item.closest('.ppc-menu-row').show();
            alert(response.message);
          }
        });

      }
  });


  });