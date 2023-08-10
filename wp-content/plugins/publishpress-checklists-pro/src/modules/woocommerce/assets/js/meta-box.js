/**
 * @package PublishPress
 * @author PublishPress
 *
 * Copyright (C) 2018 PublishPress
 *
 * ------------------------------------------------------------------------------
 * Based on Edit Flow
 * Author: Daniel Bachhuber, Scott Bressler, Mohammad Jangda, Automattic, and
 * others
 * Copyright (c) 2009-2016 Mohammad Jangda, Daniel Bachhuber, et al.
 * ------------------------------------------------------------------------------
 *
 * This file is part of PublishPress
 *
 * PublishPress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PublishPress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PublishPress.  If not, see <http://www.gnu.org/licenses/>.
 */

(function ($, window, document, PP_Checklists, PPCH_WooCommerce) {
    'use strict';

    $(function () {
        /**
         *
         * Categories number
         *
         */
        if ($('.post-type-product #pp-checklists-req-categories_count').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var count = $('#product_catchecklist input:checked').length,
                    min_value = parseInt(objectL10n_checklist_requirements.requirements.categories_count.value[0]),
                    max_value = parseInt(objectL10n_checklist_requirements.requirements.categories_count.value[1]);

                $('.post-type-product  #pp-checklists-req-categories_count').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    PP_Checklists.check_valid_quantity(count, min_value, max_value)
                );
            });
        }

        /**
         *
         * Virtual
         *
         */
        if ($('#pp-checklists-req-virtual_checkbox').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var is_virtual = $('#woocommerce-product-data #_virtual:checked').length > 0;

                $('#pp-checklists-req-virtual_checkbox').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    is_virtual
                );
            });
        }

        /**
         *
         * Downloadable
         *
         */
        if ($('#pp-checklists-req-downloadable').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var is_downloadable = $('#woocommerce-product-data #_downloadable:checked').length > 0;

                $('#pp-checklists-req-downloadable').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    is_downloadable
                );
            });
        }

        /**
         *
         * Regular price
         *
         */
        if ($('#pp-checklists-req-regular_price').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var price = $('#woocommerce-product-data #_regular_price').val();

                price = price.replace(',', '.');
                price = parseFloat(price);

                $('#pp-checklists-req-regular_price').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    price > 0
                );
            });
        }

        /**
         *
         * Sale price
         *
         */
        if ($('#pp-checklists-req-sale_price').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var price = $('#woocommerce-product-data #_sale_price').val();

                price = price.replace(',', '.');
                price = parseFloat(price);

                $('#pp-checklists-req-sale_price').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    price > 0
                );
            });
        }

        /**
         *
         * Scheduled Sale price
         *
         */
        if ($('#pp-checklists-req-sale_price_scheduled').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var scheduled_from = $('#woocommerce-product-data #_sale_price_dates_from').val(),
                    is_scheduled = scheduled_from !== null && scheduled_from !== false && typeof scheduled_from !== 'undefined' && scheduled_from !== '';

                $('#pp-checklists-req-sale_price_scheduled').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    is_scheduled
                );
            });
        }

        /**
         *
         * Discount
         *
         */
        if ($('#pp-checklists-req-discount').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var regular_price = $('#woocommerce-product-data #_regular_price').val(),
                    sale_price = $('#woocommerce-product-data #_sale_price').val(),
                    discount = 0,
                    state = false;

                regular_price = regular_price.replace(',', '.');
                regular_price = parseFloat(regular_price);
                sale_price = sale_price.replace(',', '.');
                sale_price = parseFloat(sale_price);

                // Discount in percent
                discount = (regular_price - sale_price) / regular_price * 100;

                state = PP_Checklists.check_valid_quantity(
                    discount,
                    PPCH_WooCommerce.discount_min,
                    PPCH_WooCommerce.discount_max
                );

                $('#pp-checklists-req-discount').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    state
                );
            });
        }

        /**
         *
         * SKU
         *
         */
        if ($('#pp-checklists-req-sku').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var sku = $('#woocommerce-product-data #_sku').val().trim(),
                    state = false;

                state = sku !== '' && sku != null && sku != 0 && sku != false;

                $('#pp-checklists-req-sku').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    state
                );
            });
        }

        /**
         *
         * Manage Stock
         *
         */
        if ($('#pp-checklists-req-manage_stock').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var is_stock_manageable = $('#woocommerce-product-data #_manage_stock:checked').length > 0;

                $('#pp-checklists-req-manage_stock').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    is_stock_manageable
                );
            });
        }

        /**
         *
         * Sold Individually
         *
         */
        if ($('#pp-checklists-req-sold_individually').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var is_sold_individually = $('#woocommerce-product-data #_sold_individually:checked').length > 0;

                $('#pp-checklists-req-sold_individually').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    is_sold_individually
                );
            });
        }

        /**
         *
         * Backorder
         *
         */
        if ($('#pp-checklists-req-backorder').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var backorder = $('#woocommerce-product-data #_backorders').val(),
                    state = backorder === PPCH_WooCommerce.backorder;

                $('#pp-checklists-req-backorder').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    state
                );
            });
        }

        /**
         *
         * Upsells
         *
         */
        if ($('#pp-checklists-req-upsell').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var options = $('#woocommerce-product-data #upsell_ids').next().find('.select2-selection__choice'),
                    state = false;

                state = PP_Checklists.check_valid_quantity(
                    options.length,
                    parseInt(PPCH_WooCommerce.upsell_min),
                    parseInt(PPCH_WooCommerce.upsell_max)
                );

                $('#pp-checklists-req-upsell').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    state
                );
            });
        }

        /**
         *
         * Crosssells
         *
         */
        if ($('#pp-checklists-req-crosssell').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var options = $('#woocommerce-product-data #crosssell_ids').next().find('.select2-selection__choice'),
                    state = false;

                state = PP_Checklists.check_valid_quantity(
                    options.length,
                    parseInt(PPCH_WooCommerce.crosssell_min),
                    parseInt(PPCH_WooCommerce.crosssell_max)
                );

                $('#pp-checklists-req-crosssell').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    state
                );
            });
        }

        /**
         *
         * Product image
         *
         */
        if ($('#pp-checklists-req-image').length > 0) {
            $(document).on(PP_Checklists.EVENT_TIC, function (event) {
                var has_image = $('#postimagediv').find('#set-post-thumbnail').find('img').length > 0;

                $('#pp-checklists-req-image').trigger(
                    PP_Checklists.EVENT_UPDATE_REQUIREMENT_STATE,
                    has_image
                );
            });
        }
    });

})(jQuery, window, document, PP_Checklists, PPCH_WooCommerce);
