<?php 

session_start();

if (!isset($_SESSION['user'])) {
	header("Location: login.html");
}

$userid = $_SESSION['user']['id'];

require('api/dbinfo.php');

$docid = mysql_real_escape_string($_GET['id']);

$query = "SELECT * FROM spreadsheets WHERE id='$docid' AND ( fk_userID ='$userid'
         or id IN (select fk_sheetID from sp_rights where fk_userID='$userid'))";
$result = mysql_query($query);

if (!$result || mysql_num_rows($result) == 0) die ('not allowed');

$docname = mysql_result($result, 0, 'docname');

?>

<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>CollabDoc</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<script src="js/jquery-1.8.2.min.js" type="text/javascript"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<script src="js/modal.js" type="text/javascript"></script>
	<script src="js/collab.js" type="text/javascript"></script>
	
	<script type="text/javascript">
		var docID = <?php echo $docid; ?>;
	</script>
</head>

<body>
	
	<div id="header">
	
		<!-- navbar -->
		<div class="navbar" style="margin-bottom: 0;">
			<div class="navbar-inner">
				<a class="brand" href="#"><b>collab</b>doc</a>
				
				<div class="pull-left">
					<div class="btn-group" data-toggle="buttons-checkbox">
						<button type="button" class="btn" id="bold-btn"><i class="icon-bold"></i></button>
						<button type="button" class="btn" id="italic-btn"><i class="icon-italic"></i></button>
					</div>
					
					<div class="btn-group">
						<button type="button" class="btn align-btn" data-align="left"><i class="icon-align-left"></i></button>
						<button type="button" class="btn align-btn" data-align="center"><i class="icon-align-center"></i></button>
						<button type="button" class="btn align-btn" data-align="right"><i class="icon-align-right"></i></button>
					</div>
					
					<div class="btn-group">
						<button type="button" class="btn"><i class="icon-print"></i></button>
					</div>
				</div>
				
				<div class="pull-right">
				
					<button type="button" class="btn btn-primary" href="#" id="share-btn"><i class="icon-share icon-white"></i> Share</button>
				
				</div>
				
			</div>
		</div>
		<!-- end navbar -->
		
		<div id="document-title-container" style="display: inline-block;">
			<span id="document-title"><?php echo $docname; ?></span> <i class="icon-pencil"></i>
		</div>
		
	</div>
	
	
	<div id="doc-wrapper">
		
		<div class="cell header-cell row-header-cell unused">&nbsp;</div>
		
		<div id="column-header"></div>
		
		<div id="row-header"></div>
		
		<div id="table-wrapper">
			<div id="table-container">
				
			</div>
		</div>
		
	</div>
	
	<!-- Modal -->
	<div id="input-dialog" class="modal hide fade modal-small" tabindex="-1" role="dialog">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="dialog-title"></h4>
		</div>
		
		<div class="modal-body">
			<input type="text" name="text-field" style="width: 315px;" />
		</div>
		
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Cancel</button>
			<button class="btn btn-primary btn-save">Save</button>
		</div>
	</div>
	
	
	<!-- Share -->
	<div id="share-dialog" class="modal hide fade" tabindex="-1" role="dialog">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="dialog-title">Share document</h4>
		</div>
		
		<div class="modal-body">
			
			<table id="users" class="table table-striped">
			
				<thead>
					<tr>
						<th>Name</th>
						<th>E-mail</th>
						<th>Rights</th>
					</tr>
				</thead>
				
				<tbody>
					
				</tbody>
							
			</table>
			
			<div class="errors">
				
			</div>
			
			<div class="input-prepend input-append" style="text-align: center;">
				<span class="add-on">Share with</span>
				<input class="input-xlarge" id="email" placeholder="E-mail" type="text">
				<button class="btn btn-primary" type="button" id="add-user"><i class="icon-plus-sign icon-white"></i> Add user</button>
			</div>
			
		</div>
		
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal">Done</button>
		</div>
	</div>
	
</body>

</html>