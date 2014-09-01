/**
 * Created by LockonDaniel on 8/31/14.
 */

$(document).ready(function(){
    $("#button-logout").click(function(){
        $.post("Users/Logout");
    });

})
