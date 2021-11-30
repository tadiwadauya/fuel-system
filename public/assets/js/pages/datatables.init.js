$(document).ready(function () {
    $("#datatable").DataTable(), $("#datatable-buttons").DataTable({
        buttons: ["copy", "excel", "pdf","print", "colvis"]
    }).buttons().container().appendTo("#datatable-buttons_wrapper .col-md-6:eq(0)")
});
