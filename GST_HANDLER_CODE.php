// ======================== GST RATE MANAGEMENT OPERATIONS ========================
if(isset($_REQUEST['submit']) && $_REQUEST['submit']=='store_gst_rate')
{
    $category_id = $_REQUEST['category_id'];
    $gst_percent = $_REQUEST['gst_percent'];
    
    // Sanitize inputs
    $category_id = mysqli_real_escape_string($con, $category_id);
    $gst_percent = mysqli_real_escape_string($con, $gst_percent);
    
    // Validate inputs
    if(empty($category_id) || empty($gst_percent)) {
        echo "<script>alert('Please fill all required fields'); window.location='../gst_rate_master.php'</script>";
        exit;
    }
    
    // Validate percentage range
    if($gst_percent < 0 || $gst_percent > 100) {
        echo "<script>alert('GST percentage must be between 0 and 100'); window.location='../gst_rate_master.php'</script>";
        exit;
    }
    
    // Disable old rates first
    $newStore->disableOldGstRate($category_id);
    
    // Set new rate
    $result = $newStore->setGstRateForCategory($category_id, $gst_percent);
    
    if($result) {
        echo "<script>window.location='../gst_rate_master.php?success=1'</script>";
    } else {
        echo "<script>alert('Error saving GST rate'); window.location='../gst_rate_master.php'</script>";
    }
}
