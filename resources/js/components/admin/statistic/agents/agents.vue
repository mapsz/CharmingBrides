<template>
  <div class="container-fluid">
    <h1>Statistic</h1>
    <div class="row">      
      <div class="col-2">
        <statistic-categories></statistic-categories>
      </div>
      <div class="col-10 statistics-agents">
        <h1>Agents</h1>
        <!-- Period -->
        <div class="row">
          <div class="input-group input-group-sm mb-3">
            <span class="pr-2">Period:</span>  
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">From</span>
            </div>
            <datepicker  
              v-model="from"
              :monday-first="true"
            ></datepicker>
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
            </div>
            <datepicker  
              v-model="to"
              :monday-first="true"
            ></datepicker>
            <button @click="getAgents()" class="btn btn-sm btn-primary ml-2">Search</button>         
          </div>
        </div>       
        <!-- List -->
        <div class="row">
          <table class="table">
            <thead class="thead-light">
              <tr>
                <th>Agent</th>
                <th>Chats</th>
                <th>Amount</th>
                <th>Letters</th>
                <th>Amount</th>
                <th>Additional</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="agent in agents" :key="agent.id">
                <td>
                  <span>
                    {{agent.name}}
                  </span>                  
                </td>
                <td>                  
                  <button v-if="agent.chats != undefined && agent.chats.count > 0" @click="chatsShow=agent" class="btn btn-link">
                    {{agent.chats.count}}
                  </button>
                  <span v-else>0</span></td>
                <td>$ {{agent.chats != undefined ? agent.chats.amount.toFixed(2) : 0}}</td>
                <td>
                  <button v-if="agent.letter != undefined && agent.letter.count > 0" @click="messageShow=agent" class="btn btn-link">
                    {{agent.letter.count}}
                  </button>
                  <span v-else>0</span>
                </td>
                <td>$ {{agent.letter != undefined ? agent.letter.amount.toFixed(2) : 0}}</td>
                <td></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>      
    </div>
    
    <!-- messages modal -->
    <div id="messages-show-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>   
          <div class="modal-body">
            <h2>{{messageShow.name}} Letters</h2>  
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th>Date</th>
                  <th>letter id</th>
                  <th>Man</th>
                  <th>Girl</th>
                  <th>lenght</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for='(letter) in agentMessages' :key='letter.id'>
                  <td>{{moment.unix(letter.date).format('lll')}}</td>
                  <td>{{letter.id}}</td>
                  <td><a :href='"/man/"+letter.man'>{{letter.man}}</a></td>
                  <td><a :href='"/girl/"+letter.girl'>{{letter.girl}}</a> </td>
                  <td>{{letter.lenght}}</td>
                  <td>{{letter.price}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> 
    
    <!-- chats modal -->
    <div id="chats-show-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>   
          <div class="modal-body">
            <h2>{{messageShow.name}} Chats</h2>  
            <table class="table">
              <thead class="thead-light">
                <tr>
                  <th>room id</th>
                  <th>Start</th>
                  <th>Stop</th>
                  <th>length</th>
                  <th>Girl</th>
                  <th>Man</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for='(chat,i) in agentChats' :key='i'>
                  <td>{{chat.id}}</td>
                  <td>{{moment.unix(chat.start).format('MMM Do YYYY, h:mm:ss a')}}</td>
                  <td>{{moment.unix(chat.stop).format('MMM Do YYYY, h:mm:ss a')}}</td>
                  <td>{{chat.length}} (sec.)</td>
                  <td><a :href='"/girl/"+chat.girl'>{{chat.girl}}</a> </td>
                  <td><a :href='"/man/"+chat.man'>{{chat.man}}</a></td>
                  <td>{{chat.price}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> 

  </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
export default {  
  mixins: [ mMoreAxios, mNotifications, mLoading ],
  components: {
    Datepicker
  },  
  data(){return{
    moment:moment,
    messageShow:false,
    chatsShow:false,
    agentsFetched:[],
    from:moment().subtract(1, 'months').format(),    
    to:moment().format(),       
  }},
  watch: {
    messageShow: function(){
      if(this.messageShow) $('#messages-show-modal').modal('show');
    },
    chatsShow: function(){
      if(this.chatsShow) $('#chats-show-modal').modal('show');
    },
  },
  computed:{
    agentMessages: function(){
      if(!this.messageShow) return false;
      let messages = [];

      //Get letters
      $.each(this.messageShow.girl, (kg, girl ) => {
        $.each(girl.user.letter, (kl, letter ) => {
          messages.push({
            'id':letter.id,
            'date':moment(letter.created_at).unix(),
            'man':letter.to_user_id,
            'girl':girl.user.id,
            'lenght':letter.body.length,
            'price':letter.letter_pay.price
          })
        });
      }); 
      
      return messages;

    },
    agentChats:function(){
      if(!this.chatsShow) return false;
      let chats = [];

      //Get letters
      $.each(this.chatsShow.girl, (kg, girl ) => {
        $.each(girl.user.room, (rl, room ) => {
          $.each( room.chats, ( ck, chat ) => {  
            chats.push({
              'id':room.id,
              'start':moment(chat.created_at).unix(),
              'stop':moment(chat.stop_at).unix(),
              'length':moment(chat.stop_at).unix() - moment(chat.created_at).unix(),
              'girl':girl.user.id,
              'man':room.user[0].id,
              'price':chat.price,
            })
          });  
        });
      }); 
      
      return chats;      
    },
    agents: function(){

      let agents =  JSON.parse(JSON.stringify(this.agentsFetched));

      let letterCount,letterAmount,chatCount,chatAmount;
      $.each(agents , ( ka, agent ) => {
        //Reshresh
        letterCount = 0;letterAmount = 0;agents[ka].letter = {amount:0,count:0};
        chatCount = 0;chatAmount = 0;agents[ka].chats = {amount:0,count:0};

        $.each( agent.girl, ( k, v ) => {
          //Letters
          $.each(v.user.letter, ( lk,l ) => {
            agents[ka].letter.amount += parseFloat(l.letter_pay.price);            
            agents[ka].letter.count += 1;
          });

          //Chats
          $.each( v.user.room, ( rk, rooms ) => {
            $.each( rooms.chats, ( ck, chat ) => {
              agents[ka].chats.amount += parseFloat(chat.price);
              agents[ka].chats.count += 1;
            });            
          });

        }); 
        
      });

      return agents;
    }
  },
  async mounted() {
    await this.getAgents();
  },
  methods:{
    async getAgents(){
      let l = this.loading('.statistics-agents');
      let r = await this.ax('get','/admin/statistic/agents/get',{
        from:moment(this.from).unix(),
        to:moment(this.to).unix()
      });
      if(!r){
        this.hideLoading(l);
        return false;
      }

      this.agentsFetched = r;
      
      // this.countStats();

      this.hideLoading(l);
    },
    // countStats(){

    // },

  }

}
</script>

<style>

</style>