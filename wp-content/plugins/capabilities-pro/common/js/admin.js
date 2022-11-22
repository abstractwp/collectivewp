jQuery(document).ready(function(e){e("a.neg-cap").attr("title",cmeAdmin.negationCaption),e("a.neg-type-caps").attr("title",cmeAdmin.typeCapsNegationCaption),e("td.cap-unreg").attr("title",cmeAdmin.typeCapUnregistered),e("a.normal-cap").attr("title",cmeAdmin.switchableCaption),e('span.cap-x:not([class*="pp-cap-key"])').attr("title",cmeAdmin.capNegated),e('table.cme-checklist input[class!="cme-check-all"]').not(":disabled").attr("title",cmeAdmin.chkCaption),e(".ppc-checkboxes-documentation-link").length>0&&e(".ppc-checkboxes-documentation-link").attr("target","blank"),e("table.cme-checklist a.neg-cap").click(function(t){e(this).closest("td").removeClass("cap-yes").removeClass("cap-no").addClass("cap-neg");var a=e(this).parent().find('input[type="checkbox"]').attr("name");return e(this).after('<input type="hidden" class="cme-negation-input" name="'+a+'" value="" />'),e('input[name="'+a+'"]').closest("td").removeClass("cap-yes").removeClass("cap-no").addClass("cap-neg"),e(this).closest("tr").hasClass("unfiltered_upload")&&(e('input[name="caps[upload_files]"]').closest("td").addClass("cap-neg"),e('input[name="caps[upload_files]"]').closest("td").append('<input type="hidden" class="cme-negation-input" name="caps[upload_files]" value="" />'),e('input[name="caps[upload_files]"]').parent().next("a.neg-cap:visible").click()),!1}),e(document).on("click","table.cme-typecaps span.cap-x,table.cme-checklist span.cap-x,table.cme-checklist td.cap-neg span",function(t){e(this).closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e(this).parent().find('input[type="checkbox"]').prop("checked",!1),e(this).parent().find("input.cme-negation-input").remove();var a=e(this).next('input[type="checkbox"]').attr("name");return a||(a=e(this).next("label").find('input[type="checkbox"]').attr("name")),e('input[name="'+a+'"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="'+a+'"]').prop("checked",!1).parent().find("input.cme-negation-input").remove(),e(this).closest("td").hasClass("capability-checkbox-rotate")&&(e(this).closest("td").find('input[type="checkbox"]').prop("checked",!0),e(this).closest("td").hasClass("upload_files")&&(e("tr.unfiltered_upload").find("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e("tr.unfiltered_upload").find('input[type="checkbox"]').prop("checked",!1),e("tr.unfiltered_upload").find("input.cme-negation-input").remove(),e('input[name="caps[unfiltered_upload]"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="caps[unfiltered_upload]"]').prop("checked",!0).parent().find("input.cme-negation-input").remove())),e(this).closest("td").find('input[type="checkbox"]').hasClass("pp-single-action-rotate")&&e(this).closest("td").find('input[type="checkbox"]').prop("checked",!0),e(this).closest("tr").hasClass("unfiltered_upload")&&(e('input[name="caps[upload_files]"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="caps[upload_files]"]').prop("checked",!0).parent().find("input.cme-negation-input").remove()),!1}),e("#publishpress_caps_form").bind("keypress",function(t){if(13==t.keyCode)return e(document.activeElement).parent().find('input[type="submit"]').first().click(),!1}),e("input.cme-check-all").click(function(t){e(this).closest("table").find('input[type="checkbox"][disabled!="disabled"]:visible').prop("checked",e(this).is(":checked"))}),e("a.cme-neg-all").click(function(t){return e(this).closest("table").find("a.neg-cap:visible").click(),!1}),e("a.cme-switch-all").click(function(t){return e(this).closest("table").find("td.cap-neg span").click(),!1}),e("table.cme-typecaps a.neg-type-caps").click(function(t){return e(this).closest("tr").find('td[class!="cap-neg"]').filter('td[class!="cap-unreg"]').each(function(){e(this).addClass("cap-neg");var t=e(this).find('input[type="checkbox"]').attr("name");e(this).append('<input type="hidden" class="cme-negation-input" name="'+t+'" value="" />'),e('input[name="'+t+'"]').parent().next("a.neg-cap:visible").click()}),!1}),e("table.cme-typecaps th").click(function(){var t=e(this).index(),a=!e(this).prop("checked_all");if(e(this).hasClass("term-cap"))var n='[class*="term-cap"]';else var n='[class*="post-cap"]';var s=e(this).closest("table").find("tr td"+n+":nth-child("+(t+1)+') input[type="checkbox"]:visible');e(s).each(function(t,n){e('input[name="'+e(this).attr("name")+'"]').prop("checked",a)}),e(this).prop("checked_all",a)}),e("a.cme-fix-read-cap").click(function(){return e('input[name="caps[read]"]').prop("checked",!0),e('input[name="SaveRole"]').trigger("click"),!1}),e(".ppc-filter-select").each(function(){var t=e(this),a=[];e(this).parent().siblings("table").find("tbody").find("tr").each(function(){a.push({value:e(this).attr("class"),text:e(this).find(".cap_type").text()})}),a.forEach(function(a,n){t.append(e("<option>",{value:a.value,text:a.text}))})}),e(".ppc-filter-select").prop("selectedIndex",0),e(".ppc-filter-select-reset").click(function(){e(this).prev(".ppc-filter-select").prop("selectedIndex",0),e(this).parent().siblings("table").find("tr").show()}),e(".ppc-filter-select").change(function(){e(this).val()?(e(this).parent().siblings("table").find("tr").hide(),e(this).parent().siblings("table").find("thead tr:first-child").show(),e(this).parent().siblings("table").find("tr."+e(this).val()).show()):e(this).parent().siblings("table").find("tr").show()}),e(".ppc-filter-text").val(""),e(".ppc-filter-text-reset").click(function(){e(this).prev(".ppc-filter-text").val(""),e(this).parent().siblings("table").find("tr").show(),e(this).parent().siblings(".ppc-filter-no-results").hide()}),e(".ppc-filter-text").keyup(function(){var t=e(this).val().trim().replace(/\s+/g,"_");e(this).parent().siblings("table").find("tr").hide(),e(this).parent().siblings("table").find('tr[class*="'+t+'"]').show(),e(this).parent().siblings("table").find("tr.cme-bulk-select").hide(),0===e(this).val().length&&e(this).parent().siblings("table").find("tr").show(),0===e(this).parent().siblings("table").find("tr:visible").length?e(this).parent().siblings(".ppc-filter-no-results").show():e(this).parent().siblings(".ppc-filter-no-results").hide()}),e(document).on("click",".ppc-roles-tab li",function(t){t.preventDefault();var a=e(this).attr("data-tab");e(".ppc-roles-tab li").removeClass("active"),e(this).addClass("active"),e(".pp-roles-tab-tr").hide(),e(".pp-roles-"+a+"-tab").show()}),e(document).on("click",".roles-capabilities-load-more",function(t){t.preventDefault(),e(".roles-capabilities-load-more").hide(),e(".roles-capabilities-load-less").show(),e("ul.pp-roles-capabilities li").show()}),e(document).on("change",'.capability-checkbox-rotate input[type="checkbox"]',function(t){let a=e(this),n=!1,s=!1,i=!1;if(a.prop("checked")?a.prop("checked")&&(s=!0):i=!0,s&&a.hasClass("interacted")&&(s=!1,i=!1,n=!0),i)a.prop("checked",!1),a.closest("td").hasClass("upload_files")&&e("tr.unfiltered_upload").find('input[name="caps[unfiltered_upload]"]').prop("checked",!1);else if(s)a.prop("checked",!0),a.closest("td").hasClass("upload_files")&&(e("tr.unfiltered_upload").find("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e("tr.unfiltered_upload").find('input[type="checkbox"]').prop("checked",!1),e("tr.unfiltered_upload").find("input.cme-negation-input").remove(),e('input[name="caps[unfiltered_upload]"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="caps[unfiltered_upload]"]').prop("checked",!0).parent().find("input.cme-negation-input").remove(),e("tr.unfiltered_upload").find('input[name="caps[unfiltered_upload]"]').prop("checked",!0));else if(n){a.closest("td").hasClass("upload_files")&&e("tr.unfiltered_upload").find("a.neg-cap").trigger("click"),a.prop("checked",!1);var p=a.closest("td");p.addClass("cap-neg");var c=p.find('input[type="checkbox"]').attr("name");p.append('<input type="hidden" class="cme-negation-input" name="'+c+'" value="" />'),e('input[name="'+c+'"]').parent().next("a.neg-cap:visible").click()}a.addClass("interacted")}),e(document).on("click",".pp-row-action-rotate",function(t){t.preventDefault();let a=e(this);var n=!1,s=!1,i=0,p=0;a.closest("tr").find('input[type="checkbox"]').each(function(){e(this).hasClass("excluded-input")||e(this).prop("checked")?!e(this).hasClass("excluded-input")&&e(this).prop("checked")&&(i++,n=!0):(i++,s=!0),e(this).closest("td").hasClass("cap-neg")&&p++}),n&&s||p>=i?(n=!0,s=!1):n||!s||a.hasClass("interacted")?n&&!s?(n=!1,s=!0):(n=!1,s=!1):(n=!0,s=!1),n?a.closest("tr").find("td").filter('td[class!="cap-unreg"]').each(function(){e(this).closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e(this).parent().find('input[type="checkbox"]').prop("checked",!0),e(this).parent().find("input.cme-negation-input").remove();var t=e(this).next('input[type="checkbox"]').attr("name");t||(t=e(this).next("label").find('input[type="checkbox"]').attr("name")),e('input[name="'+t+'"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="'+t+'"]').prop("checked",!0).parent().find("input.cme-negation-input").remove(),e(this).closest("td").hasClass("upload_files")&&(e("tr.unfiltered_upload").find("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e("tr.unfiltered_upload").find('input[type="checkbox"]').prop("checked",!1),e("tr.unfiltered_upload").find("input.cme-negation-input").remove(),e('input[name="caps[unfiltered_upload]"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="caps[unfiltered_upload]"]').prop("checked",!0).parent().find("input.cme-negation-input").remove(),e("tr.unfiltered_upload").find('input[name="caps[unfiltered_upload]"]').prop("checked",!0))}):s?a.closest("tr").find("td").filter('td[class!="cap-unreg"]').each(function(){e(this).closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e(this).parent().find('input[type="checkbox"]').prop("checked",!1),e(this).parent().find("input.cme-negation-input").remove();var t=e(this).next('input[type="checkbox"]').attr("name");t||(t=e(this).next("label").find('input[type="checkbox"]').attr("name")),e('input[name="'+t+'"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="'+t+'"]').prop("checked",!1).parent().find("input.cme-negation-input").remove(),e(this).closest("td").hasClass("upload_files")&&e("tr.unfiltered_upload").find('input[name="caps[unfiltered_upload]"]').prop("checked",!1)}):a.closest("tr").find('td[class!="cap-neg"]').filter('td[class!="cap-unreg"]').each(function(){e(this).addClass("cap-neg");var t=e(this).find('input[type="checkbox"]').attr("name");e(this).append('<input type="hidden" class="cme-negation-input" name="'+t+'" value="" />'),e('input[name="'+t+'"]').parent().next("a.neg-cap:visible").click(),e(this).closest("td").hasClass("upload_files")&&e("tr.unfiltered_upload").find("a.neg-cap").trigger("click")}),a.addClass("interacted")}),e(document).on("change",'tr.unfiltered_upload input[name="caps[unfiltered_upload]"]',function(t){let a=e(this);a.prop("checked")?(e('input[name="caps[upload_files]"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="caps[upload_files]"]').prop("checked",!0).parent().find("input.cme-negation-input").remove()):a.prop("checked")||(e('input[name="caps[upload_files]"]').parent().closest("td").removeClass("cap-neg").removeClass("cap-yes").addClass("cap-no"),e('input[name="caps[upload_files]"]').prop("checked",!1).parent().find("input.cme-negation-input").remove())}),e(document).on("click",".pp-single-action-rotate",function(t){let a=e(this);var n=!1,s=!1;a.prop("checked")?s=!0:a.prop("checked")||(n=!0),n&&s?(n=!0,s=!1):n||!s||a.hasClass("interacted")?n&&!s?(n=!1,s=!0):(n=!1,s=!1):(n=!0,s=!1),n||s||(t.preventDefault(),a.closest("td").find("a.neg-cap").click()),a.addClass("interacted"),navigator.userAgent.toLowerCase().indexOf("firefox")>-1&&document.getSelection().empty()})});