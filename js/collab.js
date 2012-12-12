;(function() {

	$(document).ready(function() {
		init();
	});
	
	var timestamp = '';
	
	function init() {
		
		generateColumnHeader();
		generateRowHeader();
		
		generateTable();
		
		adjustSize();
		
		$('#table-wrapper').scroll(function() {
			$('#column-header').scrollLeft($(this).scrollLeft());
			$('#row-header').scrollTop($(this).scrollTop());
		});
		
		$('#table-container td').click(function() {
			$('#table-container td').removeClass('cell-selected');
			$(this).addClass('cell-selected');
			
			$('#bold-btn').removeClass('active');
			$('#italic-btn').removeClass('active');
			
			if ($('.cell-content', this).hasClass('style-bold')) {
				$('#bold-btn').addClass('active');
			}
			
			if ($('.cell-content', this).hasClass('style-italic')) {
				$('#italic-btn').addClass('active');
			}
			
			//send selected cell id, cell status and document id
			$.get('api/put_online.php', {docid: docID, cellid: $(this).attr('id'), locked: 0});
		});
		
		$('#table-container td').dblclick(function() {
			var id = $(this).attr('id');
			
			modal.title = "Edit cell " + id;
			modal.default_text = $('#' + id + ' .cell-content').text();
			modal.callback = function(text) {
				insertCellContent(id, text, true);
			}
			
			modal.show();
		});
		
		$(window).resize(function() {
			adjustSize();
		});
		
		$('#document-title-container').click(function() {
			modal.title = "Edit document title";
			modal.default_text = $('#document-title').text();
			modal.callback = function(text) {
				$('#document-title').text(text);
			}
			
			modal.show();
		});
		
		$('#bold-btn').click(function() {
			getSelectedCell().toggleClass('style-bold');
		});
		
		$('#italic-btn').click(function() {
			getSelectedCell().toggleClass('style-italic');
		});
		
		$('.align-btn').click(function() {
			getSelectedCell().css('text-align', $(this).attr('data-align'));
		});
		
		$('#share-btn').click(function() {
			$('#share-dialog').modal('show');
			
			getUsers();
		});
		
		$('#add-user').click(function() {
		
			var email = $('#email').val();
			
			$.post('api/sharedoc.php', {'docID':docID, 'email':email}, function(response) {
				
				$('#share-dialog .errors').html('').hide();
			
				if (response.status == 'error') {
					for (var i in response.errors) {
					
						var alert = $('<div>').addClass('alert alert-error fade in');
						var btn = $('<button>').addClass('close').attr({'type':'button', 'data-dismiss':'alert'}).html('&times;');
						var msg = $('<small>').text(response.errors[i]);
						
						alert.append(btn).append(msg);
						
						$('#share-dialog .errors').append(alert);
						
						break;
					}
					
					$('#share-dialog .errors').fadeIn('fast');
					
				} else {
				
					getUsers();
					
					$('#email').val('');
					
				}

			
			}, 'json');
			
		});
		
	}
	
	function insertCellContent(id, text, put) {
		if (put) {
			$('#' + id + ' .cell-content').text(text);
			
			var outerHtml = $('#' + id + ' .cell-content').clone().wrap('<div>').parent().html();
			$.post('api/put_cell.php', {docid: docID, cell_id: id, content: outerHtml });
		} else {
			$('#' + id + ' .cell-content').remove()
			$('#' + id).append(text);
		}
				
		var cell = id.split('-');
		$('#rh' + cell[1]).height($('#tr'+cell[1]).height() - 11);
		
	}
	
	function getSelectedCell() {
		return $('.cell-selected .cell-content');
	}
	
	function adjustSize() {
		var hh = $('#header').height(); 			//header height
		var ch = $('#column-header').height();		//column header height
		
		var h = $(window).height() - hh - ch;
		
		//scrollbar height IE fix
		if ($.browser.msie && $.browser.version < 9) h -= 16;
		
		//max-height used for IE overflow fix
		$('#row-header').css('max-height', h + 'px');
		$('#table-wrapper').css('max-height', h + 'px');
	}
	
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[#&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
		
		/*
		EXEMPLU FOLOSIRE
		var first = getUrlVars()["id"];
		*/
	}
	
	function generateColumnHeader() {
		var table = $('<table>').css({'table-layout': 'fixed', 'width' : '0' });
		var tr = $('<tr>');
		
		var d = $('<td>').addClass('cell header-cell row-header-cell').html('&nbsp;');
		tr.append(d);
		
		for (var i = 65; i <= 90; i++) {
			d = $('<td>').addClass('cell header-cell').text(String.fromCharCode(i));
			tr.append(d);
		}
		
		//blank cell
		d = $('<td>').addClass('cell header-cell row-header-cell').html('&nbsp;');
		tr.append(d);
		
		table.append(tr);
		$('#column-header').append(table);
	}
	
	function generateRowHeader() {
		var table = $('<table>').css({'table-layout': 'fixed', 'width' : '0' });
		var tr;
		
		for (var i = 1; i <= 25; i++) {
			tr = $('<tr>');
			d = $('<td>').addClass('cell header-cell row-header-cell').text(i).attr('id', 'rh'+i);
			tr.append(d);
			table.append(tr);
		}
		
		//blank cell
		tr = $('<tr>');
		d = $('<td>').addClass('cell header-cell row-header-cell').html('&nbsp;');
		tr.append(d);
		table.append(tr);
		
		$('#row-header').append(table);
	}
	
	function generateTable() {
		var table = $('<table>').css({'table-layout': 'fixed', 'width' : '0' });
		var tr, td, cc;
		
		for (var i = 1; i <= 25; i++) {
			tr = $('<tr>').attr('id', 'tr'+i);
			
			for (var j = 1; j <= 26; j++) {
				cc = $('<div>').addClass('cell-content').html('&nbsp;');
				td = $('<td>').addClass('cell').attr('id', String.fromCharCode(64+j) + "-" + i).append(cc);
				tr.append(td);
			}
			
			table.append(tr);
		}
		
		$('#table-container').append(table);
	}
	
	function getUsers() {
		var tr;
		
		//remove all
		$('#users tbody').html('');
		
		$.post('api/get_users_for_doc.php', {docID: docID}, function(response) {
		
			//console.log(response);
			
			//show owner
			var html = '<td>' + response.owner.fullname + '</td>';
			html += '<td><span class="muted">' + response.owner.email + '</span></td>';
			html += '<td>owner</td>';
			tr = $('<tr>').html(html);
			$('#users tbody').append(tr);
		
			for (var i in response.users) {
				
				var user = response.users[i];
				
				var html = '<td>' + user.fullname + '</td>';
				html += '<td><span class="muted">' + user.email + '</span></td>';
				html += '<td>user</td>';
				
				tr = $('<tr>').html(html);
				
				$('#users tbody').append(tr);
			}
		
		}, 'json');
		
	}
	
	(function poll(){
		setTimeout(function() {
		
			$.get('api/get_online.php', {docid: docID, timestamp: timestamp}, function(response) {
			
				$('.cell-border').remove();
				
				for (var i in response.cells) {
					
					var label = $('<div>').addClass('cell-label').text(response.cells[i].fullname).css('background-color', response.cells[i].color);
					var border = $('<div>').addClass('cell-border').css('border-color', response.cells[i].color).append(label);
					
					$('#' + response.cells[i].cell_id).append(border);
				}
				
				for (var i in response.data) {
				
					insertCellContent(response.data[i].cell_id, response.data[i].content);
				
				}
				
				if (response.data.length > 0) timestamp = response.data[0].data;
				
				poll();
				
			}, 'json');
			
		}, 1000);
	})();
	
})();
