<?php
// Handle file downloads
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  

if (isset($_GET['download'])) {
    $filename = basename($_GET['download']);
    $dir = '/home/shesayur/public_html/db';
    $filepath = $dir . '/' . $filename;
    
    // Security check - ensure file is in the specified directory
    if (realpath($filepath) !== false && strpos(realpath($filepath), realpath($dir)) === 0 && is_file($filepath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
}

$dir = '/home/shesayur/public_html/db';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Database Backup Files</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
    <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
        <!-- Sweet Alert-->
     <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
    <style>
         h1 {
            color: #333;
            font-weight: 700;
            /*margin-bottom: 10px;*/
        }
         p {
            color: #666;
            font-size: 14px;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        table {
            margin-bottom: 0;
        }
        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #eee;
        }
        tbody tr:hover {
            background-color: #f8f9ff;
            box-shadow: 0 2px 4px rgba(102, 126, 234, 0.1);
        }
        tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #333;
        }
        .file-icon {
            color: #667eea;
            margin-right: 8px;
            font-size: 16px;
        }
        .filename {
            font-weight: 500;
            word-break: break-word;
        }
        .date-time {
            color: #666;
            font-size: 13px;
        }
        .file-size {
            color: #666;
            font-size: 13px;
            text-align: right;
        }
        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }
        .btn-download:active {
            transform: translateY(0);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .empty-state i {
            font-size: 48px;
            color: #ddd;
            margin-bottom: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-value {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
     <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">


    

    

  <?php include ('leftbar.php')?>
  

        <!--**********************************
            Content body start
        ***********************************-->
        <div class="content-body">
            <!-- row -->



<div class="container-fluid">
    <div class="container">
        
            <div class="row mb-3">
        <div class="col-md-12">
             <h1><i class="fas fa-database"></i> Database Backup Files</h1>
            <p>Manage and download your database backup files</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

        <?php
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                $files = [];
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {
                        $filepath = $dir . '/' . $file;
                        if (is_file($filepath)) {
                            $files[] = [
                                'name' => $file,
                                'path' => $filepath,
                                'time' => filemtime($filepath),
                                'size' => filesize($filepath)
                            ];
                        }
                    }
                }
                closedir($dh);

                // Sort files by modification time (newest first)
                usort($files, function($a, $b) {
                    return $b['time'] - $a['time'];
                });

                if (count($files) > 0) {
                    $total_size = 0;
                    foreach ($files as $f) {
                        $total_size += $f['size'];
                    }

                    // Display stats
                    echo '<div class="stats">';
                    echo '<div class="stat-card">';
                    echo '<div class="stat-value">' . count($files) . '</div>';
                    echo '<div class="stat-label">Total Files</div>';
                    echo '</div>';
                    echo '<div class="stat-card">';
                    echo '<div class="stat-value">' . round($total_size / 1024 / 1024, 2) . '</div>';
                    echo '<div class="stat-label">Total Size (MB)</div>';
                    echo '</div>';
                    echo '</div>';

                    // Display table
                    echo '<div class="table-wrapper">';
                    echo '<table class="table table-hover">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th style="width: 10%"><i class="fas fa-arrow-down-1-9"></i> Sl No</th>';
                    echo '<th style="width: 40%"><i class="fas fa-file"></i> Filename</th>';
                    echo '<th style="width: 20%"><i class="fas fa-calendar"></i> Date & Time</th>';
                    echo '<th style="width: 15%;"><i class="fas fa-weight"></i> Size</th>';
                    echo '<th style="width: 15%; text-align: center;"><i class="fas fa-download"></i> Action</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                $sn=1;
                    foreach ($files as $file) {
                        $datetime = date("Y-m-d H:i:s", $file['time']);
                        $size = round($file['size'] / 1024/ 1024, 2); // Convert to KB
                        echo '<tr><td>'.$sn.'</td>';
                        
                        echo '<td><span class="file-icon"><i class="fas fa-database"></i></span><span class="filename">' . htmlspecialchars($file['name']) . '</span></td>';
                        echo '<td><span class="date-time">' . date("d.m.Y h:i:s A",strtotime($datetime)) . '</span></td>';
                        echo '<td><span class="file-size">' . number_format($size, 2) . ' MB</span></td>';
                        echo '<td style="text-align: center;">';
                        echo '<a href="?download=' . urlencode($file['name']) . '" class="btn-download">';
                        echo '<i class="fas fa-download"></i> Download';
                        echo '</a>';
                        echo '</td>';
                        echo '</tr>';
                        $sn++;
                    }

                    echo '</tbody>';
                    echo '</table>';
                    echo '</div>';
                } else {
                    echo '<div class="empty-state">';
                    echo '<i class="fas fa-inbox"></i>';
                    echo '<h5>No Files Found</h5>';
                    echo '<p>There are no database backup files in this directory.</p>';
                    echo '</div>';
                }
            } else {
                echo '<div class="alert alert-danger" role="alert">Cannot open directory.</div>';
            }
        } else {
            echo '<div class="alert alert-danger" role="alert">Directory does not exist.</div>';
        }
        ?>
    </div>
  </div>
</div>  </div>
        <!--**********************************
            Content body end
        ***********************************-->


        <!--**********************************
            Footer start
        ***********************************--></div>
        <?php include('footer.php') ?>
        <!--**********************************
            Footer end
        ***********************************-->

        <!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!--  vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


</body>

</html>