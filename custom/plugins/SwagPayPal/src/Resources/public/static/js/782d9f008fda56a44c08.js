(window["webpackJsonpPluginswag-pay-pal"]=window["webpackJsonpPluginswag-pay-pal"]||[]).push([[708],{9394:function(){},4708:function(e,a,n){"use strict";n.r(a),n.d(a,{default:function(){return t}}),n(8294);var t=Shopware.Component.wrapComponentConfig({template:'{# @deprecated tag:v10.0.0 - Will be removed without replacement #}\n{% block swag_paypal_content_card_channel_config_plus %}\n<sw-card\n    position-identifier="swag-paypal-card-channel-config-plus"\n    :title="$tc(\'swag-paypal.settingForm.plus.cardTitle\')"\n>\n\n    {# @deprecated tag:v10.0.0 - Will be removed without replacement #}\n    {% block swag_paypal_content_card_channel_config_plus_settings %}\n    <div v-if="actualConfigData" class="swag-paypal-settings-plus-fields">\n\n        {# @deprecated tag:v10.0.0 - Will be removed without replacement #}\n        {% block swag_paypal_content_card_channel_config_plus_settings_warning %}\n        <template v-if="isPayPalPLUSActive">\n            <sw-alert variant="warning">\n                <span v-html="$tc(\'swag-paypal.settingForm.plus.warning.active\')" />\n            </sw-alert>\n        </template>\n\n        <template v-if="isPayPalPLUSInActive">\n            <sw-alert variant="info">\n                <span v-html="$tc(\'swag-paypal.settingForm.plus.warning.inactive\')" />\n            </sw-alert>\n        </template>\n        {% endblock %}\n\n        {# @deprecated tag:v10.0.0 - Will be removed without replacement #}\n        {% block swag_paypal_content_card_channel_config_plus_settings_onboarding %}\n        <p\n            :class="{ \'swag-paypal-payal-plus-disabled\': isPayPalPLUSInActive}"\n            v-html="$tc(\'swag-paypal.settingForm.plus.introduction\')"\n        />\n        {% endblock %}\n\n        {# @deprecated tag:v10.0.0 - Will be removed without replacement #}\n        {% block swag_paypal_content_card_channel_config_plus_settings_checkout_enabled %}\n        <swag-paypal-inherit-wrapper\n            v-bind="{ actualConfigData, allConfigs, selectedSalesChannelId }"\n            path="SwagPayPal.settings.plusCheckoutEnabled"\n        >\n            <template #content="props">\n                <sw-switch-field\n                    name="SwagPayPal.settings.plusCheckoutEnabled"\n                    bordered\n                    :mapInheritance="props"\n                    :label="$tc(\'swag-paypal.settingForm.plus.plusCheckoutEnabled.label\')"\n                    :disabled="ifItWasNotActive() || props.isInherited || !acl.can(\'swag_paypal.editor\')"\n                    :value="props.currentValue"\n                    @update:value="props.updateCurrentValue"\n                />\n\n                {# @deprecated tag:v10.0.0 - Will be removed without replacement #}\n                {% block swag_paypal_content_card_channel_config_behaviour_settings_intent_hint %}\n                <swag-paypal-settings-hint :hintText="$tc(\'swag-paypal.settingForm.plus.hint\')" />\n                {% endblock %}\n            </template>\n        </swag-paypal-inherit-wrapper>\n        {% endblock %}\n    </div>\n    {% endblock %}\n</sw-card>\n{% endblock %}\n',inject:["acl"],props:{actualConfigData:{type:Object,required:!0,default:()=>({})},allConfigs:{type:Object,required:!0},selectedSalesChannelId:{type:String,required:!1,default:null}},computed:{isPayPalPLUSActive(){return this.actualConfigData["SwagPayPal.settings.plusCheckoutEnabled"]},isPayPalPLUSInActive(){return!this.isPayPalPLUSActive}},methods:{checkTextFieldInheritance(e){return"string"!=typeof e||e.length<=0},checkBoolFieldInheritance(e){return"boolean"!=typeof e},ifItWasNotActive(){return!this.actualConfigData["SwagPayPal.settings.plusCheckoutEnabled"]}}})},8294:function(e,a,n){var t=n(9394);t.__esModule&&(t=t.default),"string"==typeof t&&(t=[[e.id,t,""]]),t.locals&&(e.exports=t.locals),n(5346).Z("14617dfe",t,!0,{})}}]);