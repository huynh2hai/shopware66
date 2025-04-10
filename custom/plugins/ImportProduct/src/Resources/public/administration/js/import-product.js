(function(){"use strict";var t={};t.p="bundles/importproduct/",window?.__sw__?.assetPath&&(t.p=window.__sw__.assetPath+"/bundles/importproduct/"),function(){let{Component:t,Mixin:e}=Shopware;t.register("import-product-index",{template:'  <sw-page class="import-product-index">\n\n    <template #smart-bar-header>\n      <h2>{{ \'Import Product from CSV\' }}</h2>\n    </template>\n\n    <template #smart-bar-actions>\n      <sw-button\n        :disabled="!file || isLoading"\n        @click="validateFile"\n        variant="primary"\n      >\n        {{ \'Validate\' }}\n      </sw-button>\n\n      <sw-button\n        :disabled="!validationResult?.valid || isLoading"\n        @click="startImport"\n        variant="primary"\n      >\n        {{ \'Import\' }}\n      </sw-button>\n    </template>\n\n\n\n    <template #content>\n      <sw-card-view>\n        <sw-card class="sw-product-csv-import__card">\n          <sw-file-input\n            v-model="file"\n            :allowedMimeTypes="[\'text/csv\']"\n            :label="$t(\'sw-product-csv-import.fileInput.label\')"\n{#            accept=".csv"#}\n            @update:value="onFileSelected"\n          />\n\n          <sw-alert\n            v-if="errors && !validationResult.valid"\n            variant="warning"\n            class="sw-product-csv-import__validation-alert"\n          >\n            <ul style="list-style: none">\n              <li v-for="(item, key) in errors">\n                Row {{ item.row }} - {{ item.errors }}\n              </li>\n            </ul>\n          </sw-alert>\n\n          <sw-alert\n            v-if="validationResult?.valid"\n            variant="success"\n            class="sw-product-csv-import__validation-success"\n          >\n            The file is valid\n          </sw-alert>\n\n          <sw-loader v-if="isLoading" />\n        </sw-card>\n      </sw-card-view>\n\n    </template>\n  </sw-page>\n',mixins:[e.getByName("notification")],data(){return{file:null,isLoading:!1,validationResult:null,errors:null,repository:null,bundles:null,token:null,id:null}},metaInfo(){return{title:this.$createTitle()}},methods:{onFileSelected(t){console.log("CLICKEC"),this.file=t},async validateFile(){if(!this.file){console.log("no FILE");return}this.isLoading=!0;let t=new FormData;t.append("file",this.file);try{let e=await this.getToken(),n=await fetch("/api/product-import/validate",{method:"POST",body:t,headers:{Authorization:`Bearer ${e}`}});this.validationResult=await n.json(),this.validationResult.valid?console.log("VALID"):(this.errors=this.validationResult.errors,console.log(this.errors),console.log("WARNING"))}catch(t){console.log("ERORRR"),console.log(t)}finally{this.isLoading=!1}},async startImport(){if(!this.file)return;this.isLoading=!0;let t=new FormData;t.append("file",this.file);try{let e=await this.getToken(),n=await fetch("/api/product-import/import",{method:"POST",body:t,headers:{Authorization:`Bearer ${e}`}}),i=await n.json();if(n.ok)this.createNotificationSuccess({title:"File upload success",message:"Your file is uploaded"}),this.id=i.id,this.$router.push({name:"import.product.detail",params:{id:this.id}});else throw Error(i.error)}catch(t){console.log("upload error"),console.log(t)}finally{this.isLoading=!1}},async getToken(){try{let t=await fetch("/api/oauth/token",{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({client_id:"SWIAMLLQAU9XVZRADDFNVE12YQ",client_secret:"aWhGVDUyNW1Gak5XVmQ0UDZzUGxONWNYWVlvaVBvSXh0aERGZTk",grant_type:"client_credentials"})});if(!t.ok)throw Error(`HTTP error! status: ${t.status}`);let e=await t.json();return this.token=e.access_token,e.access_token}catch(t){console.log("GENERATE TOKEN"),console.log(t)}}}});let{Component:n,Mixin:i}=Shopware,{Criteria:o}=Shopware.Data;n.register("import-product-detail",{template:'<sw-page class="import-product-detail">\n\n  <template #content>\n    <sw-entity-listing\n      v-if="bundles"\n      :items="bundles"\n      :repository="repository"\n      :showSelection="false"\n      :columns="columns"\n      :allowDelete="false"\n      :allowEdit="false"\n    >\n\n    </sw-entity-listing>\n  </template>\n</sw-page>\n\n{#  <template #smart-bar-action>#}\n{#    <sw-button :routerLink="{ name: \'import.product.index\' }">#}\n{#      {{ \'Cancel\' }}#}\n{#    </sw-button>#}\n\n{#    <sw-button-process#}\n{#      :isLoading="isLoading"#}\n{#      :processSuccess="processSuccess"#}\n{#      variant="primary"#}\n{#      @process-finish="saveFinish"#}\n{#      @click="onClickSave"#}\n{#    >#}\n{#    {{ \'SAVE\' }}#}\n{#    </sw-button-process>#}\n{#  </template>#}\n\n{#  <template #content>#}\n{#    <sw-card-view>#}\n{#      <sw-card v-if="bundle" :isLoading="isLoading">#}\n{#        <sw-field v-model="bundle.status"></sw-field>#}\n\n{#        <sw-text-field#}\n{#          v-model:value="bundle.status"#}\n{#          label="asdfasdf"#}\n{#        />#}\n\n{#      </sw-card>#}\n{#    </sw-card-view>#}\n{#  </template>#}\n{#</sw-page>#}\n',inject:["repositoryFactory"],mixins:[i.getByName("notification")],metaInfo(){return{title:this.$createTitle()}},data(){return{bundles:null,isLoading:!1,processSuccess:!1,repository:null}},computed:{columns(){return this.getColumns()}},created(){this.createdComponent()},methods:{createdComponent(){this.repository=this.repositoryFactory.create("product_import_log"),this.getBundle()},getBundle(){let t=new o;t.setIds([this.$route.params.id]),this.repository.search(t,Shopware.Context.api).then(t=>{this.bundles=t})},getColumns(){return[{property:"fileName",label:"fileName",inlineEdit:"string",allowResize:!0},{property:"status",label:"Status",inlineEdit:"string",allowResize:!0},{property:"totalRecords",label:"Total Record",inlineEdit:"string",allowResize:!0}]}}}),Shopware.Module.register("import-product",{type:"plugin",name:"Import",title:"Import Product",description:"Import Product via CSV file",color:"#ff3d58",icon:"preview-vimeo",routes:{index:{component:"import-product-index",path:"index"},detail:{component:"import-product-detail",path:"detail/:id",meta:{parentPath:"import.product.index"}}},navigation:[{label:"Import Product",color:"#ff3d58",path:"import.product.index",icon:"preview-vimeo",position:81,parent:"sw-extension"}]})}()})();