$(function(){
    $('.album-artist-list').select2({
        tags: true,
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
                var mappedData = $.map(data, function(el) {
                    //el.id = el.artist_id;
                    //console.log(el);
                    return el
                });
                return {
                    results: mappedData,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            //cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 2,
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
});
