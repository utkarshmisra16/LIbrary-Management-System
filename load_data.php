<?php  
 //load_data.php  
 // $connect = mysqli_connect("localhost", "root", "", "library_db");  
 $output = '';  
 if(isset($_POST["brand_id"]))  
 {  
      if($_POST["brand_id"] != '')  
      {  
        $date = $_POST["brand_id"];
        $newdate = strtotime ( '+3 day' , strtotime ( $date ) ) ;
        $newdate = date ( 'j/m/Y  ' , $newdate ); 
      }  
      else  
      {  
           $newdate = ""; 
      }  
      $result = $newdate;  
      if($result)  
      {  
          $output = "<input type='text' class='form-control' name='dueDate' value='$result'>";  
      }  
      echo $output;  
 }  
 ?> 