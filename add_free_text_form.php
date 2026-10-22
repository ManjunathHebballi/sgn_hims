<?php
// add_free_text_form.php
$test_id = isset($_GET['test_id']) ? htmlspecialchars($_GET['test_id']) : '';
$test_free_text_name = isset($_GET['test_free_text_name']) ? htmlspecialchars($_GET['test_free_text_name']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Free Text</title>
  <base target="_parent">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="./css/style.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/sweetalert2/sweetalert2.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.css" rel="stylesheet">
      
</head>
<body>
<div class="container mt-4">
  <h4>Add Free Text</h4>
  <form action="controller/processLab.php" method="post" id="freeTextForm">
    <div class="form-group">
      <label for="free_text_body">Body</label>
      <textarea class="form-control summernote" id="free_text_body" name="body" rows="8" maxlength="65535"></textarea>
     
    </div>
    <div class="form-group">
      <label for="free_text_gender">Gender</label>
      <select class="form-control" id="free_text_gender" name="gender">
        <option value="both">All</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div class="form-group">
      <label for="free_text_position">Position</label>
      <input readonly class="form-control" type="number" step="1" value="1" name="position" id="free_text_position">
    </div>
    <input type="hidden" name="test_id" value="<?php echo $test_id; ?>">
    <input type="hidden" name="test_free_text_name" value="<?php echo $test_free_text_name; ?>">
    <button type="submit" name="submit" value="testFreeText" class="btn btn-success">Save</button>
  </form>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="css/sweetalert2/sweetalert2.min.js"></script>
<script src="js/sweet-alerts.init.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-bs4.min.js"></script>

<script>
$('#freeTextForm').on('submit', function() {
  setTimeout(function() {
    window.parent.$('#genericIframeModal').modal('hide');
    window.parent.location.reload();
  }, 500);
});
        $(document).ready(function() {
          $('#free_text_body').summernote({
            height: 200
          });
        });
      </script>
</body>
</html> 