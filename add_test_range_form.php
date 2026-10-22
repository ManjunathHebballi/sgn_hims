<?php
// add_test_range_form.php
$specimen = isset($_GET['specimen']) ? htmlspecialchars($_GET['specimen']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Test Range</title>
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h4>Add Test Range <?php if ($specimen) echo 'for <span class="text-primary">' . $specimen . '</span>'; ?></h4>
  <form action="controller/processLab.php" method="post">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="lower_limit">Lower limit</label>
          <input class="form-control" type="text" name="lower_limit" id="lower_limit">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="upper_limit">Upper limit</label>
          <input class="form-control" type="text" name="upper_limit" id="upper_limit">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="critical_lower_limit">Critical lower limit</label>
          <input class="form-control" type="text" name="critical_lower_limit" id="critical_lower_limit">
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="critical_upper_limit">Critical upper limit</label>
          <input class="form-control" type="text" name="critical_upper_limit" id="critical_upper_limit">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="form-group">
          <label for="reference_range_id">Reference Range</label>
          <select class="form-control" name="reference_range_id" id="reference_range_id">
            <option value="0">Any</option>
            <option value="1">Newborn (upto one month)</option>
            <!-- Add more options as needed -->
          </select>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="gender">Gender</label>
          <select class="form-control" name="gender" id="gender">
            <option value="both">All</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
      </div>
    </div>
    <div class="form-group">
      <label for="normal_value">Normal value</label>
      <textarea rows="3" class="form-control" maxlength="65535" name="normal_value" id="normal_value"></textarea>
    </div>
    <div class="form-group">
      <label for="abnormal_condition">Special condition</label>
      <textarea class="form-control" maxlength="65535" name="abnormal_condition" id="abnormal_condition"></textarea>
    </div>
    <button type="submit" name="submit" value="testRange" class="btn btn-success">Save</button>
  </form>
</div>
</body>
</html> 