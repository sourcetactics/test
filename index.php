<?php 

class Calculation{
    private $x;
    private $y;
    private $radius;

    private function l_b_calc(){
        $x = $this->x - $this->radius;
        $y = $this->y - $this->radius;
        return ["x" => $x, "y" => $y];
    }
    private function r_t_calc(){
      $x = $this->x + $this->radius;
      $y = $this->y + $this->radius;
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
       echo 1;
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

              if($x_left_static > $x_left){
                  $x_min_flag = false;
              }
              if($y_left_static > $y_left){
                  $y_min_flag = false;
              }
              if($x_right_static < $x_right){
                  $x_max_flag = false;
              }
              if($y_right_static < $y_right){
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

//curl_setopt($ch, CURLOPT_POSTFIELDS, $result);


$html = curl_exec($ch); // ресурс curl

curl_close($ch);
 


$tests = json_decode($html,true);
//var_dump($tests);
$calc = new Calculation();
$area_class = new Min_area_rectangle();
$results_cords = $calc->loop($tests);
var_dump($area_class->calc_area($results_cords));


/*

$result = '{
    "key":"LT+DW4pE3iWU+YMKTD0F2GogvwD9k/QhpCVbDZUYsODVhiffVDQcl/Vn+MqQ2LhoxGDSkTBZasj2jlPgKRUclQ==",
    "method":"CheckResults",
    "params": [
      {
        "left_bottom":{"x":0,"y":0},
        "right_top":{"x":0,"y":0}
      }
    ]
    
  }';
$result = json_decode($result,true);
*/

?>