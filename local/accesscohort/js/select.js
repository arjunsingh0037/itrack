//for organization filter we will get cohortid 

$("#id_org_id").click(function(){

	$.get(M.cfg.wwwroot+'/local/accesscohort/ajax/cohort.php?id='+this.value,function(data,status){
		$("#id_cohort_id").empty();
		var list = '';
		//list += '<option value="'+'select-all'+'">'+'Select All'+'</option>';
		for(var i in data){
			
			list += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
		}

		$("#id_cohort_id").html(list);
	});
});
$("#id_cohort_id").click(function(){
	var e = document.getElementById("id_org_id");
	var strOrg = e.options[e.selectedIndex].value;
	
	var selectedValue = [];
	$.each($("#id_cohort_id option:selected"), function(){            
		selectedValue.push($(this).val());

	});
        //alert("You have selected value is - " + selectedValue.join(", "));
        var val = selectedValue.join(",");
        //alert(val);
        $.get(M.cfg.wwwroot+'/local/accesscohort/ajax/course.php?id='+val+'&orgid='+strOrg,function(data,status){

        	$("#id_courseid").empty();
        	var list = '';
        	list += '<option value="'+'select-all'+'">'+'Select All'+'</option>';
        	for(var i in data){
        		list += '<option value="'+data[i].id+'">'+data[i].fullname+'</option>';
        	}
        	$("#id_courseid").html(list);
        });
    });