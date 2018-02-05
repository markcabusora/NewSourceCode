$(document).ready(function(){
	$.ajax({
		//url : "http://localhost/New%20Source%20Code/mostcitedthesisdata.php",
		url : "mostcitedthesisdata.php",
		type : "GET",
		success :  function(data) {
			//console.log(data);
			alert(data);
			
			var thesisID = [];
			var count = [];
			var chartdata = data;

			for(var i in data) {
				thesisID.push(data[i].thesisID);
				count.push(data[i].count);
			}

			var ctx = $("#mycanvas");

			var LineGraph = new Chart(ctx, {
				type : 'line',
				data : chartdata
			});



		},
		error : function(data) {
			alert(data);
		}
	});
});