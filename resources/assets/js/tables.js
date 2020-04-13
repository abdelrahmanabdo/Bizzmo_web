/* Customizing tables style to match new designs */
$(document).ready(function() {
    //data tables
    $('#mytable').dataTable({	
        "order": [],
        "aoColumnDefs": [ { 'bSortable': false, 'aTargets': [ "no-sort" ] }],
        "iDisplayLength": 10,			
        "pagingType": "numbers",
        "info": false	
    });
	$('#listtable').dataTable({	
        "order": [],
		"aoColumnDefs": [ { 'bSortable': false, 'aTargets': [ "no-sort" ] } ],
		"paging": false,
		"bFilter" : false,
		"bLengthChange": false,
        "info": false
    });
    var pagination = $('.paging_numbers');
    if(pagination.length == 1){
        $(pagination).parent().addClass('bm-pagination');
    } else {
        pagination.each(function(){
            $(this).parent().addclass('bm-pagination');
        })
    }
});