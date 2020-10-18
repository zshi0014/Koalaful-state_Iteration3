!function(e){var t={};function n(o){if(t[o])return t[o].exports;var r=t[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var r in e)n.d(o,r,function(t){return e[t]}.bind(null,r));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=145)}({0:function(e,t){!function(){e.exports=this.wp.element}()},1:function(e,t){!function(){e.exports=this.wp.i18n}()},11:function(e,t){!function(){e.exports=this.NelioContent.components}()},13:function(e,t){!function(){e.exports=this.React}()},134:function(e,t,n){},135:function(e,t,n){},136:function(e,t,n){},137:function(e,t,n){},138:function(e,t,n){},145:function(e,t,n){"use strict";n.r(t);var o={};n.r(o),n.d(o,"isDialogOpen",(function(){return g})),n.d(o,"isEditingNewTemplate",(function(){return O})),n.d(o,"isSavingTemplate",(function(){return v})),n.d(o,"getAttributes",(function(){return h})),n.d(o,"getAttribute",(function(){return y})),n.d(o,"isTemplateBeingDeleted",(function(){return j}));var r={};n.r(r),n.d(r,"saveTemplate",(function(){return _})),n.d(r,"setAttributes",(function(){return S})),n.d(r,"openDialog",(function(){return T})),n.d(r,"closeDialog",(function(){return A})),n.d(r,"_markAsSaving",(function(){return D})),n.d(r,"deleteTemplate",(function(){return P})),n.d(r,"_markAsBeingDeleted",(function(){return k})),n.d(r,"_markAsDeleted",(function(){return C}));var i=n(0),a=n(8),c=n.n(a),l=n(2),s=n(4);n(28);function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function p(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){c()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var f=n(17),m=n.n(f),d=n(5);var b=Object(l.combineReducers)({editor:function(e,t){switch(e||(e={attributes:{},isDialogOpen:!1,isEditingNewTemplate:!1,isSavingTemplate:!1}),t.type){case"OPEN_DIALOG":return p(p({},e),{},{attributes:p({},t.template),isDialogOpen:!0,isEditingNewTemplate:t.isNew});case"SET_ATTRIBUTES":return p(p({},e),{},{attributes:p(p({},e.attributes),t.attributes)});case"MARK_AS_SAVING":return p(p({},e),{},{isSavingTemplate:t.isSaving});case"CLOSE_DIALOG":return p(p({},e),{},{attributes:{},isDialogOpen:!1,isEditingNewTemplate:!1,isSavingTemplate:!1})}return e},templatesBeingDeleted:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[],t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"MARK_AS_BEING_DELETED":return[].concat(m()(e),[t.templateId]);case"MARK_AS_DELETED":return Object(d.without)(e,t.templateId)}return e}});function g(e){return e.editor.isDialogOpen||!1}function O(e){return e.editor.isEditingNewTemplate||!1}function v(e){return e.editor.isSavingTemplate||!1}function h(e){return e.editor.attributes}function y(e,t){return e.editor.attributes[t]}function j(e,t){return e.templatesBeingDeleted.includes(t)}var x=n(7),E=n.n(x),w=E.a.mark(_);function _(){var e,t,n,o,r,i,a;return E.a.wrap((function(c){for(;;)switch(c.prev=c.next){case 0:return c.prev=0,c.next=3,Object(s.dispatch)("nelio-content/template-settings","_markAsSaving",!0);case 3:return c.next=5,Object(s.select)("nelio-content/template-settings","isEditingNewTemplate");case 5:return e=c.sent,c.next=8,Object(s.select)("nelio-content/template-settings","getAttributes");case 8:return t=c.sent,c.next=11,Object(s.select)("nelio-content/data","getSiteId");case 11:return n=c.sent,c.next=14,Object(s.select)("nelio-content/data","getAuthenticationToken");case 14:return o=c.sent,r=e?"POST":"PUT",i=e?"https://api.neliocontent.com/v1/site/".concat(n,"/template"):"https://api.neliocontent.com/v1/site/".concat(n,"/template/").concat(t.id),c.next=19,Object(s.apiFetch)({url:i,method:r,credentials:"omit",mode:"cors",headers:{Authorization:"Bearer ".concat(o)},data:t});case 19:return a=c.sent,c.next=22,Object(s.dispatch)("nelio-content/data","receiveSocialTemplates",a);case 22:return c.next=24,Object(s.dispatch)("nelio-content/template-settings","closeDialog");case 24:c.next=30;break;case 26:return c.prev=26,c.t0=c.catch(0),c.next=30,Object(s.dispatch)("nelio-content/template-settings","_markAsSaving",!1);case 30:case"end":return c.stop()}}),w,null,[[0,26]])}function S(e){return{type:"SET_ATTRIBUTES",attributes:e}}function T(e,t){return{type:"OPEN_DIALOG",isNew:t,template:e}}function A(){return{type:"CLOSE_DIALOG"}}function D(e){return{type:"MARK_AS_SAVING",isSaving:e}}var N=E.a.mark(P);function P(e){var t,n;return E.a.wrap((function(o){for(;;)switch(o.prev=o.next){case 0:return o.prev=0,o.next=3,Object(s.dispatch)("nelio-content/template-settings","_markAsBeingDeleted",e);case 3:return o.next=5,Object(s.select)("nelio-content/data","getSiteId");case 5:return t=o.sent,o.next=8,Object(s.select)("nelio-content/data","getAuthenticationToken");case 8:return n=o.sent,o.next=11,Object(s.apiFetch)({url:"https://api.neliocontent.com/v1/site/".concat(t,"/template/").concat(e),method:"DELETE",credentials:"omit",mode:"cors",headers:{Authorization:"Bearer ".concat(n)}});case 11:return o.next=13,Object(s.dispatch)("nelio-content/data","deleteSocialTemplate",e);case 13:return o.next=15,Object(s.dispatch)("nelio-content/template-settings","_markAsDeleted",e);case 15:return o.next=17,Object(s.dispatch)("nelio-content/template-settings","closeDialog");case 17:o.next=21;break;case 19:o.prev=19,o.t0=o.catch(0);case 21:case"end":return o.stop()}}),N,null,[[0,19]])}function k(e){return{type:"MARK_AS_BEING_DELETED",templateId:e}}function C(e){return{type:"MARK_AS_DELETED",templateId:e}}function I(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function R(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?I(Object(n),!0).forEach((function(t){c()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):I(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}Object(l.registerStore)("nelio-content/template-settings",{reducer:b,controls:s.controls,actions:R({},r),selectors:R({},o)});var B=n(3),M=n(1),L=n(11),G=(n(134),n(135),n(6)),$=n(15),V=n.n($),F=n(25),U=n.n(F),K=(n(136),/({title}|{permalink}|{tags}|{excerpt})/g),z=Object(l.withSelect)((function(e,t){var n=t.templateId,o=t.isDefault,r=void 0!==o&&o;if(r)return{filterSummary:Object(M._x)("Default Template","text","nelio-content"),hasActions:!1,hasIcon:!1,isValid:!0,text:Q("{title} {permalink}")};var i=e("nelio-content/data"),a=i.getSocialProfile,c=(0,i.getSocialTemplate)(n)||{},l=c.profileId,s=(0,e("nelio-content/template-settings").isTemplateBeingDeleted)(n);return{template:c,filterSummary:H(e("nelio-content/data"),c),hasActions:!r&&!s,hasIcon:!r,isBeingDeleted:s,isValid:!l||!!a(l),network:c.network,profileId:c.profileId,text:Q(c.text)}})),W=Object(l.withDispatch)((function(e,t){var n=t.templateId,o=t.template,r=e("nelio-content/template-settings"),i=r.openDialog,a=r.deleteTemplate;return{deleteTemplate:function(){return a(n)},editTemplate:function(){return i(o,!1)}}})),q=Object(G.compose)(z,W)((function(e){var t=e.deleteTemplate,n=e.editTemplate,o=e.filterSummary,r=e.hasActions,a=e.hasIcon,c=e.isBeingDeleted,l=e.isValid,s=e.network,u=e.profileId,p=e.text;return Object(i.createElement)("div",{className:V()({"nelio-content-template":!0,"nelio-content-template--is-invalid":!l,"nelio-content-template--is-being-deleted":c})},Object(i.createElement)("div",{className:"nelio-content-template__text"},U()({mixedString:p,components:{code:Object(i.createElement)("span",{className:"nelio-content-template__code-tag"}),newline:Object(i.createElement)("span",{className:"nelio-content-template__newline-tag"})}})),Object(i.createElement)("div",{className:"nelio-content-template__extra"},c?Object(i.createElement)(L.DeleteButton,{isDeleting:c}):Object(i.createElement)("div",{className:"nelio-content-template__filters"},o),r&&Object(i.createElement)("div",{className:"nelio-content-template__actions"},Object(i.createElement)("span",{className:"nelio-content-template__bullet-separator"},"•"),Object(i.createElement)(B.Button,{isLink:!0,onClick:n},Object(M._x)("Edit","command","nelio-content"))," | ",Object(i.createElement)(L.DeleteButton,{onClick:t}))),a&&!!u&&Object(i.createElement)("div",{className:"nelio-content-template__icon"},Object(i.createElement)(L.SocialProfileIcon,{profileId:u})),a&&!u&&Object(i.createElement)("div",{className:"nelio-content-template__icon"},Object(i.createElement)(L.SocialNetworkIcon,{network:s})))}));function H(e,t){if(!t.author)return J(e,t);var n=e.getAuthor(t.author)||{};return Object(M.sprintf)(Object(M._x)("%1$s by %2$s","text","nelio-content"),J(e,t),n.name||Object(M._x)("Unknown Author","text","nelio-content"))}function J(e,t){if("nc_any"===t.postType)return Object(M._x)("All Content","text","nelio-content");if("post"===t.postType&&"nc_none"===t.postCategory)return Object(M._x)("Posts","text","nelio-content");if("post"===t.postType){var n=Object(M._x)("Unknown Category","text","nelio-content"),o=e.getPostCategory(t.postCategory)||{};return Object(M.sprintf)(Object(M._x)("Posts in %s","text","nelio-content"),o.label||n)}var r=e.getPostType(t.postType);return r?r.labels.plural:"—"}function Q(e){return e=" ".concat(e," "),e=X(e),e=Y(e),e=Z(e),e=ee(e),e=Object(d.trim)(e)}var X=function(e){return e.replace(/\r?\n/g,"¶")},Y=function(e){return e.replace(new RegExp("{{","g"),"{⁠{").replace(new RegExp("}}","g"),"}⁠}")},Z=function(e){return e.replace(K,"{{code}}$1{{/code}}")},ee=function(e){return e.replace(/ *¶ */g,"{{newline}}¶{{/newline}}")},te=Object(l.withSelect)((function(e,t){var n=t.type,o=e("nelio-content/data").getSocialTemplates;return{templates:Object(d.map)(o(n),"id")}}))((function(e){var t=e.templates;return Object(i.createElement)("div",{className:"nelio-content-template-list"},Object(i.createElement)(q,{isDefault:!0}),t.map((function(e){return Object(i.createElement)(q,{key:"nelio-content-template-".concat(e),templateId:e})})))})),ne=n(9),oe=(n(137),Object(l.withDispatch)((function(e,t){var n=t.type,o=e("nelio-content/template-settings").openDialog;return{newTemplate:function(){return o(Object(ne.createSocialTemplate)(n),!0)}}}))((function(e){var t=e.newTemplate,n=e.icon,o=e.label;return Object(i.createElement)("div",{className:"nelio-content-template-section-title"},Object(i.createElement)(B.Dashicon,{icon:n}),Object(i.createElement)("h2",{className:"nelio-content-template-section-title__header"},o),Object(i.createElement)(B.Button,{isSmall:!0,className:"page-title-action",onClick:t},Object(M._x)("Add Template","command","nelio-content")))}))),re=n(20),ie=(n(138),Object(l.withSelect)((function(e,t){var n=t.value,o=e("nelio-content/data"),r=o.getPostTypes,i=o.getPostCategories,a=r(),c=Object(d.map)(a,"name").includes("post")?i():[],l=Object(d.filter)(a,(function(e){return"post"!==e.name})),s=[{label:Object(M._x)("All Content","text","nelio-content"),value:"nc_any"}].concat(m()(Object(d.sortBy)(Object(d.map)(c,(function(e){return{label:Object(M.sprintf)(Object(M._x)("Posts in %s","text","nelio-content"),e.label),value:"post:".concat(e.name)}})),"label")),m()(Object(d.sortBy)(Object(d.map)(l,(function(e){return{label:e.labels.plural,value:e.name}})),"label")));return{options:s,selectedOption:(("post"===n.postType?Object(d.find)(s,{value:"post:".concat(n.postCategory)}):Object(d.find)(s,{value:n.postType}))||s[0]).value}}))((function(e){var t=e.className,n=e.disabled,o=e.onChange,r=e.options,a=e.selectedOption;return Object(i.createElement)(B.SelectControl,{className:t,disabled:n,value:a,options:r,onChange:function(e){return/^post:/.test(e)?o({postType:"post",postCategory:e.replace(/^post:/,"")}):o({postType:e,postCategory:void 0})}})}))),ae=Object(l.withSelect)((function(e){return{attributes:(0,e("nelio-content/template-settings").getAttributes)()}})),ce=Object(l.withSelect)((function(e,t){var n=t.attributes,o=e("nelio-content/template-settings"),r=o.isDialogOpen,i=o.isEditingNewTemplate,a=o.isSavingTemplate,c=o.isTemplateBeingDeleted;return{isOpen:r(),isNewTemplate:i(),isDeleting:c(n.id),isSaving:a()}})),le=Object(l.withDispatch)((function(e,t){var n=t.attributes,o=e("nelio-content/template-settings"),r=o.closeDialog,i=o.deleteTemplate,a=o.saveTemplate;return{deleteTemplate:function(){return i(n.id)},closeDialog:r,setAttributes:o.setAttributes,save:function(){return a()}}})),se=Object(l.withSelect)((function(e,t){var n=t.attributes,o=n.network;if(!o)return{error:Object(M._x)("Please select a profile","user","nelio-content")};var r=n.profileId,i=n.targetName;return r&&Object(re.getMultiTargetNetworks)().includes(i)&&!i?{error:Object(re.getTargetLabel)(o,"selectTargetError")}:n.text?void 0:{error:Object(M._x)("Please create a template with some content","user","nelio-content")}})),ue=Object(G.ifCondition)((function(e){return e.isOpen})),pe=Object(G.compose)(ae,ce,le,se,ue)((function(e){var t=e.attributes,n=(t=void 0===t?{}:t).author,o=t.network,r=t.postCategory,a=t.postType,c=t.profileId,l=t.targetName,s=t.text,u=e.className,p=void 0===u?"":u,f=e.closeDialog,m=e.deleteTemplate,d=e.error,b=e.isDeleting,g=e.isNewTemplate,O=e.isSaving,v=e.save,h=e.setAttributes,y=O||b;return Object(i.createElement)(B.Modal,{className:"nelio-content-social-template-editor-dialog ".concat(p),title:g?Object(M._x)("New Template","text","nelio-content"):Object(M._x)("Edit Template","text","nelio-content"),isDismissable:!1,isDismissible:!1,shouldCloseOnEsc:!1,shouldCloseOnClickOutside:!1,onRequestClose:f},Object(i.createElement)(L.SocialProfileSelector,{includeNetworks:!0,disabled:y,network:o,profileId:c,targetName:l,onChange:h}),Object(i.createElement)(B.TextareaControl,{className:"nelio-content-social-template-editor-dialog__text",disabled:y,value:s,onChange:function(e){return h({text:e})},placeholder:Object(M._x)("Write a template…","user","nelio-content")}),Object(i.createElement)("div",{className:"nelio-content-social-template-editor-dialog__author-and-type"},Object(i.createElement)(L.AuthorSearcher,{className:"nelio-content-social-template-editor-dialog__author",hasAllAuthors:!0,value:n,disabled:y,onChange:function(e){return h({author:e})}}),Object(i.createElement)(ie,{className:"nelio-content-social-template-editor-dialog__type",disabled:y,value:{postType:a,postCategory:r},onChange:function(e){return h({postType:e.postType,postCategory:e.postCategory})}})),Object(i.createElement)("div",{className:"nelio-content-social-template-editor-dialog__buttons"},!g&&Object(i.createElement)(L.DeleteButton,{disabled:y,isDeleting:b,onClick:m}),Object(i.createElement)("div",{className:"nelio-content-social-template-editor-dialog__default-buttons-wrapper"},Object(i.createElement)(B.Button,{className:"nelio-content-social-template-editor-dialog__cancel-button",isSecondary:!0,disabled:y,onClick:f},Object(M._x)("Cancel","command","nelio-content")),Object(i.createElement)(L.SaveButton,{className:"nelio-content-social-template-editor-dialog__save-button",disabled:y,isPrimary:!0,isSaving:O,isUpdate:!g,error:d,onClick:v}))))})),fe=Object(l.withSelect)((function(e){var t=e("nelio-content/data"),n=t.isLoadingSocialProfiles;return{isLoading:(0,t.isLoadingSocialTemplates)()||n()}}))((function(e){return e.isLoading?Object(i.createElement)("div",{className:"nelio-content-social-templates-layout"},Object(i.createElement)(L.LoadingAnimation,null)):Object(i.createElement)(i.StrictMode,null,Object(i.createElement)(B.SlotFillProvider,null,Object(i.createElement)("div",{className:"nelio-content-social-templates-layout"},Object(i.createElement)(oe,{icon:"megaphone",label:Object(M._x)("Publication","text","nelio-content"),type:"publication"}),Object(i.createElement)(te,{type:"publication"}),Object(i.createElement)(oe,{icon:"share-alt",label:Object(M._x)("Reshare","text","nelio-content"),type:"reshare"}),Object(i.createElement)(te,{type:"reshare"}),Object(i.createElement)(pe,null),Object(i.createElement)(B.Popover.Slot,null))))}));window.NelioContent=window.NelioContent||{},window.NelioContent.initPage=function(e){var t=document.getElementById(e);Object(i.render)(Object(i.createElement)(fe,null),t)}},15:function(e,t,n){var o;
/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/!function(){"use strict";var n={}.hasOwnProperty;function r(){for(var e=[],t=0;t<arguments.length;t++){var o=arguments[t];if(o){var i=typeof o;if("string"===i||"number"===i)e.push(o);else if(Array.isArray(o)&&o.length){var a=r.apply(null,o);a&&e.push(a)}else if("object"===i)for(var c in o)n.call(o,c)&&o[c]&&e.push(c)}}return e.join(" ")}e.exports?(r.default=r,e.exports=r):void 0===(o=function(){return r}.apply(t,[]))||(e.exports=o)}()},17:function(e,t,n){var o=n(29),r=n(30),i=n(24),a=n(31);e.exports=function(e){return o(e)||r(e)||i(e)||a()}},19:function(e,t){e.exports=function(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,o=new Array(t);n<t;n++)o[n]=e[n];return o}},2:function(e,t){!function(){e.exports=this.wp.data}()},20:function(e,t){!function(){e.exports=this.NelioContent.networks}()},24:function(e,t,n){var o=n(19);e.exports=function(e,t){if(e){if("string"==typeof e)return o(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?o(e,t):void 0}}},25:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},r=c(n(13)),i=c(n(38)),a=c(n(41));function c(e){return e&&e.__esModule?e:{default:e}}var l=void 0;function s(e,t){var n,a,c,u,p,f,m,d,b=[],g={};for(f=0;f<e.length;f++)if("string"!==(p=e[f]).type){if(!t.hasOwnProperty(p.value)||void 0===t[p.value])throw new Error("Invalid interpolation, missing component node: `"+p.value+"`");if("object"!==o(t[p.value]))throw new Error("Invalid interpolation, component node must be a ReactElement or null: `"+p.value+"`","\n> "+l);if("componentClose"===p.type)throw new Error("Missing opening component token: `"+p.value+"`");if("componentOpen"===p.type){n=t[p.value],c=f;break}b.push(t[p.value])}else b.push(p.value);return n&&(u=function(e,t){var n,o,r=t[e],i=0;for(o=e+1;o<t.length;o++)if((n=t[o]).value===r.value){if("componentOpen"===n.type){i++;continue}if("componentClose"===n.type){if(0===i)return o;i--}}throw new Error("Missing closing component token `"+r.value+"`")}(c,e),m=s(e.slice(c+1,u),t),a=r.default.cloneElement(n,{},m),b.push(a),u<e.length-1&&(d=s(e.slice(u+1),t),b=b.concat(d))),1===b.length?b[0]:(b.forEach((function(e,t){e&&(g["interpolation-child-"+t]=e)})),(0,i.default)(g))}t.default=function(e){var t=e.mixedString,n=e.components,r=e.throwErrors;if(l=t,!n)return t;if("object"!==(void 0===n?"undefined":o(n))){if(r)throw new Error("Interpolation Error: unable to process `"+t+"` because components is not an object");return t}var i=(0,a.default)(t);try{return s(i,n)}catch(e){if(r)throw new Error("Interpolation Error: unable to process `"+t+"` because of error `"+e.message+"`");return t}}},28:function(e,t){!function(){e.exports=this.NelioContent.data}()},29:function(e,t,n){var o=n(19);e.exports=function(e){if(Array.isArray(e))return o(e)}},3:function(e,t){!function(){e.exports=this.wp.components}()},30:function(e,t){e.exports=function(e){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(e))return Array.from(e)}},31:function(e,t){e.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},35:function(e,t,n){"use strict";function o(e){return function(){return e}}var r=function(){};r.thatReturns=o,r.thatReturnsFalse=o(!1),r.thatReturnsTrue=o(!0),r.thatReturnsNull=o(null),r.thatReturnsThis=function(){return this},r.thatReturnsArgument=function(e){return e},e.exports=r},38:function(e,t,n){"use strict";var o=n(13),r="function"==typeof Symbol&&Symbol.for&&Symbol.for("react.element")||60103,i=n(35),a=n(39),c=n(40),l="function"==typeof Symbol&&Symbol.iterator;function s(e,t){return e&&"object"==typeof e&&null!=e.key?(n=e.key,o={"=":"=0",":":"=2"},"$"+(""+n).replace(/[=:]/g,(function(e){return o[e]}))):t.toString(36);var n,o}function u(e,t,n,o){var i,c=typeof e;if("undefined"!==c&&"boolean"!==c||(e=null),null===e||"string"===c||"number"===c||"object"===c&&e.$$typeof===r)return n(o,e,""===t?"."+s(e,0):t),1;var p=0,f=""===t?".":t+":";if(Array.isArray(e))for(var m=0;m<e.length;m++)p+=u(i=e[m],f+s(i,m),n,o);else{var d=function(e){var t=e&&(l&&e[l]||e["@@iterator"]);if("function"==typeof t)return t}(e);if(d){0;for(var b,g=d.call(e),O=0;!(b=g.next()).done;)p+=u(i=b.value,f+s(i,O++),n,o)}else if("object"===c){0;var v=""+e;a(!1,"Objects are not valid as a React child (found: %s).%s","[object Object]"===v?"object with keys {"+Object.keys(e).join(", ")+"}":v,"")}}return p}var p=/\/+/g;function f(e){return(""+e).replace(p,"$&/")}var m,d,b=g,g=function(e){if(this.instancePool.length){var t=this.instancePool.pop();return this.call(t,e),t}return new this(e)},O=function(e){a(e instanceof this,"Trying to release an instance into a pool of a different type."),e.destructor(),this.instancePool.length<this.poolSize&&this.instancePool.push(e)};function v(e,t,n,o){this.result=e,this.keyPrefix=t,this.func=n,this.context=o,this.count=0}function h(e,t,n){var r,a,c=e.result,l=e.keyPrefix,s=e.func,u=e.context,p=s.call(u,t,e.count++);Array.isArray(p)?y(p,c,n,i.thatReturnsArgument):null!=p&&(o.isValidElement(p)&&(r=p,a=l+(!p.key||t&&t.key===p.key?"":f(p.key)+"/")+n,p=o.cloneElement(r,{key:a},void 0!==r.props?r.props.children:void 0)),c.push(p))}function y(e,t,n,o,r){var i="";null!=n&&(i=f(n)+"/");var a=v.getPooled(t,i,o,r);!function(e,t,n){null==e||u(e,"",t,n)}(e,h,a),v.release(a)}v.prototype.destructor=function(){this.result=null,this.keyPrefix=null,this.func=null,this.context=null,this.count=0},m=function(e,t,n,o){if(this.instancePool.length){var r=this.instancePool.pop();return this.call(r,e,t,n,o),r}return new this(e,t,n,o)},(d=v).instancePool=[],d.getPooled=m||b,d.poolSize||(d.poolSize=10),d.release=O;e.exports=function(e){if("object"!=typeof e||!e||Array.isArray(e))return c(!1,"React.addons.createFragment only accepts a single object. Got: %s",e),e;if(o.isValidElement(e))return c(!1,"React.addons.createFragment does not accept a ReactElement without a wrapper object."),e;a(1!==e.nodeType,"React.addons.createFragment(...): Encountered an invalid child; DOM elements are not valid children of React components.");var t=[];for(var n in e)y(e[n],t,n,i.thatReturnsArgument);return t}},39:function(e,t,n){"use strict";e.exports=function(e,t,n,o,r,i,a,c){if(!e){var l;if(void 0===t)l=new Error("Minified exception occurred; use the non-minified dev environment for the full error message and additional helpful warnings.");else{var s=[n,o,r,i,a,c],u=0;(l=new Error(t.replace(/%s/g,(function(){return s[u++]})))).name="Invariant Violation"}throw l.framesToPop=1,l}}},4:function(e,t){!function(){e.exports=this.wp.dataControls}()},40:function(e,t,n){"use strict";var o=n(35);e.exports=o},41:function(e,t,n){"use strict";function o(e){return e.match(/^\{\{\//)?{type:"componentClose",value:e.replace(/\W/g,"")}:e.match(/\/\}\}$/)?{type:"componentSelfClosing",value:e.replace(/\W/g,"")}:e.match(/^\{\{/)?{type:"componentOpen",value:e.replace(/\W/g,"")}:{type:"string",value:e}}e.exports=function(e){return e.split(/(\{\{\/?\s*\w+\s*\/?\}\})/g).map(o)}},5:function(e,t){!function(){e.exports=this.lodash}()},6:function(e,t){!function(){e.exports=this.wp.compose}()},7:function(e,t){!function(){e.exports=this.regeneratorRuntime}()},8:function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},9:function(e,t){!function(){e.exports=this.NelioContent.utils}()}});