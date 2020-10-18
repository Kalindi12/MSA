<!DOCTYPE html>
<html lang="en">
	<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>COVID-19 API</title>
    <link rel="stylesheet"  href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            h1,h2,.middle,.formsd {
                text-align:center;
            }

            .table,.stats {
            margin-left: auto;
            margin-right: auto;
            }
        </style>
    </head>

<body style="background-color: firebrick; border-style: solid ;border-color: firebrick;border-width: 10px;">
<h1 style="color:lightcoral text-align:center;">COVID-19 UPDATE</h1>

<div style="border-color: lightsalmon; border-radius:100px;">
<h2 style="color:lightcoral text-align:center;"><b> Wordwide Cases</b></h2>
</div>

<section class="middle">
<table class="table table-hover" style="background-color:white; color:black; width:65%;">
<thead>
<tr style="height:70px;">
<th style="color:black; background-color:firebrick; color:white; font-size:20px;">Confirmed Cases</th>
<th style="color:black; background-color: firebrick;color:white;font-size:20px;">Total Deaths </th>
<th style="color:black; background-color:firebrick;color:white;font-size:20px;">Total Recovered</th>
</tr><tr style="height:70px;">
	<?php
	//Worlwide record API
    $worldurl = "https://2019ncov.asia/api/cdr";
    $dataworld = file_get_contents($worldurl);
    $resworld = json_decode($dataworld);
   
    echo '<td style="color:white;background-color:firebrick;font-size:20px; ">' .$resworld->results[0]->confirmed .'</td>';
    echo '<td style="color:white;background-color:firebrick;font-size:20px; ">' .$resworld->results[2]->recovered.'</td>';
    echo '<td style="color:white;background-color:firebrick; font-size:20px;">' .$resworld->results[1]->deaths.'</td>';	   
    ?>
</tr>
</thead>
</table>
</section>


<div class="formsd"> <h2 style="color:lightcoral;"><b>COVID-19 DATA BY COUNTRY </b></h2>
    <form action="covid.php" method="POST" name="covid">
	  <label style="color:white;font-size:30px;">Enter Country Name:</label>
	  <input type="text" id="cname" name="cname"><br><br>
	  <input type="submit" value="Submit" onclick="return IsEmpty();" style="color:black; font-size:30px;">
	</form>
<br>
	
    <?php
           if($_SERVER['REQUEST_METHOD']=='POST')
           {
               countries();
           }

    ?>
	
</div>

<div id="map"></div>
<div class="map">
<table><tbody>
<tr id="data">
</tr>
</tbody></table>
</div>
  

<script>
<?php function countries(){
    error_reporting(0);
    
    $country =  $_POST["cname"];
    $country = strtolower($country);
    $country = preg_replace('/\s*/', '', $country);

    $totalcases = 0;
    $countrycases = 0;
    $result = 0;

    //Worlwide record API
    $worldurl = "https://2019ncov.asia/api/cdr";
    $dataworld = file_get_contents($worldurl);
    $resworld = json_decode($dataworld);

    //Country wise record API
    $countryurl = "https://corona-api.com/countries";
    $datacountry = file_get_contents($countryurl);
    $rescountry = json_decode($datacountry);

    //Country wise record API
    $countryurl = "https://corona-api.com/countries";
    $datacountry = file_get_contents($countryurl);
    $rescountry = json_decode($datacountry);

    foreach($rescountry as $data){
        foreach($data as $res){  
            $res->name = strtolower($res->name);
            $res->name = preg_replace('/\s*/', '', $res->name);
            if($country == $res->name)
            {
                echo "<table border='4' class='stats' cellspacing='0' style='color:firebrick; background-color:white;'>
                <header style='color:lightcoral ; font-size:50px;'>".strtoupper($country)."</header>
                <tr>
                <th>Confirmed Cases</th>
                <th>Total Deaths</th>
                <th>Total Recovered</th>
                </tr>";
                echo"<tr>";
                echo '<td><font style="color:firebrick; font-size:30px;"><center>'.$res->latest_data->confirmed.'</center></td>';
                echo '<td><font style="color:firebrick;font-size:30px;"><center>' .$res->latest_data->recovered.'</center></td>';
                echo '<td><font style="color:firebrick;font-size:30px; "><center>'.$res->latest_data->deaths.'</center></td>';
                echo '<br>';
                $countrycases = $res->latest_data->confirmed;
                echo"</tr>";
                echo "</table>";

                $totalcases = $resworld->results[0]->confirmed;

                //Ratio of country's cases in respect to world total cases
                $result = ($countrycases * 100) / $totalcases;
                echo '<br><b> <font style="color:lightcoral; font-size:40px;">' .number_format($result, 2). '%  of world confirmed cases are contributed by ' .$country;
            }   
        }
    }   
}

?>  

//Validation for empty field
function IsEmpty() {
  if (document.forms['covid'].cname.value === "") {
    alert("Country name is Mandatory");
    return false;
  }
  return true;
}

</script>

<br>
<div class="middle">
<iframe src="https://ourworldindata.org/grapher/total-cases-covid-19?tab=map" width="80%" height="500px"></iframe>
</div>

</body>
</html>