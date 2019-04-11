<template>
    <div class="container-fluid">
        <div class="container">
            <h1>Memberships</h1>
            <table class="table table-striped">
                <thead  class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Letter Price</th>
                        <th scope="col">Chat Price</th>
                        <th scope="col">Period</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="membership in memberships">
                        <td>{{ membership.id }}</td>
                        <td>{{ membership.name }}</td>
                        <td>{{ membership.price }}</td>
                        <td>{{ membership.letter_price }}</td>
                        <td>{{ membership.chat_price }}</td>
                        <td>{{ membership.period }}</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</template>

<script>
    export default {
        props:['memberships'],
        mounted() {
            console.log(this.memberships)
        }
    }
</script>
