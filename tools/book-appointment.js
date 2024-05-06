

$(document).ready(function () {

	function getdoctor(val) {
		$.ajax({
			type: "POST",
			url: "../patient/get-details.php",
			data:'specilizationid='+val,
			success: function(data){
				$("#doctor").html(data);
			}
		});
	}

	function getfee(val) {
		$.ajax({
			type: "POST",
			url: "../patient/get-details.php",
			data:'doctor='+val,
			success: function(data){
				$("#fees").html(data);
			}
		});
	}

	function gettimes(val, doc) {
		$.ajax({
			type: "POST",
			url: "../patient/get-details.php",
			data:'appdate='+val+'&id='+doc,
			success: function(data){
				$("#myslots").html(data);
			}
		});
	}

	
});
