$(function($) {
    // this bit needs to be loaded on every page where an ajax POST may happen
    $.ajaxSetup({
        data: {
            _ctoken: Cookies.get('_ccookie')
        }
    });
    // now you can use plain old POST requests like always
});
$(document).ready(function() {
    var oTable = $('#big_table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "pageLength": 20,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('admin/' . $module . '/data_list') ?>",
            "type": "POST",
        },
        //Set column definition initialisation properties.
        "columnDefs": [{
            "targets": [-1], //first column / numbering column
            "searchable": false, //set not orderable
            "orderable": false, //set not orderable
        }, ],
        "fnDrawCallback": function(oSettings) {
            $.ajaxSetup({
                data: {
                    _ctoken: Cookies.get('_ccookie')
                }
            });
        }
    });
});