<template>
        <div class="container">
            <h1>Chat Histories</h1>
            <table class="table table-striped">
                <thead  class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Roon</th>
                        <th scope="col">Man</th>
                        <th scope="col">Girl</th>
                        <th scope="col">Start</th>
                        <th scope="col">Stop</th>
                        <th scope="col">Lenght</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="h in chatHistories" :id="'row' + h.id" >
                        <td>{{ h.id }}</td>
                        <td>{{ h.roomId }}</td>
                        <td>{{ h.man.name }} ({{h.girl.id}})</td>
                        <td>{{ h.girl.name }} ({{h.man.id}})</td>
                        <td>{{ moment(h.start * 1000).format("DD.MM.YY hh:mm:ss")}}</td>
                        <td>{{ moment(h.stop * 1000).format("DD.MM.YY hh:mm:ss") }}</td>
                        <td>{{h.lenght}}</td>
                        <td>{{ h.price }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
</template>

<script>
    export default {
        props:['chat_histories'],
        data(){return{           
            chatHistories:[],
            moment:moment,
        }},
        mounted() {
            this.chatHistories = this.chat_histories;
            console.log(this.chatHistories);

        },
    }
</script>
