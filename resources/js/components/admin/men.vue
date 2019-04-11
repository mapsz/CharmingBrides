<template>
    <div class="container-fluid">
        <div class="container">
            <h1>Men</h1>
            <table class="table table-striped">
                <thead  class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Balance</th>
                        <th scope="col">Membership</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Add</th>
                        <th scope="col">History</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="man in dataMen" :id="'row' + man.id" >
                        <td>{{ man.id }}</td>
                        <td>{{ man.name }}</td>
                        <td class="balance">{{ man.balance }}</td>
                        <td>{{ man.membership.name }}</td>
                        <td>{{ man.membership.endDate }}</td> <!--  @@@ date format -->
                        <td>
                            <button class="btn btn-info" @click="openMembeshipsModal(man.id)">Add Membership</button>
                        </td>
                        <td>
                            <button class="btn btn-info" @click="openHisoryModal(man.id)">History</button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <!-- Modal -->
        <div class="modal fade" id="membershipsModal" tabindex="-1" role="dialog" aria-labelledby="membershipsModal" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="membershipsModal">Add membership</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4>{{membershipUser.name}}</h4>
                    <table class="table table-striped">
                        <thead  class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Letter Price</th>
                                <th scope="col">Chat Price</th>
                                <th scope="col">Period</th>
                                <th scope="col"class="addMembership">Add</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="membership in memberships">
                                <td >{{ membership.id }}</td>
                                <td>{{ membership.name }}</td>
                                <td>{{ membership.price }}</td>
                                <td>{{ membership.letter_price }}</td>
                                <td>{{ membership.chat_price }}</td>
                                <td>{{ membership.period }}</td>
                                <td class="addMembership">
                                    <button class="btn btn-info" @click="addMembership(membershipUser.id,membership.id)">Add</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>        
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- History Modal -->
        <div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="membershipsModal" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="membershipsModal">Membership History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4>{{membershipUser.name}}</h4>
                    <table class="table table-striped">
                        <thead  class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Date</th>
                                <th scope="col"class="addMembership">Add</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="membership in memberships">
                                <td >{{ historyMembership.id }}</td>
                                <td>{{ historyMembership.name }}</td>
                                <td>{{ historyMembership.price }}</td>
                                <td>{{ historyMembership.created_at }}</td>
                                <td class="addMembership">
                                    <button class="btn btn-info" @click="addMembership(membershipUser.id,membership.id)">Add</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>        
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['men'],
        data(){
            return {
                dataMen: [],
                membershipUser:false,
                memberships:[],
                historyMembership:false,
            }
        },        
        mounted() {
            
            let $this = this;
            //Add men to data
            let emptyMembersip = {'name':"",'endDate':""}
            $.each(this.men, function(index, val) {
                val.membership = emptyMembersip;
                $this.dataMen.push(val);                
            });

            //Get memberships
            $.each(this.dataMen, function(index, val) {
                $this.getCurrentMembership(val.id);
            }); 
        },
        computed: {
        },
        methods: {
            async openMembeshipsModal(manId){
                this.memberships = [];
                this.membershipUser = this.men.filter(x => x.id === manId)[0];     

                $('#membershipsModal').modal('show');

                this.memberships = await this.getMemberships();
            },
            async getMemberships(){
                var r = await axios.get('/admin/memberships/get')
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        return r.data.data;

                    })
                    .catch((r) => {console.log(r);return false;}); 

                return r;                  
            },
            addMembership(userId, membershipId){
                //Hide buttons
                $('.addMembership').hide();

                //Send request
                axios.post('/admin/user/membership', {                        
                        'userId'           : userId,
                        'membershipId'     : membershipId,
                    })
                    .then((r) => {

                        //Request error
                        if(!r.data) return false;

                        //Return with error
                        if(r.data.error == 1){
                            console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }
                        
                        //Success                        
                        //Change user balance                        
                        $('#row'+userId+' .balance').html(r.data.balance);
                        //Set membership
                        this.getCurrentMembership(userId);
                        //Color row
                        $('#row'+userId).css('background-color','#8bc34aa6');
                        //Hide modal
                        $('#membershipsModal').modal('hide');
                        //Show add buttons
                        $('.addMembership').show();
                    })
                    .catch((error) => {
                        if(error.response.status == 422){                            
                            this.errors = error.response.data.errors;
                        }
                        return false;
                    });


                // 
            },
            async getCurrentMembership(userId){
                var r = await axios.get('/memberships/current', {
                                        params: {
                                          user_id: userId
                                        }
                  })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }
                        return r.data.membership;

                    })
                    .catch((r) => {console.log(r);return false;});   

                //Set membership
                let i = this.dataMen.findIndex(x => x.id === userId);  
                if(r){
                    this.dataMen[i].membership = r;
                }else{
                    this.dataMen[i].membership.name = 'none';
                }

                return true;
            },

            async openHisoryModal(userId){
                this.historyMembership = false;

                $('#historyModal').modal('show');

                this.getHistoryMembership(userId);
            },
            async getHistoryMembership(userId){
                var r = await axios.get('/memberships/history', {
                                        params: {
                                          user_id: userId
                                        }
                  })
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            console.log('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }
                        return r.data.membership;

                    })
                    .catch((r) => {console.log(r);return false;});   

                //Set membership
                if(r)
                    this.historyMembership = r;

                return true;
            },
        }
    }
</script>
