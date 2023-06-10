$(document).ready(function () {
    let currenciesTable = $('#currencies-table').DataTable({
        "ordering": true,
        "bInfo": true,
        "bLengthChange": false,
        "searching": true,
        "pageLength": 10,
        "order": [[0, "asc"]],
        responsive: true,
        bPaginate: true,
        "columnDefs": [
            { "targets": 0, "orderable": true, "width": "10%" },
            { "targets": 1, "orderable": true, "width": "10%", "responsivePriority": 0 },
            { "targets": 2, "orderable": true, "width": "10%", "responsivePriority": 1 },
        ],
    });

    let exchangeHistoryTable = $('#exchange-history-table').DataTable({
        "ordering": true,
        "bInfo": true,
        "bLengthChange": false,
        "searching": true,
        "pageLength": 10,
        "order": [[2, "desc"]],
        responsive: true,
        bPaginate: true,
        "columnDefs": [
            { "targets": 0, "orderable": true, "width": "10%" },
            { "targets": 1, "orderable": true, "width": "10%", "responsivePriority": 0 },
            { "targets": 2, "orderable": true, "width": "10%", "responsivePriority": 1 },
        ],
    });
});