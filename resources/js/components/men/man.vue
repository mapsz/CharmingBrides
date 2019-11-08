<template>
    <div class="container-fluid m-3">
        <!-- Example -->
        <ul>          
          <li>id:{{user.id}}</li>
          <li>name:{{user.man.name}}</li>
        </ul>
        
        <!-- <a :href="'/letters?with='+{{user.id}}"> -->
        <a :href="'/letters?companion='+user.id">
          <button class="btn btn-primary">Send message</button>
        </a>
    </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:['p-man'],
        data(){
          return {
            user: JSON.parse(this.pMan),
          }
        },
        computed:{
          requiredAll:function(){
            //
          },
        },             
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
          async hihi(){
            //
          }
        }
    }
</script>
