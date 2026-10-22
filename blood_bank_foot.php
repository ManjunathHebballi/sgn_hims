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
            $('table.bb-table').each(function () {
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
    </script>

</body>

</html>
