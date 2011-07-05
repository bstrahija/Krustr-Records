<html>
<head>
	<title>Vaš kupon</title>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />

</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#FFFFFF" >

<table cellpadding="0" cellspacing="0" width="600" style="background-color:#fff; padding:0px;" align="center">
	<tbody>
		<?php if (isset($gift_message) && $gift_message) : ?>
		<tr>
			<td style="font-size: 14px; padding: 0 0 15px 0;">
				<table align="left" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Verdana, Geneva, sans-serif; color:#111; background-color:#fff;">
					<tbody>
						<tr>
							<td style="font-size: 14px; padding:15px 25px 15px 25px; color: #111; border-bottom: 1px solid #111;">
								<?php echo $gift_message; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		<?php endif; ?>
		
		<tr>
			<td>
				<table align="left" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Verdana, Geneva, sans-serif; color:#444; background-color:#fff;">
					<tbody>
						<tr>
							<td>
								<a style="border:none; text-decoration:none;" href="<?php echo site_url() ?>">
									<img style="border: none;" src="<?php echo site_url('addons/mail/logo_bw.png'); ?>" alt="logo_bw" width="245" height="145" />
								</a><br><br>
							</td>
							<td style="font-size: 48px; font-weight: bold; color: #111; text-align: right; padding: 0 20px 0 0;">
								<span style="font-size: 14px; color: #333; font-weight: normal;"><br>ID: <?php echo @$track_id; ?></span>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table align="left" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Verdana, Geneva, sans-serif; color:#444; background-color:#fff; border: 2px dashed #111;">
					<tbody>
												
						<tr>
							<td style="font-size: 38px; padding:15px 25px 15px; color: #111; border-bottom: 2px dashed #111;">
								<strong style="white-space: nowrap;">
									KUPON 
									<?php if (isset($coupon_number)) echo ' #', $coupon_number; ?>
								</strong><br>
							</td>
							<td style="font-size: 28px; padding:10px 25px 15px; color: #111; border-bottom: 2px dashed #111; text-align: right;" align="right">
								<strong>
									<span style="font-size: 14px">Oznaka kupona:</span> <br>
									<?php echo @$coupon_code; ?>
								</strong><br>
							</td>
						</tr>
						
						<tr>
							<td colspan="2" style="font-size: 22px; padding:15px 25px 15px; color: #111; border-bottom: 2px dashed #111;">
								<strong><?php echo @$title; ?></strong>
								<?php if (isset($subline)) : ?>
									<br><?php echo $subline; ?>
								<?php endif; ?>
							</td>
						</tr>
						
						<tr valign="top">
							<td colspan="2" style="font-size: 14px; color: #111; padding: 15px 15px 20px 25px; line-height: 130%;">
								<table width="100%" cellpadding="0" cellspacing="0">
									<tbody>
										<tr valign="top">
											<td style="font-size: 14px; color: #111; padding: 15px 5px 20px 0; line-height: 130%;" width="50%">
												Nositelj kupona:<br>
												<strong style="font-size: 20px;"><?php echo @$carrier_name; ?></strong><br><br>
												
												Kupon je važeći do:<br>
												<strong style="font-size: 20px;"><?php echo @$coupon_expires; ?></strong><br><br>
												
											</td>
											<td style="font-size: 14px; color: #111; padding: 15px 0 20px 5px; line-height: 130%;" width="50%">
												<?php if ( ! @$is_gift) : ?>
													Vrijednost kupona:<br>
													<strong style="font-size: 20px;"><?php echo number_format(@$coupon_value, 2, ",", "."); ?> kn</strong><br><br>
													
													Cijena kupona:<br>
													<strong style="font-size: 20px;"><?php echo number_format(@$coupon_price, 2, ",", "."); ?> kn</strong><br><br>
												<?php endif; ?>
											</td>
										</tr>
										
										<tr valign="top">
											<td style="padding: 0 10px 0 0;">
												Kupon iskoristite kod:<br>
												<strong><?php echo @$company->title; ?></strong><br>
												<?php echo nl2br(@$company->fields['address']); ?>
											</td>
											<td style="0 0 0 10px;">
												<br>
												Tel: <?php echo @$company->fields['phone_number']; ?><br>
												E-mail: <?php echo @$company->fields['email']; ?><br>
												Web: <?php echo @$company->fields['www']; ?><br>
											</td>
										</tr>
									</tbody>
								</table>
								<br>
								
								<div style="font-size: 11px; color: #666; border-top: 1px solid #222; padding: 10px 0 10px 0;"><strong>Napomena:</strong> <br><?php echo nl2br(strip_tags(@$deal->fields['voucher_terms'])); ?></div>
								
								<div style="font-size: 11px; color: #666; border-top: 1px solid #222; padding: 10px 0 0 0;">
									<strong>Uplatitelj:</strong> <?php echo @$buyer_name; ?><br>
									<strong>Uplaćeno za:</strong> <?php echo @$company->title; ?><br>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
		
		<tr>
			<td>
				<table align="left" cellpadding="0" cellspacing="0"  width="100%" style="font-family:Verdana, Geneva, sans-serif; color:#444; background-color:#fff;">
					<tbody>
						<tr>
							<td style="font-size: 11px; color: #6a6a6a; padding: 20px 30px 25px 30px;">
								<strong style="font-size: 13px;">Upute za korištenje kupona:</strong><br><br>
								<ol style="margin: 0 0 0 0;">
									<li>Kupon ispišite.</li>
									<li>Pokažite kupon prilikom korištenja usluge ili preuzimanja robe. U slučaju rezervacije navedite broj kupona.</li>
									<li>Uživajte!</li>
								</ol>
								<br>
								
								<?php if ( ! @$is_gift) : ?>
									Vaša kartica je terećena na navedeni iznos <strong><?php echo number_format(@$coupon_price, 2, ",", "."); ?> kn</strong>. Kupon je potvrda o uplati. Bez Kupona ne možete ostvariti pravo na popust. Svaki Kupon ima jedinstvenu oznaku <strong><?php echo @$coupon_code; ?></strong>. Vrijedi za jednu upotrebu. Da bi izbjegli zlouporabe Kupon ne djelite s drugima.
									<br>
									<br>
								<?php endif; ?>
								
								<strong>www.MudraKupovina.hr</strong>
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