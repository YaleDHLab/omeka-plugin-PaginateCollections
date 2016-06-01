<?php echo'<div>Public Show.php</div>' ?>
<br>
<?php echo $total_items." items returned"; ?>

<br><br>

<?php
while ($row = mysql_fetch_array($query_response)) {
  foreach($row as $key => $value) {
    echo "Key: $key; Value: $value</br>"; 
  }
  echo "<br><br>";
}

?>
