(self.webpackChunk=self.webpackChunk||[]).push([[823],{3823:(e,t,n)=>{"use strict";var i;n.r(t),n.d(t,{Init:()=>o}),BaseAdmin.forms=BaseAdmin.forms||{},BaseAdmin.forms.initializedClass="formField-initialized";var o=function(){n.e(2).then(n.t.bind(n,4002,23)).then((function(e){if((i=e.default)(document).on("click",".js-addwToCollection",(function(e){e.preventDefault();var t=i(this).closest(".js-collectionContainer").find(".js-collectionItems"),n=parseInt(i(this).attr("data-initial-count"))+1,o=i(this).data("prototype").replace(/__NAME__/g,n);i(this).attr("data-initial-count",n),t.append(o),t.find("textarea.withEditor").each((function(){var e=new CustomEvent("baseAdmin:initTextEditor",{detail:this});document.dispatchEvent(e)})),i(document).trigger("multiplyField",{counter:n,elem:o})})),i(document).on("click",'[data-toggle="removeParent"]',(function(e){i(this).closest(i(this).data("target")).remove()})),document.querySelectorAll('[data-toggle="dateRangeCalendar"], [data-toggle="calendar"]').length&&(window.moment=n.e(381).then(n.t.bind(n,5582,23)).then((function(e){window.moment=e.default,n.e(486).then(n.t.bind(n,486,23)).then((function(e){n.e(830).then(n.bind(n,5930)).then((function(e){return e.init()})),n.e(804).then(n.bind(n,6804)).then((function(e){return e.init()}))}))}))),document.querySelectorAll('[data-toggle="timePicker"]').length)n.e(52).then(n.bind(n,4052)).then((function(e){return e.init()}));if(document.querySelectorAll('[data-toggle="imageInput"], [data-toggle="fileInput"]').length)n.e(700).then(n.bind(n,8700)).then((function(e){return e.init()}));document.querySelectorAll('[data-toggle="colorPicker"]').length&&n.e(997).then(n.t.bind(n,8997,23)).then((function(e){i('[data-toggle="colorPicker"]').each((function(e){i(this).colorpicker()}))})),document.querySelectorAll(".sortableWrapper").length&&(window.Sortable=n.e(474).then(n.bind(n,1474)).then((function(){i(".sortableWrapper").each((function(){Sortable.create(this,{group:jsHelper.uuid(),handle:".sortingHandler",draggable:".sortableItem"})}))}))),document.querySelectorAll("select").length&&n.e(686).then(n.t.bind(n,686,23)).then((function(){!function(e){i.fn.select2.defaults.set("theme","bootstrap"),i.fn.select2.defaults.set("width","100%"),i.fn.select2.defaults.set("dropdownAutoWidth",!0),document.addEventListener("baseAdmin:ajaxLoadWrappers",(function(e){i(e.detail).find("select.select2, select[multiple]").each((function(){var e=i(this);e.select2({dropdownParent:e.parent()})}))})),i(document).on("select2:open",(function(){document.querySelector(".select2-container--open .select2-search__field").focus()}));var t=new CustomEvent("baseAdmin:ajaxLoadWrappers",{detail:e});document.dispatchEvent(t)}(i("body"))}))}))}}}]);