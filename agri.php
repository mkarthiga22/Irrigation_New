<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <title>Irrigation System</title>
    <style>
      html {
        width: 100%;
        height: 100%;
        overflow: hidden;
      }

      body {
        width: 100%;
        height: 75%;
        font-family: "Helvetica";
        background: #000;
        color: #fff;
        font-size: 24px;
        text-align: center;
        letter-spacing: 1.4px;
      }
      .login {
        position: absolute;
        top: 40%;
        left: 50%;
        margin: -150px 0 0 -150px;
        width: 400px;
        height: 400px;
      }

      .login h1 {
        color: #fff;
        text-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        letter-spacing: 1px;
        text-align: center;
      }

      input {
        width: 100%;
        margin-bottom: 10px;
        background: rgba(0, 0, 0, 0.3);
        border: none;
        outline: none;
        padding: 10px;
        font-size: 13px;
        color: #fff;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 0, 0, 0.3);
        border-radius: 4px;
        box-shadow: inset 0 -5px 45px rgba(100, 100, 100, 0.2),
          0 1px 1px rgba(255, 255, 255, 0.2);
        -webkit-transition: box-shadow 0.5s ease;
        -moz-transition: box-shadow 0.5s ease;
        -o-transition: box-shadow 0.5s ease;
        -ms-transition: box-shadow 0.5s ease;
        transition: box-shadow 0.5s ease;
      }

      .input-field {
        width: 100%;
        padding: 10px;
      }

      .btn {
        margin-top: 20px;
        padding: 10px;
        cursor: pointer;
      }
      .result {
        padding-top: 40px;
		color: white;
      }
		
      #weather-title, #weather-desc{
        font-size: 1.35rem;
        font-weight: 900;

      }
      #weather-title{
        margin-top: 40px;


      }
      #weather-desc{
      font-weight: 750;
      margin-top: 15px;

      }
      .header{
      font-size: 2rem;
      margin-bottom: 30px;
      }
    </style>
  </head>

  <body style="background: grey">
    <div class="login">
      <h2 class="header">Smart Irrigation System</h2>

      <!-- Main Input For Receiving Query to our ML -->
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <select name="soil_type" class="input-field">
          <option value="0">Sandy</option>
          <option value="1">clay</option>
        </select>
        <!-- <input
          type="text"
          name="Bedrooms"
          placeholder="Bedrooms"
          required="required"
        /> -->
        <!-- <input type="text" name="Bathrooms" placeholder="Bathrooms" required="required" />
        <input type="text" name="Grade" placeholder="Grade" required="required" /> -->
        <button type="submit" class="btn btn-primary btn-block btn-large"> Predict </button>
      </form>
      <div class="result">
        <div> 
		<?php
			
				$conn = new mysqli('localhost', 'root', '', 'ds');

				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				if( isset($_POST['soil_type'])){
					$Avg = 0;
					if(isset($_POST['soil_type']))
					{
						$soil_type = $_POST['soil_type'];
						
						if($soil_type == 1)
						{
							$sql = "SELECT *, Avg(Moisture) as total_amount FROM irrigation_table WHERE Moisture < 35";
							$query = $conn->query($sql);

							if($query->num_rows > 0)
							{
								$srow = $query->fetch_assoc();
								$Avg = $srow['total_amount'];
								//echo "Average : " . number_format($Avg, 2) . "\n";
							}
						}
						
						if($soil_type == 0)
						{
							$sql = "SELECT *, Avg(Moisture) as total_amount FROM irrigation_table WHERE Moisture < 40";
							$query = $conn->query($sql);
							
							if($query->num_rows > 0)
							{
								$srow = $query->fetch_assoc();
								$Avg = $srow['total_amount'];
								//echo "Average : " . number_format($Avg, 2) . "\n";
							}
						}
						 if($Avg > 33)
						 {
							echo "Result = Irrigate";
						 }
						 else
						 {
							echo "Result = No Irrigation";
						 }
						
					}	
				}
			?>
		</div>
        <div id="weather">
          <div id="weather-title"></div>
          <div id="weather-desc"></div>
        </div>
    </div>
    </div>
  </body>
  <script>
    let lat = 0, lan = 0
      window.onload = function() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(e){
            console.log(e);
            lat = e.coords.latitude;
            lan = e.coords.longitude;
             console.log(lat,lan)




fetch(`http://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lan}&appid=023948875cdf202700d5163464c3ae2b`)
.then(response => response.json())
.then(json => {console.log(json)
  let title = document.querySelector("#weather-title");
              let desc = document.querySelector("#weather-desc");
              
              title.innerHTML = json.weather[0].main;
              if(json.weather[0].main === 'Clouds' || json.weather[0].main=== 'Rains')
                    //  desc.innerHTML = json.weather[0].description;
                     desc.innerHTML = "It seems cloudy or rainy.";
                     
              });
             
          });
        } 
      }
  </script>
</html>
