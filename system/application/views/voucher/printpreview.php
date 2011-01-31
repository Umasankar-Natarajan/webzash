<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Print - <?php echo $current_voucher_type['name']; ?> Voucher Number <?php echo $voucher_number; ?></title>
<?php echo link_tag(asset_url() . 'images/favicon.ico', 'shortcut icon', 'image/ico'); ?>
<link type="text/css" rel="stylesheet" href="<?php echo asset_url(); ?>css/printvoucher.css">
</head>
<body>
	<div id="print-account-name"><span class="value"><?php echo  $this->config->item('account_name'); ?></span></div>
	<div id="print-account-address"><span class="value"><?php echo $this->config->item('account_address'); ?></span></div>
	<br />
	<div id="print-voucher-type"><span class="value"><?php echo $current_voucher_type['name']; ?> Voucher</span></div>
	<br />
	<div id="print-voucher-number"><?php echo $current_voucher_type['name']; ?> Voucher Number : <span class="value"><?php echo voucher_number_prefix($voucher_type_id) . $voucher_number; ?></span></div>
	<div id="print-voucher-number"><?php echo $current_voucher_type['name']; ?> Voucher Date : <span class="value"><?php echo $voucher_date; ?></span></div>
	<br />
	<table id="print-voucher-table">
		<thead>
			<tr class="tr-title"><th>Ledger A/C</th><th>Dr Amount</th><th>Cr Amount</th></tr>
		</thead>
		<tbody>
		<?php
			$currency = $this->config->item('account_currency_symbol');
			foreach ($ledger_data as $id => $row)
			{
				echo "<tr class=\"tr-ledger\">";
				if ($row['dc'] == "D")
				{
					echo "<td class=\"ledger-name item\">By " . $row['name'] . "</td>";
				} else {
					echo "<td class=\"ledger-name item\">&nbsp;&nbsp;To " . $row['name'] . "</td>";
				}
				if ($row['dc'] == "D")
				{
					echo "<td class=\"ledger-dr item\">" . $currency . " " . $row['amount'] . "</td>";
					echo "<td class=\"ledger-cr last-item\"></td>";
				} else {
					echo "<td class=\"ledger-dr item\"></td>";
					echo "<td class=\"ledger-cr last-item\">" . $currency . " " . $row['amount'] . "</td>";
				}
				echo "</tr>";
			}
			echo "<tr class=\"tr-total\"><td class=\"total-name\">Total</td><td class=\"total-dr\">" . $currency . " " .  $voucher_dr_total . "</td><td class=\"total-cr\">" . $currency . " " . $voucher_cr_total . "</td></tr>";
		?>
		</tbody>
	</table>
	<br />
	<div id="print-voucher-narration">Narration : <span class="value"><?php echo $voucher_narration; ?></span></div>
	<br />
	<form>
	<input class="hide-print" type="button" onClick="window.print()" value="Print voucher">
	</form>
</body>
</html>
