<?php

$invoice = $this->invoice;
$products = $this->products;
	// comapny details
$details = $this->company;



$cnt = 1;
?>

<style>

@import url(https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css);
/*<link href="" rel="stylesheet">*/

#global_wrapper{
	background-color: white !important;
	border-top: 1px solid gray;
}


</style>



<div class="container ">
	<header>

		<!--show header with image -->

	</header>


	<div class="content" id="main_content">

		<h3 class="text-lg text-center">Invoice</h1>


			<div class="grid grid-cols-4 grid-flow-col gap-4">

				<div class="col-span-3">
					<p>Customer Name:<?=$invoice['customer_name']?></p>
					<p>Address:<?=$invoice['address']?></p>
					<p>Contact No:<?=$invoice['contact_number']?></p>
					<p>Email:<?=$invoice['customer_email']?></p>
					<p>Invoice Number:<?=$invoice['invoice_number']?></p>
				</div>

				<div class="col-span-1">

					<p>Date: <?=$invoice['creation_date']?></p>

				</div>

			</div>


			<div class="proudcts-table w-10/12">
				<table class="w-10/12 border-2 table-fixed">
					<thead class="border-2">
						<tr>
							<th class="border-2 p-1">S NO.</th>
							<th class="border-2 p-1">Product Name</th>
							<th class="border-2 p-1">Amount</th>
						</tr>

					</thead>

					<tbody class="border-2">

						<?php foreach($products as $key => $value): ?>

							<tr class="border-2 p-4">
								<td class="border-2 p-1"><?=$key?></td>
								<td class="border-2 p-1"><?=$value['product_name']?></td>
								<td class="border-2 p-1"><?=($value['quantity']*$value['price'])?></td>
							</tr>


						<?php endforeach; ?>

						<tr>
							<td></td>

							<td class="border-2 p-1">Discount
							</td>
							<td class="border-2 p-1">
								<?=$invoice['discount']?>
							</td>
						</tr>
						<?php if($invoice['currency']): ?>
							<?php if($invoice['state']): ?>
								<tr>
									<td></td>
									<td class="border-2 p-1">IGST</td>
									<td class="border-2 p-1"><?=$invoice['igst'];?></td>
								</tr>

							<?php else: ?>
								<tr>
									<td></td>
									<td class="border-2 p-1">CGST</td>
									<td class="border-2 p-1"><?=$invoice['cgst'];?></td>
								</tr>
								<tr>
									<td></td>
									<td class="border-2 p-1">SGST</td>
									<td class="border-2 p-1"><?=$invoice['sgst'];?></td>
								</tr>

							<?php endif; ?>

						<?php endif; ?>



						<tr>
							<td></td>
							<td class="border-2 p-1">Total</td>
							<td class="border-2 p-1"><?=$invoice['total']?></td>
						</tr>
					</tbody>
				</table>


			</div>



			





			<p>PAN No:<?=$details['pan.no']?></p>
			<p>GST No:<?=$details['gst.no']?></p>
			<p>LUT No:<?=$details['lut.no']?></p>


			<h2>Bank Details:</h2>

			<p>Account Name:<?=$details['account.name']?></p>
			<p>Account No:<?=$details['account.no']?></p>
			<p>Bank:<?=$details['bank.name']?></p>
			<p>Account Address:<?=$details['account.address']?></p>
			<p>IFSC Code:<?=$details['ifsc.code']?></p>



		</div>

		




	</div>

	<button onclick="printDiv()" > Print</button>


	<script type="text/javascript">

		function printDiv() {
			var printContents = document.getElementById('main_content').innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			document.body.innerHTML = originalContents;
		}

	</script>