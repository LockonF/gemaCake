/**
 * Created by LockonDaniel on 8/30/14.
 */

$(document).ready(function(){
    $('.btn').button();

$('#changeFileTrue').change(function(){
        $("#fileChooser").removeClass('hidden');
});
$('#changeFileFalse').change(function(){
    $("#fileChooser").addClass('hidden');
});




$('.delete-element').click(function(event)
{
   event.preventDefault();
   $('#usuario-span-eliminar').append('{{usuario.User.username}}')
});


});

    function createOrModElement(clase,metodo,forma)
    {

           var formElement = document.getElementById(forma)
            var formData = new FormData(formElement);
            $.ajax({
                url: clase+"/"+metodo,
                type: "POST",
                data: formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success: function(data){
                    if(data=='success')
                    {
                        var  alert = $(".alert-success");
                        alert.addClass('in');
                        alert.toggle();
                    }
                    else
                    {
                        $("#error").html(data);
                        var alert = $(".alert-danger");
                        alert.addClass('in');
                        alert.toggle();

                    }
                    window.setTimeout(function() {
                        alert.removeClass('in');
                        alert.toggle();
                    }, 2500);

                }
            });
        return false;
    }



function deleteElement(id,name,clase,div)
{
    $('.span-eliminar-elemento').html(name);
    $('.funcion-eliminar').click(function(){
        $.post(clase+'/eliminar',{'id':id},
        function()
        {
            $("#modal-preguntar").modal('hide');
            $("#modal-confirmar").modal('show');
            $(".ver-"+div).empty();
        });
    });
}