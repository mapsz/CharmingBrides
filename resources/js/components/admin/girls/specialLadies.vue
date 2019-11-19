<template>
    <div class="row">   
        <div class="col-12 border m-1">
            <!-- List -->
            <h3>Speacial Ladies</h3>
            <ul>    
                <li v-for="girl in girls">
                    {{girl.id}} - {{girl.name}}
                    <button @click="deleteGirl(girl.id)" class="btn-special-lady btn btn-danger btn-sm float-right">X</button>
                    <hr />
                </li>
            </ul>
            <!-- errors -->
            <div v-show="error" class="alert alert-danger">
                {{error}}
            </div>
            <!-- Add -->
            <div>
                <input type="text" v-model="putGirl" style="width:60px">
                <button @click="putGirls()" class="btn-special-lady btn btn-primary btn-sm">Add Special Lady</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                girls:[],
                putGirl:"",
                error:false,
            }
        }, 
        mounted() {
            console.log('special ladies mounted.');
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
            async deleteGirl(id){

                $('.btn-special-lady').hide();

               let r = await axios({
                    method: 'delete',
                    url: '/admin/girls/special/ladies',
                    data: {'id':id},
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

                await this.getGirls();

                $('.btn-special-lady').show();  

                return true;       
            },
            async putGirls(){

              $('.btn-special-lady').hide();
                
              let r = await axios({
                    method: 'put',
                    url: '/admin/girls/special/ladies',
                    data: {'id':this.putGirl},
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
                        this.error = r.data.text;
                        return false;
                    }
                    //Success
                    return r.data.data;
                  })
                  .catch((error) => {this.error = error;return false;});

                await this.getGirls();
                this.putGirl = "";

                $('.btn-special-lady').show(); 
            },
        }
    }
</script>
