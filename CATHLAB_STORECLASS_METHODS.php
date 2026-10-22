// =====================================================
// STORECLASS EXTENSION FOR CATHLAB
// Add these methods to model/storeClass.php
// =====================================================

/**
 * Get CathLab Store ID
 */
public function getCathLabStoreId() {
    $query = "SELECT id FROM store WHERE store_type = 'cathlab' LIMIT 1";
    $result = mysqli_query($this->db, $query);
    if($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row['id'];
    }
    return null;
}

/**
 * Get CathLab Items
 */
public function getCathLabItems($store_id) {
    $query = "SELECT csi.*, sc.category_name, su.unit_name
              FROM cathlab_store_items csi
              LEFT JOIN store_category sc ON csi.category_id = sc.id
              LEFT JOIN store_unit su ON csi.unit_id = su.id
              WHERE csi.store_id = $store_id AND csi.status = 'active' AND csi.deleted_at IS NULL
              ORDER BY csi.item_name";
    return mysqli_query($this->db, $query);
}

/**
 * Get Available Batches for Item
 */
public function getAvailableBatches($item_id, $store_id) {
    $query = "SELECT id, batch_number, expiry_date, available_quantity, cost_price
              FROM cathlab_batch_master
              WHERE item_id = $item_id AND store_id = $store_id 
              AND status = 'active' AND available_quantity > 0
              ORDER BY expiry_date ASC";
    return mysqli_query($this->db, $query);
}

/**
 * Get CathLab Stock Summary
 */
public function getCathLabStockSummary($store_id) {
    $query = "SELECT 
                csi.id,
                csi.item_code,
                csi.item_name,
                SUM(cbm.available_quantity) AS available,
                SUM(cbm.reserved_quantity) AS reserved,
                SUM(cbm.consumed_quantity) AS consumed,
                COUNT(DISTINCT CASE WHEN cbm.expiry_date < CURDATE() THEN cbm.id END) AS expired_batches,
                (SUM(cbm.available_quantity) * csi.cost_price) AS inventory_value,
                csi.reorder_level
              FROM cathlab_store_items csi
              LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id
              WHERE csi.store_id = $store_id AND csi.status = 'active'
              GROUP BY csi.id
              ORDER BY csi.item_name";
    return mysqli_query($this->db, $query);
}

/**
 * Get Expiry Alert Items
 */
public function getCathLabExpiryAlerts($store_id, $days = 30) {
    $query = "SELECT 
                csi.item_code,
                csi.item_name,
                cbm.id AS batch_id,
                cbm.batch_number,
                cbm.expiry_date,
                DATEDIFF(cbm.expiry_date, CURDATE()) AS days_to_expiry,
                cbm.available_quantity,
                CASE 
                    WHEN cbm.expiry_date < CURDATE() THEN 'EXPIRED'
                    WHEN DATEDIFF(cbm.expiry_date, CURDATE()) <= $days THEN 'EXPIRING_SOON'
                    ELSE 'OK'
                END AS status
              FROM cathlab_batch_master cbm
              JOIN cathlab_store_items csi ON cbm.item_id = csi.id
              WHERE csi.store_id = $store_id AND cbm.status = 'active'
              AND (cbm.expiry_date < DATE_ADD(CURDATE(), INTERVAL $days DAY))
              ORDER BY cbm.expiry_date ASC";
    return mysqli_query($this->db, $query);
}

/**
 * Get Procedure-wise Consumption
 */
public function getProcedureConsumption($start_date, $end_date, $store_id) {
    $query = "SELECT 
                cp.procedure_code,
                cp.procedure_type,
                p.name AS patient_name,
                p.patient_id_card,
                u.name AS doctor_name,
                csi.item_code,
                csi.item_name,
                cpi.quantity_consumed,
                cpi.cost_price,
                (cpi.quantity_consumed * cpi.cost_price) AS total_cost,
                csi.selling_price,
                (cpi.quantity_consumed * csi.selling_price) AS total_revenue,
                cbm.batch_number,
                cbm.expiry_date,
                cpi.status
              FROM cathlab_procedure cp
              JOIN patient p ON cp.patient_id = p.id
              JOIN users u ON cp.doctor_id = u.id
              JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id
              JOIN cathlab_store_items csi ON cpi.item_id = csi.id
              LEFT JOIN cathlab_batch_master cbm ON cpi.batch_id = cbm.id
              WHERE csi.store_id = $store_id
              AND cp.procedure_date BETWEEN '$start_date' AND '$end_date'
              ORDER BY cp.procedure_date DESC";
    return mysqli_query($this->db, $query);
}

/**
 * Get Cost vs Revenue Report
 */
public function getCathLabCostVsRevenue($start_date, $end_date, $store_id) {
    $query = "SELECT 
                DATE(cp.procedure_date) AS procedure_date,
                COUNT(DISTINCT cp.id) AS total_procedures,
                SUM(cpi.quantity_consumed * cpi.cost_price) AS total_cost,
                SUM(cpi.quantity_consumed * csi.selling_price) AS total_revenue,
                (SUM(cpi.quantity_consumed * csi.selling_price) - SUM(cpi.quantity_consumed * cpi.cost_price)) AS margin,
                ROUND(
                    ((SUM(cpi.quantity_consumed * csi.selling_price) - SUM(cpi.quantity_consumed * cpi.cost_price)) / 
                     SUM(cpi.quantity_consumed * csi.selling_price) * 100), 2
                ) AS margin_percent
              FROM cathlab_procedure cp
              JOIN cathlab_procedure_items cpi ON cp.id = cpi.procedure_id
              JOIN cathlab_store_items csi ON cpi.item_id = csi.id
              WHERE csi.store_id = $store_id
              AND cp.procedure_date BETWEEN '$start_date' AND '$end_date'
              AND cpi.status != 'cancelled'
              GROUP BY DATE(cp.procedure_date)
              ORDER BY procedure_date DESC";
    return mysqli_query($this->db, $query);
}

/**
 * Get Low Stock Items
 */
public function getCathLabLowStockItems($store_id) {
    $query = "SELECT 
                csi.id,
                csi.item_code,
                csi.item_name,
                SUM(cbm.available_quantity) AS available,
                csi.reorder_level,
                CASE 
                    WHEN SUM(cbm.available_quantity) <= 0 THEN 'OUT_OF_STOCK'
                    WHEN SUM(cbm.available_quantity) <= (csi.reorder_level / 2) THEN 'CRITICAL'
                    WHEN SUM(cbm.available_quantity) <= csi.reorder_level THEN 'LOW_STOCK'
                END AS status
              FROM cathlab_store_items csi
              LEFT JOIN cathlab_batch_master cbm ON csi.id = cbm.item_id 
              AND cbm.status = 'active'
              WHERE csi.store_id = $store_id AND csi.status = 'active'
              GROUP BY csi.id
              HAVING SUM(cbm.available_quantity) <= csi.reorder_level
              ORDER BY status, available";
    return mysqli_query($this->db, $query);
}

/**
 * Get Stock Ledger for Item
 */
public function getItemStockLedger($item_id, $store_id, $limit = 100) {
    $query = "SELECT 
                csl.created_at,
                csl.transaction_type,
                csl.quantity,
                csl.reference_type,
                cbm.batch_number,
                cbm.expiry_date,
                u.name AS created_by
              FROM cathlab_stock_ledger csl
              LEFT JOIN cathlab_batch_master cbm ON csl.batch_id = cbm.id
              JOIN users u ON csl.created_by = u.id
              WHERE csl.item_id = $item_id AND csl.store_id = $store_id
              ORDER BY csl.created_at DESC
              LIMIT $limit";
    return mysqli_query($this->db, $query);
}

/**
 * Get Patient Implant History
 */
public function getPatientImplants($patient_id) {
    $query = "SELECT 
                cit.id,
                cit.admission_id,
                cit.procedure_id,
                csi.item_name,
                cbm.batch_number,
                cbm.expiry_date,
                cit.quantity_used,
                u.name AS implanted_by,
                cit.implant_site,
                cit.created_at,
                cp.procedure_code
              FROM cathlab_implant_traceability cit
              JOIN cathlab_store_items csi ON cit.item_id = csi.id
              JOIN cathlab_batch_master cbm ON cit.batch_id = cbm.id
              JOIN users u ON cit.implanted_by = u.id
              JOIN cathlab_procedure cp ON cit.procedure_id = cp.id
              WHERE cit.patient_id = $patient_id
              ORDER BY cit.created_at DESC";
    return mysqli_query($this->db, $query);
}

/**
 * Create CathLab Item
 */
public function createCathLabItem($store_id, $item_code, $item_name, $category_id, $unit_id, $item_type, 
                                   $cost_price, $selling_price, $is_batch_mandatory, $is_expiry_mandatory, 
                                   $created_by) {
    $item_code = mysqli_real_escape_string($this->db, $item_code);
    $item_name = mysqli_real_escape_string($this->db, $item_name);
    
    $query = "INSERT INTO cathlab_store_items 
              (store_id, item_code, item_name, category_id, unit_id, item_type, cost_price, selling_price, 
               is_batch_mandatory, is_expiry_mandatory, status, created_by, created_at)
              VALUES ($store_id, '$item_code', '$item_name', $category_id, $unit_id, '$item_type', 
                      $cost_price, $selling_price, $is_batch_mandatory, $is_expiry_mandatory, 'active', $created_by, NOW())";
    
    return mysqli_query($this->db, $query);
}

/**
 * Get Procedure Details
 */
public function getProcedureDetails($procedure_id) {
    $query = "SELECT cp.*, p.name AS patient_name, pa.ip_no, u.name AS doctor_name
              FROM cathlab_procedure cp
              JOIN patient p ON cp.patient_id = p.id
              JOIN patient_admission pa ON cp.admission_id = pa.id
              JOIN users u ON cp.doctor_id = u.id
              WHERE cp.id = $procedure_id";
    
    $result = mysqli_query($this->db, $query);
    return $result ? mysqli_fetch_assoc($result) : null;
}

/**
 * Get Procedure Items for Billing
 */
public function getProcedureItemsForBilling($procedure_id) {
    $query = "SELECT cpi.*, csi.item_name, csi.selling_price, cbm.batch_number, cbm.expiry_date
              FROM cathlab_procedure_items cpi
              JOIN cathlab_store_items csi ON cpi.item_id = csi.id
              LEFT JOIN cathlab_batch_master cbm ON cpi.batch_id = cbm.id
              WHERE cpi.procedure_id = $procedure_id AND cpi.status = 'pending_billing'
              ORDER BY cpi.created_at";
    return mysqli_query($this->db, $query);
}
