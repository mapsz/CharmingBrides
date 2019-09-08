<script>
  export default {
    data(){return{
      axiosErrors:[],
      axiosErrorsCode:false,
      axiosShowErrors:true,
    }},
    mounted() {
    },
    methods: {
      async ax(type,route,data = "",hideButton = false){

        //Hide button
        if(hideButton) $(hideButton).hide();

        let r = false;
        switch (type) {
          case 'get':
            r = await axios.get(route, {params:data})              
              .then((r) => {return this.axiosHandle(r);})
              .catch((error) => {this.axiosCatch(error);return false;});
            break;
          case 'put':
            r = await axios.put(route, data)              
              .then((r) => {return this.axiosHandle(r);})
              .catch((error) => {this.axiosCatch(error);return false;});         
            break;
          case 'post':
            r = await axios.post(route, data)              
              .then((r) => {return this.axiosHandle(r);})
              .catch((error) => {this.axiosCatch(error);return false;});         
            break;
          case 'delete':
            r = await axios.delete(route, {data:data})              
              .then((r) => {return this.axiosHandle(r);})
              .catch((error) => {this.axiosCatch(error);return false;});         
            break;            
          default:
            return false;
        }

        //Show button
        if(hideButton) $(hideButton).fadeIn();

        if(!r){
          this.derror('ax error - '+this.axiosErrorsCode+' '+route);
        } 

        return r;
      },
      axiosHandle(r){
        //Check data
        if(!r.hasOwnProperty('data')){
          this.axiosErrors = ['Somethink gone wrong!'];
          this.axiosErrorsCode = 1;         
          if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
          return false;
        }
        //Check error
        if(!r.data.hasOwnProperty('error')){
          this.axiosErrors = ['Somethink gone wrong!'];
          this.axiosErrorsCode = 2;
          if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
          return false;
        }
        //Success
        if(r.data.error == 0){
          if(r.data.hasOwnProperty('data')){
            if (this.isJson(r.data.data))  return JSON.parse(r.data.data);
            else                            return r.data.data;
            
          }else{
            return true;
          }
        }
        //Error
        if(r.data.error == 1 && r.data.hasOwnProperty('text')){
          if(r.data.hasOwnProperty('code')){
            console.log('error code - '+r.data.code)
          }
          this.axiosErrors = [r.data.text];
          this.axiosErrorsCode = 3;
          if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
        }else{
          this.axiosErrors = ['Somethink gone wrong!'];
          this.axiosErrorsCode = 4;
          if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
        }
        return false;   
      },
      axiosCatch(error){
        if(error.hasOwnProperty('response')){
          if(error.response.hasOwnProperty('status')){
            if(error.response.status == 422){ //Validate
              this.axiosErrors = error.response.data.errors;
              this.axiosErrorsCode = 5;
              if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
              return false;
            }

            if(error.response.status == 401){ //Unauth
              location.reload();
              return false;
            }           


            this.axiosErrors = ['Somethink gone wrong!'];
            this.axiosErrorsCode = 6;
            if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
            return false;
            
          }else{
            this.axiosErrors = ['Somethink gone wrong!'];
            this.axiosErrorsCode = 7;
            if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
          }
        }else{
          this.axiosErrors = ['Somethink gone wrong!'];
          this.axiosErrorsCode = 8;
          if(this.axiosShowErrors) this.showErrors(this.axiosErrors,this.axiosErrorsCode);
        }          
        return false;
      },
      isJson(str) {
          if (typeof str !== 'string') return false;
          try {
              const result = JSON.parse(str);
              const type = Object.prototype.toString.call(result);
              return type === '[object Object]' 
                  || type === '[object Array]';
          } catch (err) {
              return false;
          }
      }
    }
  }


</script>