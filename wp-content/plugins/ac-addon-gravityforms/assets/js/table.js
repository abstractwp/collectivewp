!function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=0)}([function(e,t,n){"use strict";n.r(t);class r{place(){let e=o(),t=document.querySelector(".gform-settings__content"),n=document.querySelector("#ac-s");t&&n&&(t.parentElement.insertBefore(e,t),e.append(n),e.insertAdjacentHTML("beforeend",'<div class="gf-acs-button-container"><button class="button">Filter</button></div>'))}}const o=()=>{let e=document.createElement("form");e.id="gf-acs-form",e.addEventListener("submit",()=>{let t=AC_SERVICES.getService("Search").getRules();if(AC_SERVICES.getService("Search").disableFields(),0===t.rules.length)return;let n=document.createElement("input");n.type="hidden",n.name="ac-rules",n.value=JSON.stringify(t),e.append(n)});const t=new URLSearchParams(window.location.search);return["page","id"].forEach(n=>{let r=t.get(n);r&&e.insertAdjacentHTML("afterbegin",`<input type="hidden" name="${n}" value="${r}">`)}),e};document.addEventListener("DOMContentLoaded",()=>{let e=document.querySelector("#gf_form_toolbar");if(e&&e.insertAdjacentHTML("afterend",'<div class="wp-header-end"></div>'),document.body.classList.contains("forms_page_gf_entries")){let e=document.querySelector(".tablenav-pages .displaying-num");if(e){let t=e.innerHTML.split(" ")[0];t=t.replace(",","").replace(".",""),"undefined"!=typeof ACP_Export&&(ACP_Export.total_num_items=t),"undefined"!=typeof ACP_Editing&&(ACP_Editing.bulk_edit.total_items=parseInt(t))}}}),AC_SERVICES.addListener("Table.Ready",()=>{let e=document.querySelector(".layout-switcher"),t=document.querySelector(".gform-form-toolbar__container #gform-form-toolbar__menu");e&&t&&t.parentElement.insertBefore(e,t)}),AC_SERVICES.addListener("Search.Loaded",e=>{e.placementFactory.register("gravity_forms_entry",new r)})}]);