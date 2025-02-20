(window["webpackJsonpPluginswag-extension-store"]=window["webpackJsonpPluginswag-extension-store"]||[]).push([[393],{9999:function(){},4393:function(e,t,n){"use strict";n.r(t),n.d(t,{default:function(){return r}}),n(6702);var r=Shopware.Component.wrapComponentConfig({template:'<sw-button\n    v-if="show"\n    class="sw-in-app-purchase-checkout-button"\n    variant="primary"\n    size="small"\n    :disabled="disabled"\n    @click="onClick"\n>\n    {{ text }}\n\n    <sw-icon\n        v-if="state === \'purchase\'"\n        name="regular-long-arrow-right"\n    />\n</sw-button>\n',props:{state:{type:String,required:!0},tosAccepted:{type:Boolean,required:!0}},computed:{show(){return["error","success","purchase"].includes(this.state)},disabled(){return"purchase"===this.state&&!this.tosAccepted},text(){switch(this.state){case"error":return this.$tc("sw-in-app-purchase-checkout-button.tryAgainButton");case"success":return this.$tc("sw-in-app-purchase-checkout-button.closeButton");case"purchase":return this.$tc("sw-in-app-purchase-checkout-button.purchaseButton");default:return null}}},methods:{onClick(){this.$emit("click")}}})},6702:function(e,t,n){var r=n(9999);r.__esModule&&(r=r.default),"string"==typeof r&&(r=[[e.id,r,""]]),r.locals&&(e.exports=r.locals),n(5346).Z("b8f042b0",r,!0,{})},5346:function(e,t,n){"use strict";function r(e,t){for(var n=[],r={},s=0;s<t.length;s++){var a=t[s],o=a[0],i={id:e+":"+s,css:a[1],media:a[2],sourceMap:a[3]};r[o]?r[o].parts.push(i):n.push(r[o]={id:o,parts:[i]})}return n}n.d(t,{Z:function(){return f}});var s="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!s)throw Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var a={},o=s&&(document.head||document.getElementsByTagName("head")[0]),i=null,u=0,c=!1,d=function(){},p=null,l="data-vue-ssr-id",h="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function f(e,t,n,s){c=n,p=s||{};var o=r(e,t);return v(o),function(t){for(var n=[],s=0;s<o.length;s++){var i=a[o[s].id];i.refs--,n.push(i)}t?v(o=r(e,t)):o=[];for(var s=0;s<n.length;s++){var i=n[s];if(0===i.refs){for(var u=0;u<i.parts.length;u++)i.parts[u]();delete a[i.id]}}}}function v(e){for(var t=0;t<e.length;t++){var n=e[t],r=a[n.id];if(r){r.refs++;for(var s=0;s<r.parts.length;s++)r.parts[s](n.parts[s]);for(;s<n.parts.length;s++)r.parts.push(m(n.parts[s]));r.parts.length>n.parts.length&&(r.parts.length=n.parts.length)}else{for(var o=[],s=0;s<n.parts.length;s++)o.push(m(n.parts[s]));a[n.id]={id:n.id,refs:1,parts:o}}}}function g(){var e=document.createElement("style");return e.type="text/css",o.appendChild(e),e}function m(e){var t,n,r=document.querySelector("style["+l+'~="'+e.id+'"]');if(r){if(c)return d;r.parentNode.removeChild(r)}if(h){var s=u++;t=w.bind(null,r=i||(i=g()),s,!1),n=w.bind(null,r,s,!0)}else t=y.bind(null,r=g()),n=function(){r.parentNode.removeChild(r)};return t(e),function(r){r?(r.css!==e.css||r.media!==e.media||r.sourceMap!==e.sourceMap)&&t(e=r):n()}}var b=function(){var e=[];return function(t,n){return e[t]=n,e.filter(Boolean).join("\n")}}();function w(e,t,n,r){var s=n?"":r.css;if(e.styleSheet)e.styleSheet.cssText=b(t,s);else{var a=document.createTextNode(s),o=e.childNodes;o[t]&&e.removeChild(o[t]),o.length?e.insertBefore(a,o[t]):e.appendChild(a)}}function y(e,t){var n=t.css,r=t.media,s=t.sourceMap;if(r&&e.setAttribute("media",r),p.ssrId&&e.setAttribute(l,t.id),s&&(n+="\n/*# sourceURL="+s.sources[0]+" */\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(s))))+" */"),e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}}}]);