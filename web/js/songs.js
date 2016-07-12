$(function(){
    $('#song-search-form').validate({
        submitHandler : function (form) {
            showTable(form);
        }
    });

    $('#song_tags').select2({
        tags:true
    });

    $('#song_genre').select2({
        tags:true

    });
    $('.show-upload-area').click(function(){
        $(this).closest('.form-group').find('.uploaded-song-wrapper').hide();
        $(this).closest('.form-group').find('.upload-song-wrapper').removeClass('hide');
    });
    $('.album-artist-list').select2({
        tags: true,
        tokenSeparators: [","],
        multiple: true,
        ajax: {
            url: "/api/artists/search",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;
                var mappedData = $.map(data, function(el) { return el });
                return {
                    results: mappedData,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatArtists, // omitted for brevity, see the source of this page
        templateSelection: formatArtistsSelection
    });

    function formatArtists (artist) {
        if (artist.loading) return artist.text;
        if(!artist.name) { return null; }
        var markup = "<div class='select2-result-repository clearfix'>" +
            "<div class='row'><div class='col-md-2'><img src='"+artist.image+"' style='"+
            "width: 100%;'></div><div class='col-md-9'><div class='select2-result-repository__title'><p>"+
            "<b>"+artist.name+"</b></p>"+
            "</div></div></div></div>";

        return markup;
    }

    function formatArtistsSelection (artist) {
        return artist.name || artist.text;
    }
    $('#song-create-form').validate({
        errorPlacement : function(error, elem) {
            error.appendTo(elem.closest('.form-group'))
        },
        submitHandler : function(form){
            $(form).ajaxSubmit({
                beforeSubmit : function () {
                    Helper.showSpinner();
                    $(form).find(':submit').button('loading');

                },
                success : function (data) {
                    Helper.hideSpinner();
                    toastr.success(data.message);
                    window.location.href = data.path;
                },
                complete : function(){
                    $(form).find(':submit').button('reset');
                    Helper.hideSpinner();
                }
            });
        }
    });

    $('#song-edit-form').validate({
        errorPlacement : function(error, elem) {
            error.appendTo(elem.closest('.form-group'))
        },
        submitHandler : function(form){
            $(form).ajaxSubmit({
                beforeSubmit : function () {
                    Helper.showSpinner();
                    $(form).find(':submit').button('loading');

                },
                success : function (data) {
                    Helper.hideSpinner();
                    toastr.success(data.message);
                    //window.location.href = data.path;
                },
                complete : function(){
                    $(form).find(':submit').button('reset');
                    Helper.hideSpinner();
                }
            });
        }
    });
    /* Image upload */
    /* Image upload */
    $('.upload-song-image-trigger').click(function () {
        var form = $('<form action="/admin/songs/'+$(this).data('target')+'/image" enctype="multipart/form-data" method="post"></form>');
        var input = $('<input type="file" name="image" onchange="showPop(this)"/>');
        input.appendTo(form);
        input.trigger('click');
        return false;
    });


});

function showPop(elem)
{

    var form = $($(elem).closest('form')[0]);
    var date = new Date();
    form.ajaxSubmit({
        beforeSubmit:Helper.showSpinner,
        success:function(data){
            Helper.hideSpinner();
            $('#model-wrapper').html(data);
            $('#modal-album-banner').modal();

        },
        error:function(){
            Helper.hideSpinner();
            toastr.error('error', 'Image is too large. Please try again with different image');
        }
    });
}
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
                    var html = [];
                    for (i in aData.artists) {
                        html.push(aData.artists[i].name);
                    }
                    $('td:eq(2)', nRow).html(html.join(', '));
                }

                if(aData.links) {
                    var html = '';
                    html += '<a href="'+ aData.links.edit+'" alt="Edit song"><i class="fa-edit fa"></i> </a>';
                    html += ' | <a class="confirm" href="#" data-confirm="Are you sure?" data-callback="deleteSong" data-href="'+ aData.links.delete+'" alt="Delete song"><i class="fa fa-trash"></i> </a>';
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
        { "data": "played", title: "Played Times"},
        { "data": "artists", title: "Artist(s)"},
        { "data": "links", title : "Actions"}
    ];
}

function deleteSong(data, e){
    toastr.success(data.message);
    e.closest('tr').hide();
}



