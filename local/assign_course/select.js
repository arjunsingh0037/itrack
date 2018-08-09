$(document).ready(function(){

    $("#id_courses").change(function(){
        var deptid = $(this).val();

        $.ajax({
            url: 'courseajax.php',
            type: 'post',
            data: {id:deptid},
            dataType: 'json',
            success:function(response){

                var len = response.length;

                $("#id_users").empty();
                for( var i = 0; i<len; i++){
                    var id = response[i]['id'];
                    var name = response[i]['name'];
                    
                    $("#id_users").append("<option value='"+id+"'>"+name+"</option>");

                }
            }
        });
    });

});