$(function(){
    $('#song-search-form').submit(function(){
        showTable($('#song-search-form'));
        return false;
    });

    showTable($('#song-search-form'));

    $('#form-create-artist #artist_artistId').blur(function(){
        if($(this).val() != '') {
            $.get('/admin/match-id/' + $(this).val() + '/artist', function(data){
                if(data.exists) {
                    toastr.error('This artist id exists. Please enter new artist ID');
                }
            })
        }

    });
    $('#form-edit-artist').validate({
        submitHandler : function(form){
            $(form).ajaxSubmit({
                success: function(data){
                    //window.location.href = data.path;
                    toastr.success(data.message)
                }
            });
        }
    });
    $('#form-edit-artist').validate({
        submitHandler : function(form){
            $(form).ajaxSubmit({
                success: function(data){
                    //window.location.href = data.path;
                    toastr.success(data.message)
                }
            });
        }
    });
    $('.album-artist-list ').select2({
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

    function showTable(form) {
        $('.data-table-wrapper').show();
        Helper.showSpinner();
        var alreadyInit = $.fn.dataTable.isDataTable('#artist-table');

        if(alreadyInit) {
            $('#artist-table').DataTable().destroy();
        }
        $.fn.dataTable.ext.errMode = 'throw';

        try {
            filterTable = $('#artist-table').DataTable({
                "bPaginate": true,
                "bButtons" : false,
                "bAutoWidth": true,
                "processing" : true,
                "fixedHeader": true,
                "serverSide" : true,
                ///"order": [[ 10, "desc" ]],
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
                    // Bold the grade for all 'A' grade browsers
                    $('td:eq(0)', nRow).html( '<object class="alt-image" data="/images/sample-album.png" type="image/png"><img class="song-image" src="' + aData.image + '"/></object><a href="/admin/artists/edit/' + aData.artistId + '">' + aData.artistId + '</a>' );

                    if(aData.albums) {
                        var html = [];
                        for (i in aData.albums) {
                            html.push('<a href="/admin/albums/'+ aData.albums[i].albumId+'">' + aData.albums[i].name + "</a>");
                        }

                        $('td:eq(2)', nRow).html(html.join(', '));
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
                    url: '/admin/artists/search',
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
            toastr.error('Something went wrong. Please try again!');
        }
        return false;
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
            { "data": "artistId", title: "Artist ID" },
            { "data": "name", title: "Name"},
            { "data": "albums", title: "Albums"}
        ];
    }

})