<html>
<head>
	<title>Mudra Kupovina - Aktivacija korisničkog računa za "<em><?php echo $identity;?></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#FFFFFF" >

<table cellpadding="0" cellspacing="0" width="100%" style="background-color:#fff; padding:0px;">
	<tbody>
		<tr>
			<td>
				<table align="left" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Verdana, Geneva, sans-serif; color:#444; background-color:#fff;">
					<tbody>
						<tr>
							<td style="background-color:#FFFFFF; padding: 10px 0 10px 20px;">
								<a style="border:none; text-decoration:none;" href="<?php echo site_url(); ?>">
									<img style="border:none;" src="<?php echo site_url('addons/mail/banner.png'); ?>" width="590" height="95" alt="Mudra kupovina">
								</a>
							</td>
						</tr>
						
						<tr>
							<td style="font-size:14px; padding:30px 30px 20px;">
								<strong style="font-size:20px; letter-spacing:-1px; color: #485673;"><?php echo @$title; ?></strong>
							</td>
							<td></td>
						</tr>
						<tr>
							<td style="font-size:14px; padding:0 30px;">
								<p>Dobro došli na Mudra Kupovinu! Vaš korisnički račun će se aktivirati klikom na donji link:<br>
								<?php echo anchor('aktivacija/'. $id .'/'. $activation_key, 'Aktivacija korisničkog računa');?></p>
								<br><br>
							</td>
							<td></td>
						</tr>
						
						<tr>
							<td style="text-align:left; font-size:11px; padding:20px 30px 20px; color:#000; border-top:#485673 1px solid;">
								<strong style="font-size:13px; font-weight:bold;">Mudra Kupovina d.o.o., Zagreb</strong> <br>
								<a style="color:#0e82d3;" href="http://www.mudrakupovina.hr">www.mudrakupovina.hr</a>, 
								<a style="color:#0e82d3;" href="mailto:info@mudrakupovina.hr">info@mudrakupovina.hr</a>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</tbody>
</table>

</body>
</html>