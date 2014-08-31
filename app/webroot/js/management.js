/**
 * Created by LockonDaniel on 8/30/14.
 */

$(document).ready(function(){
$('#btn-enviar').click(function(event){
    event.preventDefault();

    if($('#btn-enviar').hasClass("usuario-modificar"))
    {

        $.post('Users/executeMod',
            $('#form-create-user').serialize(),
            function(data){
                alert(data);
            }

        );
    }
    else
    {
        $.post('Users/createUser',
            $('#form-create-user').serialize(),
            function(data){
                alert(data);
            }

        );
    }

});

$('.delete-element').click(function(event)
{
   event.preventDefault();
   $('#usuario-span-eliminar').append('{{usuario.User.username}}')
});


});

function deleteElement(id,name,clase)
{
    $('.span-eliminar-elemento').html(name);
    $('.funcion-eliminar').click(function(){
        $.post(clase+'/eliminar',{'id':id},
        function()
        {
            $("#modal-preguntar").modal('hide');
            $("#modal-confirmar").modal('show');
            $(".ver-usuario").empty();
        });
    });
}