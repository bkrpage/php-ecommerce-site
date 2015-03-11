<?php
require($_SERVER['DOCUMENT_ROOT'] . '/php-eshop/src/require.php');

$conn = Common::connect_db();
$searchTerm="Creamy";
search($searchTerm,$conn);

function search($searchTerm,$conn){
    $new = splitSearchTerm($searchTerm);
    //echo $new;
    $result=mysqli_query($conn,$new);
    if(mysqli_num_rows($result)>0){
        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){
            $tempProd=$row['ITEM_ID'];
            $r=mysqli_query($conn,"SELECT * FROM ITEM WHERE ITEM_ID ='$tempProd';");
            while ($r = mysqli_fetch_array($r,MYSQLI_ASSOC)){
                echo($r['ITEM_NAME']);
            }
        }
    }else{
        echo("No Results Found");
    }
}
function splitSearchTerm ($searchTerm){

    $arr=explode(" ",$searchTerm);
    $new="SELECT DISTINCT ITEM_ID FROM TAG WHERE TAG IN (";
    foreach($arr as $v){
        $new=$new."'";
        $new=$new.$v;
        $new=$new."',";
        //echo($new);
    }
    $new=rtrim($new, ",");
    $new=$new.");";
    return ($new);
}
?>