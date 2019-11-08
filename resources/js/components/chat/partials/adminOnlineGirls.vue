<template>
  <div class="container-fluid">
    <button class="btn btn-success float-right text-dark" type="button"  data-toggle="modal" data-target="#exampleModal">
      <b>Girls Online ({{onlineGirls.length}})</b>
    </button>


  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">All Girls Online</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div v-for="girl in onlineGirls" class="col">
              <center>
                <img 
                  :src="girl.photo[0]" 
                  alt="" 
                ><br>              
                ({{girl.id}}) {{girl.name}}<br>
                <!-- <span v-if="girl.agent">{{girl.agent}}</span> -->
                <!-- <span v-else style="color: gray;font-style: italic;">no agent</span> -->
              </center>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:['p-online-users'],
        data(){
          return {
            //
          }
        },
        computed:{
          onlineGirls:function(){
            let online = [];


            $.each(this.pOnlineUsers, (i, v) => {
              //Girls add
              if(v.man !== 1)
                online.push(v);                
            });


            return online;
          },
        }
    }
</script>

<style scoped>

  img{
    height: 100px !important;
  }

</style>