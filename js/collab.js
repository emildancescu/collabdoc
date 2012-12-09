$(document).ready(function() {
	init();
});

var modal = {
	title : '',
	
	default_text : '',
	
	callback : function() {
		
	},
	
	init : function() {
		var t = this;
	
		$('#input-dialog .btn-save').click(function() {
			t.callback($('#input-dialog input').val());
			$('#input-dialog').modal('hide');
		});
		
		$('#input-dialog').keypress(function(e) {
		    if (e.which == 13) {
		        t.callback($('#input-dialog input').val());
		        $('#input-dialog').modal('hide');
		    }
		});
		
		$('#input-dialog').on('shown', function() {
			$('#input-dialog input').focus();
		});
	},
	
	show : function() {
		var default_text = this.default_text;
		var title = this.title;
		
		if (String.prototype.trim) default_text = default_text.trim();
		
		$('#input-dialog .dialog-title').text(title);
		
		$('#input-dialog input').val(default_text);
		
		$('#input-dialog').modal('show');
	}
};

function init() {
	
	generateColumnHeader();
	generateRowHeader();
	
	generateTable();
	
	adjustSize();
	
	modal.init();
	
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
	});
	
	$('#table-container td').dblclick(function() {
		var id = $(this).attr('id');
		
		modal.title = "Edit cell " + id;
		modal.default_text = $('#' + id).text();
		modal.callback = function(text) {
			$('#' + id + ' .cell-content').text(text);
			
			var cell = id.split('-');
			
			$('#rh' + cell[1]).height($('#tr'+cell[1]).height() - 11);
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
	
	for (var i = 1; i <= 100; i++) {
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
	
	for (var i = 1; i <= 100; i++) {
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
