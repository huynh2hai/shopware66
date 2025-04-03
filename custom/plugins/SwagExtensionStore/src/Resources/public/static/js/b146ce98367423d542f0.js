(window["webpackJsonpPluginswag-extension-store"]=window["webpackJsonpPluginswag-extension-store"]||[]).push([[157],{6729:function(){},8301:function(e,n,r){"use strict";r.r(n),r.d(n,{default:function(){return t}}),r(1850);var t={template:'{% block sw_extension_store_error_card %}\n    <sw-meteor-card\n        class="sw-extension-store-error-card"\n        :class="componentClasses"\n    >\n        {% block sw_extension_store_error_card_label %}\n            <sw-label\n                class="sw-extension-store-error-card__label"\n                appearance="pill"\n                :variant="variant"\n            >\n                <slot name="icon">\n                    {% block sw_extension_store_error_card_icon %}\n                        <sw-icon\n                            :name="iconName"\n                            size="30px"\n                        />\n                    {% endblock %}\n                </slot>\n            </sw-label>\n        {% endblock %}\n\n        {% block sw_extension_store_error_card_title %}\n            <h2 v-if="title"\n                v-html="title"\n                class="sw-extension-store-error-card__title"\n            >\n            </h2>\n        {% endblock %}\n\n        {% block sw_extension_store_error_card_message %}\n            <div class="sw-extension-store-error-card__message">\n                <slot></slot>\n            </div>\n        {% endblock %}\n\n        {% block sw_extension_store_error_card_actions %}\n            <div class="sw-extension-store-error-card__actions">\n                <slot name="actions"></slot>\n            </div>\n        {% endblock %}\n    </sw-meteor-card>\n{% endblock %}\n',props:{title:{type:String,required:!1},variant:{type:String,required:!1,default:"neutral",validator(e){return["info","danger","success","warning","neutral"].includes(e)}}},computed:{iconName(){switch(this.variant){case"danger":return"regular-times-circle";case"info":default:return"regular-info-circle";case"warning":return"regular-exclamation-circle";case"success":return"regular-check-circle"}},componentClasses(){return[`sw-extension-store-error-card--variant-${this.variant}`]}}}},1850:function(e,n,r){var t=r(6729);t.__esModule&&(t=t.default),"string"==typeof t&&(t=[[e.id,t,""]]),t.locals&&(e.exports=t.locals),r(5346).Z("7a11ff0a",t,!0,{})},5346:function(e,n,r){"use strict";function t(e,n){for(var r=[],t={},s=0;s<n.length;s++){var o=n[s],a=o[0],i={id:e+":"+s,css:o[1],media:o[2],sourceMap:o[3]};t[a]?t[a].parts.push(i):r.push(t[a]={id:a,parts:[i]})}return r}r.d(n,{Z:function(){return v}});var s="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!s)throw Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var o={},a=s&&(document.head||document.getElementsByTagName("head")[0]),i=null,c=0,l=!1,d=function(){},u=null,p="data-vue-ssr-id",f="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function v(e,n,r,s){l=r,u=s||{};var a=t(e,n);return h(a),function(n){for(var r=[],s=0;s<a.length;s++){var i=o[a[s].id];i.refs--,r.push(i)}n?h(a=t(e,n)):a=[];for(var s=0;s<r.length;s++){var i=r[s];if(0===i.refs){for(var c=0;c<i.parts.length;c++)i.parts[c]();delete o[i.id]}}}}function h(e){for(var n=0;n<e.length;n++){var r=e[n],t=o[r.id];if(t){t.refs++;for(var s=0;s<t.parts.length;s++)t.parts[s](r.parts[s]);for(;s<r.parts.length;s++)t.parts.push(m(r.parts[s]));t.parts.length>r.parts.length&&(t.parts.length=r.parts.length)}else{for(var a=[],s=0;s<r.parts.length;s++)a.push(m(r.parts[s]));o[r.id]={id:r.id,refs:1,parts:a}}}}function g(){var e=document.createElement("style");return e.type="text/css",a.appendChild(e),e}function m(e){var n,r,t=document.querySelector("style["+p+'~="'+e.id+'"]');if(t){if(l)return d;t.parentNode.removeChild(t)}if(f){var s=c++;n=w.bind(null,t=i||(i=g()),s,!1),r=w.bind(null,t,s,!0)}else n=b.bind(null,t=g()),r=function(){t.parentNode.removeChild(t)};return n(e),function(t){t?(t.css!==e.css||t.media!==e.media||t.sourceMap!==e.sourceMap)&&n(e=t):r()}}var _=function(){var e=[];return function(n,r){return e[n]=r,e.filter(Boolean).join("\n")}}();function w(e,n,r,t){var s=r?"":t.css;if(e.styleSheet)e.styleSheet.cssText=_(n,s);else{var o=document.createTextNode(s),a=e.childNodes;a[n]&&e.removeChild(a[n]),a.length?e.insertBefore(o,a[n]):e.appendChild(o)}}function b(e,n){var r=n.css,t=n.media,s=n.sourceMap;if(t&&e.setAttribute("media",t),u.ssrId&&e.setAttribute(p,n.id),s&&(r+="\n/*# sourceURL="+s.sources[0]+" */\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),e.styleSheet)e.styleSheet.cssText=r;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(r))}}}}]);