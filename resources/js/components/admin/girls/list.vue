<template>
    <div>
      
        <h1>Girl</h1>
        <table class="table table-striped">
            <thead  class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Birth</th>
                    <th scope="col">Location</th>
                    <th scope="col">More</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="girl in girls">
                    <td>{{ girl.id }}</td>
                    <td>{{ girl.name }}</td>
                    <td>{{ girl.birth }}</td>
                    <td>{{ girl.location }}</td>
                    <td><button class="btn btn-info" @click="showMoreInfo(girl.id)">more</button></td>
                </tr>
            </tbody>
        </table>
    

        <!-- modal -->
        <div class="modal" id="moreInfoModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">#{{modalGirl.id}} {{modalGirl.name}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p v-for="(value, key) in modalGirl">
                    {{key}}: {{value}}
                </p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

    </div>
</template>

<script>
    export default {
        props:['girls'],
        data(){
            return {
                modalGirl:{},
            }
        },        
        mounted() {
            console.log('Component mounted.');
            console.log(this.men);
        },
        methods: {
            showMoreInfo(id){     
                this.modalGirl = this.girls.find((x => x.id == id));

                $('#moreInfoModal').modal('show');
            }
        }
    }
</script>
