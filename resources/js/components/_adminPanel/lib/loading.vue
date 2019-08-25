<script>
    export default {
        mixins: [mDebug ],
        data(){
          return {
            loading:{
              id:0,
              top:0,
              left:0,
              width:0,
              height:0,
              minHeight:100,
              loadingHTML:"",  
            }    
          }
        },
        mounted() {
        },        
        methods:{
          showLoading(element){
            //Element exists
            if(!$(element).length){
              this.dlog(element + ' not found');
              return false;
            }
            
            this.loading.width = $(element)[0].offsetWidth;
            this.loading.height = $(element)[0].offsetHeight;

            //Set min height
            if(this.height < this.minHeight){
              // this.height = this.minHeight;
              // $(this.pLoading).height(this.minHeight+'px');
              //add upper loading @@@
            }

            let id = (this.loading.id++) + 1;
            this.editHTML(id);

            $(element).prepend(this.loadingHTML);

            return id;

          },
          hideLoading(id){
            //Element exists
            if(!$('#loading'+id).length){
              this.dlog(id + ' not found');
              return false;
            }
            this.dlog('#loading'+id);
            $('#loading'+id).remove();
          },
          editHTML(id){
            this.loadingHTML = ''+
            '<div id="loading'+id+'" '+     
              'style="position:absolute;'+
              'width: '+this.loading.width+'px;'+
              'height: '+this.loading.height+'px;'+
              'background-color: #000000a6;z-index: 100;">'+
            '</div>';
          }
        },

    }
</script>