<template>
  <div class="container-fluid mailer-list-container">
    <h2>Mailer List</h2>
    <table class="table table-sm">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">User</th>
          <th scope="col">From</th>
          <th scope="col">To</th>
          <th scope="col">type</th>
          <th scope="col">Progress</th>
          <th scope="col">Created At</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="m in mailers">
          <td>{{m.id}}</td>
          <td>{{m.user}}</td>          
          <td>{{m.from}}</td>
          <td>{{m.to}}</td>
          <td>{{m.type}}</td>
          <td>
            <span v-if="m.progress == 0" class='text-success'>
              <b>Done</b>
            </span>
            <span v-else class='text-primary'>
              {{m.progress}}
            </span>
          </td>
          <td>{{m.created_at}}</td>
        </tr>
      </tbody>
    </table>      
  </div>
</template>

<script>
    export default {        
        mixins: [ mMoreAxios, mNotifications, mLoading ],
        props:['p-row','p-attr'],
        data(){
          return {
            mailers:[]
          }
        },           
        mounted() {
          this.getMailers();
        },
        methods: {
          async getMailers(){
            let l = this.loading('.mailer-list-container');

            let r = await this.ax('get','/admin/get/mailers');
            if(!r){
              this.hideLoading(l);
              return;
            }      

            this.mailers = r;
          }
        }
    }
</script>
