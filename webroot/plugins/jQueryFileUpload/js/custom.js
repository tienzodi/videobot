//var url = '/2014/Backend/demo/plugins/jQueryFileUpload/server/php/';
//var upload_temp = 'files/uploads/temp/';
//upload_init('upload_1');
function upload_init(id, multiple, max_size)
{
        $('#'+id).fileupload({
        url: url+'index.php',
        dataType: 'json',
        add: function(e, data) {
            var uploadErrors = [];
            var acceptFileTypes = /^image\/(gif|jpe?g|png)$/i;
            if(data.originalFiles[0]['type'] && !acceptFileTypes.test(data.originalFiles[0]['type'])) {
                uploadErrors.push('Not an accepted file type');
            }
            if(data.originalFiles[0]['size'] && data.originalFiles[0]['size'] > max_size && max_size > 0) {
                uploadErrors.push('Filesize is too big (<'+max_size+' B)');
            }
            if(uploadErrors.length > 0) {
                alert(uploadErrors.join("\n"));
            } else {
                data.submit();
            }
        },
        done: function (e, data) {
            $.each(data.result.files, function (index, file) {
                var parent = $('div.wrapper_upload_'+id);
                var msg = ""; 
                if(typeof file.error === 'undefined')
                {
                    //var deleteUrl = file.deleteUrl;
                    var deleteUrl = upload_temp + file.name;
                    var del = '<a href="javascript:void(0)" wrapper_upload="'+id+'" data-url="'+deleteUrl+'" data-type="DELETE" class="btn btn-danger delete"><i class="glyphicon glyphicon-trash"></i></a> ';
                    var img = '<img src="'+file.thumbnailUrl+'"/>';//url
                    error = "<span style='color:green'>Success</span>";
                    //progressall(parent, e, data);
                    msg =  del + img;
                    if(multiple == 'multiple')
                    {
                        var data_url = '<input type="hidden" name="multi_'+id+'[]" value="'+upload_temp+file.name+'"/>';
                        msg += data_url;
                        ($('<p/>').html(msg)).appendTo('#files_'+id);
                    }
                    else
                    {
                        autoDeleteImage(parent);
                        parent.find('#files_'+id).html($('<p/>').html(msg));
                    }
                    parent.find('input[data-type="result"]').val(upload_temp + file.name);
                    parent.find('input[data-type="result"]').change();
                }
                else
                {
                    error = "<span style='color:red'>" + file.error + "<span>";
                    msg = error;
                    if(multiple == 'multiple')
                    {
                        ($('<p/>').html(msg)).appendTo('#files_'+id);
                    }
                    else
                    {
                        autoDeleteImage(parent);
                        parent.find('#files_'+id).html($('<p/>').html(msg));
                    }
                    parent.find('input[data-type="result"]').val("");
                    parent.find('input[data-type="result"]').change();
                }
            });
        },
        progressall: function (e, data) {
            var parent = $('div.wrapper_upload_'+id);
            progressall(parent, e, data);
		}
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');
}


function progressall(parent,e,data)
{
    parent.find('.progress-bar').css('width', '0%');
    parent.find("#progress").fadeIn();
    var progress = parseInt(data.loaded / data.total * 100, 10);
    parent.find('#progress .progress-bar').css(
        'width',
        progress + '%'
    );
    if(progress == 100)
        setTimeout(function(){parent.find("#progress").fadeOut();},1000);
}

function autoDeleteImage(parent)
{
    parent.find('.files a').each(function(index, element){
        var data_url = $(this).attr('data-url');
        $.ajax({
			url: url + 'delete.php',
    		type:'post',
            data:{data_url:data_url},
    		success:function(data){
    		  
    		}
		});
    });
}

    
$(document).ready(function(){
    $( document ).on( "click", "a.delete", function() {
        var data_url = $(this).attr('data-url');
		if(typeof data_url !== 'undefined'){
			var wrapper_upload = $(this).attr('wrapper_upload');
			var $this = $(this);
			$.ajax({
				url: url + 'delete.php',
				type:'post',
				data:{data_url:data_url},
				success:function(data){
				  if(data == 1)
				  {
						var _this = $this;
						_this.parent('p').remove();
						$('.wrapper_upload_' + wrapper_upload).find('input[data-type="result"]').val('');
						$('.wrapper_upload_' + wrapper_upload).find('input[data-type="result"]').change();
				  }
				  else
				  {
						alert("Can't delete image!");
				  }
				}
			});
		}
    });

});