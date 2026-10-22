<?php 
require_once(__DIR__ . '/model/session_config.php');
include('model/dbconnect.php');
$newlogin = new userlogin;                  
    $con=$newlogin->dbconnect();
    $sql="SELECT c.*,d.dept_name,concat(doc.name,' ',doc.last_name) as doctor FROM central_opd c,department d,doctor doc WHERE c.dept_id=d.id and c.id='$_REQUEST[id]' and doc.id=c.doctor";
    $res=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Patient Token</title>
  <style>
    @media print {
      @page {
        size: 78mm auto; /* Half roll length approx */
        margin: 0;
      }
      body {
        margin: 0;
        font-family: Arial, sans-serif;
        font-size: 12px;
         text-transform: uppercase;
      }
    }

    .receipt {
      width: 78mm;
      margin: 0 ;
      padding: 5px;
      text-align: center;
    }

    .logo {
      margin-bottom: 5px;
    }

    .logo img {
      max-width: 50px;
      height: auto;
    }

    .header {
      font-weight: bold;
      font-size: 14px;
      text-transform: uppercase;
      margin-bottom: 5px;
    }

    .token {
      font-size: 22px;
      font-weight: bold;
      margin: 5px 0;
    }

    .datetime {
      font-size: 14px;
      margin-bottom: 5px;
    }

    .info {
      font-size: 14px;
      line-height: 1;
      margin-top: 5px;
      text-align: left;
    }

    .info div {
      margin: 2px 10px;
    }

    hr {
      margin: 5px 0;
      border-top: 1px dashed #000;
    }

    .spacer {
      height: 20mm; /* Adjust to push content to half-page */
    }
  </style>
</head>
<body onload="window.print()">
  <div class="receipt">
    <div class="logo">
      <img src="images/logo.png" alt="Logo">
    </div>
    <div class="header"><?= $_SESSION['college_name'] ?></div>
   <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; margin-left: 5px; margin-right: 5px">
  <div style="text-align: left; width: 50%;">
    Token :<span class="token"> <?= $row['token'] ?></span>
  </div>
  <div style="text-align: right; width: 50%;">
    <span class="datetime">
      <?= date("d.m.Y", strtotime($row['date'])) ?><br>
      <?= date("h:i:s A", strtotime($row['created_at'])) ?>
    </span>
  </div>
</div>
    </div>
    <hr>
    <div class="info">
      <div><strong>Doctor:</strong> <?= $row['doctor'] ?></div>
      <div><strong>Patient:</strong> <?= $row['name'] ?></div>
      <div><strong>Sex/Age:</strong> <?= $row['gender'] ?> / <?= $row['age'] ?></div>
    </div>
     <hr>
    <div class="spacer"></div>
    <hr>
  </div>
  <script>
    window.addEventListener('afterprint', function () {
        // Close the window after printing
        window.close();
      }, { once: true });
</script>
</body>
</html>
