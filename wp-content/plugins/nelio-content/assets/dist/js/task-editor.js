this.NelioContent=this.NelioContent||{},this.NelioContent.taskEditor=function(e){var t={};function n(r){if(t[r])return t[r].exports;var o=t[r]={i:r,l:!1,exports:{}};return e[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}return n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)n.d(r,o,function(t){return e[t]}.bind(null,o));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=166)}({0:function(e,t){!function(){e.exports=this.wp.element}()},1:function(e,t){!function(){e.exports=this.wp.i18n}()},10:function(e,t){!function(){e.exports=this.NelioContent.components}()},14:function(e,t){!function(){e.exports=this.NelioContent.date}()},147:function(e,t,n){},148:function(e,t,n){},149:function(e,t,n){},15:function(e,t){function n(t){return e.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},n(t)}e.exports=n},16:function(e,t){function n(){return e.exports=n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e},n.apply(this,arguments)}e.exports=n},166:function(e,t,n){"use strict";n.r(t);var r={};n.r(r),n.d(r,"getId",(function(){return g})),n.d(r,"getTask",(function(){return k})),n.d(r,"getAssigneeId",(function(){return _})),n.d(r,"getDateType",(function(){return E})),n.d(r,"getDateValue",(function(){return x})),n.d(r,"getDateDue",(function(){return T})),n.d(r,"getColor",(function(){return S})),n.d(r,"getPost",(function(){return D})),n.d(r,"getSaveAttributes",(function(){return w})),n.d(r,"isNewTask",(function(){return P})),n.d(r,"isVisible",(function(){return C})),n.d(r,"isSaving",(function(){return N})),n.d(r,"getValidationError",(function(){return A}));var o={};n.r(o),n.d(o,"setPost",(function(){return V})),n.d(o,"setTask",(function(){return I})),n.d(o,"setAssigneeId",(function(){return R})),n.d(o,"setDateType",(function(){return B})),n.d(o,"setDateValue",(function(){return M})),n.d(o,"setDateDue",(function(){return L})),n.d(o,"setColor",(function(){return U})),n.d(o,"openNewTaskEditor",(function(){return H})),n.d(o,"close",(function(){return J})),n.d(o,"saveAndClose",(function(){return Q})),n.d(o,"setValidationError",(function(){return W})),n.d(o,"markAsSaving",(function(){return X}));var a=n(0),c=n(5),i=n(6),s=n(2),u=n(1),l=n(10),d=n(4),f=(n(42),n(7)),b=n.n(f),p=n(3);function O(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function j(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?O(Object(n),!0).forEach((function(t){b()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):O(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function v(e){return void 0===e?e:"".concat(e)}function y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function m(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?y(Object(n),!0).forEach((function(t){b()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):y(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}var h=Object(s.combineReducers)({attributes:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"OPEN_EDITOR":return j(j({},e),{},{id:t.task.id,task:t.task.task,assigneeId:t.task.assigneeId,assignerId:t.task.assignerId,dateType:t.task.dateType||"exact",dateValue:v(t.task.dateValue),color:t.task.color,completed:!1,post:t.post});case"SET_POST":return t.post?j(j({},e),{},{post:t.post}):Object(p.omit)(e,"post");case"SET_TASK":return j(j({},e),{},{task:t.task});case"SET_ASSIGNEE_ID":return j(j({},e),{},{assigneeId:t.assigneeId});case"SET_DATE_TYPE":return j(j({},e),{},{dateType:t.dateType});case"SET_DATE_VALUE":return j(j({},e),{},{dateValue:v(t.dateValue)});case"SET_DATE_DUE":return j(j({},e),{},{dateDue:t.dateDue});case"SET_COLOR":return j(j({},e),{},{color:t.color})}return e},status:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0;switch(t.type){case"OPEN_EDITOR":return m(m({},e),{},{error:void 0,isNewTask:t.isNewTask,isSaving:!1,isVisible:!0});case"CLOSE_EDITOR":return m(m({},e),{},{isVisible:!1});case"SET_VALIDATION_ERROR":return m(m({},e),{},{error:t.error});case"MARK_AS_SAVING":return m(m({},e),{},{isSaving:!!t.isSaving})}return e}});function g(e){return e.attributes.id}function k(e){return e.attributes.task}function _(e){return e.attributes.assigneeId}function E(e){return e.attributes.dateType}function x(e){return e.attributes.dateValue}function T(e){return e.attributes.dateDue}function S(e){return e.attributes.color}function D(e){return e.attributes.post}function w(e){var t=e.attributes;return t.post&&(t.postId=t.post.id,t.postType=t.post.type,t.postAuthor=t.post.author),Object(p.omit)(t,"post")}function P(e){return!!e.status.isNewTask}function C(e){return e.status.isVisible||!1}function N(e){return e.status.isSaving||!1}function A(e){return e.status.error}function V(e){return{type:"SET_POST",post:e}}function I(e){return{type:"SET_TASK",task:e}}function R(e){return{type:"SET_ASSIGNEE_ID",assigneeId:e}}function B(e){return{type:"SET_DATE_TYPE",dateType:e}}function M(e){return{type:"SET_DATE_VALUE",dateValue:e}}function L(e){return{type:"SET_DATE_DUE",dateDue:e}}function U(e){return{type:"SET_COLOR",color:e}}var G=n(9),K=n.n(G),Y=n(8),F=K.a.mark(Q);function q(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function z(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?q(Object(n),!0).forEach((function(t){b()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):q(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function H(e,t){return{type:"OPEN_EDITOR",task:z(z({},Object(Y.createTask)()),t),post:e||void 0,isNewTask:!0}}function J(){return{type:"CLOSE_EDITOR"}}function Q(){var e,t,n,r,o,a,c,i,s;return K.a.wrap((function(u){for(;;)switch(u.prev=u.next){case 0:return e="nelio-content/task-editor",u.next=3,Object(d.select)(e,"isSaving");case 3:if(!u.sent){u.next=6;break}return u.abrupt("return");case 6:return u.next=8,Object(d.dispatch)(e,"markAsSaving",!0);case 8:return u.prev=8,u.next=11,Object(d.select)(e,"getPost");case 11:return t=u.sent,u.next=14,Object(d.select)(e,"getSaveAttributes");case 14:return n=u.sent,u.next=17,Object(d.select)(e,"isNewTask");case 17:return r=u.sent,u.next=20,Object(d.select)("nelio-content/data","getSiteId");case 20:return o=u.sent,u.next=23,Object(d.select)("nelio-content/data","getAuthenticationToken");case 23:return a=u.sent,c=r?"POST":"PUT",i=r?"https://api.neliocontent.com/v1/site/".concat(o,"/task"):"https://api.neliocontent.com/v1/site/".concat(o,"/task/").concat(n.id),u.t0=d.apiFetch,u.t1=i,u.t2=c,u.t3={Authorization:"Bearer ".concat(a)},u.t4=z,u.t5=z({},n),u.t6={},u.next=35,Object(Y.getBaseDatetime)(t,n.dateType);case 35:return u.t7=u.sent,u.t8={baseDatetime:u.t7},u.t9=(0,u.t4)(u.t5,u.t6,u.t8),u.t10={url:u.t1,method:u.t2,credentials:"omit",mode:"cors",headers:u.t3,data:u.t9},u.next=41,(0,u.t0)(u.t10);case 41:return s=u.sent,u.next=44,Object(d.dispatch)("nelio-content/data","receiveTasks",s);case 44:return u.prev=44,u.next=47,Object(d.apiFetch)({path:"/nelio-content/v1/notifications/task",method:"POST",data:z({isNewTask:!0},s)});case 47:u.next=51;break;case 49:u.prev=49,u.t11=u.catch(44);case 51:u.next=55;break;case 53:u.prev=53,u.t12=u.catch(8);case 55:return u.next=57,Object(d.dispatch)(e,"markAsSaving",!1);case 57:return u.next=59,Object(d.dispatch)(e,"close");case 59:case"end":return u.stop()}}),F,null,[[8,53],[44,49]])}function W(e){return{type:"SET_VALIDATION_ERROR",error:e}}function X(e){return{type:"MARK_AS_SAVING",isSaving:e}}Object(s.registerStore)("nelio-content/task-editor",{reducer:h,controls:d.controls,actions:o,selectors:r});n(147);var Z=n(20),$=n.n(Z),ee=n(21),te=n.n(ee),ne=n(22),re=n.n(ne),oe=n(23),ae=n.n(oe),ce=n(15),ie=n.n(ce);function se(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=ie()(e);if(t){var o=ie()(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return ae()(this,n)}}var ue=function(e){re()(n,e);var t=se(n);function n(){var e;$()(this,n);for(var r=arguments.length,o=new Array(r),a=0;a<r;a++)o[a]=arguments[a];return(e=t.call.apply(t,[this].concat(o))).validate(),e}return te()(n,[{key:"componentDidUpdate",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{};return e.assigneeId!==this.props.assigneeId||e.dateType!==this.props.dateType||e.dateValue!==this.props.dateValue||e.task!==this.props.task?this.validate():void 0}},{key:"validate",value:function(){var e=this.props,t=e.assigneeId,n=e.dateValue,r=e.task,o=e.clearErrors,a=e.setError;return Object(p.trim)(r)?t?n?o():a(Object(u._x)("Please specify a date","user","nelio-content")):a(Object(u._x)("Please assign the task to someone","user","nelio-content")):a(Object(u._x)("Please enter a task","user","nelio-content"))}},{key:"render",value:function(){return null}}]),n}(a.Component),le=Object(s.withSelect)((function(e){var t=e("nelio-content/task-editor"),n=t.getAssigneeId,r=t.getDateType,o=t.getDateValue,a=t.getTask;return{assigneeId:n(),dateType:r(),dateValue:o(),task:a()}})),de=Object(s.withDispatch)((function(e){var t=e("nelio-content/task-editor").setValidationError;return{setError:t,clearErrors:function(){return t()}}})),fe=Object(i.compose)(le,de)(ue),be=Object(s.withSelect)((function(e){return{task:(0,e("nelio-content/task-editor").getTask)()}})),pe=Object(s.withDispatch)((function(e){return{setTask:e("nelio-content/task-editor").setTask}})),Oe=Object(i.compose)(be,pe)((function(e){var t=e.disabled,n=e.task,r=e.setTask;return Object(a.createElement)("div",{className:"nelio-content-task-editor__actual-task"},Object(a.createElement)(c.TextareaControl,{disabled:t,value:n,onChange:function(e){return r(e)},placeholder:Object(u._x)("Describe the task…","user","nelio-content")}))})),je=Object(s.withSelect)((function(e){return{assigneeId:(0,e("nelio-content/task-editor").getAssigneeId)()}})),ve=Object(s.withDispatch)((function(e){return{setAssigneeId:e("nelio-content/task-editor").setAssigneeId}})),ye=Object(s.withSelect)((function(e){return{isMultiAuthor:(0,e("nelio-content/data").isMultiAuthor)()}})),me=Object(i.ifCondition)((function(e){return e.isMultiAuthor})),he=Object(i.compose)(je,ve,ye,me)((function(e){var t=e.disabled,n=e.assigneeId,r=e.setAssigneeId;return Object(a.createElement)("div",{className:"nelio-content-task-editor__assignee"},Object(a.createElement)(l.AuthorSearcher,{disabled:t,value:n,onChange:function(e){return r(e)},placeholder:Object(u._x)("Assign task to…","command","nelio-content")}))})),ge=n(16),ke=n.n(ge),_e=n(17),Ee=n.n(_e),xe=(n(148),[{value:"0",label:Object(u._x)("Same day as publication","text","nelio-content")},{value:"-1",label:Object(u._x)("The day before publication","text","nelio-content")},{value:"-7",label:Object(u._x)("A week before publication","text","nelio-content")},{value:"negative-days",label:Object(u._x)("__ days before publication","text","nelio-content")},{value:"positive-days",label:Object(u._x)("__ days after publication","text","nelio-content")},{value:"exact",label:Object(u._x)("Choose a custom date…","user","nelio-content")}]),Te=function(e){var t=e.dateValue,n=e.disabled,r=e.setDateType,o=e.setDateValue;return Object(a.createElement)(c.SelectControl,{className:"nelio-content-task-editor-date-due__default-select",disabled:n,options:xe,value:t,onChange:function(e){["positive-days","negative-days","exact"].includes(e)?(r(e),o("")):o(e)}})},Se=n(14),De=Object(s.withDispatch)((function(e){var t=e("nelio-content/task-editor"),n=t.setDateType,r=t.setDateValue;return{onClick:function(){n("predefined-offset"),r("0")}}}))((function(e){var t=e.disabled,n=e.onClick;return Object(a.createElement)("div",{className:"nelio-content-task-editor-date-due__back-button"},Object(a.createElement)(c.Button,{isLink:!0,disabled:t,onClick:n},Object(u._x)("Back","command","nelio-content")))})),we=Object(s.withDispatch)((function(e){return{setDateDue:e("nelio-content/task-editor").setDateDue}}))((function(e){var t=e.disabled,n=e.dateValue,r=e.hasBackButton,o=e.setDateValue,c=e.setDateDue;return Object(a.createElement)("div",{className:"nelio-content-task-editor-date-due__exact-date"},Object(a.createElement)(l.DateInput,{className:"nelio-content-task-editor-date-due__exact-date-input",disabled:t,value:n||"",onChange:function(e){o(e),c(Object(Se.date)("c",e))}}),!!r&&Object(a.createElement)(De,{disabled:t}))})),Pe=function(e){var t=e.disabled,n=e.dateValue,r=e.setDateValue;return Object(a.createElement)("div",{className:"nelio-content-task-editor-date-due__days-offset"},Object(a.createElement)(l.NumberControl,{className:"nelio-content-task-editor-date-due__days-input",disabled:t,placeholder:Object(u._x)("Days before publication…","text","nelio-content"),min:2,value:n,onChange:r}),Object(a.createElement)(De,{disabled:t}))},Ce=function(e){var t=e.disabled,n=e.dateValue,r=e.setDateValue;return Object(a.createElement)("div",{className:"nelio-content-task-editor-date-due__days-offset"},Object(a.createElement)(l.NumberControl,{className:"nelio-content-task-editor-date-due__days-input",disabled:t,placeholder:Object(u._x)("Days after publication…","text","nelio-content"),min:1,value:n,onChange:r}),Object(a.createElement)(De,{disabled:t}))},Ne=Object(s.withSelect)((function(e){var t=e("nelio-content/task-editor"),n=t.getDateType,r=t.getDateValue,o=(0,t.getPost)();return{dateType:n(),dateValue:r(),isRelatedToUnpublishedPost:o&&"publish"!==o.status}})),Ae=Object(s.withDispatch)((function(e){var t=e("nelio-content/task-editor");return{setDateType:t.setDateType,setDateValue:t.setDateValue}})),Ve=Object(i.compose)(Ne,Ae)((function(e){var t=e.dateType,n=e.isRelatedToUnpublishedPost,r=Ee()(e,["dateType","isRelatedToUnpublishedPost"]),o="nelio-content-task-editor-date-due nelio-content-task-editor__date-due";return"negative-days"===t?Object(a.createElement)("div",{className:o},Object(a.createElement)(Pe,r)):"positive-days"===t?Object(a.createElement)("div",{className:o},Object(a.createElement)(Ce,r)):"exact"===t?Object(a.createElement)("div",{className:o},Object(a.createElement)(we,ke()({hasBackButton:n},r))):Object(a.createElement)("div",{className:o},Object(a.createElement)(Te,r))})),Ie=(n(149),function(e){var t=e.color,n=e.disabled,r=e.label,o=e.selected,c=e.onChange;return Object(a.createElement)("li",{className:"nelio-content-task-colors__option nelio-content-task-colors__option--is-".concat(t)},Object(a.createElement)("input",{disabled:n,type:"checkbox",checked:!!o,onChange:function(e){return c(!!e.target.checked)},title:r})," ",Object(a.createElement)("span",{className:"screen-reader-text"},r))}),Re=[{value:"red",label:Object(u._x)("Red","text","nelio-content")},{value:"orange",label:Object(u._x)("Orange","text","nelio-content")},{value:"yellow",label:Object(u._x)("Yellow","text","nelio-content")},{value:"green",label:Object(u._x)("Green","text","nelio-content")},{value:"cyan",label:Object(u._x)("Cyan","text","nelio-content")},{value:"blue",label:Object(u._x)("Blue","text","nelio-content")},{value:"purple",label:Object(u._x)("Purple","text","nelio-content")}],Be=Object(s.withSelect)((function(e){return{color:(0,e("nelio-content/task-editor").getColor)()}})),Me=Object(s.withDispatch)((function(e){return{setColor:e("nelio-content/task-editor").setColor}})),Le=Object(i.compose)(Be,Me)((function(e){var t=e.className,n=void 0===t?"":t,r=e.color,o=e.disabled,c=e.setColor;return Object(a.createElement)("div",{className:"nelio-content-task-colors ".concat(n)},Object(a.createElement)("p",{className:"screen-reader-text"},Object(u._x)("Select a color:","user","nelio-content")),Object(a.createElement)("ul",{className:"nelio-content-task-colors__list"},Re.map((function(e){var t=e.value,n=e.label;return Object(a.createElement)(Ie,{key:t,disabled:o,color:t,label:n,selected:r===t,onChange:function(e){return e&&c(t)}})}))))})),Ue=Object(s.withSelect)((function(e){var t=e("nelio-content/task-editor"),n=t.getValidationError,r=t.isVisible,o=t.isSaving;return{error:n(),isVisible:r(),isSaving:o()}})),Ge=Object(s.withDispatch)((function(e){var t=e("nelio-content/task-editor");return{close:t.close,saveAndClose:t.saveAndClose}})),Ke=Object(i.ifCondition)((function(e){return e.isVisible}));t.default=Object(i.compose)(Ue,Ge,Ke)((function(e){var t=e.className,n=void 0===t?"":t,r=e.close,o=e.error,i=e.isSaving,s=e.saveAndClose;return Object(a.createElement)(c.Modal,{className:"nelio-content-task-editor ".concat(n),title:Object(u._x)("Create Task","text","nelio-content"),isDismissable:!1,isDismissible:!1,shouldCloseOnEsc:!i,shouldCloseOnClickOutside:!1,onRequestClose:r},Object(a.createElement)(fe,null),Object(a.createElement)(Oe,{disabled:i}),Object(a.createElement)("div",{className:"nelio-content-task-editor__options"},Object(a.createElement)(he,{disabled:i}),Object(a.createElement)(Ve,{disabled:i})),Object(a.createElement)("div",{className:"nelio-content-task-editor__actions"},Object(a.createElement)(Le,{className:"nelio-content-task-editor__colors",disabled:i}),Object(a.createElement)(c.Button,{isSecondary:!0,disabled:i,onClick:r},Object(u._x)("Cancel","command","nelio-content")),Object(a.createElement)(l.SaveButton,{isPrimary:!0,error:o,isSaving:i,onClick:s})))}))},17:function(e,t,n){var r=n(38);e.exports=function(e,t){if(null==e)return{};var n,o,a=r(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(o=0;o<c.length;o++)n=c[o],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(a[n]=e[n])}return a}},2:function(e,t){!function(){e.exports=this.wp.data}()},20:function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},21:function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}e.exports=function(e,t,r){return t&&n(e.prototype,t),r&&n(e,r),e}},22:function(e,t,n){var r=n(39);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&r(e,t)}},23:function(e,t,n){var r=n(31),o=n(26);e.exports=function(e,t){return!t||"object"!==r(t)&&"function"!=typeof t?o(e):t}},26:function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},3:function(e,t){!function(){e.exports=this.lodash}()},31:function(e,t){function n(t){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?e.exports=n=function(e){return typeof e}:e.exports=n=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(t)}e.exports=n},38:function(e,t){e.exports=function(e,t){if(null==e)return{};var n,r,o={},a=Object.keys(e);for(r=0;r<a.length;r++)n=a[r],t.indexOf(n)>=0||(o[n]=e[n]);return o}},39:function(e,t){function n(t,r){return e.exports=n=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},n(t,r)}e.exports=n},4:function(e,t){!function(){e.exports=this.wp.dataControls}()},42:function(e,t){!function(){e.exports=this.NelioContent.data}()},5:function(e,t){!function(){e.exports=this.wp.components}()},6:function(e,t){!function(){e.exports=this.wp.compose}()},7:function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},8:function(e,t){!function(){e.exports=this.NelioContent.utils}()},9:function(e,t){!function(){e.exports=this.regeneratorRuntime}()}});