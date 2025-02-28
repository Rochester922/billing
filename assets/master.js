/*     $(function($) {
         // this bit needs to be loaded on every page where an ajax POST may happen
         $.ajaxSetup({
             data: {
                 _ctoken: Cookies.get('_ccookie')
             }
         });
        
     });*/
$(function() {
    // Table setup
    // ------------------------------
    // Setting datatable defaults
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        responsive: true,
        columnDefs: [{
            orderable: false,
            width: '230px',
            targets: [-1]
        }],
        dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Search:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: {
                'first': 'First',
                'last': 'Last',
                'next': '&rarr;',
                'previous': '&larr;'
            }
        },
        drawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function() {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
    // Basic responsive configuration
    $('.datatable-responsive').DataTable();
    // External table additions
    // ------------------------------
    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder', 'Type to search...');
    $('.dataTables_filter').attr('style','float:right;')
    $('.dataTables_length').attr('style','float:left;')
    var actiontools = $('.actiontools').html();
    $('.dataTables_length').after('<div class="action_btns btn-group">'+actiontools+'</div>');
    // Enable Select2 select for the length option
     $('select').select2();
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });
    $(".styled, .multiselect-container input").uniform({
        radioClass: 'choice'
    });
   
    /*Input MASK for MAC Address*/
    $('#mac_mask').inputmask({
        mask: 'hh:hh:hh:hh:hh:hh',
        definitions: {
            h: '[A-Fa-f0-9]',
            n: '[0-9]',
             v: 'A'
        }
    });
});
/*Resellers and dealers drop down*/
function getDealer() {
    var reseller = $("#reseller_drop").val();
    // alert(reseller);
    $.ajax({
        type: "POST",
        url: SiteURL + "/users/get_dealer_dropdown",
        data: 'reseller=' + reseller,
        success: function(data) {
            $("#dealer-list").html(data);
            $('select').select2();
        }
    });
}
/*End Resellers and dealers drop down*/
function package_selecter() {
    var packs = $('#tariff_custom').find("option:selected").text();
    // alert(packs);
    if (packs == "CUSTOM PACKAGE") {
        $('#Custom_Packages').show();
    } else {
        $('#Custom_Packages').hide();
    }
}
/*$("#select_all").click(function() { //"select all" change
    // $('.checkbox_pack').attr('checked','checked')
    $('.checkbox_pack').prop('checked', true);
    //$(".checkbox_pack").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
});*/
function uncheck_all() {
    $('.checkbox_pack').prop('checked', false);
}

function check_all() {
    $('.checkbox_pack').prop('checked', true);
}
/*$("#deselect_all").click(function() { //"select all" change
    // $(".checkbox_pack").prop('checked', $(this).prop("checked",false)); //change all ".checkbox" checked status
    // $('.checkbox_pack').removeAttr('checked');
    
});*/
function show_custom_selection(type) {
    if (type == 'All') {
        $('#cust_sel > select').prop('disabled', true);
    } else {
        $('#cust_sel > select').prop('disabled', false);
        // $('#cust_sel').show();
    }
}
$("#goback").click(function() {
    /* Act on the event */
    history.back();
});

function load_table(path) {
    var oTable = $('#big_table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "pageLength": 20,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": path,
            "type": "POST",
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [-1], //button column / actions column
            "searchable": false, //set not orderable
            "orderable": false, //set not orderable
        }, ],
        "fnDrawCallback": function(oSettings) {
            $.ajaxSetup({
                data: {
                    _ctoken: Cookies.get('_ccookie')
                }
            });
            $('.tooltips').tooltip({
                trigger: 'hover',
                html: true
            });
        }
    });
}