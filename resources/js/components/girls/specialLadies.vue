<template>
  <div class="container-fluid maxw">
    <div class="titleContainer media px-lg-4 my-3">        
      <div class="align-self-center d-none d-sm-block">
        <button class="btn ml-2 p-1"> 
          <i class="icon-angle-double-left"></i>
          Online Ladies
        </button>
      </div>
      <div  class="media-body">
        <h2 class="text-center">Special Ladies</h2>
      </div>
      <div class="align-self-center d-none d-sm-block">
        <button class="btn mr-2 p-1">
          New Ladies 
          <i class="icon-angle-double-right"></i>
        </button>
      </div>
    </div>  
    <div class="row px-lg-0">
      <div 
        v-for="girl in girls" 
        class="col-6 col-sm-3 my-2 p-1"
      >
        <div class="card h-100 shadow">
          <a :href="'/girl/'+girl.id">
            <img class="card-img-top " :src="girl.photo[0]" alt="Juliya">
            <div class="card-body media">
              <div class="align-self-end">
                <h5 class="card-title name">{{girl.name}}</h5>
                <span class="card-text">
                  <span class="age">Age: {{girl.age}}</span><br>
                  <span class="city">City: {{girl.location}}</span><br>
                  <span class="id">ID: {{girl.id}}</span>
                </span>
              </div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data(){
      return {
        assets:assets,
        girls:[],
      }
    }, 
    mounted() {
      this.getGirls();
    },
    methods: {
      async getGirls(){
        let r = await axios({
            method: 'get',
            url: '/girls/special/ladies',
          })
          .then((r) => {                    
            //Check requst
            if(!r.data){
                this.errors = ['Somethink gone wrong'];                        
                return false;
            } 

            //Check errors
            if(r.data.error == 1){
                console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                return false;
            }
            //Success
            return r.data.data;
          })
          .catch((error) => {return false;});

        console.log(r);

        this.girls = r;

        return true;
      },
    }
  }
</script>
