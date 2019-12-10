<?php
$place[0] = "";
$error = "";
$success = "";
function get_string_between($string, $start, $end){
        $string = " ".$string;
        $ini = strpos($string,$start);
        if ($ini == 0) return "";
        $ini += strlen($start);   
        $len = strpos($string,$end,$ini) - $ini;
        return substr($string,$ini,$len);
    }
if (array_key_exists("Place", $_GET))
{
    $place = explode(",",$_GET["Place"], 2);
    $cityname = preg_replace('#[ -]+#', '-', $place[0]);
    $base_url = "https://www.weather-forecast.com/locations/".$cityname."/forecasts/latest";
    $file_headers = @get_headers($base_url);
    if($file_headers[0] == 'HTTP/1.1 404 Not Found')
    {
        $error = '<div class="alert alert-danger" role="alert">The entered location could not be found.</div>';
    }
    else 
    {
        $file_content = file_get_contents($base_url);
        $req_content = get_string_between($file_content, '<span class="phrase">', '</span>');
        if($req_content == "")
        {
        $error = '<div class="alert alert-danger" role="alert">The entered location could not be found.</div>';
        }
        else
        {
            $success = '<div class="alert alert-success" role="alert">'.$req_content.'</div>';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="rocksha">
    <link rel="icon" href="images/cloud.png">

    <title>The Weather Man</title>

   <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
      <link href="styles.css" rel="stylesheet">
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="inner cover">
            <h1 class="cover-heading">The Weather Man</h1>
            <p class="lead">Enter the name of a city.</p>
            <form>
            <fieldset class="form-group">
            <input type="text" class="form-control" id="Place" name="Place" placeholder="Enter any city" value="<?echo $place[0] ?>">
            </fieldset>
               <button type="submit" class="btn btn-primary">Submit</button>   
              </form>
          </div>
            <br/>
          <div id="message"><? echo $success.$error ?></div>

          <div class="mastfoot">
            <div class="inner">
              <p>Weather Scraper by @rocksha.</p>
            </div>
          </div>

        </div>

      </div>

    </div>

       <!-- jQuery first, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.3.2/js/tether.min.js"></script>
      <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAwG8nc-o76qj9RuUUn7yDbqgxOz1zuLkw&sensor=false&libraries=places"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/geocomplete/1.7.0/jquery.geocomplete.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js" integrity="sha384-vZ2WRJMwsjRMW/8U7i6PWi6AlO1L79snBrmgiDpgIWJ82z8eA5lenwvxbMV1PAh7" crossorigin="anonymous"></script>
      <script>
      $(function () {	
	$("#Place")
		.geocomplete()
		.bind("geocode:result", function (event, result) {						
			console.log(result);
	});
});
</script>
  </body>
</html>
