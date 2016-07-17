$(function() {
    toastr.options.closeButton = true;
    toastr.options.closeMethod = 'fadeOut';
    toastr.options.closeDuration = 30;
    toastr.options.progressBar = true;
    toastr.options.closeEasing = 'linear';

    $(document).on( 'click','.dialog', function(){
        Helper.dialog($(this));
        return false;
    });

    $('.upload-banner-trigger').click(function () {
        var form = $('<form action="/admin/albums/'+$(this).data('target')+'/banner" enctype="multipart/form-data" method="post"></form>');
        var input = $('<input type="file" name="banner" onchange="showPop(this)"/>');
        input.appendTo(form);
        input.trigger('click');
        return false;
    });

    $(document).on( 'click','.confirm', function(){
        Helper.confirm($(this));
        return false;
    });



    $('#form-create-album #album_albumId').blur(function(){
        var that = $(this);
        if($(this).val() != '') {
            $.get('/admin/match-id/' + $(this).val() + '/album', function(data){
                if(data.exists) {
                    that.focus();
                    toastr.error('This artist id exists. Please enter new artist ID');
                }
            })
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
        templateResult: formatSongs, // omitted for brevity, see the source of this page
        templateSelection: formatSongSelection
    });



    $('.album-wrapper .song-selector').on("select2:selecting", function(e,d) {
        // what you would like to happen
        var selectedSong = e.params.args.data;
        var albumId = $('#album-id').val();
        Helper.showSpinner();
        $.post('/admin/albums/'+ albumId +'/songs', { songs : [selectedSong.id]}, function (data) {
            toastr.success('Song Added!');
            data = $.parseJSON(data);
            Helper.hideSpinner();
            var str = '';
            for(i in data) {
                str =  "<tr>" +"<td width='40'><img src='" + data[i].image+ "' width='30'></td>" +
                "<td><p>" + data[i].display_name+ "</p>";
                str +=  data[i].num_of_played ?  "<small><i class='fa fa-music'></i> "+ data[i].num_of_played +" times played</small>"  : "";
                str += "</td><td><a href='#' data-href='/admin/albums/" + albumId + "/songs/delete/" + data[i].id+ "' class='text-danger confirm' title='Remove song' class='confirm text-danger' data-confirm='Are you sure?' data-callback='updateSongList'><i class='fa fa-trash-o'></i> </a> </td></tr>";

                $('.album-wrapper .album-song-list').append(str);
            }
        });
    });

    function formatSongs (song) {
        if (song.loading) return song.text;

        var markup = "<div class='select2-result-repository clearfix'>" +
                "<div class='row'><div class='col-md-2'><img src='"+song.image+"' style='"+
                "width: 100%;'></div><div class='col-md-9'><div class='select2-result-repository__title'><p>"+
                "<b>"+song.display_name+"</b></p>";
        if(song.artists.length > 0) {
            for (i in song.artists) {
                markup += "<span>" + song.artists[i].name+"</span>";
            }
        }
        markup += "</div></div></div></div>";


        return markup;
    }

    function formatSongSelection (song) {
        return song.full_name || song.text;
    }

});

function updateSongList(data, ele){

    ele.closest('tr').remove();
}

var Helper = {
    showSpinner : function(){
        $('#waiting-div').fadeIn();
    },
    hideSpinner : function() {
        $('#waiting-div').fadeOut();
    },
    dialog: function(element){
        var url = element.data('href');
        Helper.showSpinner();
        $.get(url, function(data){
            Helper.hideSpinner();
            $('#modal-wrapper .modal-dialog').html(data);
            $('#modal-wrapper').modal('show');
        });
        return false;
    },
    trigger: function(element){
        var url = element.data('href');
        var callback =  element.data('callback');
        var loadingText = element.data('loading-text');
        var loadingTextShowable = typeof  loadingText != 'undefined' ? true : false;
        Helper.showSpinner();

        if(loadingTextShowable) {
            element.button('loading');
        }

        $.get(url, function(data){
            if(loadingTextShowable) {
                element.button('reset');
            }
            Helper.hideSpinner();
            if(window[callback]) {
                window[callback](data);
            }
        });
        return false;
    },
    confirm: function(element){
        var url = element.data('href');
        Helper.showSpinner();
        var confirm_massage = element.data('confirm');
        var callback = element.data('callback');
        var modal = element.data('modal');
        var func = window[callback];

        if(typeof modal =='undefined') {
            if(window.confirm(confirm_massage)) {
                Helper.showSpinner();
                $.get(url, function(data){
                    Helper.hideSpinner();
                    console.log(typeof func);
                    if(typeof  func == 'function'){
                        func(data, element);
                    }
                });
            }
        }
        else {
            $( "#dialog" ).dialog({
                resizable: false,
                height:200,
                width:400,
                modal: true,
                title:element.data('title'),
                open:function(){
                    $( "#dialog .dialog-content").html(confirm_massage);
                },
                buttons: {
                    "Yes": function() {
                        that= $(this)
                        $.get(url, function(data){
                            that.dialog( "close" );
                            if(typeof  func == 'function'){
                                func(data, element);
                            }

                        });

                    },
                    "Cancel": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        }
        Helper.hideSpinner();
        return false;
    },
    closeModal:function(){
        $('#modal-wrapper').modal('hide');

    }

};
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
            /*if(data.success) {
                var image = data.file_path;
                $('body').find('#image-cropper').html(
                    '<p><small class="advise">Drag the square to change position.</small></p>'+
                    '<img src="'+data.file_path+'?__t='+date.getTime()+'" style="height="/><div class="footer-bar"><input type="button" value="Set the Profile Picture" class="close-dialog"/></div>'
                );
                $('#image-cropper').dialog({
                    'width' : 'auto',
                    'modal': true,
                    title: 'Edit Profile Photo'

                });

                $('#image-cropper').closest('.ui-dialog').addClass('left-20');
                $('#image-cropper').find('.close-dialog').blur();
                jcrop_api = $('#image-cropper img').Jcrop({
                    setSelect:   [0,0,302, 352],
                    allowResize : false,
                    allowSelect:false,
                    onChange: updateCords
                });

            }
            else {
                alert(data.message);
            }*/
        },
        error:function(){
            Helper.hideSpinner();
            Helper.message_old('error', 'Image is too large. Please try again with different image');
        }
    });
}

