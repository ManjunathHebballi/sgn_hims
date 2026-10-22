<?php
require_once(__DIR__ . '/model/session_config.php');
include('model/dbconnect.php');

// Get visit_no, name, age, gender from GET or POST
$visit_no = isset($_GET['visit_no']) ? $_GET['visit_no'] : '';
$name = isset($_GET['name']) ? $_GET['name'] : '';
$age = isset($_GET['age']) ? $_GET['age'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';

// If not provided, you may want to fetch from DB using visit_no
// ...

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sample Barcode Print</title>
    <style>
        body { font-family: Arial, sans-serif; text-transform: uppercase;}
        .barcode-label {
            width: 320px;
            padding: 10px 0 0 0;
            margin: 0 auto;
            text-align: center;
        }
        .barcode {
            margin: 10px 0;
        }
        .info {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        @media print {
            body { margin: 0; }
            .barcode-label { box-shadow: none; border: none; }
            button { display: none; }
        }
    </style>
    <!-- JsBarcode CDN -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    <div class="barcode-label">
        <div class="info">
            <?php echo htmlspecialchars($name); ?> [<?php echo htmlspecialchars($age); ?>/<?php echo htmlspecialchars($gender); ?>]
        </div>
        <svg id="barcode"></svg>
        <div style="font-size:14px; margin-top:2px;">
            <?php echo htmlspecialchars($visit_no); ?>
        </div>
        <button onclick="window.print()" style="margin-top:10px;">Print</button>
    </div>
    <script>
        JsBarcode("#barcode", "<?php echo addslashes($visit_no); ?>", {
            format: "CODE128",
            width: 2,
            height: 60,
            displayValue: false,
            margin: 0
        });
    </script>
</body>
</html>
