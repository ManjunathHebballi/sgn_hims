<?php 
require_once(__DIR__ . '/model/session_config.php');
date_default_timezone_set("Asia/Kolkata");  
// Assuming $lab is available or include the class if not
include_once('model/labClass.php');
$lab = new lab();
$test_id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
$test_name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?php echo $_REQUEST['name']; ?></title>
  <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
  <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
  <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
  <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
  <link href="./css/style.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
  <link href="css/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.8/dist/purify.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
  <style>
    .summernote-smaller ~ .note-editor .note-toolbar .btn,
              .note-editor .note-toolbar .btn {
                padding: 0.15rem 0.4rem !important;
                font-size: 0.85rem !important;
                line-height: 1.2 !important;
                border-radius: 0.15rem !important;
              }
              .summernote-smaller ~ .note-editor .note-toolbar .note-btn-group,
              .note-editor .note-toolbar .note-btn-group {
                margin-right: 0.2rem !important;
              }
              .summernote-smaller ~ .note-editor .note-toolbar .note-btn,
              .note-editor .note-toolbar .note-btn {
                min-width: 1.5rem !important;
                min-height: 1.5rem !important;
              }
              </style>
</head>
<body>
<div id="main-wrapper">
  <?php  include('leftbar.php') ?>
  <div class="content-body">
    <div class="container-fluid">
        <div class="card">
        <div class="card-body">
      <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
          <h4 class="text-uppercase"><?php echo $_REQUEST['name']; ?></h4> </div>
      </div>
      
      <div class="row mb-3">
        <div class="col-12">
          <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-primary btn-sm mr-2 mb-2" id="addHeadingBtn">+ New Test Heading</button>
            <button class="btn btn-primary btn-sm mr-2 mb-2" id="addParameterBtn">+ Parameter</button>
            <button class="btn btn-primary btn-sm mr-2 mb-2" id="addFreeTextBtn">+ Free Text</button>
            <button class="btn btn-primary btn-sm mr-2 mb-2" id="addAntibioticBtn">+ Antibiotic</button>
            <button class="btn btn-primary btn-sm mb-2" id="addTableFormatBtn">+ Table Format</button>
          </div>
        </div>
      </div>
      <div class="row">
          <div class="col-md-12">
        
            <?php
          

            // Fetch all parameters/headings for this test id
            $parameters = $lab->getLabTestParameterById($test_id);
    //  print_r($parameters);
            // Group by type 'head'
            $heads = [];
            if ($parameters && is_array($parameters)) {
                foreach ($parameters as $param) {
                    // print_r($param);
                    if (isset($param['type'])) {
                        $heads[] = $param;
                    }
                }
            }
            // print_r($heads);
            ?>

            <div class="row" id="testCardContainer">
                <?php if (!empty($heads)): ?>
                    <?php foreach ($heads as $head): ?>
                        <div class="col-md-12 mb-3 test-card" 
                             data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>" 
                             data-position="<?= htmlspecialchars($head['position'] ?? 1) ?>">
                            <div class="lab_test_test_parameter p-2" style="background: #fff; border-radius: 6px; border: 1px solid #e3e3e3;">
                                <div class="d-flex align-items-center">
                                  
                                    <a style="margin-left: 20px; font-weight: 600; color: #222; text-decoration: none;"
                                       href="#"
                                       class="editBtn"
                                       data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                       data-type="<?= htmlspecialchars($head['type'] ?? '') ?>"
                                       data-name="<?= htmlspecialchars($head['name'] ?? $head['head'] ?? '') ?>"
                                       data-gender="<?= htmlspecialchars($head['gender'] ?? 'both') ?>"
                                       data-position="<?= htmlspecialchars($head['position'] ?? 1) ?>"
                                       data-default="<?= htmlspecialchars($head['is_print'] ?? 0) ?>"
                                       data-readonly="<?= htmlspecialchars($head['is_readonly'] ?? 0) ?>"
                                    >
                                        <?= ($head['name'] ?? $head['head'] ?? 'Heading') ?>
                                    </a>
                                    <div class="pull-right ml-auto d-flex align-items-center gap-2">
                                        <?php if (isset($head['type']) && $head['type'] === 'parameter'): ?>
                                            <a class="btn btn-mini btn-outline-success testRangeBtn mr-2"
                                               href="#"
                                               data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                               data-name="<?= htmlspecialchars($head['name'] ?? $head['head'] ?? 'SPECIMEN') ?>"
                                               data-toggle="modal"
                                               data-target="#testrangesModal"
                                               style="margin-right: 6px;">
                                                Test Ranges
                                            </a>
                                            <a class="btn btn-mini btn-outline-info resultOptionBtn mr-2"
                                               href="#"
                                               data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                               data-name="<?= htmlspecialchars($head['name'] ?? $head['head'] ?? 'SPECIMEN') ?>"
                                               data-toggle="modal"
                                               data-target="#resultoptionsModal"
                                               style="margin-right: 6px;">
                                                Result Options
                                            </a>
                                        <?php elseif (isset($head['type']) && $head['type'] === 'antibiotic'): ?>
                                            <a class="btn btn-mini btn-outline-secondary addAntibioticsBtn mr-2"
                                               href="#"
                                               data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                               style="margin-right: 6px;">
                                                Add Antibiotics
                                            </a>
                                            <a class="btn btn-mini btn-outline-warning addDataRowsBtn mr-2"
                                               href="#"
                                               data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                               data-name="<?= htmlspecialchars($test_name) ?>"
                                               style="margin-right: 6px;">
                                                Add Data Rows
                                            </a>
                                        <?php elseif (isset($head['type']) && $head['type'] === 'table_format'): ?>
                                         
                                            <a class="btn btn-mini btn-outline-warning addTableDataRowsBtn mr-2"
                                               href="#"
                                               data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                               data-name="<?= htmlspecialchars($test_name) ?>"
                                               style="margin-right: 6px;">
                                                Add Data Rows
                                            </a>
                                            <a class="btn btn-mini btn-outline-info resultOptionBtn mr-2"
                                               href="#"
                                               data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                               data-name="<?= htmlspecialchars($head['name'] ?? $head['head'] ?? 'SPECIMEN') ?>"
                                               data-toggle="modal"
                                               data-target="#resultoptionsModal"
                                               style="margin-right: 6px;">
                                                Result Options
                                            </a>
                                        <?php endif; ?>
                                        <a class="btn btn-mini btn-outline-primary editBtn mr-2"
                                           href="#"
                                           data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                           data-type="<?= htmlspecialchars($head['type'] ?? '') ?>"
                                           data-name="<?= htmlspecialchars($head['name'] ?? $head['head'] ?? '') ?>"
                                           data-gender="<?= htmlspecialchars($head['gender'] ?? 'both') ?>"
                                           data-position="<?= htmlspecialchars($head['position'] ?? 1) ?>"
                                           data-default="<?= htmlspecialchars($head['is_print'] ?? 0) ?>"
                                           data-readonly="<?= htmlspecialchars($head['is_readonly'] ?? 0) ?>"
                                           style="margin-right: 6px;">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a class="btn btn-mini btn-danger deleteBtn"
                                           href="#"
                                           data-id="<?= htmlspecialchars($head['id'] ?? $head['test_parameter_id'] ?? '') ?>"
                                           data-type="<?= htmlspecialchars($head['type'] ?? '') ?>"
                                           data-name="<?= htmlspecialchars($head['name'] ?? $head['head'] ?? '') ?>"
                                           data-confirm="This parameter will be permanantly deleted from your system. Are you sure to proceed?"
                                           rel="nofollow"
                                           style="margin-right: 0;">
                                            <i class="fa fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <?php if (isset($head['type']) && $head['type'] === 'antibiotic'): ?>
                                    <div class="mt-3">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Antibiotic</th>
                                                        <th>Inhibitation Zone</th>
                                                        <th>Standard Zone</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="testAntibioticBody">
                                                    <?php
                                                     $result=$lab->getTestAntibiotic($head['id'] ?? $head['test_parameter_id'] ?? '');
                                                     if(mysqli_num_rows($result)>0) {
                                                      while($row=mysqli_fetch_assoc($result))
                                                      {
                                                          echo "<tr>";
                                                          echo "<td>".htmlspecialchars($row['heading1'])."</td>";
                                                          echo "<td>".htmlspecialchars($row['heading2'])."</td>";
                                                          echo "<td>".htmlspecialchars($row['heading3'])."</td>";
                                                          echo "<td>";  
                                                          echo "<a class='btn btn-mini btn-danger deleteTableRowBtn' href='#' data-id='".htmlspecialchars($row['id'])."' title='Delete' data-confirm='This parameter will be permanantly deleted from your system. Are you sure to proceed?' rel='nofollow'><i class='fa fa-trash'></i> Delete</a>";
                                                          echo "</td>"; 
                                                          echo "</tr>";
                                                      }
                                                     }
                                                     else
                                                     {
                                                        echo "<tr>";
                                                        echo "<td colspan='4' class='text-center text-muted'>No data available</td>";
                                                        echo "</tr>";
                                                     }
                                                     ?> 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif;
                                if (isset($head['type']) && $head['type'] === 'table_format'): ?>
                                    <div class="mt-3">
                                          <div class="d-flex justify-content-between table-responsive">
                                            <table class="table table-bordered table-sm mb-0">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <?php 
                                                        $result=$lab->getTableFormat($head['id'] ?? $head['test_parameter_id'] ?? '');
                                                        $tab_row=0;
                                                        while($row=mysqli_fetch_assoc($result))
                                                        {
                                                            echo "<th>".htmlspecialchars($row['column_name'])."</th>";
                                                            $tab_row++;
                                                        }
                                                        ?>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="table_data_row_body">
                                                    <?php
                                                    $result=$lab->getTableDataRow($head['id'] ?? $head['test_parameter_id'] ?? '');
                                                    while($row=mysqli_fetch_assoc($result))
                                                    {
                                                        echo "<tr>";
                                                        echo "<td>".htmlspecialchars($row['data_name'])."</td>";
                                                        for($i=1;$i<$tab_row;$i++)
                                                        {
                                                            echo "<td></td>";
                                                           
                                                        }
                                                        echo "<td>";
                                                        echo "<a class='btn btn-mini btn-danger deleteBtn' href='#' data-id='".htmlspecialchars($row['id'])."' title='Delete' data-confirm='This parameter will be permanantly deleted from your system. Are you sure to proceed?' rel='nofollow'><i class='fa fa-trash'></i> Delete</a>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">No test headings found for this test.</div>
                    </div>
                <?php endif; ?>
            </div>
</div>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- Test Heading Modal -->
<div class="modal fade" id="headingModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Test Heading</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form new_test_heading" id="new_test_heading" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <div class="form-group">
            <label for="test_heading_name">Heading name</label>
            <input type="text" class="form-control" id="test_heading_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="test_heading_gender">Gender</label>
            <select class="form-control" id="test_heading_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="test_heading_position">Position</label>
            <input readonly="readonly" class="form-control numeric integer optional input-mini" type="number" step="1" value="7" name="position" id="test_heading_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="test_heading_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <input type="hidden" name="test_id" value="<?php echo $_REQUEST['id']; ?>">
          <input type="hidden" name="test_heading_name" value="<?php echo $_REQUEST['name']; ?>">
          <button type="submit" name="submit" value="testHeading" class="btn btn-success" disable_with="Saving..." data-disable-with="Save">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Parameter Modal -->
<div class="modal fade" id="parameterModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Parameter</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form test_parameter_form" id="new_test_parameter" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <div class="form-group">
            <label for="test_parameter_name">name</label>
            <input type="text" class="form-control" id="test_parameter_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="test_parameter_unit">Unit</label>
            <input type="text" class="form-control" id="test_parameter_unit" name="unit" maxlength="255">
          </div>
          <div class="form-group">
            <label for="test_parameter_method">Method</label>
            <input type="text" class="form-control" id="test_parameter_method" name="method" maxlength="255">
          </div>
          <div class="form-group">
            <label for="test_parameter_gender">Gender</label>
            <select class="form-control" id="test_parameter_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="test_parameter_position">Position</label>
            <input readonly="readonly" class="form-control numeric integer optional input-mini" type="number" step="1" value="7" name="position" id="test_parameter_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="test_parameter_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <br>
          <input type="hidden" name="test_id" value="<?php echo $_REQUEST['id']; ?>">
          <input type="hidden" name="test_parameter_name" value="<?php echo $_REQUEST['name']; ?>">
        <button type="submit" name="submit" value="testParameter" class="btn btn-success" data-disable-with="Saving..">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Free Text Modal -->
<div class="modal fade" id="freeTextModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form class="simple_form form-horizontal" id="new_lab_test_free_text" novalidate="novalidate" action="controller/processLab.php" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
        <div class="modal-header">  
          <h5 class="modal-title">New Free Text</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body page-format-body">
          <div class="form-group">
            <label for="lab_test_free_text_lab_test_free_text_body_attributes_body">Body</label>
            <textarea class="form-control summernote summernote-smaller span10 html-editor page-format" rows="13" maxlength="65535" name="body" id="lab_test_free_text_lab_test_free_text_body_attributes_body"></textarea>
          </div>
          <div class="form-group">
            <label for="lab_test_free_text_gender">Gender</label>
            <select class="form-control input-medium" id="lab_test_free_text_gender" name="gender">
              <option selected="selected" value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="lab_test_free_text_position">Position</label>
            <input readonly="readonly" class="form-control numeric integer optional input-mini" type="number" step="1" value="7" name="position" id="lab_test_free_text_position">
          </div>
         
        <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="lab_test_free_text_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="free_text_readonly" id="lab_test_free_text_readonly"  value="1" checked>
                    <label class="form-check-label" for="option_header">Free Text Readonly</label>
                </div>
          </div>
          </div> 
        <div class="modal-footer">
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_free_text_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
        <button type="submit" name="submit" value="testFreeText" class="btn btn-success" data-disable-with="Saving..">Save</button>

          <a class="btn" data-dismiss="modal" href="#">Close</a>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Antibiotic Modal -->
<div class="modal fade" id="antibioticModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Antibiotic</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form form-horizontal" id="new_test_group" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <div class="form-group">
            <label for="test_group_name">name</label>
            <input type="text" class="form-control" id="test_group_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="test_group_gender">Gender</label>
            <select class="form-control" id="test_group_gender" name="gender">
              <option selected="selected" value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="test_group_position">Position</label>
            <input readonly="readonly" class="form-control numeric integer optional input-mini" type="number" step="1" value="2" name="position" id="test_group_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="test_group_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <br>
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_free_text_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" name="submit" value="antibiotic" class="btn btn-success" data-disable-with="Saving..">Save</button>
         </form>
      </div>
    </div>
  </div>
</div>
<!-- Table Format Modal -->
<div class="modal fade" id="tableFormatModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">New Table Format</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form form-horizontal" id="new_table_format" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <div class="form-group">
            <label for="table_format_name">Table Name</label>
            <input type="text" class="form-control" id="table_format_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="table_format_columns">Columns (comma separated)</label>
            <input type="text" class="form-control" id="table_format_columns" name="columns" required placeholder="e.g. Column1, Column2, Column3">
          </div>
          <div class="form-group">
            <label for="table_format_gender">Gender</label>
            <select class="form-control" id="table_format_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="table_format_position">Position</label>
            <input readonly="readonly" class="form-control numeric integer optional input-mini" type="number" step="1" value="7" name="position" id="table_format_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="table_format_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_table_format_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <button type="submit" name="submit" value="tableFormat" class="btn btn-success" data-disable-with="Saving..">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Edit Test Heading Modal -->
<div class="modal fade" id="editHeadingModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Test Heading</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form edit_test_heading" id="edit_test_heading" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <input type="hidden" name="id" id="edit_heading_id">
          <input type="hidden" name="test_id" value="<?php echo $_REQUEST['id']; ?>">
          <input type="hidden" name="test_heading_name" value="<?php echo $_REQUEST['name']; ?>">
          <div class="form-group">
            <label for="edit_heading_name">Heading name</label>
            <input type="text" class="form-control" id="edit_heading_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="edit_heading_gender">Gender</label>
            <select class="form-control" id="edit_heading_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_heading_position">Position</label>
            <input class="form-control numeric integer optional input-mini" type="number" step="1" name="position" id="edit_heading_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="edit_heading_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <input type="hidden" name="readonly"  value="0">
          <button type="submit" name="submit" value="editTestHeading" class="btn btn-success" disable_with="Saving..." data-disable-with="Save">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Parameter Modal -->
<div class="modal fade" id="editParameterModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Parameter</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form edit_test_parameter_form" id="edit_test_parameter" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <input type="hidden" name="id" id="edit_parameter_id">
          <input type="hidden" name="test_id" value="<?php echo $_REQUEST['id']; ?>">
          <input type="hidden" name="test_parameter_name" value="<?php echo $_REQUEST['name']; ?>">
          <div class="form-group">
            <label for="edit_parameter_name">Name</label>
            <input type="text" class="form-control" id="edit_parameter_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="edit_parameter_unit">Unit</label>
            <input type="text" class="form-control" id="edit_parameter_unit" name="unit" maxlength="255">
          </div>
          <div class="form-group">
            <label for="edit_parameter_method">Method</label>
            <input type="text" class="form-control" id="edit_parameter_method" name="method" maxlength="255">
          </div>
          <div class="form-group">
            <label for="edit_parameter_gender">Gender</label>
            <select class="form-control" id="edit_parameter_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_parameter_position">Position</label>
            <input class="form-control numeric integer optional input-mini" type="number" step="1" name="position" id="edit_parameter_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="edit_parameter_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <input type="hidden" name="readonly"  value="0">
          <button type="submit" name="submit" value="editTestParameter" class="btn btn-success" data-disable-with="Saving..">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Free Text Modal -->
<div class="modal fade" id="editFreeTextModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form class="simple_form form-horizontal" id="edit_lab_test_free_text" novalidate="novalidate" action="controller/processLab.php" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
        <div class="modal-header">  
          <h5 class="modal-title">Edit Free Text</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body page-format-body">
          <input type="hidden" name="id" id="edit_freetext_id">
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_free_text_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <div class="form-group">
            <label for="edit_freetext_body">Body</label>
            <textarea class="form-control summernote summernote-smaller span10 html-editor page-format" rows="13" maxlength="65535" name="body" id="edit_freetext_body"></textarea>
          </div>
          <div class="form-group">
            <label for="edit_freetext_gender">Gender</label>
            <select class="form-control input-medium" id="edit_freetext_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_freetext_position">Position</label>
            <input class="form-control numeric integer optional input-mini" type="number" step="1" name="position" id="edit_freetext_position">
          </div>
         
        <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="edit_freetext_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="readonly" id="edit_freetext_readonly"  value="1">
                    <label class="form-check-label" for="option_header">Free Text Readonly</label>
                </div>
          </div>
          </div> 
        <div class="modal-footer">
        <button type="submit" name="submit" value="editTestFreeText" class="btn btn-success" disable_with="Saving..." data-disable-with="Save">Save Changes</button>

          <a class="btn" data-dismiss="modal" href="#">Close</a>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Antibiotic Modal -->
<div class="modal fade" id="editAntibioticModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Antibiotic</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form edit_test_antibiotic" id="edit_test_antibiotic" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <input type="hidden" name="id" id="edit_antibiotic_id">
          <input type="hidden" name="test_id" value="<?php echo $_REQUEST['id']; ?>">
          <input type="hidden" name="test_antibiotic_name" value="<?php echo $_REQUEST['name']; ?>">
          <div class="form-group">
            <label for="edit_antibiotic_name">Name</label>
            <input type="text" class="form-control" id="edit_antibiotic_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="edit_antibiotic_gender">Gender</label>
            <select class="form-control" id="edit_antibiotic_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_antibiotic_position">Position</label>
            <input class="form-control numeric integer optional input-mini" type="number" step="1" name="position" id="edit_antibiotic_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="edit_antibiotic_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <input type="hidden" name="readonly"  value="0">
          <button type="submit" name="submit" value="editTestAntibiotic" class="btn btn-success" disable_with="Saving..." data-disable-with="Save">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Table Format Modal -->
<div class="modal fade" id="editTableFormatModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Table Format</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form class="simple_form edit_test_table_format" id="edit_test_table_format" action="controller/processLab.php" accept-charset="UTF-8" data-remote="true" method="post">
          <input type="hidden" name="id" id="edit_tableformat_id">
          <input type="hidden" name="test_id" value="<?php echo $_REQUEST['id']; ?>">
          <input type="hidden" name="test_table_format_name" value="<?php echo $_REQUEST['name']; ?>">
          <div class="form-group">
            <label for="edit_tableformat_name">Name</label>
            <input type="text" class="form-control" id="edit_tableformat_name" name="name" required maxlength="255">
          </div>
          <div class="form-group">
            <label for="edit_tableformat_gender">Gender</label>
            <select class="form-control" id="edit_tableformat_gender" name="gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_tableformat_position">Position</label>
            <input class="form-control numeric integer optional input-mini" type="number" step="1" name="position" id="edit_tableformat_position">
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="edit_tableformat_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
          <input type="hidden" name="readonly"  value="0">
          <button type="submit" name="submit" value="editTestTableFormat" class="btn btn-success" disable_with="Saving..." data-disable-with="Save">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Generic Edit Modal (Fallback) -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" action="controller/processLab.php" method="post">
        <div class="modal-header">
          <h5 class="modal-title">Edit Item</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="edit_id">
          <input type="hidden" name="type" id="edit_type">
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <div class="form-group">
            <label for="edit_name">Name</label>
            <input type="text" class="form-control" name="name" id="edit_name" required>
          </div>
          <div class="form-group">
            <label for="edit_gender">Gender</label>
            <select class="form-control" name="gender" id="edit_gender">
              <option value="both">All</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_position">Position</label>
            <input type="number" class="form-control" name="position" id="edit_position" min="1" step="1" required>
          </div>
          <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="default_load" id="edit_default_load" checked value="1">
                    <label class="form-check-label" for="option_header">Default Loading</label>
                </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="submit" value="editTestItem" class="btn btn-success">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Single Modal for Test Ranges (Bootstrap 4 compatible) -->
<div class="modal fade" id="testrangesModal" tabindex="-1" role="dialog" aria-labelledby="testrangesLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="testrangesLabel">
          Test Ranges of <span class="text-primary" id="testrangesModalName"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body" >
            <button type="button" class="btn btn-primary mb-3" id="addTestRangeBtn">
              <i class="fa fa-plus"></i> New Test Range
            </button>
            <div class='table-responsive'>
            <table class='table table-bordered table-striped display text-center'>
            <thead class='thead-dark'><tr>
                <th>Reference range</th>
                <th>Gender</th>
                <th>Limit</th>
                <th>Critical Limit</th>
                <th>Normal value</th>
                <th>Special condition</th>
                <th>Action</th>
                </tr></thead>
                <tbody id="testrangesModalBody">
            
            
            </tbody></table></div>
        <!-- Content will be loaded dynamically -->
      </div>
      <div class="modal-footer">
        <input type="hidden" name="parameter_id" id="test_parameter_id">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Add Test Range Modal (static, Bootstrap 4) -->
<div class="modal fade" id="testrangefieldform" tabindex="-1" role="dialog" aria-labelledby="testrangefieldformLabel" aria-hidden="true">
  <div class="modal-dialog" style="width: 760px; max-width: 100%;" role="document">
    <div class="modal-content">
      <div class="modal-header">
           <h5 class="modal-title" id="testrangefieldformLabel">
          Test Ranges of <span class="text-primary" id="testrangefieldformName"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body" id="testrangefieldformBody">
        <!-- Place your static form HTML here -->
        <form class="simple_form new_test_range" id="new_test_range" action="controller/processLab.php" accept-charset="UTF-8" method="post">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_range_lower_limit">Lower limit</label>
                <input class="form-control" type="text" name="lower_limit" id="test_range_lower_limit">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_range_upper_limit">Upper limit</label>
                <input class="form-control" type="text" name="upper_limit" id="test_range_upper_limit">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_range_critical_lower_limit">Critical lower limit</label>
                <input class="form-control" type="text" name="critical_lower_limit" id="test_range_critical_lower_limit">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_range_critical_upper_limit">Critical upper limit</label>
                <input class="form-control" type="text" name="critical_upper_limit" id="test_range_critical_upper_limit">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_range_reference_range_id">Reference Range</label>
                <select class="form-control" name="reference_range_id" id="test_range_reference_range_id">
                  <option value="0">Any</option>
                 <?php 
                 $data1=$lab->getLabReference();
                 while($row1=mysqli_fetch_assoc($data1))
                 {
                 ?>
                 <option value="<?= $row1['id']?>"><?= $row1['reference_name']?></option>
                 <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="test_range_gender">Gender</label>
                <select class="form-control" name="gender" id="test_range_gender">
                  <option value="both">All</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="test_range_normal_value">Normal value</label>
                <textarea rows="3" class="form-control" maxlength="65535" name="normal_value" id="test_range_normal_value"></textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="test_range_abnormal_condition">Special condition</label>
                <textarea class="form-control" maxlength="65535" name="abnormal_condition" id="test_range_abnormal_condition"></textarea>
              </div>
            </div>
          </div>
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_free_text_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="parameter_id" id="test_range_parameter_id" value="">
        
          <button type="submit" name="submit" value="test_range" class="btn btn-success">Submit</button>
        </form>
      </div>
      <div class="modal-footer">
        <a class="btn" data-dismiss="modal" href="#">Close</a>
      </div>
    </div>
  </div>
</div>
<!-- Result Option Modal (Bootstrap 4 style, hidden by default) -->
<div class="modal fade" id="resultoptionsModal" tabindex="-1" role="dialog" aria-labelledby="resultoptionsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="resultoptionsLabel">
          Result options of <span class="text-primary" id="resultoptionsModalName"></span>
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <div class="d-flex justify-content-end mb-2">
          <button class="btn btn-primary btn-sm" id="addResultOptionBtn" data-toggle="modal" data-target="#addResultOptionModal">
            <i class="fa fa-plus"></i> New Result Option
          </button>
          </div>
        <div class="table-responsive">
          <table class="table table-bordered table-striped text-center" id="resultoptiontable">
            <thead class="thead-dark">
              <tr>
                <th>Option</th>
                <th>Highlight Result</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="result_option_table_body">
              <!-- Result options will be loaded here dynamically -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="testParameterId">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Add Result Option Modal -->
<div class="modal fade" id="addResultOptionModal" tabindex="-1" role="dialog" aria-labelledby="addResultOptionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addResultOptionModalLabel">
                    Result options of <span class="text-primary" id="addResultOptionModalName"></span>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                </div>
                <div class="modal-body">
                  <form id="new_result_option" action="controller/processLab.php" class="simple_form form-horizontal" method="post" autocomplete="off">
                    <div class="form-group">
                      <label for="result_option_option" class="required">Option</label>
                      <input type="text" class="form-control" id="result_option_option" name="option" maxlength="255" required>
                    </div>
                    <!-- <div class="form-group">
                      <label for="result_option_position">Position</label> -->
                      <input type="hidden" class="form-control" id="result_option_position" name="position" step="1" min="1" value="1">
                    <!-- </div> -->
                    <div class="form-group form-check">
                      <input type="checkbox" class="form-check-input" id="result_bold" name="result_bold" value="1">
                      <label class="form-check-label" for="result_bold">Result Highlight</label>
                    </div>
                    <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="test_free_text_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="test_parameter_id" id="testParameter_id">
                    <button type="submit" name="submit" value="add_test_result" class="btn btn-primary">Save</button>
                  </form>
                </div>
                <div class="modal-footer">
                  <a class="btn" data-dismiss="modal" href="#">Close</a>
                </div>
              </div>
            </div>
          </div>
<!-- Add Data Rows Modal -->
<div class="modal fade" id="testGroupHeadingModal" tabindex="-1" role="dialog" aria-labelledby="testGroupHeadingLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 400px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="testGroupHeadingLabel">New Row</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
      </div>
      <div class="modal-body">
        <form class="simple_form form-horizontal" id="new_test_group_row" action="controller/processLab.php" accept-charset="UTF-8" method="post">
          <input type="hidden" name="parameter_id" id="test_group_row_parameter_id" value="<?= $_REQUEST['id']?>">
          <input type="hidden" name="test_name" id="test_group_row_test_name" value="<?= $_REQUEST['name']?>">
          <div class="form-group">
            <label for="test_group_row_heading1">Antibiotics</label>
            <input class="form-control" maxlength="255" type="text" name="heading1" id="test_group_row_heading1">
          </div>
          <div class="form-group">
            <label for="test_group_row_heading2">Inhibition zone (in mm)</label>
            <input class="form-control" maxlength="255" type="text" name="heading2" id="test_group_row_heading2">
          </div>
          <div class="form-group">
            <label for="test_group_row_heading3">Std. Zone (in mm)</label>
            <input class="form-control" maxlength="255" type="text" name="heading3" id="test_group_row_heading3">
          </div>
          <button type="submit" name="submit" value="add_test_group_row" class="btn btn-primary float-right" data-disable-with="Saving..">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Table Data Rows Modal -->
<div class="modal fade" id="tableDataRowsModal" tabindex="-1" role="dialog" aria-labelledby="tableDataRowsModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 400px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tableDataRowsModalLabel">New Table Data Row</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
      </div>
      <div class="modal-body">
        <form class="simple_form form-horizontal" id="new_table_data_row" action="controller/processLab.php" accept-charset="UTF-8" method="post">
          <input type="hidden" name="parameter_id" id="table_data_row_parameter_id">
          <input type="hidden" name="test_name" id="table_data_row_test_name">
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_free_text_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <div class="form-group">
            <label for="table_data_row_antibiotic">Data name</label>
            <input class="form-control" maxlength="255" type="text" name="data_name" id="table_data_row_antibiotic" required>
          </div>
          <div class="form-group">
            <label for="table_data_row_position">Position</label>
            <input class="form-control numeric integer optional input-mini" type="number" step="1" value="10" name="position" id="table_data_row_position">
          </div>
          <button type="submit" name="submit" value="add_table_data_row" class="btn btn-primary float-right" data-disable-with="Saving..">Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Antibiotic List Modal -->
<div class="modal fade" id="antibioticListModal" tabindex="-1" role="dialog" aria-labelledby="antibioticListLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 800px;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="antibioticListLabel">Antibiotic Lists</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
      </div>
      <div class="modal-body">
        <form id="antibioticListForm" action="controller/processLab.php" method="post">
          <div class="container-fluid">
            <div class="row" id="antibioticCheckboxContainer">
              <!-- Checkboxes will be loaded here by JS -->
            </div>
          </div>
          <input type="hidden" name="parameter_id" id="antibioticListParameterId">
          <input type="hidden" name="test_name" id="antibioticListTestName">
          <button type="submit" name="submit" value="add_antibiotic_list" class="btn btn-info float-right" data-disable-with="Adding...">Add</button>
        </form>
      </div>
      <div class="modal-footer">
        <a class="btn btn-secondary" data-dismiss="modal" href="#">Close</a>
      </div>
    </div>
  </div>
</div>

<!-- Edit Test Range Modal -->
<div class="modal fade" id="editTestRangeModal" tabindex="-1" role="dialog" aria-labelledby="editTestRangeLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editTestRangeLabel">Edit Test Range</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
      </div>
      <div class="modal-body">
        <form id="edit_test_range_form" method="post" action="controller/processLab.php">
          <input type="hidden" name="id" id="edit_range_id">
          <input type="hidden" name="test_id" value="<?php echo htmlspecialchars($_REQUEST['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <input type="hidden" name="test_name" value="<?php echo htmlspecialchars($_REQUEST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <div class="form-group">
            <label>Reference Range</label>
            <select class="form-control" name="reference_range_id" id="edit_reference_range_id">
              <option value="0">Any</option>
               <?php 
                 $data1=$lab->getLabReference();
                 while($row1=mysqli_fetch_assoc($data1))
                 {
                 ?>
                 <option value="<?= $row1['id']?>"><?= $row1['reference_name']?></option>
                 <?php } ?>
                </select>
            </select>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Gender</label>
              <select class="form-control" name="gender" id="edit_gender">
                <option value="both">All</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
              </select>
            </div>
            <div class="form-group col-md-6">
              <label>Unit</label>
              <input type="text" class="form-control" id="edit_unit" disabled>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Lower limit</label>
              <input class="form-control" type="text" name="lower_limit" id="edit_lower_limit">
            </div>
            <div class="form-group col-md-6">
              <label>Upper limit</label>
              <input class="form-control" type="text" name="upper_limit" id="edit_upper_limit">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Critical lower limit</label>
              <input class="form-control" type="text" name="critical_lower_limit" id="edit_critical_lower_limit">
            </div>
            <div class="form-group col-md-6">
              <label>Critical upper limit</label>
              <input class="form-control" type="text" name="critical_upper_limit" id="edit_critical_upper_limit">
            </div>
          </div>
          <div class="form-group">
            <label>Normal value</label>
            <textarea rows="3" class="form-control" name="normal_value" id="edit_normal_value"></textarea>
          </div>
          <div class="form-group">
            <label>Special condition</label>
            <textarea class="form-control" name="abnormal_condition" id="edit_abnormal_condition"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" name="submit" value="update_test_range">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="editResultOptionModal" tabindex="-1" role="dialog" aria-labelledby="editResultOptionLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editResultOptionLabel">
          Edit Result Option
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
      </div>
       <form id="editResultOptionForm" action="controller/processLab.php" method="post">
      <div class="modal-body">
       
          <input type="hidden" id="editResultOptionId" name="resid">
          <div class="form-group">
            <label for="editResultOption">Option</label>
            <input type="text" class="form-control" id="editResultOption" name="option" required>
          </div>
          <div class="form-group">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="editHighlightResult" name="highlight_result" value=1>
              <label class="custom-control-label" for="editHighlightResult">Highlight Result</label>
            </div>
          </div>
      
      </div>
      <div class="modal-footer">
          <input type="hidden" name="id" value="<?= $_REQUEST['id']?>">
          <input type="hidden" name="name" value="<?= $_REQUEST['name']?>">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" name="submit" value="update_result_option" class="btn btn-primary" id="updateResultOptionBtn">Update</button>
      </div>
        </form>
    </div>
  </div>
</div>
<!-- Scripts -->
<script src="./vendor/global/global.min.js"></script>
<script src="./js/quixnav-init.js"></script>
<script src="./js/custom.min.js"></script>
<script src="css/sweetalert2/sweetalert2.min.js"></script>
<!-- <script src="js/sweet-alerts.init.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>
<script>
$(function() {
  // Show/hide section form
  $('#toggleSectionBtn').click(function() {
    $('#sectionForm')[0].reset();
    $('#sectionCollapse').collapse('show');
  });
  $('#closeSectionBtn').click(function() {
    $('#sectionCollapse').collapse('hide');
  });
  // Show modals
  function getNextPosition() {
    return $('#testCardContainer .test-card').length + 1;
  }

  $('#addHeadingBtn').click(function() {
    $('#test_heading_position').val(getNextPosition());
    $('#headingModal').modal('show');
  });
  $('#addParameterBtn').click(function() {
    $('#test_parameter_position').val(getNextPosition());
    $('#parameterModal').modal('show');
  });
  $('#addFreeTextBtn').click(function() {
    $('#lab_test_free_text_position').val(getNextPosition());
    $('#freeTextModal').modal('show');
  });
  $('#addAntibioticBtn').click(function() {
    $('#test_group_position').val(getNextPosition());
    $('#antibioticModal').modal('show');
  });
  $('#addTableFormatBtn').click(function() {
    $('#table_format_position').val($('#testCardContainer .test-card').length + 1);
    $('#tableFormatModal').modal('show');
  });

  // Summernote init
  var isPasting = false;
  $('#lab_test_free_text_lab_test_free_text_body_attributes_body').summernote({
    height: 250,
    toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['fontname','strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']],
      ['insert', ['link', 'picture', 'video', 'table']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ],
    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather', 'Times New Roman', 'Verdana'],
    callbacks: {
      onPaste: function(e) {
        if (isPasting) return;
        isPasting = true;
        e.preventDefault();
        var clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
        var htmlData = clipboardData.getData('text/html');
        var textData = clipboardData.getData('text');
        var pasteContent = htmlData || textData;
        // Sanitize HTML, allow only safe tags (including table)
        var clean = DOMPurify.sanitize(pasteContent, {
          ALLOWED_TAGS: ['table', 'thead', 'tbody', 'tr', 'td', 'th', 'b', 'strong', 'i', 'u', 'ul', 'ol', 'li', 'p', 'br', 'span'],
          ALLOWED_ATTR: ['colspan', 'rowspan']
        });
        $('#lab_test_free_text_lab_test_free_text_body_attributes_body').summernote('pasteHTML', clean);
        isPasting = false;
      }
    }
  });

  // Delete button
  $(document).on('click', '.deleteBtn', function() {
    var $btn = $(this);
    var id = $btn.data('id');
    var type = $btn.data('type');
    var name = $btn.data('name');
    if (!id || !type) {
      alert('Missing parameter id or type.');
      return;
    }
    Swal.fire({
      title: 'Are you sure?',
      text: 'Delete "' + name + '"?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'ajax.php',
          type: 'POST',
          data: {
            page: 'delete_test_item',
            id: id,
            type: type
          },
          success: function(response) {
            $btn.closest('.col-md-12').remove();
          },
          error: function(xhr, status, error) {
            alert('Delete failed: ' + error);
          }
        });
      }
    });
  });

  // Edit button - handles different types of test items
  $(document).on('click', '.editBtn', function() {
    var $btn = $(this);
    var id = $btn.data('id');
    var type = $btn.data('type');
    var name = $btn.data('name');
    var gender = $btn.data('gender') || 'both';
    var position = $btn.data('position') || 1;
    var defaults = $btn.data('default') || 1;
    var readonly = $btn.data('readonly') || 1;
    
    // First, fetch the current data for the item
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {
        page: 'get_test_item_data',
        id: id,
        type: type
      },
      success: function(response) {
        try {
          var data = JSON.parse(response);
          
          // Populate the appropriate modal based on type
          switch(type) {
            case 'head':
              $('#edit_heading_id').val(id);
              $('#edit_heading_name').val(data.name || name);
              $('#edit_heading_gender').val(data.gender || gender);
              $('#edit_heading_position').val(data.position || position);

              // Set the value and checked state for default_load
              var defaultVal = (typeof data.defaults !== 'undefined') ? data.defaults : defaults;
            //   $('#edit_heading_default_load').val(defaultVal);
              if (parseInt(defaultVal) === 1) {
                $('#edit_heading_default_load').prop('checked', true);
              } else {
                $('#edit_heading_default_load').prop('checked', false);
              }
              
              

              $('#editHeadingModal').modal('show');
              break;
              
            case 'parameter':
              $('#edit_parameter_id').val(id);
              $('#edit_parameter_name').val(data.name || name);
              $('#edit_parameter_unit').val(data.unit || '');
              $('#edit_parameter_method').val(data.method || '');
              $('#edit_parameter_gender').val(data.gender || gender);
              $('#edit_parameter_position').val(data.position || position);
              var defaultVal = (typeof data.defaults !== 'undefined') ? data.defaults : defaults;
            //   alert(defaultVal)
            //   $('#edit_parameter_default_load').val(defaultVal);
              if (parseInt(defaultVal) === 1) {
                $('#edit_parameter_default_load').prop('checked', true);
              } else {
                $('#edit_parameter_default_load').prop('checked', false);
              }
              $('#editParameterModal').modal('show');
              break;
              
            case 'free_text':
              $('#edit_freetext_id').val(id);
              $('#edit_freetext_body').val(data.name || name);
              $('#edit_freetext_gender').val(data.gender || gender);
              $('#edit_freetext_position').val(data.position || position);
              var defaultVal = (typeof data.defaults !== 'undefined') ? data.defaults : defaults;
            //   $('#edit_freetext_default_load').val(defaultVal);
              if (parseInt(defaultVal) === 1) {
                $('#edit_freetext_default_load').prop('checked', true);
              } else {
                $('#edit_freetext_default_load').prop('checked', false);
              }
              
              var readonlyVal = (typeof data.readonly !== 'undefined') ? data.readonly : readonly;
            //   $('#edit_freetext_default_load').val(defaultVal);
              if (parseInt(readonlyVal) === 1) {
                $('#edit_freetext_readonly').prop('checked', true);
              } else {
                $('#edit_freetext_readonly').prop('checked', false);
              }
              
              // Initialize summernote for the edit modal
              $('#edit_freetext_body').summernote({
                height: 200,
                toolbar: [
      ['style', ['bold', 'italic', 'underline', 'clear']],
      ['font', ['fontname','strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['height', ['height']],
      ['insert', ['link', 'picture', 'video', 'table']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ],
    fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Merriweather', 'Times New Roman', 'Verdana'],
    callbacks: {
      onPaste: function(e) {
        if (isPasting) return;
        isPasting = true;
        e.preventDefault();
        var clipboardData = (e.originalEvent || e).clipboardData || window.clipboardData;
        var htmlData = clipboardData.getData('text/html');
        var textData = clipboardData.getData('text');
        var pasteContent = htmlData || textData;
        // Sanitize HTML, allow only safe tags (including table)
        var clean = DOMPurify.sanitize(pasteContent, {
          ALLOWED_TAGS: ['table', 'thead', 'tbody', 'tr', 'td', 'th', 'b', 'strong', 'i', 'u', 'ul', 'ol', 'li', 'p', 'br', 'span'],
          ALLOWED_ATTR: ['colspan', 'rowspan']
        });
        $('#edit_freetext_body').summernote('pasteHTML', clean);
        isPasting = false;
      }
    }
              });
              $('#editFreeTextModal').modal('show');
              break;
              
            case 'antibiotic':
              $('#edit_antibiotic_id').val(id);
              $('#edit_antibiotic_name').val(data.name || name);
              $('#edit_antibiotic_gender').val(data.gender || gender);
              $('#edit_antibiotic_position').val(data.position || position);
              var defaultVal = (typeof data.defaults !== 'undefined') ? data.defaults : defaults;
            //   $('#edit_antibiotic_default_load').val(defaultVal);
              if (parseInt(defaultVal) === 1) {
                $('#edit_antibiotic_default_load').prop('checked', true);
              } else {
                $('#edit_antibiotic_default_load').prop('checked', false);
              }

              $('#editAntibioticModal').modal('show');
              break;
              
            case 'table_format':
              $('#edit_tableformat_id').val(id);
              $('#edit_tableformat_name').val(data.name || name);
              $('#edit_tableformat_gender').val(data.gender || gender);
              $('#edit_tableformat_position').val(data.position || position);
              var defaultVal = (typeof data.defaults !== 'undefined') ? data.defaults : defaults;
            //   $('#edit_tableformat_default_load').val(defaultVal);
              if (parseInt(defaultVal) === 1) {
                $('#edit_tableformat_default_load').prop('checked', true);
              } else {
                $('#edit_tableformat_default_load').prop('checked', false);
              }

              $('#editTableFormatModal').modal('show');
              break;
              
            default:
              // Fallback to generic edit modal if type is not recognized
              $('#edit_id').val(id);
              $('#edit_type').val(type);
              $('#edit_name').val(name);
              $('#edit_gender').val(gender);
              $('#edit_position').val(position);
              if (parseInt(defaults) === 1) {
                $('#edit_default_load').prop('checked', true);
              } else {
                $('#edit_default_load').prop('checked', false);
              }
              $('#editModal').modal('show');
              break;
          }
        } catch (e) {
          console.error('Error parsing response:', e);
          // Fallback to generic edit modal
          $('#edit_id').val(id);
          $('#edit_type').val(type);
          $('#edit_name').val(name);
          $('#edit_gender').val(gender);
          $('#edit_position').val(position);
          if (parseInt(defaults) === 1) {
            $('#edit_default_load').prop('checked', true);
          } else {
            $('#edit_default_load').prop('checked', false);
          }

          $('#editModal').modal('show');
        }
      },
      error: function(xhr, status, error) {
        console.error('Error fetching item data:', error);
        // Fallback to generic edit modal
        $('#edit_id').val(id);
        $('#edit_type').val(type);
        $('#edit_name').val(name);
        $('#edit_gender').val(gender);
        $('#edit_position').val(position);
        $('#edit_default_load').val(defaults);

        $('#editModal').modal('show');
      }
    });
  });

  $(document).on('click', '.resultOptionBtn', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    $('#testParameterId').val(id);
    $('#resultoptionsModalName').text(name);
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {
        page: 'get_test_results',
        id: id
      },
      success: function(response) {
        $('#result_option_table_body').html(response);
      },
      error: function(xhr, status, error) {
        $('#result_option_table_body').html('<div class="alert alert-danger">Failed to load test ranges: ' + error + '</div>');
      }
    });
  });
  $(document).on('click', '#addResultOptionBtn', function() {
//   alert('here');
        $('#addResultOptionModalName').text($('#resultoptionsModalName').text());
        $('#testParameter_id').val($('#testParameterId').val());
  
});
  // Test Range button
  $(document).on('click', '.testRangeBtn', function() {
    var id = $(this).data('id');
    var name = $(this).data('name');
    $('#test_parameter_id').val(id);
    $('#testrangesModalName').text(name);
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {
        page: 'get_test_ranges',
        id: id
      },
      success: function(response) {
        $('#testrangesModalBody').html(response);
      },
      error: function(xhr, status, error) {
        $('#testrangesModalBody').html('<div class="alert alert-danger">Failed to load test ranges: ' + error + '</div>');
      }
    });
  });

  // Add Test Range button
  $(document).on('click', '#addTestRangeBtn', function() {
    $('#testrangesModal').modal('hide');
    $('#testrangesModal').one('hidden.bs.modal', function () {
        $('#testrangefieldformName').text($('#testrangesModalName').text());
        $('#testrangefieldform').modal('show');
        $('#test_range_parameter_id').val($('#test_parameter_id').val());
    });
});



$(document).on('click', '.delete-test-result1', function () {
  var id = $(this).data('id');
  Swal.fire({
    title: 'Are you sure?',
    text: 'You want to delete this test result?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel'
  }).then(function (result) {
    if (result.isConfirmed) {
      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        data: {
          page: 'delete_test_result1',
          id: id
        },
        success: function (response) {
          Swal.fire({
            title: 'Deleted!',
            text: response,
            icon: 'success'
          });
          // Optionally, refresh the table or remove the row here
        },
        error: function (xhr, status, error) {
          Swal.fire({
            title: 'Error!',
            text: 'Failed to delete test result: ' + error,
            icon: 'error'
          });
        }
      });
    }
  });
});

  // Add Data Rows button
    $(document).on('click', '.addDataRowsBtn', function() {
      var parameterId = $(this).data('id');
      var testName = $(this).data('name');
      $('#test_group_row_parameter_id').val(parameterId);
      $('#test_group_row_test_name').val(testName);
      $('#testGroupHeadingModal').modal('show');
    });
    
    // Add Table Data Rows button
    $(document).on('click', '.addTableDataRowsBtn', function() {
      var parameterId = $(this).data('id');
      var testName = $(this).data('name');
      
      // Calculate position based on existing table data
      var existingRows = $(this).closest('.test-card').find('table tbody tr').length;
      var nextPosition = existingRows + 1;
      
      $('#table_data_row_parameter_id').val(parameterId);
      $('#table_data_row_test_name').val(testName);
      $('#table_data_row_position').val(nextPosition);
      $('#tableDataRowsModal').modal('show');
    });
 

  // Drag and drop sorting
  var el = document.getElementById('testCardContainer');
  if (el) {
    Sortable.create(el, {
      animation: 150,
      onEnd: function (evt) {
        var order = [];
        el.querySelectorAll('.test-card').forEach(function(card, idx) {
          order.push({
            id: card.getAttribute('data-id'),
            position: idx + 1
          });
        });
        var test_id = "<?php echo htmlspecialchars($test_id, ENT_QUOTES, 'UTF-8'); ?>";
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'controller/processLab.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('submit=update_positions&test_id=' + encodeURIComponent(test_id) + '&order=' + encodeURIComponent(JSON.stringify(order)));
      }
    });
  }

  // Move up/down buttons (if you add them in the future)
  if (typeof window.testCardMoveHandlersAdded === 'undefined') {
    window.testCardMoveHandlersAdded = true;
    document.body.addEventListener('click', function(e) {
      if (e.target.closest('.move-up-btn')) {
        var btn = e.target.closest('.move-up-btn');
        var card = btn.closest('.test-card');
        var prevCard = card.previousElementSibling;
        while (prevCard && !prevCard.classList.contains('test-card')) {
          prevCard = prevCard.previousElementSibling;
        }
        if (prevCard) {
          card.parentNode.insertBefore(prevCard, card);
          var id1 = card.getAttribute('data-id');
          var id2 = prevCard.getAttribute('data-id');
          var pos1 = card.getAttribute('data-position');
          var pos2 = prevCard.getAttribute('data-position');
          card.setAttribute('data-position', pos2);
          prevCard.setAttribute('data-position', pos1);
          var test_id = "<?php echo htmlspecialchars($test_id, ENT_QUOTES, 'UTF-8'); ?>";
          var xhr = new XMLHttpRequest();
          xhr.open('POST', 'controller/processLab.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.send('action=swap_positions&id1=' + encodeURIComponent(id1) + '&pos1=' + encodeURIComponent(pos2) + '&id2=' + encodeURIComponent(id2) + '&pos2=' + encodeURIComponent(pos1) + '&test_id=' + encodeURIComponent(test_id));
        }
      }
      if (e.target.closest('.move-down-btn')) {
        var btn = e.target.closest('.move-down-btn');
        var card = btn.closest('.test-card');
        var nextCard = card.nextElementSibling;
        while (nextCard && !nextCard.classList.contains('test-card')) {
          nextCard = nextCard.nextElementSibling;
        }
        if (nextCard) {
          card.parentNode.insertBefore(card, nextCard.nextSibling);
          var id1 = card.getAttribute('data-id');
          var id2 = nextCard.getAttribute('data-id');
          var pos1 = card.getAttribute('data-position');
          var pos2 = nextCard.getAttribute('data-position');
          card.setAttribute('data-position', pos2);
          nextCard.setAttribute('data-position', pos1);
          var test_id = "<?php echo htmlspecialchars($test_id, ENT_QUOTES, 'UTF-8'); ?>";
          var xhr = new XMLHttpRequest();
          xhr.open('POST', 'controller/processLab.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.send('action=swap_positions&id1=' + encodeURIComponent(id1) + '&pos1=' + encodeURIComponent(pos2) + '&id2=' + encodeURIComponent(id2) + '&pos2=' + encodeURIComponent(pos1) + '&test_id=' + encodeURIComponent(test_id));
        }
      }
    });
  }

  // Force remove modal backdrop on modal close
  $('#testrangefieldform').on('hidden.bs.modal', function () {
      $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
      // Re-open the testrangesModal
      $('#testrangesModal').modal('show');
  });
  $('#editTestRangeModal').on('hidden.bs.modal', function () {
      $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
      // Re-open the testrangesModal
      $('#testrangesModal').modal('show');
  });
   $('#editResultOptionModal').on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
      $('body').removeClass('modal-open');
    $('#resultoptionsModal').modal('show');
  });
});
$(document).off('click', '.delete-test-result').on('click', '.delete-test-result', function() {
                            var button = $(this);
                            var id = button.data('id');
                            if (confirm('Are you sure you want to delete this test result?')) {
                                $.ajax({
                                    url: 'ajax.php',
                                    type: 'POST',
                                    data: {
                                        page: 'delete_test_result',
                                        id: id
                                    },
                                    success: function(response) {
                                        // Optionally, you can check response for success
                                        // Remove the row from the table
                                        button.closest('tr').remove();
                                    },
                                    error: function() {
                                        alert('Failed to delete test result. Please try again.');
                                    }
                                });
                            }
                        });
// Generate allAntibiotics array from PHP
var allAntibiotics = <?php
  $antibiotics = [];
  $result = $lab->getAllAntibiotics(); // You need to implement this method to get all antibiotics (id, name)
  while($row = mysqli_fetch_assoc($result)) {
    $antibiotics[] = ['id' => $row['id'], 'name' => $row['antibiotic_name']];
  }
  echo json_encode($antibiotics);
?>;

// Helper to get antibiotics already in the table
function getSelectedAntibioticNames() {
  var names = [];
  $('#testAntibioticBody tr').each(function() {
    var tds = $(this).find('td');
    if (tds.length > 0) {
      var name = tds.eq(0).text().trim();
      if (name) names.push(name);
    }
  });
  return names;
}

$(document).on('click', '.addAntibioticsBtn', function() {
  var parameterId = $(this).data('id');
  var testName = "<?= htmlspecialchars($test_name) ?>";
  $('#antibioticListParameterId').val(parameterId);
  $('#antibioticListTestName').val(testName);

  var selectedNames = getSelectedAntibioticNames();
  // Remove table borders and improve UI with spacing and alignment
  var html = '<table class="table" style="margin-bottom:0; border:none; box-shadow:none;"><tbody>';
  for (var i = 0; i < allAntibiotics.length; i += 3) {
    html += '<tr style="border:none;">';
    for (var j = 0; j < 3; j++) {
      var ab = allAntibiotics[i + j];
      if (!ab) {
        // Add empty cell for alignment if not enough antibiotics in last row
        html += '<td style="border:none; background:transparent;"></td>';
        continue;
      }
      var checked = selectedNames.includes(ab.name) ? 'checked disabled' : '';
      html += '<td style="border:none; background:transparent; padding:8px 12px;">';
      html += '<label class="checkbox" style="font-weight:normal; cursor:pointer;">';
      html += '<input type="checkbox" name="antibiotic_ids[]" value="' + ab.name.replace(/"/g, '&quot;') + '" ' + checked + ' style="margin-right:6px;"> ' + ab.name;
      html += '</label></td>';
    }
    html += '</tr>';
  }
  html +="</tbody></table>";
  $('#antibioticCheckboxContainer').html(html);
  $('#antibioticListModal').modal('show');
});

$(document).on('click', '.deleteTableRowBtn', function() {
  var button = $(this);
  var id = button.data('id');
  if (confirm('Are you sure you want to delete this table data row?')) {
    $.ajax({
      url: 'ajax.php',
      type: 'POST',
      data: {
        page: 'delete_table_data_row',
        id: id
      },
      success: function(response) {
        button.closest('tr').remove();
      },
      error: function() {
        alert('Failed to delete table data row. Please try again.');
      }
    });
  }
});

  $(document).on('click', '.edit-test-range', function() {
    var id = $(this).data('id');
     $('#edit_range_id').val($(this).data('id'));
          $('#edit_reference_range_id').val($(this).data('reference_range') || '0');
          $('#edit_gender').val($(this).data('gender') || 'both');
          $('#edit_unit').val($(this).data('unit') || '');
          $('#edit_lower_limit').val($(this).data('lower_limit') || '');
          $('#edit_upper_limit').val($(this).data('upper_limit') || '');
          $('#edit_critical_lower_limit').val($(this).data('critical_lower_limit') || '');
          $('#edit_critical_upper_limit').val($(this).data('critical_upper_limit') || '');
          $('#edit_normal_value').val($(this).data('normal_value') || '');
          $('#edit_abnormal_condition').val($(this).data('special_condition') || '');
        
    $('#testrangesModal').modal('hide');
    $('#testrangesModal').one('hidden.bs.modal', function () {
        $('#editTestRangeModal').modal('show');
    });

         
  });
$(document).on('click', '.edit-result-option', function() {
    var id = $(this).data('id');
    var option = $(this).data('option');
    var resBold = $(this).data('res_bold');
    
    // Populate the edit modal
    $('#editResultOptionId').val(id);
    $('#editResultOption').val(option);
    $('#editHighlightResult').prop('checked', resBold === 'Yes');
    
    // Show the edit modal
    $('#resultoptionsModal').modal('hide');
    $('#resultoptionsModal').one('hidden.bs.modal', function () {
        $('#editResultOptionModal').modal('show');
    });
  });


</script>
</body>
</html>

