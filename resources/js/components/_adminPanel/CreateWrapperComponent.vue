<template>
    <div>        
        <admin-panel-create-component 
          :prop_name="prop_name"  
          :prop_inputs="prop_inputs" 
          :prop_route="prop_route" 
          :prop_edit="prop_edit" 
          :prop_edit_data="prop_edit_data" 
          @putSuccess="putSuccess" 
        />
    </div>
</template>

<script>
    export default {
        props:['prop_inputs','prop_name','prop_edit','prop_edit_data','prop_route'],
        mounted() {
            // console.log('Component mounted.')
        },
        methods:{
            putSuccess(a){
                window.location.replace("/"+this.prop_route+"?work=add&id="+a.id);
            }
        }
    }
</script>
