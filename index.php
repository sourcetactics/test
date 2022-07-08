<?php 
define("MAX_INT",9223372036854775807);

class Calculation{
    private $x;
    private $y;
    private $radius;

    private function l_b_calc(){
        $x = $this->x - $this->radius;
        $y = $this->y - $this->radius;
        if($x > MAX_INT || $x < (MAX_INT * -1)){
          $x = number_format($x,0,'.','');
        }
        if($y > MAX_INT || $y < (MAX_INT * -1)){
          $y = number_format($y,0,'.','');
        }
        return ["x" => $x, "y" => $y];
    }
    private function r_t_calc(){
      $x = $this->x + $this->radius;
      $y = $this->y + $this->radius;
      if($x > MAX_INT || $x < (MAX_INT * -1)){
        $x = number_format($x,0,'.','');
      }
      if($y > MAX_INT || $y < (MAX_INT * -1)){
        $y = number_format($y,0,'.','');
      }
      return ["x" => $x, "y" => $y];
    }
    private function result_opt(){
      return [ "left_bottom" => $this->l_b_calc(), "right_top" => $this->r_t_calc() ];
    }
    public function loop(array $tests){
      for($i = 0; $i < count($tests['result']); $i++){
        $result = [];
          for($a = 0; $a < count($tests['result'][$i]); $a++){
            
            $this->x = $tests['result'][$i][$a]['x'];
            $this->y = $tests['result'][$i][$a]['y'];
            $this->radius = $tests['result'][$i][$a]['radius'];
            
            $result[] = $this->result_opt();
            
          }

        $result_all[] = $result;
      
      }
      return $result_all; 
    }
}

class Min_area_rectangle{
  public function calc_area(array $data){

    $data_results = [];

    for($i = 0; $i < count($data); $i++){
      
      for($a = 0; $a < count($data[$i]); $a++){

          $x_left_static = $data[$i][$a]["left_bottom"]["x"];
          $x_right_static = $data[$i][$a]["right_top"]["x"];
          $y_left_static = $data[$i][$a]["left_bottom"]["y"];
          $y_right_static = $data[$i][$a]["right_top"]["y"];

          $x_min_flag = true;
          $y_min_flag = true;
          $x_max_flag = true;
          $y_max_flag = true;
            for($e = 0; $e < count($data[$i]); $e++){
      
           

              $x_left = $data[$i][$e]["left_bottom"]["x"];
              $x_right = $data[$i][$e]["right_top"]["x"];
              $y_left = $data[$i][$e]["left_bottom"]["y"];
              $y_right = $data[$i][$e]["right_top"]["y"];

              if((int) $x_left_static > (int) $x_left){
                  $x_min_flag = false;
              }
              if((int) $y_left_static > (int) $y_left){
                  $y_min_flag = false;
              }
              if((int) $x_right_static < (int) $x_right){
                  $x_max_flag = false;
              }
              if((int) $y_right_static < (int) $y_right){
                  $y_max_flag = false;
              }
              
            }

          if($x_min_flag === true){
            $min_x = $x_left_static;
          }
          if($y_min_flag === true){
            $min_y = $y_left_static;
          }
          if($x_max_flag === true){
            $max_x = $x_right_static;
          }
          if($y_max_flag === true){
            $max_y = $y_right_static;
          }
          
          
        
      }

      $data_results[] = ["left_bottom" => ["x" => $min_x,"y" => $min_y], "right_top" => ["x" => $max_x,"y" => $max_y]];
  }
    return $data_results;
  }
}



$json = '{
    "key" : "LT+DW4pE3iWU+YMKTD0F2GogvwD9k/QhpCVbDZUYsODVhiffVDQcl/Vn+MqQ2LhoxGDSkTBZasj2jlPgKRUclQ==",
    "method" : "GetTasks", 
    "params" : null
}';
$data = json_decode($json);







$ch = curl_init('http://contest.elecard.ru/api');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


$html = curl_exec($ch);

 


$tests = json_decode($html,true);
$calc = new Calculation();
$area_class = new Min_area_rectangle();
$results_cords = $calc->loop($tests);
$results_areas = $area_class->calc_area($results_cords);
$results_areas = json_encode($results_areas);


$result_query = '{
  "key":"LT+DW4pE3iWU+YMKTD0F2GogvwD9k/QhpCVbDZUYsODVhiffVDQcl/Vn+MqQ2LhoxGDSkTBZasj2jlPgKRUclQ==",
  "method":"CheckResults",
  "params": '.$results_areas.'
  
}';


curl_setopt($ch, CURLOPT_POSTFIELDS, $result_query);
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
$html = curl_exec($ch);

echo $html;

curl_close($ch);

/*
foreach ($tests["result"] as $value) {
  var_dump($value);
  echo "l_b x:" .$value["left_bottom"]["x"]."<br>";
  echo "l_b y:" .$value["left_bottom"]["y"] ."<br>";
  echo "r_t x:" .$value["right_top"]["x"]."<br>";
  echo "r_t y:" .$value["right_top"]["y"] ."<hr>";

}
foreach ($results_areas as $value) {
   echo "l_b x:" .$value["left_bottom"]["x"]."<br>";
   echo "l_b y:" .$value["left_bottom"]["y"] ."<br>";
   echo "r_t x:" .$value["right_top"]["x"]."<br>";
   echo "r_t y:" .$value["right_top"]["y"] ."<hr>";

}
*/

?>