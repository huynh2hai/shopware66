(window["webpackJsonpPluginswag-extension-store"]=window["webpackJsonpPluginswag-extension-store"]||[]).push([[626],{4661:function(){},5626:function(e,t,i){"use strict";i.r(t),i.d(t,{default:function(){return s}}),i(9883);var s={template:'{% block sw_extension_store_slider %}\n    <div class="sw-extension-store-slider sw-meteor-card"\n         :class="[\n             cardClasses,\n             `sw-extension-store-slider__slides-count-${usedSlideCount}`\n         ]">\n        {% block sw_extension_store_slider_slides %}\n            <div class="sw-extension-store-slider__slides" ref="movingImageWrapper">\n                {% block sw_extension_store_slider_loader %}\n                    <div v-if="!images" class="sw-extension-store-slider__loader">\n                        <sw-loader></sw-loader>\n                    </div>\n                {% endblock %}\n\n                <template v-else>\n                    {% block sw_extension_store_slider_slide_item %}\n                        <div v-for="img, key in images"\n                             class="sw-extension-store-slider__slide-item"\n                             :class="slideClasses(key)"\n                             :data-key="key"\n                             :style="getActiveStyle(key)"\n                             :key="key">\n                            <img :src="img">\n                        </div>\n                    {% endblock %}\n                </template>\n            </div>\n        {% endblock %}\n\n        {% block sw_extension_store_slider_navigation %}\n            <div class="sw-extension-store-slider__navigation" v-if="images.length > usedSlideCount">\n                {% block sw_extension_store_slider_navigation_inner %}\n                    <button class="sw-extension-store-slider__btn-back" @click="previous()" :disabled="isDisabledPrevious">\n                        {% block sw_extension_store_slider_navigation_inner_icon_left %}\n                            <sw-icon name="regular-chevron-left" size="20"></sw-icon>\n                        {% endblock %}\n                    </button>\n\n                    <button class="sw-extension-store-slider__btn-next" @click="next()" :disabled="isDisabledNext">\n                        {% block sw_extension_store_slider_navigation_inner_icon_right %}\n                            <sw-icon name="regular-chevron-right" size="20"></sw-icon>\n                        {% endblock %}\n                    </button>\n                {% endblock %}\n            </div>\n        {% endblock %}\n    </div>\n{% endblock %}\n',props:{images:{type:Array,required:!0},infinite:{type:Boolean,required:!1,default:!1},slideCount:{type:Number,required:!1,default:2},large:{type:Boolean,required:!1,default:!1}},data(){return{activeImgIndex:0,lastActiveImgIndex:null,isDirectionRight:null}},computed:{cardClasses(){return{"sw-card--large":this.large}},lastActive(){return this.activeImgIndex+this.usedSlideCount-1},isDisabledNext(){return!this.isInfinite&&this.lastActive===this.images.length-1},isDisabledPrevious(){return!this.isInfinite&&0===this.activeImgIndex},sliderHasOneImageMoreThanTheSlideCount(){return this.images.length===this.usedSlideCount+1},isInfinite(){return!this.sliderHasOneImageMoreThanTheSlideCount&&this.infinite},usedSlideCount(){return this.slideCount>3?3:this.slideCount<1?1:this.slideCount}},methods:{getActiveStyle(e){if(!this.isActive(e))return{};let t=100/this.usedSlideCount;return null===this.isDirectionRight?{left:`${e*t}%`}:this.moveActiveImage(e,t)},moveActiveImage(e,t){if(e===this.activeImgIndex)return{left:"0%"};if(!this.$refs.movingImageWrapper)return{};let i=parseInt(this.$refs.movingImageWrapper.querySelector(`[data-key="${e}"]`).style.left,10);return Number.isNaN(i)?{left:`${100-t}%`}:{left:this.isDirectionRight?`${i-t}%`:`${i+t}%`}},next(){this.isDirectionRight=!0,this.changeSlide(1)},previous(){this.isDirectionRight=!1,this.changeSlide(-1)},changeSlide(e){if(this.lastActiveImgIndex=this.activeImgIndex,this.isInfinite){if(0===this.activeImgIndex&&!this.isDirectionRight){this.activeImgIndex=this.images.length-1;return}if(this.activeImgIndex===this.images.length-1&&this.isDirectionRight){this.activeImgIndex=0;return}}this.activeImgIndex+=e},isActive(e){let t=!1;for(let i=0;i<this.usedSlideCount;i+=1){if(t)return!0;t=this.activeImgIndex+i===e}return t||this.lastActive>this.images.length-1&&e<=this.lastActive-this.images.length},isNext(e){let t=this.activeImgIndex+this.usedSlideCount;return t===e||t>this.images.length-1&&t-this.images.length===e},slideClasses(e){return this.sliderHasOneImageMoreThanTheSlideCount?{"is--previous":0===e&&!this.isActive(e),"is--next":this.images.length-1===e&&!this.isActive(e),"is--active":this.isActive(e)}:{"is--previous":this.activeImgIndex-1===e||0===this.activeImgIndex&&e===this.images.length-1,"is--active":this.isActive(e),"is--next":this.isNext(e)}}}}},9883:function(e,t,i){var s=i(4661);s.__esModule&&(s=s.default),"string"==typeof s&&(s=[[e.id,s,""]]),s.locals&&(e.exports=s.locals),i(5346).Z("4fce1c06",s,!0,{})},5346:function(e,t,i){"use strict";function s(e,t){for(var i=[],s={},n=0;n<t.length;n++){var r=t[n],a=r[0],o={id:e+":"+n,css:r[1],media:r[2],sourceMap:r[3]};s[a]?s[a].parts.push(o):i.push(s[a]={id:a,parts:[o]})}return i}i.d(t,{Z:function(){return v}});var n="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!n)throw Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var r={},a=n&&(document.head||document.getElementsByTagName("head")[0]),o=null,l=0,d=!1,c=function(){},u=null,h="data-vue-ssr-id",g="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function v(e,t,i,n){d=i,u=n||{};var a=s(e,t);return f(a),function(t){for(var i=[],n=0;n<a.length;n++){var o=r[a[n].id];o.refs--,i.push(o)}t?f(a=s(e,t)):a=[];for(var n=0;n<i.length;n++){var o=i[n];if(0===o.refs){for(var l=0;l<o.parts.length;l++)o.parts[l]();delete r[o.id]}}}}function f(e){for(var t=0;t<e.length;t++){var i=e[t],s=r[i.id];if(s){s.refs++;for(var n=0;n<s.parts.length;n++)s.parts[n](i.parts[n]);for(;n<i.parts.length;n++)s.parts.push(p(i.parts[n]));s.parts.length>i.parts.length&&(s.parts.length=i.parts.length)}else{for(var a=[],n=0;n<i.parts.length;n++)a.push(p(i.parts[n]));r[i.id]={id:i.id,refs:1,parts:a}}}}function m(){var e=document.createElement("style");return e.type="text/css",a.appendChild(e),e}function p(e){var t,i,s=document.querySelector("style["+h+'~="'+e.id+'"]');if(s){if(d)return c;s.parentNode.removeChild(s)}if(g){var n=l++;t=x.bind(null,s=o||(o=m()),n,!1),i=x.bind(null,s,n,!0)}else t=I.bind(null,s=m()),i=function(){s.parentNode.removeChild(s)};return t(e),function(s){s?(s.css!==e.css||s.media!==e.media||s.sourceMap!==e.sourceMap)&&t(e=s):i()}}var _=function(){var e=[];return function(t,i){return e[t]=i,e.filter(Boolean).join("\n")}}();function x(e,t,i,s){var n=i?"":s.css;if(e.styleSheet)e.styleSheet.cssText=_(t,n);else{var r=document.createTextNode(n),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(r,a[t]):e.appendChild(r)}}function I(e,t){var i=t.css,s=t.media,n=t.sourceMap;if(s&&e.setAttribute("media",s),u.ssrId&&e.setAttribute(h,t.id),n&&(i+="\n/*# sourceURL="+n.sources[0]+" */\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(n))))+" */"),e.styleSheet)e.styleSheet.cssText=i;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(i))}}}}]);