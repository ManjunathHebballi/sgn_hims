            </div><!-- /container-fluid -->
        </div><!-- /content-body -->

        <?php include('footer.php') ?>

    </div><!-- /main-wrapper -->

    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/quixnav-init.js"></script>
    <script src="./js/custom.min.js"></script>

    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/libs/jszip/jszip.min.js"></script>
    <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            $('table.pr-table').each(function () {
                $(this).DataTable({
                    dom: 'Bfrtip',
                    responsive: true,
                    pageLength: 25,
                    order: [],
                    buttons: [
                        { extend: 'excelHtml5', title: document.title },
                        { extend: 'print', title: document.title }
                    ]
                });
            });
        });

        /**
         * Item rows shared by the requisition and tender forms. Rows post as
         * parallel arrays (item_id[], quantity[], rate[]) which is what
         * controller/processProcurement.php::itemRows() expects.
         */
        function prAddRow(tableId) {
            var $body = $('#' + tableId + ' tbody');
            var $first = $body.find('tr').first();
            var $row = $first.clone();
            $row.find('input').val('');
            $row.find('select').prop('selectedIndex', 0);
            $body.append($row);
            prRecalc(tableId);
        }

        function prRemoveRow(btn, tableId) {
            var $body = $('#' + tableId + ' tbody');
            if ($body.find('tr').length <= 1) {
                $(btn).closest('tr').find('input').val('');
                $(btn).closest('tr').find('select').prop('selectedIndex', 0);
            } else {
                $(btn).closest('tr').remove();
            }
            prRecalc(tableId);
        }

        /** Line values and the estimated total, so the amount band is visible up front. */
        function prRecalc(tableId) {
            var total = 0;
            $('#' + tableId + ' tbody tr').each(function () {
                var q = parseFloat($(this).find('.pr-qty').val()) || 0;
                var r = parseFloat($(this).find('.pr-rate').val()) || 0;
                var v = q * r;
                total += v;
                $(this).find('.pr-value').text(v.toFixed(2));
            });
            $('#pr-total').text(total.toFixed(2));
            $('#pr-total-hint').toggle(total > 50000);
        }

        $(document).on('input', '.pr-qty, .pr-rate', function () {
            prRecalc($(this).closest('table').attr('id'));
        });
    </script>

</body>

</html>
