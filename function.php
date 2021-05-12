<?php
/*******************************************************************************
* EnsiasGraph                                                                  *
*                                                                              *
*                                                                              *
* Date:    2021-05-09                                                          *
* Author:  Hind RADADI et Amina ALHAOUIL                                       *
* Encadrant :Ahmed Zellou                                                      *
*******************************************************************************/
    class chart{
    //DB stuff
    private $conn;
    private $table = 'charts' ;

    // chart properties
    public $id;
    public $title_graph;//title of graph
    public $color_background;// the coulor of background
    public $color_graph;// the color of the graph
    public $color_axes;//the color of the axes
    public $title_axe_x;//the title of the x-axes
    public $title_axe_y;//the title of the y-axes

/*******************************************************************************
*                               Public methods                                 *
*******************************************************************************/
    // constructor with DB
    public function __construct(){

    $this->title_graph = 'title of graph';
    $this->color_background = 'black';
    $this->color_axes = 'black' ;
    $this->title_axe_x = 'X' ;
    $this->title_axe_y = 'Y' ;
    }
    
    
        
    // fonction to draw the graph 
    function drawGraph($title_graph,$color_background,$color_axes,$title_axe_x,$title_axe_y,$color_graph,$type_graph,$color_text,$array){
        
        if($type_graph == "line" || $type_graph == "bar"){
        // the svg tag allows us to create graphic formats 
        echo '
        <svg  viewbox="0 2 500 370" style="background-color:'. $color_background .' ; font-size:12px ; width:53%;"> 
        <rect x="30"  y="-10"  width="490"  height="280"   style="fill:transparent ; stroke:'.  $color_axes .' ; stoke-width:1 ;"/>
        <text x="0" y="15" fill="'.$color_text.'">'. $title_axe_y .'</text>
        <text x="470" y="290" fill="'.$color_text.'">'. $title_axe_x .'</text>
        <g class="graph-x">';

        
        // determine the type of keys
        foreach ($array as $key => $value){
            $type=gettype($key);} 
            // if the type is an integer the array will be sorted
            if($type == "integer"){
                //sort of data
                ksort($array);
            }
            //sinon c-a-d si le type est String

        // Calculations for obtaining the coordinates of points to draw the graph
        $nb = max($array);  
        if ($nb <= 50){
                $mdata = 10 ;
        }else {
                $mdata = 20  ;
        }
    
        $max_data=max($array) + $mdata;
        $max_width =500;
        $max_height =270;

        $xgap = $max_width / (count($array) + 1);
        $ygap = $max_height / (count($array) + 1);
        $plz= $xgap;
        $plz2= $ygap;
        
        $one_unit =$max_height / $max_data ;
        foreach ($array as $key => $value){
            // diplay the keys of axis X
            echo '
            <text x='. $plz .'  y="295" fill="'.$color_text.'" >'. $key.'</text>';
            $plz +=$xgap;
        }
        //l'axe des ordonnees
        echo'
        </g>
        <g class="graph-y">';

        foreach ($array as $key => $value){
            $ye = $max_height - ($value * $one_unit );
            $xe = $ye  ;
            // diplay the values of axis Y
            echo'
            <text x="0" y='. $xe .' fill="'.$color_text.'" >'. $value .'</text>';
            $xe += $one_unit ;
        }
        echo '</g>';

        $num = $xgap;
        $num2 = $ygap;
        $points="";
        $elements= "";
        foreach ($array as $key => $value){
            $y = $max_height - ($value * $one_unit );
            $y = $y ;
            $elements .= "<polyline points='$num,0 $num,$max_height' style='stroke:#ffffff22;' />";
            $elements .= "<polyline points='0,$y $max_width,$y' style='stroke:#ffffff22;' />";
            // $elements .= "<text x='$num' y='12' style='fill:white;' >$key</text>";
            $elements .= "<text x='$num' y='".($y  - 10)."' style='fill:$color_text;' >$value</text>";
            $points .= " $num,$y ";
            if($type_graph=="line"){  // To draw a line-chart
            echo "<polyline points='$points' style='stroke:$color_graph;' />";
            $elements .= "<circle  r='5' cx='$num' cy='$y' style='stroke:white;fill:black ;' />";
            }elseif($type_graph=="bar"){ // To draw a bar-chart
                $height = $max_height - $y; // calculate the height of the bars
            echo "<rect x='$num' y='$y' width='50' height='$height' style='fill:$color_graph;;stroke-width:5;' />";
            }
            $points = " $num,$y ";
            $num +=$xgap;
            $num2 +=$ygap;      
        }
        // dispay of the graph
        echo $elements; 
    }elseif($type_graph=="pie"){//pour tracer Pie-Chart
        //Title of the graph
        echo '<p class="title">'. $title_graph .'</p>';

        // array contains the sum of values
        $sumValues = array();
        // array contains values
        $values = array();
        $sum = 0;
        $sumValues[0] = 0;
        //filling of arrays
        foreach ($array as $key => $value) {
            $values[] = $value;
            $sum += $value;
            $sumValues[] = $sum;
        }
        if($sum == 100){
        echo'
        <svg viewBox="0 0 64 64" class="pie">
        ';
        // array to fill colors used
        $tabColor = array();
         for($i = 0; $i < count($values); $i++) { 
          // generate random colors for the pie
          $rand1=rand(0,250);
          $rand2=rand(0,250);
          $rand3=rand(0,250);
          $color="rgb($rand1,$rand2,$rand3)";
          // draw the pie
            echo '<circle r="25%" cx="50%" cy="50%" style="stroke-dasharray: '.($values[$i]+10).' 100; stroke: '.$color.'; stroke-dashoffset: -'.$sumValues[$i].';animation-delay: 0.25s">
            </circle>
            ';
            $tabColor[] = $color; 
            
         }
         // style of the pie
        echo'
        <style>
        .pie {
          width: 300px;
          border-radius: 50%;
        }
        
        .pie circle {
          fill: none;
          stroke: gold;
          stroke-width: 32;
          animation: rotate 1.5s ease-in;
        }
        
        @keyframes rotate {
          to {
            x
          } 
        }
        </style>';
        // this part is for generate the legend of the pie
        for($i = 0; $i < count($values); $i++) {
            echo '
            <div class="canvas">  
                <div class="legend">  
                <ul class="caption-list">
                    <li style="background-color: '.$tabColor[$i].';" class="caption-item">  '.array_search($values[$i],$array).'=>'.$values[$i].'</li>
                </ul>
                </div>
            </div>
            <style>
            @keyframes render {
                0% {
                stroke-dasharray: 0 100;
                }
            }
            
            .canvas {
                display: inline;
                justify-content: space-between;
                align-items: center;
                max-width: 800px;
            }
            
            .legend {
                max-width: 10px;
                margin-left: 30px;
            }
            
            .title {  
                font-family: "Verdana", sans-serif;
                font-size: 18px;
                line-height: 21px;
                color: #591d48;
                margin: 20px;
            }
            
            .caption-list {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            
            .caption-item {
                position: relative;
               
                margin: 20px 0;
                padding-left: 20px;
                border-radius: 5px;
                font-family: "Verdana", sans-serif;
                font-size: 16px;
                line-height: 18px;
                color: #591d48;  
                cursor: pointer;
            }
            
            .caption-item:hover {
                opacity: 0.8;
            }
            
            </style>
            ';
        }
    }else{
        echo 'Impossible! the sum of values must equal to 100';
    }    
            
        }

        
        
        
    }


}
 
?>