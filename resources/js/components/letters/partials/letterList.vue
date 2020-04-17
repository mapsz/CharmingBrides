<template>
  <div class="container-fluid p-0">
    <ul>
      <li v-for="(currentLetter,k) in pLetters" :key="k">
        <!-- Letter -->
        From {{getName(currentLetter.user_id)}}<br>
        {{formateDate(currentLetter.created_at)}}<br>
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
            <span v-if="currentLetter.payed === true" style="color:limegreen">
              payed
            </span>
            <!-- not payed -->
            <span v-if="currentLetter.payed === false" style="color:tomato">
              not payed
            </span>
          </div>
        </div>
        
        <!-- message -->
        <div v-if="pUser.man !== 1 || currentLetter.payed" class="message">
            {{currentLetter.body}}
        </div>

        <!-- photo -->
        <div v-if="currentLetter.photos.length > 0">
          <div v-for="(photo,k) in currentLetter.photos" :key="k">
            <img :src="photo" alt="photo" style="width:90%;max-height: 400px; margin-bottom:20px">
          </div>          
        </div>        
        <hr>
      </li>
    </ul>
  </div>
</template>

<script>
    export default {        
        props:['p-letters','p-user-from','p-user','p-companion'],
        methods: {
          payLetter(letter){
            this.$emit('pay-letter',letter);
          },
          getName(id){
            if(id == this.pUserFrom.id){
              return this.pUserFrom.name;
            }else{
              return this.pCompanion.name;
            }
          },
          formateDate(date){
            return moment(date).format('lll');
          }
        }
    }
</script>
