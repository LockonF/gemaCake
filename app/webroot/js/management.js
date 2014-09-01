/**
 * Created by LockonDaniel on 8/30/14.
 */

$(document).ready(function(){


$('.delete-element').click(function(event)
{
   event.preventDefault();
   $('#usuario-span-eliminar').append('{{usuario.User.username}}')
});


});

    function createOrModElement(clase,metodo,forma)
    {
        $.post(clase+'/'+metodo,
            $('#'+forma).serialize(),
            function(data){
                console.log("Result:"+data);
                if(data=='success')
                {
                    var  alert = $(".alert-success");
                    alert.addClass('in');
                    alert.toggle();
                }
                else
                {
                    var alert = $(".alert-danger");
                    alert.addClass('in');
                    alert.toggle();

                }
                window.setTimeout(function() {
                    alert.removeClass('in');
                    alert.toggle();
                }, 2500);


            }

        );
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