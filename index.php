<?php  

session_start();

if (!isset($_SESSION['user'])) {
	header("Location: login.html");
}

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
	<script src="js/main.js" type="text/javascript"></script>
</head>

<body>
	
	<div id="header">
	
		<!-- navbar -->
		<div class="navbar" style="margin-bottom: 0;">
			<div class="navbar-inner">
				<a class="brand" href="#"><b>collab</b>doc</a>
				
				<ul class="nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">File <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a id="new-file" href="#"><i class="icon-file"></i> New file</a></li>
							<li class="divider"></li>
							<li><a href="#"><i class="icon-print"></i> Print</a></li>
						</ul>
					</li>
					<li><a href="#">Options</a></li>
				</ul>
				
				<div class="pull-right">
				
					<div class="btn-group">
						<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
							<i class="icon-user"></i> <?php echo $_SESSION['user']['fullname']; ?>
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu pull-right">
						<!-- dropdown menu links -->
							<li><a href="#">Edit profile</a></li>
							<li class="divider"></li>
							<li><a href="api/logout.php">Logout</a></li>
						</ul>
					</div>
				</div>
				
			</div>
		</div>
		<!-- end navbar -->
				
	</div>
	
	
	<div>
	
		<table id="#docs" class="table table-striped">
			
			<thead>
				<tr>
					<th>#</th>
					<th>Document name</th>
					<th>Creation date</th>
					<th>Options</th>
				</tr>
			</thead>
			
			<tbody>
				
			</tbody>
						
		</table>
	
	</div>
	
	
</body>

</html>