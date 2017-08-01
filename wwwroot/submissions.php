<?php>
    include("APIDataManager.php");
    $dataManager = new APIDataManager();
    $dataManager->connect();
    $data = $dataManager->select("select * FROM Basement_Systems.Client ORDER BY date DESC;");
{
?>
<!doctype html>
<html lan="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <title>Treehouse Newsletter Signup Page</title>
    <meta name="viewport" content="wide=device-width">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto" type="text/css">
    <link rel="icon" href="https://dc69b531ebf7a086ce97-290115cc0d6de62a29c33db202ae565c.ssl.cf1.rackcdn.com/7/favicon.ico" type="image/ico">
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <link rel="stylesheet" href="substyles.css" type="text/css">
</head>
<body>
    <section>
  <h1>Submissions</h1>
  <div class="tbl-header">
    <table cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th>Subcribtion Date</th>
          <th>Email</th>
          <th>First Name</th>
          <th>LastName</th>
        </tr>
      </thead>
    </table>
  </div>
  <div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0">
      <tbody>
         <?php
           $i = 0;
           while ($row = $data[$i]) {
               //$class = ($i == 0) ? "" : "alt";
               echo "<tr>";
               echo "<td>".$row['date']."</td>";
               echo "<td>".$row['Email']."</td>";
               echo "<td>".$row['firstName']."</td>";
               echo "<td>".$row['lastName']."</td>";
               echo "</tr>";
               $i = $i + 1;
           }

        ?>
      </tbody>
    </table>
  </div>
</section>
</body>
<script>
    $(window).on("load resize ", function() {
  var scrollWidth = $('.tbl-content').width() - $('.tbl-content table').width();
  $('.tbl-header').css({'padding-right':scrollWidth});
}).resize();
</script>
</html>
<?php
}
?>