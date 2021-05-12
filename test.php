
<?php
 require 'connexion.php';
 require 'function.php' ;
//  ob_start();
$graph=new chart();
// array for drawing the graph
$array = [2014 => 25,2015 => 10, 2016 => 20, 2012 => 30, 2013 => 15];
$bool = true;

// recover data from user
if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $color_bg = $_POST['color_bg'];
    $color_cb = $_POST['color_cb'];
    $color_axe = $_POST['color_axe'];
    $axe_x = $_POST['axe_x'];
    $axe_y = $_POST['axe_y']; 
    $type_graph = $_POST['chart'];
    $color_text = $_POST['color_text'];

    // this part is just to require the user to fill all the fields in case it's a line or bar charts
    if($type_graph == "line" || $type_graph == "bar"){
      if(empty($title) or empty($color_bg) or empty($color_cb) or empty($color_axe) or empty($axe_x) or empty($axe_y) or empty($color_text)){
        echo "Please complete all fields";
        $bool = false;
      }
    }

    // if it's a pie chart or all the fields are complete the graph will draw
    if($type_graph == "pie" || $bool == true){
      $echo = $graph->drawGraph($title,$color_bg,$color_axe,$axe_x,$axe_y,$color_cb,$type_graph,$color_text,$array);
      echo $echo;
      
  }
    
 }
 
echo "<br><a href='config.php'>Home Page</a>"; // return to the home page
echo "<br><a href='capture.php'>Take a screen</a>";




?>

