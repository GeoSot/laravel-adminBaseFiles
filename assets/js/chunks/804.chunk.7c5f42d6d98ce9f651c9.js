(self.webpackChunk=self.webpackChunk||[]).push([[804],{6804:(a,t,n)=>{"use strict";n.r(t),n.d(t,{init:()=>o});var e,i=BaseAdmin.forms.initializedClass,d='[data-toggle="calendar"]',l='[data-clear="calendar"]',o=function(){n.e(2).then(n.t.bind(n,4002,23)).then((function(a){(e=a.default)(document).on("click",d+":not(."+i+")",(function(){c(e(this))})),c(),e(document).on("click",l,(function(){e(d).find("input").each((function(){e(this).val("")}))}))}))},c=function(){var a=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null;e(d).not("."+i).addClass(i).each((function(a,t){if(!e(t).find('input[name$="_formatted"]').first().is(":disabled")){e(t).addClass(BaseAdmin.forms.initializedClass);var n=e(t).find("input").not("*_formatted").first().val(),i=n?moment(n):moment();e(t).daterangepicker({showDropdowns:!0,singleDatePicker:!0,startDate:i,timePicker:null!=e(t).attr("data-locale").match(/H:|h:|mm/g),timePicker24Hour:!0,minYear:1930,locale:{format:e(t).attr("data-locale")}},(function(a,t,n){m(a,t,n)}))}})),a&&a.click()},m=function(a,t,n){var i=e(undefined.element),d=e('[name="'+i.data("name")+'"]'),l=e('[name="'+i.data("name")+'_formatted"]');i.data("name").indexOf("]")>0&&(l=e('[name="'+i.data("name").replace("]","_formatted]")+'"]')),l.val(a.format(i.attr("data-locale"))),d.val(a.format("YYYY-MM-DD HH:mm:ss"))}}}]);