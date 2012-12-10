;(function() {

	var modal = {
		title : '',
		
		default_text : '',
		
		callback : function() {
			
		},
		
		init : function() {
			var t = this;
		
			$('#input-dialog .btn-save').click(function() {
				var r = t.callback($('#input-dialog input').val());
				if (r !== false) $('#input-dialog').modal('hide');
			});
			
			$('#input-dialog').keypress(function(e) {
			    if (e.which == 13) {
			        var r = t.callback($('#input-dialog input').val());
			        if (r !== false) $('#input-dialog').modal('hide');
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
		},
		
		dismiss : function() {
			$('#input-dialog').modal('hide');
		}
	};
	
	$(document).ready(function() {
	
		modal.init();
		window.modal = modal;
		
	});
	
})();