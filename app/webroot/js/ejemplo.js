/**
 * Created by LockonDaniel on 8/22/14.
 */



$(document).ready(function(){

   $("#Submit").click(function(){
       console.log(JSON.stringify( {title:$("#Title").val(),body:$("#Body").val()}));
       $.post("Posts/ajaxExample",
           {title:$("#Title").val(),body:$("#Body").val()}
       );

   });

});