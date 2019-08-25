<template>
<!--     <div v-if="loading == true">
        <div id="loading"></div>
    </div> -->
</template>

<script>
    export default {
        props:['p-loading'],
        data(){
          return {
            top:0,
            left:0,
            width:0,
            height:0,
            minHeight:100,
            loadingHTML:"",      
          }
        },
        mounted() {
          //
        },
        watch: {
          pLoading: function (val) {
            if(this.pLoading && $(this.pLoading).length > 0){
              this.showLoading();
            }else{
              this.hideLoading();
            }
          },
        },          
        methods:{
          showLoading(){
            this.width = $(this.pLoading)[0].offsetWidth;
            this.height = $(this.pLoading)[0].offsetHeight;

            //Set min height
            if(this.height < this.minHeight){
              // this.height = this.minHeight;
              // $(this.pLoading).height(this.minHeight+'px');
              //add upper loading @@@
            }

            this.editHTML();

            $(this.pLoading).prepend(this.loadingHTML);

          },
          hideLoading(){
            //hide
            $('#loading').remove();
          },
          editHTML(){

            this.loadingHTML = ''+
            '<div id="loading" '+
              'style="position:absolute;'+
              'width: '+this.width+'px;'+
              'height: '+this.height+'px;'+
              'background-color: #000000a6;z-index: 100;">'+
            '</div>';
          }
        },

    }
</script>

