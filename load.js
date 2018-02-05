
  (function() {
    $.ajax({
      	url: 'samplejson.js',
      	
    }).done(function(response) {
    	var dat = response;
      console.log("check");
      console.log(response.mydata);
      var res = response.thesis_id;
      $.each(res, function(key, value) {
      	console.log(value);
      });
    });
  })();