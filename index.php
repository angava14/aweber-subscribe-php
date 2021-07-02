<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->
    <h2 class="active"> Subscribe </h2>
   
    <!-- Icon -->
    <div class="fadeIn first">
      <img src="AGV.png" id="icon" alt="User Icon" />
    </div>

    <!-- Login Form -->
    <form action="./action.php" class="wrapper">
      <input type="text" id="name" class="fadeIn second" name="name" placeholder="Full Name">
      <input type="text" id="email" class="fadeIn second" name="email" placeholder="Email">
      <textarea class="info" cols="50" rows="5" wrap="soft" readonly="yes" id="politica_servicio" name="politica_servicio">The only personal data that is collected by the project is the data that you provide via the user registration. Your personal data is collected by the project to perform user validation i.e. to deter automated systems having direct access to the product, for statistical analysis and correspondence purposes only.
        The project will retain your e-mail address in order to provide you with newsletters and information about product updates and information that may be of interest related to the soil moisture thematic (i.e. information about workshops on soil moisture).</textarea>

      <label for="checkbox" class="checkboxes"><input type="checkbox" id="acepto_terminos"  class="fadeIn third checkboxes" name="acepto_terminos">I have read and accept the 
      terms and conditions</label>
   
      <input type="submit" class="fadeIn fourth" value="Subscribe">
      <label style="color:red" value=""><?php if(isset($_GET['error'])){echo 'Please type a Name and Email';} ?><label>
    </form>


  </div>
</div>

</body>
</html>

<script type="application/javascript"> /* Token Auto-Refresh after every 30 segs*/
    var intervalId = window.setInterval(function(){
      var xmlHttp = new XMLHttpRequest();
      xmlHttp.open( "GET", 'updateToken.php', false ); 
      xmlHttp.send( null );
    }, 60000);
</script>
