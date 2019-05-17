<template>
  <div class="container-fluid maxw">
    <div class="row px-lg-0">
      <div 
        v-for="girl in girls" 
        class="col-6 col-sm-3 my-2 p-1"
      >
        <div class="card h-100 shadow">
          <a :href="'/girl/'+girl.id">
            <img class="card-img-top " :src="assets+'/media/gallery/'+girl.id+'_0.jpg'" alt="Juliya">
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
