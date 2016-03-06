<?php include('autoload.php'); 
if(isset($_POST['pass'])){
		if(md5($_POST['pass']) == '57e2d94e1b124ff5512b3900395fab92'){;	
			$_SESSION['user'] = true;
			redirect(BASE_URL);
		}
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>Login</title>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<link href="style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
	<center>
		<table class="zoom" style="">
			<tr>
				<td align="center" style="vertical-align:middle !important">
					<form method="post">
						<input type="hidden" name="action" value="login">					
						<table class="form">
							<thead>
								<tr align="center">
									<td colspan="2">
										<h2>Login</h2>
										<i style="color:red;"><?=@$message;?></i>		
									</td>
								</tr>
							</thead>
							<tbody>								
								<tr>
									<td align=right>
										<?=T('pass');?>
									</td>
									<td>
										<input type="password" name="pass">
									</td>	
								</tr>								
								<tr>
									<td colspan="2" align="center">
										<input type="submit" value="send">
									</td>
								</tr>
							</tbody>	
							</table>						
						</form>
					
				</td>	
			</tr>
		</table>
		
		<?php inspect($_SESSION); ?>
		
	</body>
</html>	