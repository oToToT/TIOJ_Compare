<?php
header('Content-Type: application/json; charset=utf-8');
if( isset( $_GET['name1'] ) && isset( $_GET['another'] )){
   if( $_GET['another']==="own" && isset( $_GET['name2']) ){
      $data = shell_exec('./crawler.py'.' '.$_GET['name1'].' '.$_GET['name2']);
      echo $data;
   }else if($_GET['another']==='robot'){
      $data = shell_exec('./crawler.py'.' '.$_GET['name1']);
      echo $data;
   }
}else{
   echo '{"status":400,"error":"error occured"}';
}
?>
