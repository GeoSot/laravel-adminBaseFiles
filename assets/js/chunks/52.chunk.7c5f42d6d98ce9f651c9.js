(self.webpackChunk=self.webpackChunk||[]).push([[52],{4052:(t,n,e)=>{"use strict";e.r(n),e.d(n,{init:()=>r});var i,u=void 0,s=[],o='[data-toggle="timePicker"]',r=function(){e.e(2).then(e.t.bind(e,4002,23)).then((function(t){(i=t.default)(document).on("click","".concat(o," input"),(function(t){new a(this)})),i((function(){i(o).each((function(t){new a(this)}))}))}))},a=function(t){var n=u;n.$wrapper=i(t).is(o)?i(t):i(t).parents(o),n.$hiddenInput=u.$wrapper.find('[type="hidden"]');var e=n.$hiddenInput.attr("name");s[e]=n,n.$hoursInput=n.$wrapper.find("input").not("[type=hidden]").first(),n.$minutesInput=n.$wrapper.find("input").not("[type=hidden]").last(),i(u.$hiddenInput).off("change.formFields"),i(u.$hoursInput).off("change.formFields"),i(u.$minutesInput).off("change.formFields"),n.totalMinutes=0,n.minutes=0,n.hours=0;var r=function(){n.totalMinutes=parseInt(n.$hiddenInput.val())||0;var t=n.getTimeSeparate();n.minutes=t.minutes,n.hours=t.hours,n.setTimeToInputs()};u.getTimeForHumans=function(){var t=n.getTimeSeparate();return("0"+t.hours).slice(-2)+":"+("0"+t.minutes).slice(-2)},u.getTimeSeparate=function(){return{hours:n.totalMinutes/60|0,minutes:n.totalMinutes%60|0}},u.getTimeToMinutes=function(){return n.totalMinutes};var a=function(){n.totalMinutes=n.minutes+60*n.hours};u.setTimeToInputs=function(){var t=n.getTimeSeparate();n.$hiddenInput.val(n.totalMinutes),n.$hoursInput.val(t.hours),n.$minutesInput.val(t.minutes)},i(u.$hoursInput).on("change.formFields",(function(t){n.hours=parseInt(i(this).val()),a(),n.setTimeToInputs()})),i(u.$minutesInput).on("change.formFields",(function(t){var e=parseInt(i(this).val());60===e&&(n.hours++,e=0),-1===e&&(e=0,n.hours>0&&(n.hours--,e=59)),n.minutes=e,a(),n.setTimeToInputs()})),i(u.$hiddenInput).on("change.formFields",(function(t){r()})),r()}}}]);