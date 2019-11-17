<template>
    <div class="container my-3">

      <div class="row my-3">
        <div class="col-6">
          <h4>Personal Details</h4>
          <div class="row my-3">
            <!-- Name -->
            <span class="col-4 font-weight-bold">First Name:</span>
            <span class="col-8">{{man.name}}</span>
            <!-- Last Name: -->
            <div class="col-12 p-0" v-if="man.surname != undefined">
              <span class="col-4 font-weight-bold">Last Name:</span>
              <span class="col-8">{{man.surname}}</span>  
            </div>          
            <!-- Date of Birth: -->
            <span class="col-4 font-weight-bold">Date of Birth:</span>
            <span class="col-8">{{man.birth}}</span>            
            <!-- E-Mail Address: -->            
            <div class="col-12 p-0" v-if="man.user_id != undefined">
              <span class="col-4 font-weight-bold">E-Mail Address:</span>
              <span class="col-8">{{man.user_id}}</span>
            </div>
          </div>
        </div>
        <div class="col-6">
          <h4>Photo</h4>
          <div v-for="photo in man.photo" class="col">            
            <img :src="'/'+photo" style="height:75px">
          </div>
        </div>
      </div>
      <div class="row my-3">
        <div class="col-6">
          <h4>About Me</h4>
          <div class="row">
            <!-- Country: -->
            <span class="col-4 font-weight-bold">Country:</span>
            <span class="col-8">{{man.country}}</span>    
            <!-- City: -->
            <span class="col-4 font-weight-bold">City:</span>
            <span class="col-8">{{man.city}}</span>  
            <!-- Height(cm): -->
            <span class="col-4 font-weight-bold">Height(cm):</span>
            <span class="col-8">{{man.height}}</span>  
            <!-- Weight: -->
            <span class="col-4 font-weight-bold">Weight(kg):</span>
            <span class="col-8">{{man.weight}}</span>  
            <!-- Smoking: -->
            <span class="col-4 font-weight-bold">Smoking:</span>
            <span class="col-8">{{man.smoking}}</span>
            <!-- Alcohol: -->
            <span class="col-4 font-weight-bold">Alcohol:</span>
            <span class="col-8">{{man.alcohol}}</span>  
            <!-- Education: -->
            <span class="col-4 font-weight-bold">Education:</span>
            <span class="col-8">{{man.education}}</span>  
            <!-- Profession: -->
            <span class="col-4 font-weight-bold">Profession:</span>
            <span class="col-8">{{man.profession}}</span>  
            <!-- Marital Status: -->
            <span class="col-4 font-weight-bold">Marital Status:</span>
            <span class="col-8">{{man.maritial}}</span>  
            <!-- Children: -->
            <span class="col-4 font-weight-bold">Children:</span>
            <span class="col-8">{{man.children}}</span>  
            <!-- More About Myself: -->
            <span class="col-12 font-weight-bold">More About Myself:</span>
            <span class="col-12">{{man.info}}</span>                    
          </div>
        </div>
        <div class="col-6">
          <h4>About My Match</h4>
          <div class="row">
            <!-- Age: -->
            <span class="col-4 font-weight-bold">Age:</span>
            <span class="col-8">From {{prefferFrom}} To {{prefferTo}}</span>  
            <!-- Hair color: -->
            <span class="col-4 font-weight-bold">Hair color:</span>
            <span class="col-8">{{man.girl_hair}}</span> 
            <!-- Height (cm): -->
            <span class="col-4 font-weight-bold">Height (cm):</span>
            <span class="col-8">From {{heightFrom}} To {{heightTo}}</span>  
            <!-- Weight: -->
            <span class="col-4 font-weight-bold">Weight(kg):</span>
            <span class="col-8">From {{weightFrom}} To {{weightTo}}</span>  
            <!-- Smoking: -->
            <span class="col-4 font-weight-bold">Smoking:</span>
            <span class="col-8">{{man.girl_smoking}}</span>  
            <!-- Alcohol: -->
            <span class="col-4 font-weight-bold">Alcohol:</span>
            <span class="col-8">{{man.girl_alcohol}}</span>              
            <!-- Education: -->
            <span class="col-4 font-weight-bold">Education:</span>
            <span class="col-8">{{man.girl_education}}</span>  
            <!-- Profession: -->
            <span class="col-4 font-weight-bold">Profession:</span>
            <span class="col-8">{{man.girl_profession}}</span>  
            <!-- Marital Status: -->
            <span class="col-4 font-weight-bold">Marital Status:</span>
            <span class="col-8">{{man.girl_maritial}}</span>  
            <!-- Children: -->
            <span class="col-4 font-weight-bold">Children:</span>
            <span class="col-8">{{man.girl_children}}</span>  
            <!-- More about my match: -->
            <span class="col-12 font-weight-bold">More About My Match:</span>
            <span class="col-12">{{man.girl_info}}</span>  
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:['p-data'],
        data(){
          return {
            man:JSON.parse(this.pData),
          }
        },  
        computed:{
          prefferFrom:function(){
            if(!this.man.preffer_from){
              return 'any';
            }else{
              return this.man.preffer_from
            }
          },
          prefferTo:function(){
            if(!this.man.preffer_to){
              return 'any';
            }else{
              return this.man.preffer_to
            }
          },       
          heightFrom:function(){
            if(!this.man.girl_height_from){
              return 'any';
            }else{
              return this.man.girl_height_to
            }
          },  
          heightTo:function(){
            if(!this.man.girl_height_to){
              return 'any';
            }else{
              return this.man.girl_height_to
            }
          },  
          weightFrom:function(){
            if(!this.man.girl_weight_from){
              return 'any';
            }else{
              return this.man.girl_weight_from
            }
          },  
          weightTo:function(){
            if(!this.man.girl_weight_to){
              return 'any';
            }else{
              return this.man.girl_weight_to
            }
          },                                             
        },        
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
          async hihi(){
            
          }
        }
    }
</script>
