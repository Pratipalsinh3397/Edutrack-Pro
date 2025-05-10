<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>

<?php include_once('includes/header.php');?>
<div class="banner banner5 text-center m-5">
	<div class="container">
	<h2 class="font-weight-bold">Notice</h2>
	</div>
</div>
<!--weelcome-->
<div class="welcome">
	<div class="container">
		<table border="1" class="table table-bordered mg-b-0">
    <?php
      $vid=$_GET['viewid'];
      $sql="SELECT * from tblpublicnotice where ID=:vid";
      $query = $dbh -> prepare($sql);
      $query->bindParam(':vid',$vid,PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);
      $cnt=1;
      if($query->rowCount() > 0)
      {
      foreach($results as $row)
      {               ?>
      <tr align="center" class="table-warning">
      <td colspan="4" style="font-size:20px;color:blue;" class="font-weight-bold">
      Notice</td></tr>
      <tr class="table-info">
          <th class="font-weight-bold">Notice Announced Date</th>
          <td class="font-weight-bold"><?php  echo $row->CreationDate;?></td>
        </tr>
          <tr class="table-info">
          <th class="font-weight-bold">Noitice Title</th>
          <td class="font-weight-bold"><?php  echo $row->NoticeTitle;?></td>
        </tr>
        <tr class="table-info">
          <th class="font-weight-bold">Message</th>
          <td class="font-weight-bold"><?php  echo $row->NoticeMessage;?></td>
          
        </tr>
        
        <?php $cnt=$cnt+1;}} ?>
      </table>
        </div>
</div>
<!--/welcome-->
<?php include_once('includes/footer.php');?>
<!--/copy-rights-->
	</body>
</html>
