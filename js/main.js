;(function() {

	var a;

	$(document).ready(function() {
		init();
	});

	function init() {
		getDocs();
		
		$('#new-file').click(function(e) {
			e.preventDefault(); //prevent hashtag display
			
			alert('new file');
		});
	}
	
	function getDocs() {
		var tr;
		
		$.get('api/get_spread.php', {}, function(response) {
		
			console.log(response);
		
			for (var i in response.files) {
				
				var file = response.files[i];
				
				var html = '<td>'+i+'</td>';
				html += '<td>'+files.docname+'</td>';
				html += '<td>'+files.data+'</td>';
				html += '<td><a href="doc.html" target="_blank" class="btn btn-success">edit</a></td>';
				
				tr = $('<tr>').html(html);
				
				$('#docs tbody').append(tr);
			}
		
		}, 'json');
		
	}

})();