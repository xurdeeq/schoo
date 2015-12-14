$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-Token": $("meta[name=_token").attr("content")
        }
    });
  // This command is used to initialize some elements and make them work properly
  $.material.init();

  $('.delete').on('click', function(){
    var token = $(this).attr('data-token');
    var id = $(this).attr('data-id');
    var URL = "/courses/"+id+"/delete";
    var data = [{
        _token : token,
        id : id
    }];
    alertMethod(URL, data);
  });

  $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
  });

});
function alertMethod(route, data)
{
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this course!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
      },
      function( isConfirm ) {
        if (isConfirm) {
            processAjax(route, data);
        }
      });
}
function processAjax (URL, data)
{
    $.ajax({
        url: URL,
        type: 'DELETE',
        data: data,
        success: function(msg) {
            if(msg.status_code === 200) {
                swal({
                        title: "Done!",
                        text : msg.message,
                        type: "success",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                    },
                    function () {
                        document.location.href = "/courses";
                    }
                );
            }
        }
    });
}
