# Item-Level GST - Installation & Deployment Guide

## ⚠️ IMPORTANT: BEFORE YOU START
- **Backup your database** completely before proceeding
- Test in a development environment first
- Have administrator access to the application
- Read this entire guide before starting

---

## 📋 Installation Checklist

- [ ] Backup database
- [ ] Review all changed files
- [ ] Execute database migration
- [ ] Update processStore.php with GST handler
- [ ] Add menu item to leftbar.php
- [ ] Clear browser cache
- [ ] Test complete workflow
- [ ] Verify calculations
- [ ] Deploy to production

---

## Step 1: Database Migration

### Files Needed:
- `migrations/gst_implementation.sql`

### Instructions:

**Option A: Using phpMyAdmin**
1. Login to phpMyAdmin
2. Select your hospital database
3. Click "SQL" tab
4. Copy and paste the entire content of `gst_implementation.sql`
5. Click "Go" button
6. Wait for success message

**Option B: Using MySQL Client**
```bash
mysql -u username -p database_name < gst_implementation.sql
```

**Option C: Using Command Line**
```sql
mysql> use alvashospital;
mysql> SOURCE migrations/gst_implementation.sql;
```

### Verification:
After migration, verify columns were added:
```sql
-- Check store_purchase_order_items
DESCRIBE store_purchase_order_items;
-- Should show: gst_percent, gst_amount, net_amount

-- Check new GST table
DESCRIBE store_item_gst_rates;
-- Should display all GST configuration fields
```

---

## Step 2: Update Controller File

### File: `controller/processStore.php`

**Action:** Add GST handler at the end of the file

### Instructions:

1. Open `controller/processStore.php`
2. Go to the **very end** of the file (just before `?>`)
3. **Find this:**
   ```php
       echo "<script>window.location='../store_issue_list.php?success=1'</script>";
   }
   
   ?>
   ```

4. **Replace with this:**
   ```php
       echo "<script>window.location='../store_issue_list.php?success=1'</script>";
   }

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

   ?>
   ```

5. **Save the file**

---

## Step 3: Add Menu Item

### File: `leftbar.php`

**Find the Store Module section** (look for similar items):
```php
<a class="dropdown-item" href="add_store_item.php">Item Master</a>
```

**Add this new menu item** in the appropriate store section:
```html
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="gst_rate_master.php"><i class="fa fa-percent mr-2"></i>GST Rate Master</a>
```

**Save the file**

---

## Step 4: File Validation

### Files Already Updated (by the system):
- ✅ `add_store_po.php` - UI with single-row item layout
- ✅ `model/storeClass.php` - All GST methods added
- ✅ `gst_rate_master.php` - New admin page created
- ✅ `ajax/getItemGstRate.php` - Auto-fill AJAX service

### Files Ready to Use:
- ✅ `migrations/gst_implementation.sql` - Database schema
- ✅ `GST_IMPLEMENTATION_GUIDE.md` - Full documentation
- ✅ `GST_COMPLETE_FLOW.md` - Workflow documentation
- ✅ `GST_HANDLER_CODE.php` - Handler code reference

---

## Step 5: Clear Cache & Test

### Browser Cache:
1. **Clear browser cache:**
   - Ctrl+Shift+Delete (Windows)
   - Cmd+Shift+Delete (Mac)
   - Select "All time"
   - Clear cache

2. **Reload application:**
   - Close browser completely
   - Reopen and login

### Server Cache (if applicable):
```bash
# If using any caching system
rm -rf /path/to/cache/*
```

---

## Step 6: Testing Workflow

### Test 1: Configure GST Rates

1. **Login as Admin**
2. **Navigate to:** Store → GST Rate Master
3. **Configure rates:**
   - Category: Medicines, GST: 5%
   - Category: Equipment, GST: 18%
   - Category: Consumables, GST: 0%
4. **Verify:** Rates appear in "Active GST Rates" section

### Test 2: Create Purchase Order with Auto-Fill

1. **Navigate to:** Store → Add Purchase Order
2. **Fill header:**
   - Supplier: Any supplier
   - PO Date: Today
   - Payment Terms: NET 15
3. **Add Item:**
   - Select item from Medicines category
   - GST % should auto-fill with 5%
   - If not, check database and category configuration
4. **Enter values:**
   - Quantity: 100
   - Rate: 50
5. **Verify calculations:**
   - Amount: 5000 (100 × 50) ✓
   - GST Amt: 250 (5000 × 5%) ✓
   - Net Amt: 5250 (5000 + 250) ✓
6. **Add another item** and verify totals update

### Test 3: Manual GST Override

1. **In same PO, add another item**
2. **Select item from Equipment category**
3. **GST % auto-fills with 18%**
4. **Override to 12%:**
   - Click GST % field
   - Clear value
   - Enter 12
5. **Verify calculations update** with new percentage

### Test 4: Save and Verify

1. **Click "Save Purchase Order"**
2. **Should see success message**
3. **Check database:**
   ```sql
   SELECT * FROM store_purchase_order_items 
   WHERE po_id = (SELECT MAX(id) FROM store_purchase_orders)
   ORDER BY created_at DESC;
   ```
4. **Verify columns:**
   - gst_percent: Should have values
   - gst_amount: Should have calculated values
   - net_amount: Should equal amount + gst_amount

### Test 5: View Purchase Order

1. **Navigate to:** Store → PO List
2. **Click on newly created PO**
3. **Verify:**
   - All items display correctly
   - GST % shown for each item
   - GST amounts calculated
   - Net amounts displayed
   - Totals correct at bottom

---

## Troubleshooting

### Issue: GST % Not Auto-Filling

**Check 1: Database columns exist**
```sql
DESCRIBE store_purchase_order_items;
-- Look for: gst_percent, gst_amount, net_amount
```

**Check 2: GST Rate configured**
```sql
SELECT * FROM store_item_gst_rates WHERE status = 1;
-- Should show active rates
```

**Check 3: Item has category**
```sql
SELECT id, item_name, category_id FROM store_items WHERE item_name = 'YourItem';
-- category_id should not be NULL
```

**Check 4: Browser cache**
- Clear browser cache completely
- Restart browser
- Try again

### Issue: Calculations Not Working

**Check 1: JavaScript enabled**
- Open browser console (F12)
- Look for JavaScript errors
- Check Network tab for AJAX calls

**Check 2: AJAX file accessible**
```
Visit: http://yourserver/ajax/getItemGstRate.php?category_id=1
Should return a number (GST %)
```

**Check 3: Check server logs**
- Look for PHP errors
- Check MySQL connection
- Verify permissions

### Issue: GST Handler Not Working

**Check 1: File updated correctly**
- Open `controller/processStore.php`
- Search for "store_gst_rate"
- Should find the handler code

**Check 2: Syntax error**
- Check for PHP syntax errors
- Visit any store page
- Should load without errors
- Check error_log file

**Check 3: Permissions**
- Verify file is readable/writable
- Check folder permissions

---

## Rollback Plan

If something goes wrong:

### Rollback Database:
```sql
-- Restore from backup
-- Or manually remove columns:
ALTER TABLE `store_purchase_order_items` DROP COLUMN `gst_percent`;
ALTER TABLE `store_purchase_order_items` DROP COLUMN `gst_amount`;
ALTER TABLE `store_purchase_order_items` DROP COLUMN `net_amount`;

DROP TABLE IF EXISTS `store_item_gst_rates`;
```

### Rollback Files:
1. Restore original versions from backup
2. Remove GST handler from processStore.php
3. Remove gst_rate_master.php
4. Remove ajax/getItemGstRate.php
5. Revert add_store_po.php to original

---

## Post-Installation Tasks

- [ ] Update documentation for end users
- [ ] Train staff on GST Rate Master
- [ ] Set up default GST rates for your organization
- [ ] Create backup after successful installation
- [ ] Monitor usage for issues
- [ ] Update SOPs/policies with GST implementation

---

## Success Indicators

✅ All tests pass
✅ Calculations are accurate
✅ Database saves correctly
✅ No error messages
✅ Menu item appears
✅ Auto-fill works
✅ Manual override works
✅ Totals update dynamically

---

## Next Steps

1. **User Training:** Train staff on GST Rate Master
2. **Data Migration:** Configure GST rates for all categories
3. **Reporting:** Set up GST reports (if needed)
4. **Documentation:** Update internal documentation
5. **Monitoring:** Monitor system for any issues

---

## Support

For issues or questions:
1. Check `GST_IMPLEMENTATION_GUIDE.md` for detailed documentation
2. Review database schema in `GST_COMPLETE_FLOW.md`
3. Check JavaScript console for errors (F12)
4. Review MySQL error log
5. Verify all files were updated correctly

---

**Installation Date:** January 28, 2026  
**Version:** 1.0  
**Status:** Ready for Deployment  

**⚠️ Remember: Always backup before making changes!**
