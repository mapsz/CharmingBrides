<template>
    <div v-if="loading == true">
        <div id="loading"></div>
    </div>
</template>

<script>
    export default {
        props:['loading'],
        mounted() {
        }

    }
</script>

<style scoped>

    #loading{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000000a6;
        z-index: 100;
    }

</style>