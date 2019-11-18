<template>
    <div class="container-fluid">
        <button @click="login(pRow.id)" type="button" class="btn btn-sm btn-info text-white">Login</button>
    </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr'],
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
          async login(userId){
            let r = await this.ax('post','/admin/man/login',{user_id:userId});

            console.log(r);

            location.reload();
          }
        }
    }
</script>
