<template>
  <div class="container-fluid p-0">
    <ul>
      <li v-for="currentLetter in pLetters">
        <!-- Letter -->
        From:{{getName(currentLetter.user_id)}}  To:{{getName(currentLetter.to_user_id)}}<br>
        <b>{{currentLetter.subject}}</b><br>
        <!-- Payed -->
        <div>
          <!-- Pay button -->
          <button 
              v-if="currentLetter.payed == false && pUser.man === 1"
              @click="payLetter(currentLetter)"
          >
            Read Letter!
          </button>      
          <!-- Pay info -->
          <div v-if="pUser.man !== 1">
            <!-- payed -->
            <span v-if="currentLetter.payed === true">
              payed
            </span>
            <!-- not payed -->
            <span v-if="currentLetter.payed === false">
              not payed
            </span>
          </div>
        </div>
        <!-- message -->
        <div v-if="pUser.man !== 1 || currentLetter.payed"class="message">
            {{currentLetter.body}}
        </div>
        <hr>
      </li>
    </ul>
  </div>
</template>

<script>
    export default {        
        props:['p-letters','p-user','p-companion'],
        methods: {
          payLetter(letter){
            this.$emit('pay-letter',letter);
          },
          getName(id){
            if(id == this.pUser.id){
              return this.pUser.name;
            }else{
              return this.pCompanion.name;
            }
          }
        }
    }
</script>
