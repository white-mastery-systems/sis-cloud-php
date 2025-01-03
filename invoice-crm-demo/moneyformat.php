
<?php
function indian_number_format($num){
        $num=explode('.',$num);
        $dec=(count($num)==2)?'.'.$num[1]:'.00';
        $num = (string)$num[0];
        if( strlen($num) < 4) return $num;
        $tail = substr($num,-3);
        $head = substr($num,0,-3);
        $head = preg_replace("/\B(?=(?:\d{2})+(?!\d))/",",",$head);
        return $head.",".$tail.$dec;
    }



function formatInIndianStyle($num){
 $pos = strpos((string)$num, ".");
 if ($pos === false) {
 $decimalpart="00";
 }
 if (!($pos === false)) {
 $decimalpart= substr($num, $pos+1, 2); $num = substr($num,0,$pos);
 }

 if(strlen($num)>3 & strlen($num) <= 12){
 $last3digits = substr($num, -3 );
 $numexceptlastdigits = substr($num, 0, -3 );
 $formatted = makeComma($numexceptlastdigits);
 $stringtoreturn = $formatted.",".$last3digits.".".$decimalpart ;
 }elseif(strlen($num)<=3){
 $stringtoreturn = $num.".".$decimalpart ;
 }elseif(strlen($num)>12){
 $stringtoreturn = number_format($num, 2);
 }

 if(substr($stringtoreturn,0,2)=="-,"){
 $stringtoreturn = "-".substr($stringtoreturn,2 );
 }

 return $stringtoreturn;
 }

 function makeComma($input){
 // This function is written by some anonymous person - I got it from Google
 if(strlen($input)<=2)
 { return $input; }
 $length=substr($input,0,strlen($input)-2);
 $formatted_input = makeComma($length).",".substr($input,-2);
 return $formatted_input;
 }
?>