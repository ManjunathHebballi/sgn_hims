<?php
// add_test_parameter_form.php
$test_id = isset($_GET['test_id']) ? htmlspecialchars($_GET['test_id']) : '';
$test_parameter_name = isset($_GET['test_parameter_name']) ? htmlspecialchars($_GET['test_parameter_name']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Test Parameter</title>
  <base target="_parent">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/sweetalert2/sweetalert2.min.css">
</head>
<body>
<div class="container mt-4">
  <h4>Add Test Parameter</h4>
  <form action="controller/processLab.php" method="post" id="parameterForm">
    <div class="form-group">
      <label for="test_parameter_name">Name</label>
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
      <input readonly class="form-control" type="number" step="1" value="1" name="position" id="test_parameter_position">
    </div>
    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
    <input type="hidden" name="test_parameter_name" value="<?php echo $test_parameter_name; ?>">
    <button type="submit" name="submit" value="testParameter" class="btn btn-success">Save</button>
  </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="css/sweetalert2/sweetalert2.min.js"></script>
<script src="js/sweet-alerts.init.js"></script>
<script>
$('#parameterForm').on('submit', function() {
  setTimeout(function() {
    window.parent.$('#genericIframeModal').modal('hide');
    window.parent.location.reload();
  }, 500);
});
</script>
</body>
</html> 