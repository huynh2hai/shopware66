(window["webpackJsonpPluginswag-extension-store"]=window["webpackJsonpPluginswag-extension-store"]||[]).push([[281],{8069:function(){},9281:function(e,n,t){"use strict";t.r(n),t.d(n,{default:function(){return r}}),t(4542);let{Utils:s,Filter:i}=Shopware;var r={template:'{% block sw_extension_listing_card %}\n    <div class="sw-extension-listing-card" @click="openDetailPage">\n        {% block sw_extension_listing_card_content %}\n            {% block sw_extension_listing_card_preview %}\n                <div class="sw-extension-listing-card__preview" :style="previewMedia">\n                    {% block sw_extension_listing_card_preview_type_label %}\n                        <sw-extension-type-label :type="extension.type"\n                                                 class="sw-extension-listing-card__extension-type-label">\n                        </sw-extension-type-label>\n                    {% endblock %}\n                </div>\n            {% endblock %}\n\n            {% block sw_extension_listing_card_info_grid %}\n                <div class="sw-extension-listing-card__info-grid">\n                    {% block sw_extension_listing_card_info_name %}\n                        <p class="sw-extension-listing-card__info-name">{{ extension.label }}</p>\n                    {% endblock %}\n\n                    {% block sw_extension_listing_card_info_description %}\n                        <p class="sw-extension-listing-card__info-description is--wrap-content">{{ extension.shortDescription }}</p>\n                    {% endblock %}\n\n                    {% block sw_extension_listing_card_info_rating %}\n                        <div class="sw-extension-listing-card__info-rating">\n                            <sw-extension-rating-stars class="sw-extension-listing-card__info-rating-stars"\n                                                       :rating="extension.rating"\n                                                       :size="12"/>\n\n                            <span class="sw-extension-listing-card__info-rating-count">\n                                {{ extension.numberOfRatings }}\n                            </span>\n                        </div>\n                    {% endblock %}\n\n                    {% block sw_extension_listing_card_info_price %}\n                        <div class="sw-extension-listing-card__info-price">\n                            <template v-if="isInstalled">\n                                {{ $tc(\'sw-extension-store.component.sw-extension-listing-card.labelInstalled\') }}\n                            </template>\n\n                            <template v-else-if="isLicensed">\n                                {{ $tc(\'sw-extension-store.component.sw-extension-listing-card.labelLicensed\') }}\n                            </template>\n\n                            <span v-else :class="discountClass">{{ calculatedPrice }}</span>\n                        </div>\n                    {% endblock %}\n\n                    {% block sw_extension_listing_card_label_display %}\n                        <sw-extension-store-label-display\n                            v-if="extension.labels.length > 0"\n                            :labels="extension.labels"\n                            class="sw-extension-listing-card__label-display">\n                        </sw-extension-store-label-display>\n                    {% endblock %}\n                </div>\n            {% endblock %}\n        {% endblock %}\n    </div>\n{% endblock %}\n',inject:["shopwareExtensionService"],props:{extension:{type:Object,required:!0}},computed:{previewMedia(){let e=s.get(this.extension,"images[0]",null);if(!e){let e=this.assetFilter("/administration/static/img/theme/default_theme_preview.jpg");return{"background-image":`url('${e}')`}}return{"background-image":`url('${e.remoteLink}')`,"background-size":"cover"}},recommendedVariant(){return this.shopwareExtensionService.orderVariantsByRecommendation(this.extension.variants)[0]},hasActiveDiscount(){return this.shopwareExtensionService.isVariantDiscounted(this.recommendedVariant)},discountClass(){return{"sw-extension-listing-card__info-price-discounted":this.hasActiveDiscount}},calculatedPrice(){return this.recommendedVariant?this.$tc("sw-extension-store.general.labelPrice",this.shopwareExtensionService.mapVariantToRecommendation(this.recommendedVariant),{price:s.format.currency(this.shopwareExtensionService.getPriceFromVariant(this.recommendedVariant),"EUR")}):null},isInstalled(){return!!Shopware.State.get("shopwareExtensions").myExtensions.data.some(e=>e.installedAt&&e.name===this.extension.name)},isLicensed(){let e=Shopware.State.get("shopwareExtensions").myExtensions.data.find(e=>e.name===this.extension.name);return void 0!==e&&!!e.storeLicense},assetFilter(){return i.getByName("asset")}},methods:{openDetailPage(){this.$router.push({name:"sw.extension.store.detail",params:{id:this.extension.id.toString()}})}}}},4542:function(e,n,t){var s=t(8069);s.__esModule&&(s=s.default),"string"==typeof s&&(s=[[e.id,s,""]]),s.locals&&(e.exports=s.locals),t(5346).Z("78e03152",s,!0,{})},5346:function(e,n,t){"use strict";function s(e,n){for(var t=[],s={},i=0;i<n.length;i++){var r=n[i],a=r[0],o={id:e+":"+i,css:r[1],media:r[2],sourceMap:r[3]};s[a]?s[a].parts.push(o):t.push(s[a]={id:a,parts:[o]})}return t}t.d(n,{Z:function(){return f}});var i="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!i)throw Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var r={},a=i&&(document.head||document.getElementsByTagName("head")[0]),o=null,l=0,c=!1,d=function(){},p=null,u="data-vue-ssr-id",g="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function f(e,n,t,i){c=t,p=i||{};var a=s(e,n);return _(a),function(n){for(var t=[],i=0;i<a.length;i++){var o=r[a[i].id];o.refs--,t.push(o)}n?_(a=s(e,n)):a=[];for(var i=0;i<t.length;i++){var o=t[i];if(0===o.refs){for(var l=0;l<o.parts.length;l++)o.parts[l]();delete r[o.id]}}}}function _(e){for(var n=0;n<e.length;n++){var t=e[n],s=r[t.id];if(s){s.refs++;for(var i=0;i<s.parts.length;i++)s.parts[i](t.parts[i]);for(;i<t.parts.length;i++)s.parts.push(m(t.parts[i]));s.parts.length>t.parts.length&&(s.parts.length=t.parts.length)}else{for(var a=[],i=0;i<t.parts.length;i++)a.push(m(t.parts[i]));r[t.id]={id:t.id,refs:1,parts:a}}}}function h(){var e=document.createElement("style");return e.type="text/css",a.appendChild(e),e}function m(e){var n,t,s=document.querySelector("style["+u+'~="'+e.id+'"]');if(s){if(c)return d;s.parentNode.removeChild(s)}if(g){var i=l++;n=x.bind(null,s=o||(o=h()),i,!1),t=x.bind(null,s,i,!0)}else n=w.bind(null,s=h()),t=function(){s.parentNode.removeChild(s)};return n(e),function(s){s?(s.css!==e.css||s.media!==e.media||s.sourceMap!==e.sourceMap)&&n(e=s):t()}}var v=function(){var e=[];return function(n,t){return e[n]=t,e.filter(Boolean).join("\n")}}();function x(e,n,t,s){var i=t?"":s.css;if(e.styleSheet)e.styleSheet.cssText=v(n,i);else{var r=document.createTextNode(i),a=e.childNodes;a[n]&&e.removeChild(a[n]),a.length?e.insertBefore(r,a[n]):e.appendChild(r)}}function w(e,n){var t=n.css,s=n.media,i=n.sourceMap;if(s&&e.setAttribute("media",s),p.ssrId&&e.setAttribute(u,n.id),i&&(t+="\n/*# sourceURL="+i.sources[0]+" */\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(i))))+" */"),e.styleSheet)e.styleSheet.cssText=t;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(t))}}}}]);