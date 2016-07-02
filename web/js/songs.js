$(function(){
    $('#song-search-form').validate({
        submitHandler : function (form) {
            showTable(form);
        }
    })
});
function showTable(form) {
    $('.data-table-wrapper').show();
    Helper.showSpinner();
    var alreadyInit = $.fn.dataTable.isDataTable('#songs-table');
    if(alreadyInit) {
        $('#songs-table').DataTable().destroy();
    }
    $.fn.dataTable.ext.errMode = 'throw';

    try {
        filterTable = $('#songs-table').DataTable({
            "bPaginate": true,
            "bButtons" : false,
            "bAutoWidth": true,
            "processing" : true,
            "fixedHeader": true,
            "serverSide" : true,
            ///"order": [[ 10, "desc" ]],
            "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                // Bold the grade for all 'A' grade browsers
                $('td:eq(0)', nRow).html( '<img class="song-image" src="' + aData.image + '"/><a target="_blank" href="' + aData.source+ '">' + aData.displayName + '</a>' );

                if(aData.artists) {
                    var html = '';
                    for (i in aData.artists) {
                        html += aData.artists[i].name + ',';
                    }
                    $('td:eq(2)', nRow).html(html);
                }

                if(aData.links) {
                    var html = '';
                    html += '<a href="'+ aData.links.edit+'" alt="Edit song"><i class="fa-edit fa"></i> </a>';
                    html += ' | <a href="'+ aData.links.delete+'" alt="Delete song"><i class="fa fa-trash"></i> </a>';
                    $('td:eq(3)', nRow).html(html);
                }

            },
            "ajax" : {
                complete: function(){
                    Helper.hideSpinner();
                   // $('[data-toggle="tooltip"]').tooltip();
                },
                url: '/admin/songs/search',
                method: "post",
                error: function(xhr, textStatus, error){
                    //handleAjaxError(xhr, textStatus, error);
                    //$(form).find(':submit').button('reset');
                },
                data : function(d) {
                    getFormCriteria(d, form);
                }
            },
            "columns": getColumns()
        });
    }
    catch(e) {
        console.log('error', e);
        toastr.success('Something went wrong. Please try again!');
    }
}

function getFormCriteria(d, form){
    data = $(form).serializeArray();
    for(i in data) {
        d[data[i].name] = data[i].value
    }
    return d;
}

function getColumns(){
    return [
        { "data": "displayName", title: "Title" },
        { "data": "played",     title: "Played Times"},
        { "data": "artists", title: "Artist(s)"},
        { "data": "links", title : "Actions"}
    ];
}