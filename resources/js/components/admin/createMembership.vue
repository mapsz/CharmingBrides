<template>
    
    <div class="container">
        <h1>Create Membership</h1>

        <!-- Name -->
        <div class="input-group my-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Name</span>
          </div>
          <input type="text" v-model="data.name" class="form-control" placeholder="Silver Membership" aria-label="name" >
        </div>

        <!-- Price -->
        <div class="input-group my-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="price">Membership Price</span>
          </div>
          <input type="text" v-model="data.price" class="form-control" placeholder="78" aria-label="price" >
        </div>   

        <!-- Letter Price -->
        <div class="input-group my-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="letter_price">Letter Price</span>
          </div>
          <input type="text" v-model="data.letter_price" class="form-control" placeholder="4.50" aria-label="letter_price" >
        </div>       

        <!-- Chat Price -->
        <div class="input-group my-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="chat_price">Chat Price</span>
          </div>
          <input type="text" v-model='data.chat_price' class="form-control" placeholder="3.50" aria-label="chat_price" >
        </div>       

        <!-- Membership Period -->
        <div class="input-group my-4">
          <div class="input-group-prepend">
            <span class="input-group-text" id="period">Membership Period</span>
          </div>
          <input type="text" v-model='data.period' class="form-control" placeholder="180" aria-label="period" >
          <div class="input-group-prepend">
            <span class="input-group-text" id="period">Days</span>
          </div>
        </div> 

        <!-- Client Visibility -->
        <div class="input-group my-4">
            <div class="input-group-prepend mr-2">
                <span class="input-group-text" id="period">Client Visible</span>
            </div>

            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" v-model='data.client_visible' id="client_visible_yes" value="1" checked="checked">
              <label class="form-check-label" for="client_visible_yes">Yes</label>
            </div>

            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" v-model='data.client_visible' id="client_visible_no" value="0">
              <label class="form-check-label" for="client_visible_no">No</label>
            </div>            
        </div>
        <div v-show="errors" class="alert alert-danger">
            <ul>
                <li v-for="error in errors">{{ error }}</li>
            </ul>
        </div>

        <button @click="storeMembership()" class="btn btn-outline-success btn-lg btn-block my-3"><b>Add Membership</b></button>

    </div>



</template>

<script>
    export default {
        mounted() {
        },
        data(){
            return {
                data:{
                    name:"",
                    price:"",
                    letter_price:"",
                    chat_price:"",
                    period:"",
                    client_visible:"",
                },
                errors:false,
            }
        },
        methods: {
            storeMembership(){
                this.errors = false;
                axios.post('/admin/memberships', {                        
                        'name'              : this.data.name,
                        'price'             : this.data.price,
                        'letter_price'      : this.data.letter_price,
                        'chat_price'        : this.data.chat_price,
                        'period'            : this.data.period,
                        'client_visible'    : this.data.client_visible,
                    })
                    .then((r) => {

                        //Request error
                        if(!r.data) return false;

                        //Return with error
                        if(r.data.error == 1){
                            console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }
                        
                        //Success
                        window.location.replace("/admin/memberships/"+r.data.id);
                    })
                    .catch((error) => {
                        if(error.response.status == 422){                            
                            this.errors = error.response.data.errors;
                        }
                        return false;
                    });
            }
        }
    }
</script>
