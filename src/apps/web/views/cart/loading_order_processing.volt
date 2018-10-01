 <script>
 var timeleft = 10;
 var downloadTimer = setInterval(function(){
   document.getElementById("progressBar").value = 10 - --timeleft;
   if(timeleft <= 0)
   {
     clearInterval(downloadTimer);
     location.reload();
   }
 },1000);
 </script>
 <section class="section--card--details">
                          <div style="  display: table;
                                        margin: 0 auto;">
                                Processing another order.... <br><br>
                                <progress value="0" max="10" id="progressBar"></progress>
                          </div>
 </section>