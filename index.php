<?php
  require __DIR__ . "/inc/bootstrap.php";
  // This parses out the request uri into it segments and make sure that the request is one of the endpoints that we have available
  $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
  $uri = explode( '/', $uri );
  if ((isset($uri[2]) && ($uri[2] != 'locations' || $uri[2] != 'location'))) {
    header("HTTP/1.1 404 Not Found");
    exit();
  }
  // this gets an new weather controller object and then decides based on the uri segments which endpoint function to call
  require PROJECT_ROOT_PATH . "/Controller/Api/WeatherController.php";
  $objFeedController = new WeatherController();
  if(isset($uri[2]) && $uri[2] == 'locations'){
    $objFeedController->locationList();
  }elseif((isset($uri[2]) && $uri[2] == 'location') && isset($uri[3])){
    $objFeedController->weatherInfo($uri[3]);
  }
  //set of global variables
  $weather_api_key = '0cb1513ad45040528e704443232402'; // remnant of an other approch but do not want to loose the key
  $currentCity = '';
  $currentState = '';
  $currentTemp = '';
  $currentCondition = '';
  $weatherMesssage = '';
  // $containsName = false;
  
  /**
   * This function takes a city (which is provided from the HTML form below) checks if there is data for that city
   * then either returns a message that there is no data for that city or returns the weather data for that city.
   * 
   * @param city - this is the city to get the weather data for
   */
  function getWeatherData($city){
    global $currentCity, $currentState, $currentTemp, $currentCondition, $weatherMesssage/*, $containsName*/;
    $weatherObject = new WeatherModel();
    $locationArray = $weatherObject->getLocations();
    $containsName = false;
    foreach($locationArray as $loc){
      if(strtolower($loc['city']) == strtolower($city)){
        $containsName = true;
        break;
      }
    }
    if(!$containsName){
      $weatherMesssage = 'There is no weather info for '.$city;
    }else{
      $currentWeather = $weatherObject->getWeather($city);
      // echo json_encode($currentWeather);  // Debugging code
      $currentCity = $currentWeather['0']['city'];
      $currentState = $currentWeather['0']['state'];
      $currentTemp = $currentWeather['0']['temp'];
      $currentCondition = $currentWeather['0']['condition_curr'];
      $weatherMesssage = $currentCity.', '.$currentState.' is currently '.$currentTemp.'°F and '.$currentCondition;
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Weather Challenge</title>
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: .9em;
        color: #000000;
        background-color: #FFFFFF;
        margin: 0;
        padding: 10px 20px 20px 20px;

        background-image: url(./weather-background-2.jpeg);
        background-repeat: no-repeat;
        background-size: 940px 588px;
        background-position: center;
        background-position-y: 80px;
      }

      .infoElements{
        text-align: center;
      }
    </style>
  </head>
  <body>
    <h2 class="infoElements">Weather</h2>
    <div class="infoElements">
      <?php 
        //Looks to see if the button was pushed and then gets the text from the user input and calls getWeatherData()
        if(isset($_GET['button1'])){
          $cityInput = htmlentities($_GET['city']);
          getWeatherData($cityInput);
        }
      ?>
      <form method="get">
        <label for="city">Enter the city</label>
        <input type="text" name="city">
        <input type="submit" name="button1" value="Select" />
      </form>  
    </div>
    <div>
      <p class="infoElements"><?php echo $weatherMesssage ?></p>
    </div>
  </body>
</html>