<?php
/**
 * De-duplicate sample_status, then optionally add the UNIQUE key that stops it
 * happening again.
 *
 * WHY THIS EXISTS
 * ---------------
 * sample_status has four independent insert paths (labClass::insertSampleCollect,
 * optreatmentClass, treatmentClass, patient_data_oplab). Each now guards against
 * a duplicate in code, but a SELECT-then-INSERT is not atomic: two concurrent
 * saves can both pass the check. Only a UNIQUE key closes it for good, and that
 * cannot be added while duplicates are already on file.
 *
 * Duplicates are not cosmetic — lab_print1.php INNER JOINs sample_status on
 * (patient_id, bill_no, test_id), so a duplicate repeats the whole test block on
 * the printed report.
 *
 * USAGE (CLI only — this deletes rows, so it must not be reachable from a browser)
 *   php sql/dedupe_sample_status.php              # audit, changes nothing
 *   php sql/dedupe_sample_status.php --apply      # delete duplicates
 *   php sql/dedupe_sample_status.php --apply --add-unique
 *
 * WHICH ROW SURVIVES
 * ------------------
 * abha_integration.php publishes care contexts keyed on sample_status.id
 * (DIAGRPT/{id}), so a row ABDM already knows about must never be the one
 * deleted. Ranking, highest first:
 *   1. abha_linked = 1, or linkRequestId set   (ABDM holds this id)
 *   2. furthest along the workflow             (finalized > result > ack > dispatch > collected)
 *   3. lowest id                               (the original)
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../model/env.php';

$apply     = in_array('--apply', $argv, true);
$addUnique = in_array('--add-unique', $argv, true);

$host = env('DB_HOST', 'localhost');
$user = env('DB_USER', 'root');
$pass = env('DB_PASSWORD', '');
$name = env('DB_NAME', 'hospital');

$db = @new mysqli($host, $user, $pass, $name);
if ($db->connect_error) {
    fwrite(STDERR, "Connection failed: {$db->connect_error}\n");
    exit(1);
}
echo "Database: $name on $host\n";
echo $apply ? "Mode: APPLY (rows will be deleted)\n" : "Mode: AUDIT ONLY (nothing will change)\n";
echo str_repeat('-', 70) . "\n";

/** Does a column exist on sample_status? */
function hasColumn(mysqli $db, $col)
{
    $st = $db->prepare("SELECT 1 FROM information_schema.COLUMNS
                         WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sample_status'
                           AND COLUMN_NAME = ? LIMIT 1");
    $st->bind_param('s', $col);
    $st->execute();
    $found = $st->get_result()->num_rows > 0;
    $st->close();
    return $found;
}

// The ABHA columns only exist where sql/abha_integration.sql has been run.
$abhaCols = array();
if (hasColumn($db, 'abha_linked'))   { $abhaCols[] = 'abha_linked'; }
if (hasColumn($db, 'linkRequestId')) { $abhaCols[] = 'linkRequestId'; }
echo $abhaCols
    ? "ABDM columns present (" . implode(', ', $abhaCols) . ") — published rows will be protected.\n"
    : "No ABDM columns on this database — ranking on workflow stage only.\n";
echo str_repeat('-', 70) . "\n";

// ---- find the duplicate groups -------------------------------------------
$groups = $db->query(
    "SELECT bill_no, patient_id, test_id, COUNT(*) AS n
       FROM sample_status
      GROUP BY bill_no, patient_id, test_id
     HAVING n > 1
      ORDER BY n DESC, bill_no"
);
if (!$groups) { fwrite(STDERR, "Query failed: {$db->error}\n"); exit(1); }

$groupCount = $groups->num_rows;
if ($groupCount === 0) {
    echo "No duplicates found.\n";
} else {
    echo "$groupCount duplicated (bill_no, patient_id, test_id) group(s).\n\n";
}

$abhaSelect = $abhaCols ? ', ' . implode(', ', $abhaCols) : '';
$deleted = 0;
$protected = 0;
$shown = 0;

while ($g = $groups->fetch_assoc()) {
    $st = $db->prepare("SELECT id, is_sample_collected, is_sample_dispatch, is_sample_acknowledge,
                               is_result_entered, is_finalized $abhaSelect
                          FROM sample_status
                         WHERE bill_no = ? AND patient_id = ? AND test_id = ?
                         ORDER BY id ASC");
    $st->bind_param('sss', $g['bill_no'], $g['patient_id'], $g['test_id']);
    $st->execute();
    $rows = array();
    $res = $st->get_result();
    while ($r = $res->fetch_assoc()) { $rows[] = $r; }
    $st->close();

    // rank: ABDM-published first, then workflow stage, then oldest id
    usort($rows, function ($a, $b) use ($abhaCols) {
        $score = function ($r) use ($abhaCols) {
            $s = 0;
            foreach ($abhaCols as $c) {
                if (!empty($r[$c]) && $r[$c] !== '0') { $s += 1000; }
            }
            $s += ((int) $r['is_finalized'])          * 100;
            $s += ((int) $r['is_result_entered'])     * 50;
            $s += ((int) $r['is_sample_acknowledge']) * 20;
            $s += ((int) $r['is_sample_dispatch'])    * 10;
            $s += ((int) $r['is_sample_collected'])   * 5;
            return $s;
        };
        $sa = $score($a); $sb = $score($b);
        if ($sa !== $sb) { return $sb - $sa; }        // higher score wins
        return ((int) $a['id']) - ((int) $b['id']);    // else oldest wins
    });

    $keep = array_shift($rows);
    $dropIds = array();
    foreach ($rows as $r) {
        // Never delete a second row ABDM also knows about — that needs a human.
        $isPublished = false;
        foreach ($abhaCols as $c) {
            if (!empty($r[$c]) && $r[$c] !== '0') { $isPublished = true; }
        }
        if ($isPublished) { $protected++; continue; }
        $dropIds[] = (int) $r['id'];
    }

    if ($shown < 15) {
        printf("  %-18s pat=%-10s test=%-6s  keep id=%-7s drop=%s\n",
            $g['bill_no'], $g['patient_id'], $g['test_id'], $keep['id'],
            $dropIds ? implode(',', $dropIds) : '(none)');
        $shown++;
        if ($shown === 15 && $groupCount > 15) { echo "  … " . ($groupCount - 15) . " more\n"; }
    }

    if ($apply && $dropIds) {
        $in = implode(',', $dropIds);
        if ($db->query("DELETE FROM sample_status WHERE id IN ($in)")) {
            $deleted += $db->affected_rows;
        } else {
            fwrite(STDERR, "  DELETE failed for $in: {$db->error}\n");
        }
    } else {
        $deleted += count($dropIds);   // audit mode: what would go
    }
}

echo str_repeat('-', 70) . "\n";
printf("%s %d duplicate row(s).\n", $apply ? 'Deleted' : 'Would delete', $deleted);
if ($protected > 0) {
    printf("Left %d row(s) alone: a second copy is already published to ABDM and needs review.\n", $protected);
}

// ---- the constraint -------------------------------------------------------
if ($addUnique) {
    if (!$apply) {
        echo "\n--add-unique needs --apply as well; skipped.\n";
    } else {
        $left = $db->query("SELECT 1 FROM sample_status
                             GROUP BY bill_no, patient_id, test_id
                            HAVING COUNT(*) > 1 LIMIT 1");
        if ($left && $left->num_rows > 0) {
            echo "\nDuplicates remain (see the protected rows above) — UNIQUE key not added.\n";
        } else {
            $exists = $db->query("SELECT 1 FROM information_schema.STATISTICS
                                   WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'sample_status'
                                     AND INDEX_NAME = 'uniq_ss_bill_patient_test' LIMIT 1");
            if ($exists && $exists->num_rows > 0) {
                echo "\nUNIQUE key already present.\n";
            } elseif ($db->query("ALTER TABLE sample_status
                                  ADD UNIQUE KEY `uniq_ss_bill_patient_test` (`bill_no`,`patient_id`,`test_id`)")) {
                echo "\nUNIQUE key uniq_ss_bill_patient_test added — duplicates are now impossible,\n";
                echo "including under concurrent saves that the code-level guards cannot catch.\n";
            } else {
                fwrite(STDERR, "\nCould not add the UNIQUE key: {$db->error}\n");
            }
        }
    }
} elseif ($apply) {
    echo "\nRe-run with --add-unique to add the UNIQUE key and close this permanently.\n";
}

$db->close();
