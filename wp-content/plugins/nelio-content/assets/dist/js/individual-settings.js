!function(e){var t={};function n(o){if(t[o])return t[o].exports;var i=t[o]={i:o,l:!1,exports:{}};return e[o].call(i.exports,i,i.exports,n),i.l=!0,i.exports}n.m=e,n.c=t,n.d=function(e,t,o){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:o})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(o,i,function(t){return e[t]}.bind(null,i));return o},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=147)}({0:function(e,t){!function(){e.exports=this.wp.element}()},1:function(e,t){!function(){e.exports=this.wp.i18n}()},11:function(e,t){!function(){e.exports=this.NelioContent.components}()},117:function(e,t,n){},118:function(e,t){function n(t,o){return e.exports=n=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},n(t,o)}e.exports=n},119:function(e,t){e.exports=function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}},120:function(e,t,n){},147:function(e,t,n){"use strict";n.r(t);var o={};n.r(o),n.d(o,"getValue",(function(){return d})),n.d(o,"getAttributes",(function(){return b})),n.d(o,"getAttribute",(function(){return p}));var i={};n.r(i),n.d(i,"setValue",(function(){return v})),n.d(i,"setAttributes",(function(){return m}));var r=n(0),c=n(2),a=n(8),s=n.n(a),l=n(3);n(28);function u(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function f(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?u(Object(n),!0).forEach((function(t){s()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):u(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function d(e,t){return e.values[t]}function b(e,t){return e.attributes[t]||{}}function p(e,t,n){return b(e,t)[n]}function v(e,t){return{type:"SET_FIELD_VALUE",field:e,value:t}}function m(e,t){return{type:"SET_FIELD_ATTRIBUTES",field:e,attributes:t}}function O(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(e);t&&(o=o.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,o)}return n}function g(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?O(Object(n),!0).forEach((function(t){s()(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):O(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}Object(c.registerStore)("nelio-content/individual-settings",{reducer:function(e,t){switch(e||(e={values:{},attributes:{}}),t.type){case"SET_FIELD_VALUE":return f(f({},e),{},{values:f(f({},e.values),{},s()({},t.field,t.value))});case"SET_FIELD_ATTRIBUTES":return f(f({},e),{},{attributes:f(f({},e.attributes),{},s()({},t.field,f(f({},e.attributes[t.field]),t.attributes)))})}return e},controls:l.controls,actions:g({},i),selectors:g({},o)});var h=n(22),j=n.n(h),y=n(6),w=(n(117),n(23)),x=n.n(w),P=n(16),_=n(5),E=n(9);function S(e,t){t({isRefreshing:!0,isStartingRefresh:!0,isRefreshingOver:!1,refreshPostCount:0,refreshPostIndex:0}),function e(t,n,o){if(n<1)return C(0,o);x()({path:Object(P.addQueryArgs)("/nelio-content/v1/analytics/post",{period:t,page:n})}).then((function(){var i=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},r=i.ids,c=i.more,a=i.total,s=i.ppp;if(Object(E.isEmpty)(r))return C(a,o);var l=s*(n-1);o({isStartingRefresh:!1,refreshPostCount:a,refreshPostIndex:Math.min(a,l)});var u=Object(_.map)(r,(function(e){return x()({path:"/nelio-content/v1/analytics/post/".concat(e,"/update"),method:"PUT"}).then((function(){++l,o({refreshPostIndex:Math.min(a,l)})}))}));Promise.all(u).then((function(){return c?e(t,n+1,o):C(a,o)}))}))}(e,1,t)}function C(e,t){t({isRefreshing:!1,isStartingRefresh:!1,isRefreshingOver:!0,refreshPostCount:e,refreshPostIndex:e})}function V(e){if(!e||!e.items)return[];var t=e.items.filter((function(e){return!!e.webProperties})),n=Object(_.flatten)(Object(_.map)(t,"webProperties"));return Object(_.map)(n,(function(e){return{label:"".concat(e.name," (").concat(e.id,")"),options:Object(_.map)(e.profiles,(function(e){return{value:"".concat(e.id),label:e.name}}))}}))}var R=n(4),A=n(1),D=n(11),N=[{value:"month",label:Object(A._x)("Posts from last month","text","nelio-content")},{value:"year",label:Object(A._x)("Posts from last year","text","nelio-content")},{value:"all",label:Object(A._x)("All posts","text","nelio-content")}],L=Object(c.withSelect)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings").getAttribute;return{isDialogOpen:!!o(n,"isRefreshDialogOpen"),isRefreshing:!!o(n,"isRefreshing"),isStartingRefresh:!!o(n,"isStartingRefresh"),isRefreshingOver:!!o(n,"isRefreshingOver"),period:o(n,"refreshPeriod"),postCount:o(n,"refreshPostCount"),postIndex:o(n,"refreshPostIndex")}})),T=Object(c.withDispatch)((function(e,t){var n=t.name,o=t.period,i=e("nelio-content/individual-settings").setAttributes;return{openDialog:function(){return i(n,{isRefreshDialogOpen:!0,isRefreshing:!1,refreshPeriod:"month"})},closeDialog:function(){return i(n,{isRefreshDialogOpen:void 0,isRefreshing:void 0,isStartingRefresh:void 0,isRefreshingOver:void 0,refreshPeriod:void 0,refreshPostCount:void 0,refreshPostIndex:void 0})},setPeriod:function(e){return i(n,{refreshPeriod:e})},refresh:function(){return S(o,(function(e){return i(n,e)}))}}})),k=Object(y.compose)(L,T)((function(e){var t=e.className,n=void 0===t?"":t,o=e.closeDialog,i=e.disabled,c=e.isDialogOpen,a=e.isRefreshing,s=e.isRefreshingOver,l=e.isStartingRefresh,u=e.openDialog,f=e.period,d=e.postCount,b=e.postIndex,p=e.refresh,v=e.setPeriod;return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(R.Button,{isSecondary:!0,isSmall:!0,disabled:i,onClick:u},Object(A._x)("Refresh Analytics","command","nelio-content")),c&&Object(r.createElement)(R.Modal,{isDismissable:!1,isDismissible:!1,title:Object(A._x)("Refresh Analytics","text","nelio-content")},Object(r.createElement)("div",{className:"".concat(n,"__dialog-content")},!a&&!s&&Object(r.createElement)(R.SelectControl,{disabled:a,value:f,onChange:v,options:N}),l&&Object(r.createElement)(D.ProgressBar,{label:Object(A._x)("Retrieving posts…","text","nelio-content")}),a&&!l&&Object(r.createElement)(D.ProgressBar,{current:b,total:d,label:"".concat(b," / ").concat(d)}),s&&Object(r.createElement)(D.ProgressBar,{current:b,total:d,label:Object(A.sprintf)(Object(A._nx)("%d post updated!","%d posts updated!",d,"text","nelio-content"),d)})),Object(r.createElement)("div",{className:"".concat(n,"__dialog-actions")},Object(r.createElement)(R.Button,{isSecondary:!0,isSmall:!0,disabled:a,onClick:o},Object(A._x)("Close","command","nelio-content")),!s&&Object(r.createElement)(R.Button,{isPrimary:!0,isSmall:!0,isBusy:a,disabled:a,onClick:p},a?Object(A._x)("Refreshing…","text","nelio-content"):Object(A._x)("Refresh Analytics","command","nelio-content")))))})),I=function(e){var t=e.className,n=void 0===t?"":t,o=e.disabled,i=void 0!==o&&o,c=e.selectProfile,a=void 0===c?function(){}:c,s=e.isAwaitingProfileSelection,l=void 0!==s&&s;return Object(r.createElement)("div",{className:n},Object(r.createElement)("div",{className:"".concat(n,"__connection-actions")},Object(r.createElement)(R.Button,{isSecondary:!0,isSmall:!0,disabled:i,onClick:a},Object(A._x)("Connect Google Analytics","command","nelio-content")),Object(r.createElement)(k,{className:n,disabled:i})),l&&Object(r.createElement)("div",{className:"".concat(n,"__overlay")},Object(r.createElement)("div",{className:"".concat(n,"__overlay-content")},Object(A._x)("Please select a Google account and copy its authentication code…","user","nelio-content"))))},B=function(e){var t=e.className,n=void 0===t?"":t,o=e.disabled,i=void 0!==o&&o,c=e.code,a=void 0===c?"":c,s=e.setCode,l=void 0===s?function(){}:s,u=e.validateCode,f=void 0===u?function(){}:u,d=e.cancel,b=void 0===d?function(){}:d,p=e.isValidating,v=void 0!==p&&p;return Object(r.createElement)("div",{className:n},Object(r.createElement)("div",{className:"".concat(n,"__code-validator")},Object(r.createElement)(R.TextControl,{className:"".concat(n,"__code-validator-input"),disabled:i||v,value:a,onChange:l,placeholder:Object(A._x)("Paste code here","user","nelio-content")}),Object(r.createElement)(R.Button,{className:"".concat(n,"__code-validator-button"),isPrimary:!0,isSmall:!0,isBusy:v,onClick:f,disabled:i||v||!a},v?Object(A._x)("Loading…","text","nelio-content"):Object(A._x)("Load Views","command","nelio-content")),Object(r.createElement)(R.Button,{className:"".concat(n,"__code-validator-button"),isSecondary:!0,isSmall:!0,onClick:b,disabled:i||v},Object(A._x)("Cancel","command","nelio-content"))))},M=Object(c.withSelect)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings"),i=o.getAttribute,r=(0,o.getValue)(n),c=i(n,"views"),a=Object(_.find)(Object(_.flatten)(Object(_.map)(c,"options")),{value:r});return{isLoading:i(n,"isLoadingViews"),selectedView:a,value:r,views:c}})),F=Object(c.withDispatch)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings"),i=o.setAttributes,r=o.setValue;return{setValue:function(e){return r(n,e)},reset:function(){return i(n,{areViewsLoaded:!1,isLoadingViews:!1,mode:"init",views:[]})}}})),U=Object(y.compose)(M,F)((function(e){var t=e.className,n=void 0===t?"":t,o=e.disabled,i=void 0!==o&&o,c=e.isLoading,a=e.name,s=e.reset,l=e.selectedView,u=e.setValue,f=e.value,d=e.views,b=void 0===d?[]:d;return Object(r.createElement)("div",{className:n},Object(r.createElement)("div",{className:"".concat(n,"__view-selector")},Object(r.createElement)("input",{type:"hidden",name:a,value:f||""}),Object(r.createElement)(D.StylizedSelectControl,{options:b,value:l,disabled:i||c,onChange:function(e){var t=e.value;return u(t)},placeholder:c?Object(A._x)("Loading…","text","nelio-content"):Object(A._x)("Select a view…","user","nelio-content")})),Object(r.createElement)("div",{className:"".concat(n,"__view-selector-actions")},Object(r.createElement)(R.Button,{isSecondary:!0,isSmall:!0,disabled:i,onClick:s},Object(A._x)("Change Google Account","command","nelio-content")),Object(r.createElement)(k,{className:n,disabled:i})))})),G=n(63),q=n.n(G),z=n(64),Q=n.n(z),H=n(65),J=n.n(H),K=n(66),W=n.n(K),X=n(57),Y=n.n(X);function Z(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Date.prototype.toString.call(Reflect.construct(Date,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,o=Y()(e);if(t){var i=Y()(this).constructor;n=Reflect.construct(o,arguments,i)}else n=o.apply(this,arguments);return W()(this,n)}}var $=function(e){J()(n,e);var t=Z(n);function n(){var e;return q()(this,n),(e=t.apply(this,arguments)).refreshViews(),e}return Q()(n,[{key:"refreshViews",value:function(){var e=this.props;!function(e,t){var n=e.isViewSelectorActive,o=e.isLoadingViews,i=e.areViewsLoaded;!n||o||i||(t({isLoadingViews:!0,areViewsLoaded:!1}),x()({path:"/nelio-content/v1/analytics/views",method:"GET"}).then((function(e){t({views:V(e),isLoadingViews:!1,areViewsLoaded:!0})})))}(e.attributes,e.setAttributes)}},{key:"componentDidUpdate",value:function(){this.refreshViews()}},{key:"render",value:function(){return null}}]),n}(r.Component),ee=Object(c.withSelect)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings"),i=o.getAttributes,r=(0,o.getValue)(n),c=i(n),a=c.disabled,s=c.mode;return{attributes:j()(c,["disabled","mode"]),disabled:a,mode:s,value:r}})),te=Object(c.withDispatch)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings"),i=o.setAttributes,r=o.setValue;return{setValue:function(e){return r(n,e)},setMode:function(e){return i(n,{mode:e})},setAttributes:function(e){return i(n,e)}}})),ne=Object(y.compose)(ee,te)((function(e){var t=e.attributes,n=t.authCode,o=t.isValidating,i=t.isLoadingViews,c=t.areViewsLoaded,a=e.disabled,s=void 0!==a&&a,l=e.mode,u=void 0===l?"init":l,f=e.name,d=e.setAttributes,b=e.setMode;return Object(r.createElement)(r.Fragment,null,Object(r.createElement)($,{attributes:{isViewSelectorActive:"view-selection"===u,isLoadingViews:i,areViewsLoaded:c},setAttributes:d}),("init"===u||"awaiting-profile-selection"===u)&&Object(r.createElement)(I,{className:"nelio-content-google-analytics-setting",name:f,disabled:s,isAwaitingProfileSelection:"awaiting-profile-selection"===u,selectProfile:function(){return function(e){var t=e.onOpen,n=void 0===t?function(){}:t,o=e.onClose,i=void 0===o?function(){}:o;n();var r=window.open("https://accounts.google.com/o/oauth2/v2/auth?client_id=1085875842646-v5k3smokc0j3hnjev0q2bq7r3kbgqlmm.apps.googleusercontent.com&response_type=code&redirect_uri=urn:ietf:wg:oauth:2.0:oob&scope=https://www.googleapis.com/auth/analytics.readonly","","width=640,height=480"),c=setInterval((function(){r.closed&&(clearInterval(c),i())}),500)}({onOpen:function(){return b("awaiting-profile-selection")},onClose:function(){return b("code-ready")}})}}),("code-ready"===u||"loading-views"===u)&&Object(r.createElement)(B,{className:"nelio-content-google-analytics-setting",disabled:s,code:n,setCode:function(e){return d({authCode:e})},validateCode:function(){return function(e,t){t({isValidating:!0}),x()({path:"/nelio-content/v1/analytics/refresh-access-token",method:"PUT",data:{code:e}}).then((function(){return t({mode:"view-selection",isValidating:!1})}))}(n,d)},cancel:function(){return b("init")},isValidating:o}),"view-selection"===u&&Object(r.createElement)(U,{className:"nelio-content-google-analytics-setting",disabled:s,name:f}))})),oe=(n(120),Object(c.withSelect)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings"),i=o.getValue,r=(0,o.getAttribute)(n,"postTypes")||[],c=i(n)||[],a=r.filter((function(e){var t=e.value;return c.includes(t)}));return{options:r,selection:a,value:c}}))),ie=Object(c.withDispatch)((function(e,t){var n=t.name,o=e("nelio-content/individual-settings").setValue;return{setValue:function(e){return o(n,e)}}})),re={GoogleAnalyticsSetting:ne,CalendarPostTypeSetting:Object(y.compose)(oe,ie)((function(e){var t=e.name,n=e.value,o=e.options,i=e.selection,c=e.setValue;return Object(r.createElement)("div",{className:"nelio-content-calendar-post-type-setting"},Object(r.createElement)("input",{type:"hidden",name:t,value:n}),Object(r.createElement)(D.StylizedSelectControl,{isMulti:!0,options:o,value:i,onChange:function(e){return c(Object(_.map)(e,"value"))}}))}))};window.NelioContent=window.NelioContent||{},window.NelioContent.initField=function(e,t){var n=t.component,o=re[n];if(o){var i=document.getElementById(e);if(i){var a=t.name,s=t.value,l=t.attributes,u=Object(c.dispatch)("nelio-content/individual-settings"),f=u.setValue,d=u.setAttributes;f(a,s),d(a,l),Object(r.render)(Object(r.createElement)(o,{name:a}),i)}}}},16:function(e,t){!function(){e.exports=this.wp.url}()},2:function(e,t){!function(){e.exports=this.wp.data}()},22:function(e,t,n){var o=n(37);e.exports=function(e,t){if(null==e)return{};var n,i,r=o(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(i=0;i<c.length;i++)n=c[i],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(r[n]=e[n])}return r}},23:function(e,t){!function(){e.exports=this.wp.apiFetch}()},28:function(e,t){!function(){e.exports=this.NelioContent.data}()},3:function(e,t){!function(){e.exports=this.wp.dataControls}()},37:function(e,t){e.exports=function(e,t){if(null==e)return{};var n,o,i={},r=Object.keys(e);for(o=0;o<r.length;o++)n=r[o],t.indexOf(n)>=0||(i[n]=e[n]);return i}},4:function(e,t){!function(){e.exports=this.wp.components}()},47:function(e,t){function n(t){return"function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?e.exports=n=function(e){return typeof e}:e.exports=n=function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},n(t)}e.exports=n},5:function(e,t){!function(){e.exports=this.lodash}()},57:function(e,t){function n(t){return e.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},n(t)}e.exports=n},6:function(e,t){!function(){e.exports=this.wp.compose}()},63:function(e,t){e.exports=function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}},64:function(e,t){function n(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}e.exports=function(e,t,o){return t&&n(e.prototype,t),o&&n(e,o),e}},65:function(e,t,n){var o=n(118);e.exports=function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&o(e,t)}},66:function(e,t,n){var o=n(47),i=n(119);e.exports=function(e,t){return!t||"object"!==o(t)&&"function"!=typeof t?i(e):t}},8:function(e,t){e.exports=function(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}},9:function(e,t){!function(){e.exports=this.NelioContent.utils}()}});