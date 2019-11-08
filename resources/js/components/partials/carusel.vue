<template>
  <div class="container">
    <div class="carusel-container media">
      <div class="row m-0">

        <div class="logo-container">
          <img class="logo" src="/img/logo.png">
        </div>
        
        <div class="text-container align-self-center">
          <div>Charming Brides <b>Ladies are:</b></div>
          <div>
            <span class="fw">Loyal</span>
            <span class="sw">and</span>
            <span class="tw">Real</span>
          </div>
          <div>
            <span class="fw">Outgoing</span>
            <span class="sw">and</span>
            <span class="tw">Trustworthy</span>
          </div>
          <div>
            <span class="fw">Vivid</span>
            <span class="sw">and</span>
            <span class="tw">Charming</span>
          </div>
          <div>
            <span class="fw">Extravert</span>
            <span class="sw">and</span>
            <span class="tw">Sincere</span>
          </div>
        </div>
        <div class="under-text">
          Have been working for years for you
        </div>

      </div>

    </div>
  </div>
</template>

<script>
  export default {}
</script>

<style scoped>
  
  .logo{
    width: 150px;
    height: 150px;
  }
  .logo-container {
      padding: 50px;
      background-color: #fff9f97a;
      width: 250px;
      border-radius: 150px;
      box-shadow: 0 0 5px rgba(0,0,0,0.5);
  }  
  .carusel-container{
    background-image: url(/img/carusel.jpg);
    background-color: #cbc4c4;
    padding: 40px;
    background-position: top right;    
  }

  .text-container{
    font-size: 16pt;
    line-height: 19pt;  
    margin-left:40px; 
    padding: 20px;
    border-radius: 20px;    
    background-color: #fff9f97a;
    box-shadow: 0 0 5px rgba(0,0,0,0.5);
  }
  .fw{
    color:rgb(204, 51, 204);
  }
  .tw{
    color:rgb(204, 51, 204);
    font-weight: 800;
  }
  .under-text {
    position: absolute;
    transform: translate(260px ,200px);
    color: #ffc6ff;
    font-size: 28pt;
    font-family: 'Merienda', cursive;
    text-shadow: 1px 1px #804a5a;
  }


</style>