// npm package: datatables.net-bs5
// github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5

$(function () {
    "use strict";
    // Coba Tes

    $(function () {
        $("#dataTableExample").DataTable({
            aLengthMenu: [
                [10, 30, 50, -1],
                [10, 30, 50, "All"],
            ],
            iDisplayLength: 10,
            language: {
                search: "",
            },
            orderCellsTop: true,
            fixedHeader: true,
        });
    });

    $("#dataTableExample").each(function () {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable
            .closest(".dataTables_wrapper")
            .find("div[id$=_filter] input");
        search_input.attr("placeholder", "Search");
        search_input.removeClass("form-control-sm");
        // LENGTH - Inline-Form control
        var length_sel = datatable
            .closest(".dataTables_wrapper")
            .find("div[id$=_length] select");
        length_sel.removeClass("form-control-sm");
    });

    $("#exportPdf").DataTable({
        buttons: [
            {
                extend: "pdf",
                text: "Save current page",
                exportOptions: {
                    modifier: {
                        page: "current",
                    },
                },
            },
        ],
    });

    $(function () {
        $("#produk_tabel").DataTable({
            aLengthMenu: [
                [10, 30, 50, -1],
                [10, 30, 50, "All"],
            ],
            iDisplayLength: 10,
            language: {
                search: "",
            },
            orderCellsTop: true,
            fixedHeader: true,
        });
    });

    $("#produk_tabel").each(function () {
        var datatable = $(this);
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        var search_input = datatable
            .closest(".dataTables_wrapper")
            .find("div[id$=_filter] input");
        search_input.attr("placeholder", "Search");
        search_input.removeClass("form-control-sm");
        // LENGTH - Inline-Form control
        var length_sel = datatable
            .closest(".dataTables_wrapper")
            .find("div[id$=_length] select");
        length_sel.removeClass("form-control-sm");
    });

    $("#dataTableExample").each(function () {
        var datatable = $(this);
    
        // Add the date range filter inputs
        var date_range_filter = $('<div class="form-group date-range-filter">' +
            '<label>Date Range:</label>' +
            '<input type="text" class="form-control form-control-sm start-date" placeholder="Start Date">' +
            '<span class="mx-2">-</span>' +
            '<input type="text" class="form-control form-control-sm end-date" placeholder="End Date">' +
            '<button class="btn btn-primary btn-sm filter-btn">Filter</button>' +
            '<button class="btn btn-default btn-sm reset-btn">Reset</button>' +
            '</div>').appendTo(datatable.closest(".dataTables_wrapper").find(".dataTables_filter"));
    
        // Handle the filter button click event
        date_range_filter.find(".filter-btn").click(function () {
            var start_date = date_range_filter.find(".start-date").val();
            var end_date = date_range_filter.find(".end-date").val();
            if (start_date !== "" && end_date !== "") {
                datatable.DataTable().column(3).search(start_date + " - " + end_date).draw();
            }
        });
    
        // Handle the reset button click event
        date_range_filter.find(".reset-btn").click(function () {
            date_range_filter.find(".start-date").val("");
            date_range_filter.find(".end-date").val("");
            datatable.DataTable().column(3).search("").draw();
        });
    
        // SEARCH - Add the placeholder for Search and Turn this into in-line form control
        // var search_input = datatable.closest(".dataTables_wrapper").find("div[id$=_filter] input");
        // search_input.attr("placeholder", "Search");
        // search_input.removeClass("form-control-sm");
    
        // LENGTH - Inline-Form control
        var length_sel = datatable.closest(".dataTables_wrapper").find("div[id$=_length] select");
        length_sel.removeClass("form-control-sm");
    });

    var minDate, maxDate;
 

    


});
