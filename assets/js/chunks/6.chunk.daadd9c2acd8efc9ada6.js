(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{J2jA:function(r,n,t){"use strict";t.r(n),t.d(n,"ajaxify",(function(){return i})),t.d(n,"ajaxifyFormOnModal",(function(){return s}));var e=[],o=function(r){var n={group:"form-group",groupError:"has-error",inputError:"is-invalid",helpBlock:"help-block",invalidHelpBlock:["invalid-feedback","d-block"]},t=this;this.$form=r,t.errors=null,this.setErrors=function(r){return t.errors=r,this};var e=function(){return t.errors?t.errors:(console.warn("you have to pass the validation errors first"),{})};this.appendFormErrors=function(){return t.clearErrors(),$.each(e(),(function(r,e){var i=t.$form.find('[name="'+r+'"]');i.parents("."+n.group).addClass(n.groupError).append(o(e)),i.addClass(n.inputError)})),this},this.clearErrors=function(){return r.find("."+n.group).each((function(r,t){$(this).removeClass(n.groupError).find("."+n.invalidHelpBlock[0]).remove(),$(this).find("."+n.inputError).removeClass(n.inputError)})),this};var o=function(r){return Array.isArray(r)&&(r=r[0]),'<p class="'+n.invalidHelpBlock.join(" ")+'">'+r+"</p>"};return this.getErrorsAsText=function(){var r="";return $.each(e(),(function(n,t){r+='<div data-input="'+n+'">'+t+"</div>"})),r},this},i=function(r){if(console.log(4),e[r])return e[r];if(this.$form=$(r),!this.$form.length)return console.error('"ajaxifyForm" --\x3e NO VALID SELECTOR'),!1;var n=this;this.form=r,this.$form=$(r),this.id=$(r).attr("id"),this._callback=null,this.errorsObj=null,this.spinnerId="formSpinner_"+jsHelper.uuid();var t=function(){$(n.form).off("submit.ajaxify")};t(),this.onSubmit=function(r){"function"==typeof r&&(n._callback=r)},$(n.form).on("submit.ajaxify",(function(r,t){if(r.preventDefault(),!n.$form.hasClass("submitting")){n.$form.addClass("submitting"),n.$form.prepend(s()),$("#"+n.spinnerId).fadeIn();var e=i(this);"function"==typeof n._callback&&n._callback(n,e)}}));var i=function(r){var t=new FormData(r);return $.ajax({method:r.method,processData:!1,cache:!1,contentType:!1,url:r.action,data:t,dataType:"json"}).fail((function(r){console.log(r);var t=r.statusText+"  Error Code: "+r.status;r.responseJSON&&(n.errorsObj=new o(n.$form).setErrors(r.responseJSON.errors),n.errorsObj.appendFormErrors(),t=n.errorsObj.getErrorsAsText()||r.responseJSON.message||t),a({title:"Oups...!",text:t,icon:"error"})})).always((function(){n.$form.removeClass("submitting"),$("#"+n.spinnerId).remove()}))};this.clearInputs=function(){return n.$form.trigger("reset"),n},this.destroy=function(){t(),delete e[n.form]};var s=function(){return'<div id="'+n.spinnerId+'" class="spinnerWrapper position-absolute d-flex justify-content-center align-items-center" style="top: 0;bottom: 0;right: 0;left: 0; background:#0000001c; z-index: 9999; display: none">    <i class="fas fa-spinner fa-pulse fa-4x"></i></div>'};return e[n.form]=n,n},s=function(r,n,t){var e=arguments.length>3&&void 0!==arguments[3]&&arguments[3];if(console.log(3),$(r).length&&$(n).length){var o=new i(r);$(n).on("hide.bs.modal",(function(r){o.clearInputs(),e&&o.destroy(),o.errorsObj&&o.errorsObj.clearErrors()})),o.onSubmit((function(r,e){e.done((function(e){r.clearInputs(),a({title:e.msg.title,text:e.msg.msg,icon:"success"}),$(n).modal("hide"),window.BaseAdmin.ajaxLoadWrappers(t)}))}))}},a=function(r){t.e(1).then(t.t.bind(null,"PSD3",7)).then((function(n){n.default.fire({title:r.title?r.title:"Error!",text:r.text,timer:15e3,icon:r.icon,timerProgressBar:!0})}))}}}]);