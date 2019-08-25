<template>
    <div class="container-fluid">
        <!-- Example -->
        me example
    </div>
</template>

<script>
    export default {        
        mixins: [ ],
        props:[],
        data(){
          return {
            //
          }
        },
        computed:{
          requiredAll:function(){
            //
          },
        },
        watch: {
          pEditData: {
            deep:true,
            handler:function(){this.setValues();},
            //
          },
          question: function (newQuestion, oldQuestion) {
            this.answer = 'Waiting for you to stop typing...'
            this.debouncedGetAnswer()
          }      
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
