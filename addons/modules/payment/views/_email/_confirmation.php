<html>
<head>
	<title><?php echo @$title; ?></title>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#FFFFFF" >

<table cellpadding="0" cellspacing="0" width="100%" style="background-color:#fff; padding:0px;">
	<tbody>
		<tr>
			<td>
				<table align="left" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Verdana, Geneva, sans-serif; color:#444; background-color:#fff;">
					<tbody>
						<tr>
							<td style="background-color:#485673; border-bottom:#404D66 1px solid; padding: 10px 0 10px 20px;">
								<a style="border:none; text-decoration:none;" href="<?php echo site_url(); ?>">
									<img style="border:none;" src="<?php echo site_url('addons/themes/_mail/logo.png'); ?>" width="588" height="99" alt="Mudra Kupovina">
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
								<p>Vaša <?php echo $credit_card_name; ?> kartica je autorizirana na <?php echo number_format($cart->amount, 2, '.', ','); ?> kn.</p>
								<p>Detalji:</p>
								<p>
									Broj transakcije: <strong><?php echo $pg_data->tid; ?></strong><br>
									Oznaka narudžbe: <strong><?php echo $cart->track_id; ?></strong><br>
									Iznos: <strong><?php echo number_format($cart->amount, 2, '.', ','); ?> kn</strong><br>
									Datum transakcije: <strong><?php echo date('d.m.Y. H:i', $cart->purchased_at); ?></strong>
								</p>
								<br><br>
							</td>
							<td></td>
						</tr>
						
						<tr>
							<td style="text-align:left; font-size:11px; padding:20px 30px 20px; color:#000; border-top:#485673 1px solid;">
								<strong style="font-size:13px; font-weight:bold;">MudraKupovina d.o.o., Zagreb</strong> <br>
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