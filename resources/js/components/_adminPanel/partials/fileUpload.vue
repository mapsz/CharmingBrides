<template>
  <div id="app">

    <file-pond    
      :label-idle='"Drop "+pName+" here or click to browse"'
      :className="'file-pond-upload-files-'+pName"
      ref="pond"
      v-show="loaded"
      name="file"
      :allow-multiple="multiFiles"
      :max-files="pMaxFileCount"
      :max-file-size="pMaxFileSize"
      :accepted-file-types="fileTypes"
      :server="server"
      v-bind:files="myFiles"
      @init="handleFilePondInit"
      @warning="handleFilePondWarning"
      @processfile="handleFilePondfFileIploaded"
    />

     <!-- video/* -->

  </div>
</template>

<script>
// Import Vue FilePond
import vueFilePond from 'vue-filepond';

// Import FilePond styles
import 'filepond/dist/filepond.min.css';

// Import FilePond plugins
// Please note that you need to install these plugins separately

// Import image preview plugin styles
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';

// Create component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview, FilePondPluginFileValidateSize );

export default {
    props:['p-files','p-name','p-route','p-max-file-size','p-max-file-count','p-file-type'],
    mixins: [mNotifications],
    data(){
      return {
        loaded:false,
        fileInputs:[],
        myFiles: [],
        server:{
          url: '/'+this.pRoute+'/file/upload',
          process: {
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }       
          }
        }
      }
    },
    watch: {
      files: {
        deep:true,
        handler:function(){this.$emit('filesUpdated',{name:this.pName,files:this.files});},
        //
      },
    },
    computed: {
      files: function () {
        let f = [];
        $.each(this.fileInputs, (k, v) => {
          f.push(v.defaultValue);
        });
        return f;
      },
      fileTypes: function(){
        let r = "";
        $.each(this.pFileType, function(index, val) {
           r+=val+',';
        });
        return r;
      },
      multiFiles: function(){
        if(this.pMaxFileCount > 1){
          return true;
        }else{
          return false;
        }
      }
    },   
    methods: {
        handleFilePondInit: function() {
            console.log('FilePond has initialized');
            this.loaded = true;

            // FilePond instance methods are available on `this.$refs.pond`
        },
        handleFilePondWarning(payload){
          if(payload.body == 'Max files'){
            this.showErrors(['Maximum number of files exceeded']);
          }
        },
        handleFilePondfFileIploaded(payload){
          //Get inputs
          this.fileInputs = $('.file-pond-upload-files-'+this.pName+' input[name=file]');

          // console.log(this.files);
        }
    },
    components: {
        FilePond
    }
};
</script>