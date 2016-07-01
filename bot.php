<?php
header("Content-type: application/x-javascript");
namespace Sprint;
class Bot
{
  public function __construct($data)
  {
    $this->command = $data["command"];
    $this->data = $data["data"];
  }
  public function generateHash()
  {
    $comArr = str_split($this->command);
    $comNum = $this->dataConversion($comArr);
    $datArr = str_split($this->data);
    $datNum = $this->dataConversion($datArr);
    $sum = $comNum + $datNum;
    $this->hash = dechex($sum);

	
  }
  public function dataConversion($arr){
    $ans = 0;
    for($i = 0;$i < count($arr);$i++){

      $arr[$i] = ord($arr[$i]);
    }
    $ans = \Sprint\scientificNotation(implode($arr));
    $start = strpos($ans,'.')+1;
    $end = strpos($ans,'e+');
    if(strlen($ans) > 21){
      $match1 = substr($ans, $start,$end - $start );
      $match2 = substr($ans,$end + 2);
      $ans = $match1.$match2;
    }
    return $ans;
  }

}



/**
 * Return scientific notation if after 'e+' was more than 20.
 * If it was less equal than 20, will return normal integer string.
 *
 * e.g.
 * 1000000000000000000000 => 1e+21
 * => return 1.0000000000000000e+21
 *
 * 10000000000000000000 => 1e+19
 * => return 10000000000000000000
 *
 * @param $num integer
 *
 * @return string
 * Note:
 * Since PHP use scientific notation from 1e+19,
 * this function return value with string.
 */
function scientificNotation($num)
{
    if (overE20($num)) {
        return sprintf("%.16e", $num);
    }
    return sprintf("%.0f", $num);
}

function overE20($num)
{
    $sn = sprintf("%e", $num);
    $e = explode("e+", $sn)[1];
    return $e > 20;
}

?>
