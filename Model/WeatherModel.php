<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
class WeatherModel extends Database
{
    /**
     * Returns the weather data for a specified city.
     * 
     * @param city - the city to request the weather for
     */
    public function getWeather($city)
    {
        return $this->select("SELECT * FROM weather WHERE city = '".$city."'");
    }

    /**
     * Returns a list of the cities that have weather data.
     */
    public function getLocations()
    {
        return $this->select("SELECT city FROM weather");
    }
}