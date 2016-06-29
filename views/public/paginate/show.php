<?php
  // define the current collection class
  $collectionclass = ('collection' .$requested_collection_id);
  echo head(array('bodyid'=>'trancription','bodyclass'=>$collectionclass)); 

  // set the current collection title as the <title> value for the page
  $this->headTitle()->prepend( mysql_result($collection_title, 0) );
  echo $this->headTitle(); 
?>

<link href="http://transcribe.library.yale.edu/projects/plugins/GuestUser/views/public/css/guest-user.css" rel="stylesheet" type="text/css">
<link href="http://transcribe.library.yale.edu/projects/plugins/GuestUser/views/public/css/admin-bar.css" rel="stylesheet" type="text/css">
<link href="http://transcribe.library.yale.edu/projects/plugins/GuestUser/views/public/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://transcribe.library.yale.edu/projects/themes/ani-yun-wiya/css/lib/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="http://transcribe.library.yale.edu/projects/themes/ani-yun-wiya/css/supersized.css" rel="stylesheet" type="text/css">

<link href="http://fonts.googleapis.com/css?family=Arvo:400" rel="stylesheet" type="text/css">


<script>
  // use jQuery to add classes to body
  $("body").addClass("collections show");
</script>


<style>
@media (max-width: 767px) {
	.main {
	padding-left: 0;
	}
	div.collectionTitle {
	width:100%;

	padding-left:15px;
	}
	.collectionTitle h1 {
	text-align:left;
	}
	div.collectionDesc {
		margin-top:0;
		padding-right:10px;

	}
	div#pageshavebeen {
		text-align:left;
	}
}
</style>



<div class="section-title">
  <div class="header-gradient">
    <div class="collectionTitle">
         <h1><?php echo mysql_result($collection_title, 0); ?></h1>
    </div>
    <div class="main">
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="collectionDesc"><?php echo mysql_result($collection_description, 0); ?></div>
            </div>
        </div>
    </div>
  </div>
</div>





<?php echo'<div>Public Show.php</div>' ?>
<br>
<?php echo $total_items." items returned"; ?>

<br><br>

<?php echo $n_collections_response; ?>

<br><br>



<?php

?>

<?php
while ($row = mysql_fetch_array($query_response)) {
  foreach($row as $key => $value) {
    echo "Key: $key; Value: $value</br>"; 
  }
  echo "<br><br>";
}

?>


<?php echo foot(); ?>
