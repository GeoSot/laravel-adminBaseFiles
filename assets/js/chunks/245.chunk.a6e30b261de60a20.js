(self.webpackChunk=self.webpackChunk||[]).push([[245],{7245:(t,e,a)=>{var n=function(){return $("#tableForm")},i=function(t,e,n,i,s){var o=arguments.length>5&&void 0!==arguments[5]?arguments[5]:null;n.status="enable"===s?1:0,a.e(700).then(a.bind(a,7700)).then((function(a){a.makeAjax(t,e,n,i,o)}))},s=function(t){var e=$("[id^=record_]:checked").map((function(){return $(this).val()})).get();if(0===e.length)return o({title:$("#no_record_selected").text(),text:$("#no_record_selected_msg").text(),icon:"warning"});var n=$(t).data("url"),s=$(t).data("method"),c={ids:e},d=$(t).data("keyword"),r=null,l=$(t).data("after_save_redirect_to");return l&&(r=function(){window.location.href=l}),"enable"===d||"disable"===d?i(n,s,c,0,d,r):function(t,e,n,i,s){var c=arguments.length>5&&void 0!==arguments[5]?arguments[5]:null;return o({title:$("#confirm_"+s).text(),text:$("#confirm_"+s+"_msg").text(),icon:"warning"},(function(s){s.isConfirmed&&a.e(700).then(a.bind(a,7700)).then((function(a){a.makeAjax(t,e,n,i,c)}))}))}(n,s,c,0,d,r)};a.e(2).then(a.t.bind(a,4002,23)).then((function(t){var e=t.default;e('[data-toggle="select-all"]').change((function(t){var a=e(this).data("target");e('[data-select-all="'+a+'"]').prop("checked",e(this).prop("checked"))})),e('[data-toggle="listing-actions"]').click((function(){s(this)})),e('[data-change="js-submit-form"]').change((function(){e(this).is('[name="trashed"]')&&e('[name="trashed"]').val(e(this).val()),n().submit()})),e('[data-click="js-submit-form"]').click((function(){n().submit()})),e("[data-sort]").click((function(){e('[name="sort"]').val(e(this).data("sort")),e('[name="order_by"]').val(e(this).data("order")),n().submit()})),e('[data-toggle="listing-actions-status"]').click((function(){var t=e(this),a=t.data("url"),n=t.data("method"),s=t.data("keyword"),o={onlyJson:!0,ids:[t.data("id")]};i(a,n,o,0,s,(function(e,a){"success"===a&&("enable"===s?(t.data("keyword","disable"),t.removeClass("btn-outline-danger").addClass("btn-outline-success"),t.find(".fas").removeClass("fa-times").addClass("fa-check")):(t.data("keyword","enable"),t.removeClass("btn-outline-success").addClass("btn-outline-danger"),t.find(".fas").removeClass("fa-check").addClass("fa-times")))}))}))}));var o=function(t,e){a.e(455).then(a.t.bind(a,2746,23)).then((function(a){a.default.fire({title:t.title||"",text:t.text,timer:1500,icon:t.icon,timerProgressBar:!0}).then((function(t){"function"==typeof e&&e(t)}))}))}}}]);