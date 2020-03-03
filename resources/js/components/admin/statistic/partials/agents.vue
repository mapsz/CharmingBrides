<template>
  <div class="container-fluid">
    <h1>Statistic</h1>
    <div class="row">
      <div class="col-2">
        <statistic-categories></statistic-categories>
      </div>
      <div class="col-10">
        <h1>Agents</h1>
        <!-- Period -->
        <div class="row" style="max-width:400px">
          <div class="input-group input-group-sm mb-3">
            <span class="pr-2">Period:</span>  
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">From</span>
            </div>
            <input type="text" class="form-control">
            <div class="input-group-prepend">
              <span class="input-group-text" id="inputGroup-sizing-sm">To</span>
            </div>
            <input type="text" class="form-control"> 
            <button class="btn btn-sm btn-primary ml-2">Search</button>         
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
              
            </tbody>
          </table>
        </div>
      </div>      
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style>

</style>