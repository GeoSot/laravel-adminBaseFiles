(self.webpackChunk=self.webpackChunk||[]).push([[804],{6804:(a,t,n)=>{"use strict";var e;n.r(t),n.d(t,{init:()=>l});var i=BaseAdmin.forms.initializedClass,o='[data-toggle="calendar"]',d='[data-clear="calendar"]',l=function(){n.e(2).then(n.t.bind(n,4002,23)).then((function(a){(e=a.default)(document).on("click",o+":not(."+i+")",(function(){r(e(this))})),r(),e(document).on("click",d,(function(){e(o).find("input").each((function(){e(this).val("")}))}))}))},r=function(){var a=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;e(o).not("."+i).addClass(i).each((function(a,t){if(!e(t).find('input[name$="_formatted"]').first().is(":disabled")){e(t).addClass(BaseAdmin.forms.initializedClass);var n=e(t).find("input").not("*_formatted").first().val(),i=n?moment(n):moment();e(t).daterangepicker({showDropdowns:!0,singleDatePicker:!0,startDate:i,autoApply:!0,drops:"auto",timePicker:null!=e(t).attr("data-locale").match(/H:|h:|mm/g),timePicker24Hour:!0,minYear:1930,locale:{format:e(t).attr("data-locale")}},c)}})),a&&a.click()},c=function(a,t,n){var i=e(this.element),o=e('[name="'+i.data("name")+'"]'),d=e('[name="'+i.data("name")+'_formatted"]');i.data("name").indexOf("]")>0&&(d=e('[name="'+i.data("name").replace("]","_formatted]")+'"]')),d.val(a.format(i.attr("data-locale"))),o.val(a.format("YYYY-MM-DD HH:mm:ss"))}}}]);