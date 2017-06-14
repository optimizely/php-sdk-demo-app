<?php

include ('process.php');

if(isset($_POST['user_id'])){
    $user_id = $_POST['user_id'];
}

?>

<!doctype html>
<html lang="en" class="height--1-1">
  <head>
     <title>Attic & Button</title>
     <!-- Latest compiled and minified OUI stylesheet -->
     <link rel="stylesheet" href="https://d2uaiq63sgqwfs.cloudfront.net/8.0.0/oui.css">
     <style>
       table, th, td, div {
         padding: 5px;
       }
       button {
         padding: 8px 15px;
       }
     </style>
  </head>
  <body class="background--grey height--1-1">
    <div class="flex flex-justified--center soft-quad--ends soft-double--bottom background--faint">
      <div class="max-width--large text--center text-align--center align--center">
        <img src="images/logo.png" class="width--200 text--center align--middle" align="middle" style="margin-bottom:35px">
        <center><h2> Welcome to Attic & Button!</h2></center>
        <div align="center">
          <form action="index.php" method="post">
              <b>Simulate a visitor:</b> <input placeholder="Joe" type="text" name="user_id" value="<?php $user_id ?>">
              <input type="submit" value="Shop">
          </form>
        </div>
        </br>
          <table style="cellspacing: 10">
            <tr>
            <?php
              $data = getItems($user_id);
              $items = $data[0];
              $variation = $data[1];
              displayItems($items);
            ?>
            <tr height='10'></tr>
          </table>
      </div>
    </div>
  </body>
</html>