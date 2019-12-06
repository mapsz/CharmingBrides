<script>
    export default {
        mixins: [mDebug ],
        data(){
          return {
            l:{
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
          loading(element){
            return this.showLoading(element);
          },
          showLoading(element){
            //Element exists
            if($(element).length == 0){
              this.dlog(element + ' not found');
              return false;
            }
            
            // console.log($(element));

            this.l.width = $(element)[0].offsetWidth;
            this.l.height = $(element)[0].offsetHeight;

            //Set min height
            if(this.height < this.minHeight){
              // this.height = this.minHeight;
              // $(this.pLoading).height(this.minHeight+'px');
              //add upper loading @@@
            }

            let id = (this.l.id++) + 1;
            this.editHTML(id);

            $(element).prepend(this.loadingHTML);

            //Preload fix
            setTimeout(()=>{
              this.changeLoadSize(element, id);
            }, 250);

            return id;

          },
          hideLoading(id){
            //Element exists
            if(!$('.loading'+id).length){
              this.dlog(id + ' not found');
              return false;
            }
            this.dlog('.loading'+id);
            $('.loading'+id).remove();
          },
          editHTML(id){
            this.loadingHTML = ''+
            '<div class="loading'+id+'" '+     
              'style="position:absolute;'+
              'width: '+this.l.width+'px;'+
              'height: '+this.l.height+'px;'+
              'background-color: #000000a6;z-index: 100;">'+
            '</div>';
          },
          changeLoadSize(element,id){
            if(!$('.loading'+id).length || !$(element).length){
              return false;
            }
            $('.loading'+id).width($(element)[0].offsetWidth).height($(element)[0].offsetHeight);

          }

        },

    }
</script>