;(function() {

	var a;

	$(document).ready(function() {
		init();
	});

	function init() {
		getDocs();
		
		$('#new-file').click(function(e) {
			e.preventDefault(); //prevent hashtag display
			
			modal.title = "New document";
			modal.default_text = "Untitled document";
			modal.callback = function(text) {
				
				if (String.prototype.trim) text = text.trim();
				
				$.post('api/new_spread.php', {'docname':text}, function(response) {
				
					$('.errors').html('').hide();
				
					if (response.status == 'error') {
						for (var i in response.errors) {
						
							var alert = $('<div>').addClass('alert alert-error fade in');
							var btn = $('<button>').addClass('close').attr({'type':'button', 'data-dismiss':'alert'}).html('&times;');
							var msg = $('<small>').text(response.errors[i]);
							
							alert.append(btn).append(msg);
							
							$('.errors').append(alert);
							
							break;
						}
						
						$('.errors').fadeIn('fast');
						
					} else {
					
						getDocs();
						
						modal.dismiss();
						
					}

				
				}, 'json');
				
				return false;
				
			}
			
			modal.show();
		});
	}
	
	function getDocs() {
		var tr;
		
		//remove all
		$('#docs tbody').html('');
		
		$.get('api/get_spread.php', {}, function(response) {
		
			console.log(response);
		
			for (var i in response.files) {
				
				var file = response.files[i];
				
				var html = '<td>' + (parseInt(i)+1) + '</td>';
				html += '<td>' + file.docname + '</td>';
				html += '<td>' + file.data + '</td>';
				html += '<td><a href="doc.php?id='+file.id+'" target="_blank" class="btn btn-small btn-success"><i class="icon-pencil icon-white"></i></a> ';
				html += '<button type="button" class="btn btn-small btn-danger"><i class="icon-trash icon-white"></i></button></td>';
				
				tr = $('<tr>').html(html);
				
				$('#docs tbody').append(tr);
			}
		
		}, 'json');
		
	}

})();