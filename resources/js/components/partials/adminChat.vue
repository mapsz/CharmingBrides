<template>
    <div class="container">

    	<div class="container p-2">
	        <h2>Online</h2>	
            <div class="row">
            	<div
            	v-for="hardOnlineUser in hardOnline"
            	class="col p-1">
                    <div                       
                      class="hardOnlineUser" @click="selectUser(hardOnlineUser.id)">
                        <img :src="'media\\girls\\zoom\\' + hardOnlineUser.id + '_0.jpg'" alt="" width="100px"><br>
                        <span 
                          v-bind:class="{'text-warning': user.id == hardOnlineUser.id}">
                            {{hardOnlineUser.name}}
                        </span>
                    </div>
            		<button class="btn btn-danger" @click="deleteHardOnline(hardOnlineUser.id)">X</button>
            		
            	</div>
            </div>  
	        <!-- <button @click="resetHardOnline()" class="btn btn-primary">Reset</button> -->
		</div>
		<div class="row">
			<div class="container p-2">
		        <input type="text" v-model="setHardOnlineId">
		        <button class="btn btn-primary" @click="setHardOnline()">add Online</button>
	        </div>
        </div>
		<div 
		v-show="user"
        class="currentUser container">					
			<h2>Current user</h2>
			{{user.name}}
        </div>
    </div>
</template>

<script>
    export default {
    	data(){
            return {
            	isDebug: 1,
                hardOnline:[],
                setHardOnlineId: "",
                user: false,

            }
        },
        mounted(){

        	this.getHardOnline();

        },
        methods: {
        	async getHardOnline(){
               var r = await axios.get('/chat/getHardOnline' )
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        return r.data;
                    })
                    .catch((r) => {this.debug(r);return false;});

                return this.hardOnline = r;
        	},

        	resetHardOnline(){
        		//
        		this.$emit('onlineReset');
        	},

        	async setHardOnline(){



                var r = await axios.post('/chat/admin/setOnline',{id:this.setHardOnlineId})
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        return r.data;
                    })
                    .catch((r) => {this.debug(r);return false;});



                if(r){
	           		this.setHardOnlineId = "";
	                this.getHardOnline();
	                this.resetHardOnline();
	                return true;
	            }else{
	            	return false;
	            }


        	},

        	async deleteHardOnline(userId){

               var r = await axios.delete('/chat/admin/deleteOnline',{data: {id:userId}})
                    .then((r) => {
                        if(!r.data) return false;

                        if(r.data.error){
                            this.debug('error' + ' ' + r.data.error + ' - ' +r.data.text);
                            return false;
                        }

                        return r.data;
                    })
                    .catch((r) => {this.debug(r);return false;});

                if(r){
	                this.getHardOnline();
	                this.resetHardOnline();
	                return true;
	            }else{
	            	return false;
	            }

        	},

        	selectUser(id){
        		let user = this.hardOnline.find(x => x.id === id);
        		this.user = user;
        		this.$emit('selectUser', user);
        	},


        	debug(x){
                if(this.isDebug){
                    console.log(x);
                }
            },
        }
    }
</script>


<style scoped>

	.hardOnlineUser:hover{
		text-decoration: underline;
		color:green;
	}

	.hardOnlineUser{
		cursor: pointer;
	}

</style>