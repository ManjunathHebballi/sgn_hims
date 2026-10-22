<?php
/**
 * Options for the free-text lookup dropdowns (chief complaints, comorbidities,
 * hypersensitivity, associated with, diagnosis, advice).
 *
 * These lookup tables are seeded by addOTTreat::ensureLookup() on every save, so
 * a value the clinician picked earlier is ALREADY a row in the lookup table.
 * Rendering the saved values as extra <option>s on top of the table therefore
 * showed each of them twice. Mark the matching lookup row selected instead, and
 * only append a saved value when the lookup no longer holds it (e.g. the master
 * row was deactivated after the visit was recorded) so nothing silently drops
 * out of an existing record.
 *
 * @param mysqli_result|false $rows   lookup rows, as returned by the model getters
 * @param string              $column column holding the label, e.g. 'complaints'
 * @param string|null         $saved  comma-separated values stored on the visit
 * @param callable|null       $label  optional row -> label, for lists that render a
 *                                    composed label such as "code-details"
 */
function lookupOptions($rows, string $column, ?string $saved, ?callable $label = null): void
{
    $selected = array_values(array_filter(
        array_map('trim', explode(',', (string) $saved)),
        static function ($v) { return $v !== ''; }
    ));

    // Match case-insensitively: "Fever" saved against "fever" in the lookup is the
    // same entry to a clinician, and must not render as two rows.
    $isSelected = [];
    foreach ($selected as $v) {
        $isSelected[strtolower($v)] = true;
    }

    $seen = [];
    if ($rows) {
        while ($r = mysqli_fetch_assoc($rows)) {
            $v = trim((string) ($label ? $label($r) : ($r[$column] ?? '')));
            if ($v === '') {
                continue;
            }
            $k = strtolower($v);
            if (isset($seen[$k])) {
                continue; // the lookup table itself can hold near-duplicates
            }
            $seen[$k] = true;
            echo '<option value="' . htmlspecialchars($v) . '"'
                . (isset($isSelected[$k]) ? ' selected' : '') . '>'
                . htmlspecialchars($v) . '</option>';
        }
    }

    foreach ($selected as $v) {
        $k = strtolower($v);
        if (isset($seen[$k])) {
            continue;
        }
        $seen[$k] = true;
        echo '<option value="' . htmlspecialchars($v) . '" selected>'
            . htmlspecialchars($v) . '</option>';
    }
}
