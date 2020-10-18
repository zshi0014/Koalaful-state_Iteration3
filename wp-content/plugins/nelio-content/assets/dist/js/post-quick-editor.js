this.NelioContent=this.NelioContent||{},this.NelioContent.postQuickEditor=function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}return n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=166)}({0:function(t,e){!function(){t.exports=this.wp.element}()},1:function(t,e){!function(){t.exports=this.wp.i18n}()},10:function(t,e){!function(){t.exports=this.NelioContent.components}()},121:function(t,e,n){},13:function(t,e,n){var o=n(34),r=n(35),i=n(29),c=n(36);t.exports=function(t){return o(t)||r(t)||i(t)||c()}},14:function(t,e){!function(){t.exports=this.NelioContent.date}()},15:function(t,e){function n(e){return t.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},n(e)}t.exports=n},166:function(t,e,n){"use strict";n.r(e);var o={};n.r(o),n.d(o,"getAllAttributes",(function(){return E})),n.d(o,"getId",(function(){return v})),n.d(o,"getTitle",(function(){return g})),n.d(o,"getPostType",(function(){return P})),n.d(o,"getPostCategory",(function(){return _})),n.d(o,"getAuthorId",(function(){return w})),n.d(o,"getDateValue",(function(){return T})),n.d(o,"getTimeValue",(function(){return S})),n.d(o,"getReference",(function(){return x})),n.d(o,"getEditorialComment",(function(){return C})),n.d(o,"isNewPost",(function(){return k})),n.d(o,"isPublished",(function(){return I})),n.d(o,"isVisible",(function(){return V})),n.d(o,"isSaving",(function(){return N})),n.d(o,"isEditorInfoVisible",(function(){return A})),n.d(o,"getValidationError",(function(){return D}));var r={};n.r(r),n.d(r,"setTitle",(function(){return q})),n.d(r,"setPostType",(function(){return R})),n.d(r,"setPostCategory",(function(){return U})),n.d(r,"setAuthorId",(function(){return M})),n.d(r,"setDateValue",(function(){return L})),n.d(r,"setTimeValue",(function(){return Y})),n.d(r,"setReference",(function(){return B})),n.d(r,"setEditorialComment",(function(){return F})),n.d(r,"openNewPostEditor",(function(){return J})),n.d(r,"openPostEditor",(function(){return X})),n.d(r,"close",(function(){return Z})),n.d(r,"saveAndClose",(function(){return tt})),n.d(r,"showEditorInfo",(function(){return et})),n.d(r,"setValidationError",(function(){return nt})),n.d(r,"markAsSaving",(function(){return ot}));var i=n(0),c=n(5),s=n(6),u=n(2),a=n(4),l=(n(42),n(8)),d=n.n(l),p=n(14),f=n(7);function b(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);e&&(o=o.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,o)}return n}function O(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?b(Object(n),!0).forEach((function(e){d()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):b(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}function m(t){return void 0===t?t:"".concat(t)}function j(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);e&&(o=o.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,o)}return n}function h(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?j(Object(n),!0).forEach((function(e){d()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):j(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var y=Object(u.combineReducers)({attributes:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},e=arguments.length>1?arguments[1]:void 0;switch(e.type){case"OPEN_EDITOR":return O(O({},t),{},{id:e.post.id,title:e.post.title,type:e.post.type,category:e.post.category,authorId:e.post.author,dateValue:Object(f.isEmpty)(e.post.date)?m(e.post.dateValue):Object(p.date)("Y-m-d",e.post.date),timeValue:Object(f.isEmpty)(e.post.date)?m(e.post.timeValue):Object(p.date)("H:i",e.post.date),reference:"",comment:""});case"SET_TITLE":return O(O({},t),{},{title:e.title});case"SET_POST_TYPE":return O(O({},t),{},{type:e.postType,category:"post"===e.postType?t.postCategory:void 0});case"SET_POST_CATEGORY":return O(O({},t),{},{type:"post",category:e.postCategory});case"SET_AUTHOR":return O(O({},t),{},{authorId:e.authorId});case"SET_DATE":return O(O({},t),{},{dateValue:m(e.dateValue)});case"SET_TIME":return O(O({},t),{},{timeValue:m(e.timeValue)});case"SET_REFERENCE":return O(O({},t),{},{reference:e.url});case"SET_EDITORIAL_COMMENT":return O(O({},t),{},{comment:e.comment})}return t},status:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},e=arguments.length>1?arguments[1]:void 0;switch(e.type){case"OPEN_EDITOR":return h(h({},t),{},{error:void 0,isEditorInfoVisible:!1,isPublished:"publish"===e.post.status,isSaving:!1,isVisible:!0});case"CLOSE_EDITOR":return h(h({},t),{},{isVisible:!1});case"SHOW_EDITOR_INFO":return h(h({},t),{},{isEditorInfoVisible:!0});case"SET_VALIDATION_ERROR":return h(h({},t),{},{error:e.error});case"MARK_AS_SAVING":return h(h({},t),{},{isSaving:!!e.isSaving})}return t}});function E(t){return t.attributes}function v(t){return t.attributes.id}function g(t){return t.attributes.title||""}function P(t){return t.attributes.type||""}function _(t){return t.attributes.category}function w(t){return t.attributes.authorId}function T(t){return t.attributes.dateValue}function S(t){return t.attributes.timeValue}function x(t){return t.attributes.reference}function C(t){return t.attributes.comment}function k(t){return!t.attributes.id}function I(t){return t.status.isPublished||!1}function V(t){return t.status.isVisible||!1}function N(t){return t.status.isSaving||!1}function A(t){return t.status.isEditorInfoVisible||!1}function D(t){return t.status.error}function q(t){return{type:"SET_TITLE",title:t}}function R(t){return{type:"SET_POST_TYPE",postType:t}}function U(t){return{type:"SET_POST_CATEGORY",postCategory:t}}function M(t){return{type:"SET_AUTHOR",authorId:t}}function L(t){return{type:"SET_DATE",dateValue:t}}function Y(t){return{type:"SET_TIME",timeValue:t}}function B(t){return{type:"SET_REFERENCE",url:t}}function F(t){return{type:"SET_EDITORIAL_COMMENT",comment:t}}var H=n(9),G=n.n(H),K=n(3),W=G.a.mark(tt);function Q(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);e&&(o=o.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,o)}return n}function $(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?Q(Object(n),!0).forEach((function(e){d()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):Q(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var z="nelio-content/post-quick-editor";function J(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return{type:"OPEN_EDITOR",post:$($({},Object(f.createPost)()),t)}}function X(t){return{type:"OPEN_EDITOR",post:$({},t)}}function Z(){return{type:"CLOSE_EDITOR"}}function tt(){var t,e;return G.a.wrap((function(n){for(;;)switch(n.prev=n.next){case 0:return n.next=2,Object(a.select)(z,"isSaving");case 2:if(!n.sent){n.next=5;break}return n.abrupt("return");case 5:return n.next=7,Object(a.dispatch)(z,"markAsSaving",!0);case 7:return n.prev=7,n.next=10,Object(a.select)(z,"getAllAttributes");case 10:return t=n.sent,n.next=13,rt(t);case 13:return e=n.sent,n.next=16,Object(a.dispatch)("nelio-content/data","receivePosts",e);case 16:n.next=20;break;case 18:n.prev=18,n.t0=n.catch(7);case 20:return n.next=22,Object(a.dispatch)(z,"markAsSaving",!1);case 22:return n.next=24,Object(a.dispatch)(z,"close");case 24:case"end":return n.stop()}}),W,null,[[7,18]])}function et(){return{type:"SHOW_EDITOR_INFO"}}function nt(t){return{type:"SET_VALIDATION_ERROR",error:t}}function ot(t){return{type:"MARK_AS_SAVING",isSaving:t}}function rt(t){var e=t.id?"PUT":"POST",n=t.id?"/nelio-content/v1/post/".concat(t.id):"/nelio-content/v1/post";return Object(a.apiFetch)({path:n,method:e,data:Object(K.omitBy)(t,f.isEmpty)})}Object(u.registerStore)("nelio-content/post-quick-editor",{reducer:y,controls:a.controls,actions:r,selectors:o});n(121);var it=n(20),ct=n.n(it),st=n(21),ut=n.n(st),at=n(22),lt=n.n(at),dt=n(23),pt=n.n(dt),ft=n(15),bt=n.n(ft),Ot=n(1);function mt(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,o=bt()(t);if(e){var r=bt()(this).constructor;n=Reflect.construct(o,arguments,r)}else n=o.apply(this,arguments);return pt()(this,n)}}var jt=function(t){lt()(n,t);var e=mt(n);function n(){var t;ct()(this,n);for(var o=arguments.length,r=new Array(o),i=0;i<o;i++)r[i]=arguments[i];return(t=e.call.apply(e,[this].concat(r))).validate(),t}return ut()(n,[{key:"componentDidUpdate",value:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return t.title!==this.props.title||t.authorId!==this.props.authorId||t.dateValue!==this.props.dateValue||t.timeValue!==this.props.timeValue||t.reference!==this.props.reference?this.validate():void 0}},{key:"validate",value:function(){var t=this.props,e=t.authorId,n=t.dateValue,o=t.reference,r=t.timeValue,i=t.title,c=t.clearErrors,s=t.setError;return Object(f.isEmpty)(Object(K.trim)(i))?s(Object(Ot._x)("Please set post’s title","user","nelio-content")):Object(f.isEmpty)(e)?s(Object(Ot._x)("Please set a post author","user","nelio-content")):Object(f.isEmpty)(n)&&!Object(f.isEmpty)(r)?s(Object(Ot._x)("Please specify a date","user","nelio-content")):!Object(f.isEmpty)(n)&&Object(f.isEmpty)(r)?s(Object(Ot._x)("Please specify a time","user","nelio-content")):o&&!Object(f.isUrl)(o)?s(Object(Ot._x)("Please type in a valid reference URL","user","nelio-content")):c()}},{key:"render",value:function(){return null}}]),n}(i.Component),ht=Object(u.withSelect)((function(t){var e=t("nelio-content/post-quick-editor"),n=e.getAuthorId,o=e.getDateValue,r=e.getReference,i=e.getTimeValue,c=e.getTitle;return{authorId:n(),dateValue:o(),reference:r(),timeValue:i(),title:c()}})),yt=Object(u.withDispatch)((function(t){var e=t("nelio-content/post-quick-editor").setValidationError;return{setError:e,clearErrors:function(){return e()}}})),Et=Object(s.compose)(ht,yt)(jt),vt=n(10),gt=Object(u.withSelect)((function(t){var e=t("nelio-content/post-quick-editor"),n=e.isNewPost,o=e.isEditorInfoVisible;return{isNewPost:n(),isEditorInfoVisible:o()}})),Pt=Object(u.withDispatch)((function(t){return{showEditorInfo:t("nelio-content/post-quick-editor").showEditorInfo}})),_t=Object(s.ifCondition)((function(t){var e=t.isNewPost,n=t.isEditorInfoVisible;return e&&!n})),wt=Object(s.compose)(gt,Pt,_t)((function(t){var e=t.disabled,n=t.showEditorInfo;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__editor-info-toggle"},Object(i.createElement)(c.Button,{isLink:!0,disabled:e,onClick:n},Object(Ot._x)("Show Editor Info","command","nelio-content")))})),Tt=Object(u.withSelect)((function(t){var e=t("nelio-content/post-quick-editor"),n=e.getValidationError,o=e.isNewPost,r=e.isSaving;return{error:n(),isNewPost:o(),isSaving:r()}})),St=Object(u.withDispatch)((function(t){var e=t("nelio-content/post-quick-editor");return{close:e.close,saveAndClose:e.saveAndClose}})),xt=Object(s.compose)(Tt,St)((function(t){var e=t.canUserEditPost,n=t.close,o=t.error,r=t.isNewPost,s=t.isSaving,u=t.saveAndClose;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__actions"},Object(i.createElement)(wt,{disabled:s}),Object(i.createElement)(c.Button,{isSecondary:!0,disabled:s,onClick:n},Object(Ot._x)("Cancel","command","nelio-content")),e&&Object(i.createElement)(vt.SaveButton,{isPrimary:!0,error:o,isSaving:s,isUpdate:!r,onClick:u}))})),Ct=Object(u.withSelect)((function(t,e){var n=e.canUserEditPost,o=(0,t("nelio-content/data").getPost)((0,t("nelio-content/post-quick-editor").getId)())||{},r=o.status,i=o.editLink,c="publish"===r,s={url:o.viewLink,label:c?Object(Ot._x)("View","command","nelio-content"):Object(Ot._x)("Preview","command","nelio-content")},u={url:i,label:Object(Ot._x)("Edit","command","nelio-content")};return n?{primary:c?s:u,secondary:c?u:s}:{secondary:s}})),kt=Object(u.withDispatch)((function(t){return{close:t("nelio-content/post-quick-editor").close}})),It=Object(s.compose)(Ct,kt)((function(t){var e=t.close,n=t.primary,o=t.secondary;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__actions"},Object(i.createElement)(c.Button,{isSecondary:!0,onClick:e},Object(Ot._x)("Cancel","command","nelio-content")),!Object(f.isEmpty)(o)&&Object(i.createElement)(c.ExternalLink,{className:"components-button is-secondary",href:o.url,onClick:e},o.label),!Object(f.isEmpty)(n)&&Object(i.createElement)(c.ExternalLink,{className:"components-button is-primary",href:n.url,onClick:e},n.label))})),Vt=Object(u.withSelect)((function(t){if((0,t("nelio-content/post-quick-editor").isNewPost)())return{useEditActions:!0};var e=t("nelio-content/data").getPost,n=t("nelio-content/post-quick-editor"),o=n.getId,r=n.getTitle,i=n.getAuthorId,c=n.getDateValue,s=n.getTimeValue,u=e(o())||{},a={title:r(),author:i(),dateValue:c(),timeValue:s()};return{useEditActions:Object(K.reduce)(Object(K.keys)(a),(function(t,e){return t||u[e]!==a[e]}),!1)}}))((function(t){var e=t.canUserEditPost;return t.useEditActions?Object(i.createElement)(xt,{canUserEditPost:e}):Object(i.createElement)(It,{canUserEditPost:e})})),Nt=Object(u.withSelect)((function(t){return{authorId:(0,t("nelio-content/post-quick-editor").getAuthorId)()}})),At=Object(u.withSelect)((function(t){return{canChangeAuthor:(0,t("nelio-content/data").canCurrentUser)("edit-others",(0,t("nelio-content/post-quick-editor").getPostType)())}})),Dt=Object(u.withDispatch)((function(t){return{setAuthorId:t("nelio-content/post-quick-editor").setAuthorId}})),qt=Object(u.withSelect)((function(t){return{isMultiAuthor:(0,t("nelio-content/data").isMultiAuthor)()}})),Rt=Object(s.ifCondition)((function(t){return t.isMultiAuthor})),Ut=Object(s.compose)(Nt,At,Dt,qt,Rt)((function(t){var e=t.disabled,n=t.authorId,o=t.canChangeAuthor,r=t.setAuthorId;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__author"},Object(i.createElement)(vt.AuthorSearcher,{value:n,onChange:r,placeholder:Object(Ot._x)("Author…","text","nelio-content"),disabled:e||!o}))})),Mt=n(13),Lt=n.n(Mt),Yt=Object(u.withSelect)((function(t){var e=t("nelio-content/data"),n=e.getPostCategories,o=e.getPostTypes,r=t("nelio-content/post-quick-editor"),i=r.getPostType,c=r.getPostCategory,s=r.isNewPost,u=o("create");return{categories:!!Object(K.find)(u,{name:"post"})?n():[],category:c(),isNewPost:s(),postTypeObjects:u,type:i()}})),Bt=Object(u.withDispatch)((function(t,e,n){var o=n.select,r=t("nelio-content/post-quick-editor"),i=r.setAuthorId,c=r.setPostType;return{setPostType:function(t){var e=o("nelio-content/post-quick-editor").getAuthorId,n=o("nelio-content/data"),r=n.canCurrentUser,s=n.getCurrentUserId,u=e(),a=s();a!==u&&!r("edit-others",t)&&i(a),c(t)},setPostCategory:r.setPostCategory}})),Ft=Object(s.ifCondition)((function(t){var e=t.isNewPost,n=t.categories,o=void 0===n?[]:n,r=t.postTypeObjects;return e&&((void 0===r?[]:r).length>1||o.length>1)})),Ht=Object(s.compose)(Yt,Bt,Ft)((function(t){var e=t.disabled,n=t.categories,o=t.postTypeObjects,r=t.type,s=t.category,u=t.setPostType,a=t.setPostCategory,l=o.filter((function(t){return"post"!==t.name})).map((function(t){var e=t.name;return{label:t.labels.singular,value:e}}));return 1===n.length?l=[].concat(Lt()(l),[{label:Object(Ot._x)("Post","text (post type)","nelio-content"),value:"post:".concat(n[0].name)}]):1<n.length&&(l=[].concat(Lt()(l),Lt()(n.map((function(t){var e=t.label,n=t.name;return{label:1<o.length?Object(Ot.sprintf)(Object(Ot._x)("Post in %s","text","nelio-content"),e):e,value:"post:".concat(n)}}))))),l=Object(K.sortBy)(l,"label"),Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__classification"},Object(i.createElement)(c.SelectControl,{disabled:e,options:l,value:"post"===r?"post:".concat(s):r,onChange:function(t){return 0===t.indexOf("post:")?a(t.replace("post:","")):u(t)}}))})),Gt=Object(u.withSelect)((function(t){var e=t("nelio-content/data"),n=e.getToday,o=e.getPost,r=t("nelio-content/post-quick-editor"),i=r.getDateValue,c=r.getId,s=r.isNewPost,u=r.isPublished,a=(o(c())||{}).dateValue;return{dateValue:i(),isPublished:u(),minDate:s()?n():a}})),Kt=Object(u.withDispatch)((function(t){return{setDateValue:t("nelio-content/post-quick-editor").setDateValue}})),Wt=Object(s.compose)(Gt,Kt)((function(t){var e=t.canUserEditPost,n=t.dateValue,o=t.disabled,r=t.isPublished,c=t.minDate,s=t.setDateValue;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__date"},Object(i.createElement)(vt.DateInput,{disabled:o||r||!e,value:n,onChange:s,min:c}))})),Qt=Object(s.ifCondition)((function(t){return!t.canUserEditPost}))((function(t){var e=t.postType;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__edit-warning"},Object(i.createElement)(c.Dashicon,{icon:"warning"}),"post"===e&&Object(Ot._x)("You’re not allowed to edit this post.","user","nelio-content"),"page"===e&&Object(Ot._x)("You’re not allowed to edit this page.","user","nelio-content"),!["post","page"].includes(e)&&Object(Ot._x)("You’re not allowed to edit this content.","user","nelio-content"))})),$t=Object(u.withSelect)((function(t){var e=t("nelio-content/post-quick-editor"),n=e.isNewPost,o=e.isEditorInfoVisible,r=e.getEditorialComment,i=e.getReference;return{comment:r(),isEditorInfoVisible:o(),isNewPost:n(),reference:i()}})),zt=Object(u.withDispatch)((function(t){var e=t("nelio-content/post-quick-editor");return{setEditorialComment:e.setEditorialComment,setReference:e.setReference}})),Jt=Object(u.withSelect)((function(t){return{isSubscribed:(0,t("nelio-content/data").isSubscribed)()}})),Xt=Object(s.ifCondition)((function(t){var e=t.isNewPost,n=t.isEditorInfoVisible;return e&&n})),Zt=Object(s.compose)($t,zt,Jt,Xt)((function(t){var e=t.comment,n=t.disabled,o=t.isSubscribed,r=t.reference,s=t.setEditorialComment,u=t.setReference;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__editor-info"},Object(i.createElement)("p",{className:"nelio-content-post-quick-editor__editor-info-title"},Object(Ot._x)("Editor Information","text","nelio-content")),Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__reference"},Object(i.createElement)(c.TextControl,{disabled:n,value:r,onChange:function(t){return u(t)},placeholder:Object(Ot._x)("Suggest a reference…","user","nelio-content")})),Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__comment"},Object(i.createElement)(c.TextareaControl,{disabled:n||!o,value:e,onChange:function(t){return s(t)},placeholder:o?Object(Ot._x)("Add an editorial comment…","user","nelio-content"):Object(Ot._x)("Upgrade to Nelio Content Premium and add editorial comments to your authors","user","nelio-content")})))})),te=Object(u.withSelect)((function(t){return{title:(0,t("nelio-content/post-quick-editor").getTitle)()}})),ee=Object(u.withDispatch)((function(t){return{setTitle:t("nelio-content/post-quick-editor").setTitle}})),ne=Object(s.compose)(te,ee)((function(t){var e=t.disabled,n=t.title,o=t.setTitle,r=t.canUserEditPost;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__title"},Object(i.createElement)(c.TextControl,{disabled:e||!r,value:n,onChange:function(t){return o(t)},placeholder:Object(Ot._x)("Title…","text","nelio-content")}))})),oe=Object(u.withSelect)((function(t){var e=t("nelio-content/post-quick-editor"),n=e.getTimeValue,o=e.isPublished;return{timeValue:n(),isPublished:o()}})),re=Object(u.withDispatch)((function(t){return{setTimeValue:t("nelio-content/post-quick-editor").setTimeValue}})),ie=Object(s.compose)(oe,re)((function(t){var e=t.canUserEditPost,n=t.disabled,o=t.isPublished,r=t.setTimeValue,c=t.timeValue;return Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__time"},Object(i.createElement)(vt.TimeInput,{disabled:n||o||!e,type:"time",value:c,onChange:r}))})),ce=Object(u.withSelect)((function(t){var e=t("nelio-content/data"),n=e.getPost,o=e.canCurrentUserEditPost,r=t("nelio-content/post-quick-editor"),i=r.getId,c=r.isNewPost,s=r.isVisible,u=r.isSaving;return{canUserEditPost:c()||o(n(i())),isNewPost:c(),isSaving:u(),isVisible:s()}})),se=Object(u.withSelect)((function(t){var e=t("nelio-content/data").getPostType,n=(0,t("nelio-content/post-quick-editor").getPostType)();return{postType:n,postTypeObject:e(n)}})),ue=Object(u.withDispatch)((function(t){return{close:t("nelio-content/post-quick-editor").close}})),ae=Object(s.ifCondition)((function(t){return t.isVisible}));e.default=Object(s.compose)(ce,se,ue,ae)((function(t){var e=t.canUserEditPost,n=t.className,o=void 0===n?"":n,r=t.close,s=t.isNewPost,u=t.isSaving,a=t.postTypeObject,l=t.postType;return Object(i.createElement)(c.Modal,{className:"nelio-content-post-quick-editor ".concat(o),title:s?a.labels.new:a.labels.edit,isDismissable:!1,isDismissible:!1,shouldCloseOnEsc:!u,shouldCloseOnClickOutside:!1,onRequestClose:r},Object(i.createElement)(Et,null),Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__title-and-classification"},Object(i.createElement)(ne,{disabled:u,canUserEditPost:e}),Object(i.createElement)(Ht,{disabled:u})),Object(i.createElement)("div",{className:"nelio-content-post-quick-editor__extra-settings"},Object(i.createElement)(Ut,{disabled:u}),Object(i.createElement)(Wt,{disabled:u,canUserEditPost:e}),Object(i.createElement)(ie,{disabled:u,canUserEditPost:e})),Object(i.createElement)(Zt,{disabled:u}),Object(i.createElement)(Qt,{postType:l,canUserEditPost:e}),Object(i.createElement)(Vt,{canUserEditPost:e}))}))},2:function(t,e){!function(){t.exports=this.wp.data}()},20:function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},21:function(t,e){function n(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}t.exports=function(t,e,o){return e&&n(t.prototype,e),o&&n(t,o),t}},22:function(t,e,n){var o=n(39);t.exports=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&o(t,e)}},23:function(t,e,n){var o=n(31),r=n(26);t.exports=function(t,e){return!e||"object"!==o(e)&&"function"!=typeof e?r(t):e}},26:function(t,e){t.exports=function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}},27:function(t,e){t.exports=function(t,e){(null==e||e>t.length)&&(e=t.length);for(var n=0,o=new Array(e);n<e;n++)o[n]=t[n];return o}},29:function(t,e,n){var o=n(27);t.exports=function(t,e){if(t){if("string"==typeof t)return o(t,e);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?o(t,e):void 0}}},3:function(t,e){!function(){t.exports=this.lodash}()},31:function(t,e){function n(e){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?t.exports=n=function(t){return typeof t}:t.exports=n=function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},n(e)}t.exports=n},34:function(t,e,n){var o=n(27);t.exports=function(t){if(Array.isArray(t))return o(t)}},35:function(t,e){t.exports=function(t){if("undefined"!=typeof Symbol&&Symbol.iterator in Object(t))return Array.from(t)}},36:function(t,e){t.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}},39:function(t,e){function n(e,o){return t.exports=n=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},n(e,o)}t.exports=n},4:function(t,e){!function(){t.exports=this.wp.dataControls}()},42:function(t,e){!function(){t.exports=this.NelioContent.data}()},5:function(t,e){!function(){t.exports=this.wp.components}()},6:function(t,e){!function(){t.exports=this.wp.compose}()},7:function(t,e){!function(){t.exports=this.NelioContent.utils}()},8:function(t,e){t.exports=function(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}},9:function(t,e){!function(){t.exports=this.regeneratorRuntime}()}});