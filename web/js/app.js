$(function() {
    toastr.options.closeButton = true;
    toastr.options.closeMethod = 'fadeOut';
    toastr.options.closeDuration = 30;
    toastr.options.progressBar = true;
    toastr.options.closeEasing = 'linear';


	$('#form-create-artist').validate({
        submitHandler : function(form){
            $(form).ajaxSubmit({
                success: function(data){
                    window.location.href = data.path;
                }
            });
        }
    });

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

    $('.album-wrapper .song-selector').select2({
        ajax: {
            url: "/api/songs/search",
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
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection
    });
    $('.album-wrapper .song-selector').on("select2:selecting", function(e,d) {
        // what you would like to happen
        var selectedSong = e.params.args.data;
        var albumId = $('#album-id').val();
        $.post('/admin/albums/'+ albumId +'/songs', { songs : [selectedSong.id]}, function (data) {
            data = $.parseJSON(data);
            for(i in data) {
                console.log(data[i]);
                $('.album-wrapper .album-song-list').append("<tr>" +
                    "<td><img src='" + data[i].image+ "' width='80'></td>" +
                    "<td>" + data[i].display_name+ "</td>" +
                    "</tr>");
            }

        });
    });
    function formatRepo (song) {
        if (song.loading) return song.text;

        var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='row'><div class='col-md-1'><img src='"+song.image+"' style='"+
                "width: 100%;'></div><div class='col-md-9'><div class='select2-result-repository__title'><p>"+
                "<b>"+song.display_name+"</b></p>"+
                "<p>" + song.artist.name+"</p></div></div></div></div>";
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.full_name || repo.text;
    }
});
