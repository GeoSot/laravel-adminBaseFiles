"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[990],{9990:(t,i,e)=>{e.r(i),e.d(i,{default:()=>a});const s={name:"media-library",props:{mediaRoute:String,inputName:String,multiple:Boolean,isLibrary:Boolean,isGrouped:Boolean,uppyOptions:Object},data:function(){return{isShown:!1,input:{previewClass:".fileinput-preview",wrapperClass:".fileinput",buttonsClass:".buttons"},isMediaLibrary:this.isLibrary,choices:[],pagination:[],media:[],searchText:""}},mounted:function(){var t=this;this.makeAjax(),document.addEventListener("uppy-complete",(function(i){return t.uploadFinished(i)}))},watch:{isShown:function(t,i){if(t){var e=document.createElement("div");return e.classList.add("modal-backdrop","show"),void document.body.appendChild(e)}this.searchText="",this.makeAjax(),document.querySelector(".modal-backdrop").remove()}},methods:{uploadFinished:function(t){var i=this;this.makeAjax(),setTimeout((function(){return i.isMediaLibrary=!0}),1500)},isPicked:function(t){return this.choices.includes(t.id)},mediumTitle:function(t){return t.title?t.title:t.filename},pickAndClose:function(t){this.chooseMedium(t),this.pick()},pick:function(){var t=this;if(this.multiple){var i=this.$el.closest(".js-collectionContainer").querySelector(".js-addToCollection");this.choices.forEach((function(e){i.click();var s=document.querySelectorAll('input[name^="'+t.inputName+'"]'),a=Array.from(s).pop();t.fillHtml(a,t.media.find((function(t){return t.id===e})))}))}else this.choices.forEach((function(i){var e=document.querySelector('input[name="'+t.inputName+'"]');t.fillHtml(e,t.media.find((function(t){return t.id===i})))}));this.choices=[],this.isShown=!1},fillHtml:function(t,i){var e=t.parentNode.querySelector(this.input.previewClass);if(e){var s=e.dataset.imgclass;e.innerHTML='<img src="'+i.url+'" class="'+s+'"/>'}var a=t.parentNode.querySelector(this.input.buttonsClass);if(a.querySelector(".js-show"))a.querySelector(".js-show").href=i.url;else{var n=this.isGrouped?"":"mb-1 ml-1";a.insertAdjacentHTML("beforeend",'<a class="'.concat(n,' js-show btn btn-secondary btn-sm align-middle" role="button" href="').concat(i.url,'" target="_blank"><i class="fas fa-eye"></i></a>'))}document.dispatchEvent(new CustomEvent("baseAdmin.fileInput.changed",{bubbles:!0,cancelable:!1,detail:{addInputVal:i.id,input:t,filled:!0,name:"".concat(i.filename,".").concat(i.extension)}}))},chooseMedium:function(t){var i=t.id;if(this.isPicked(t)){var e=this.choices.indexOf(i);this.choices.splice(e,1)}else this.multiple||(this.choices=[]),this.choices.push(i)},search:function(){var t=this;setTimeout((function(){t.makeAjax(null,{keyword:t.searchText})}),100)},makeAjax:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:null,i=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},e=this;t=t||this.mediaRoute;var s=Object.assign({only_data:!0,num_of_items:10,extra_filters:{the_file_exists:!0}},i);BaseAdmin.makeAjax(t,"GET",s,0,(function(t,i){e.media=t.records.data,e.pagination=t.records.links}))}}};const a=(0,e(1900).Z)(s,(function(){var t=this,i=t.$createElement,e=t._self._c||i;return e("div",{staticClass:"d-inline-block"},[e("button",{class:["btn btn-outline-primary btn-sm  ml-auto",{"mb-1":!1===t.isGrouped}],attrs:{type:"button"},on:{click:function(i){t.isShown=!0}}},[t.isMediaLibrary?e("span",{staticClass:"text-muted"},[e("i",{staticClass:"fas fa-photo-video"})]):t._e(),t._v(" "),t.isMediaLibrary?t._e():e("span",{staticClass:"text-muted"},[e("i",{staticClass:"fas fa-upload"})])]),t._v(" "),e("div",{class:["modal",{show:t.isShown},{"d-block":t.isShown}],attrs:{tabindex:"-1",role:"dialog","aria-labelledby":"...","aria-hidden":"true"}},[e("div",{staticClass:"modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable",attrs:{role:"document"}},[e("div",{staticClass:"modal-content"},[e("div",{staticClass:"modal-content"},[e("div",{staticClass:"modal-header"},[e("div",{staticClass:"modal-title h5"},[t.isMediaLibrary?e("span",{staticClass:"text-muted"},[t._v("\n                            Media Library\n                        ")]):e("span",{staticClass:"text-muted"},[t._v("\n                           Upload File/s\n                        ")]),t._v(" "),e("button",{staticClass:"btn btn-outline-info ml-2 px-3",attrs:{type:"button",disabled:t.isMediaLibrary,title:"Media Library"},on:{click:function(i){t.isMediaLibrary=!0}}},[e("i",{staticClass:"fas fa-photo-video"})]),t._v(" "),e("button",{staticClass:"btn btn-outline-info ml-2 px-3",attrs:{type:"button",disabled:!t.isMediaLibrary,title:"Upload File/s"},on:{click:function(i){t.isMediaLibrary=!1}}},[e("i",{staticClass:"fas fa-upload"})])]),t._v(" "),e("button",{staticClass:"close",attrs:{type:"button","aria-label":"Close"},on:{click:function(i){t.isShown=!1}}},[e("span",{attrs:{"aria-hidden":"true"}},[t._v("×")])])]),t._v(" "),e("div",{staticClass:"modal-body"},[e("uppy",{directives:[{name:"show",rawName:"v-show",value:!0!==t.isMediaLibrary,expression:"isMediaLibrary!==true"}],class:["justify-content-center",{"d-flex":!t.isMediaLibrary}],attrs:{options:this.uppyOptions,"is-shown":!0!==t.isMediaLibrary}},[t._v("\n                            >\n                        ")]),t._v(" "),e("div",{staticClass:"mx-n2 mx-md-2"},[t.isMediaLibrary?e("div",{staticClass:"card"},[e("div",{staticClass:"card-body p-0"},[e("div",{staticClass:"p-3 "},[e("input",{directives:[{name:"model",rawName:"v-model.trim",value:t.searchText,expression:"searchText",modifiers:{trim:!0}}],staticClass:"form-control",attrs:{placeholder:"Search..."},domProps:{value:t.searchText},on:{keyup:t.search,input:function(i){i.target.composing||(t.searchText=i.target.value.trim())},blur:function(i){return t.$forceUpdate()}}})]),t._v(" "),e("div",{staticClass:"container"},[e("div",{staticClass:"row row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2  "},t._l(t.media,(function(i){return e("div",{key:i.id,staticClass:"col text-center mb-2",attrs:{id:i.id}},[e("div",{class:["mouse-pointer py-1",{"border-primary":t.isPicked(i)}],staticStyle:{border:"2px solid transparent"},on:{dblclick:function(e){return t.pickAndClose(i)},click:function(e){return t.chooseMedium(i)}}},[e("div",{domProps:{innerHTML:t._s(i.thumb_html)}}),t._v(" "),e("div",{staticClass:"small text-muted",domProps:{innerHTML:t._s(t.mediumTitle(i))}})])])})),0)]),t._v(" "),e("div",{staticClass:"p-2"},[e("ul",{staticClass:"pagination pagination-sm justify-content-end m-0"},t._l(t.pagination,(function(i){return e("li",{class:["page-item",{active:i.active},{disabled:null===i.url}]},[i.active?e("span",{staticClass:"page-link",domProps:{innerHTML:t._s(i.label)}}):e("a",{staticClass:"page-link",attrs:{href:i.url},domProps:{innerHTML:t._s(i.label)},on:{click:function(e){return e.preventDefault(),t.makeAjax(i.url)}}})])})),0)])])]):t._e()])],1),t._v(" "),e("div",{staticClass:"modal-footer"},[t.choices.length>0&&t.isMediaLibrary?e("button",{staticClass:"btn btn-success",attrs:{type:"button"},on:{click:t.pick}},[t._v("\n                            Pick Selected\n                        ")]):t._e(),t._v(" "),e("button",{staticClass:"btn btn-secondary",attrs:{type:"button"},on:{click:function(i){t.isShown=!1}}},[t._v("Close")])])])])])])])}),[],!1,null,null,null).exports},1900:(t,i,e)=>{function s(t,i,e,s,a,n,o,r){var l,c="function"==typeof t?t.options:t;if(i&&(c.render=i,c.staticRenderFns=e,c._compiled=!0),s&&(c.functional=!0),n&&(c._scopeId="data-v-"+n),o?(l=function(t){(t=t||this.$vnode&&this.$vnode.ssrContext||this.parent&&this.parent.$vnode&&this.parent.$vnode.ssrContext)||"undefined"==typeof __VUE_SSR_CONTEXT__||(t=__VUE_SSR_CONTEXT__),a&&a.call(this,t),t&&t._registeredComponents&&t._registeredComponents.add(o)},c._ssrRegister=l):a&&(l=r?function(){a.call(this,(c.functional?this.parent:this).$root.$options.shadowRoot)}:a),l)if(c.functional){c._injectStyles=l;var d=c.render;c.render=function(t,i){return l.call(i),d(t,i)}}else{var u=c.beforeCreate;c.beforeCreate=u?[].concat(u,l):[l]}return{exports:t,options:c}}e.d(i,{Z:()=>s})}}]);