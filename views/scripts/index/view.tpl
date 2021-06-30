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
					<p><span class="text-lg text-gray-400 mr-2">Customer Name:  </span><span class="text-lg font-semibold text-gray-700"><?=$invoice['customer_name']?></span></p>
					<p><span class="text-lg text-gray-400 mr-2">Address:  </span><span class="text-lg font-semibold text-gray-700"><?=$invoice['address']?></span></p>

					<p><span class="text-lg text-gray-400 mr-2">Contact No:</span><span class="text-lg font-semibold text-gray-700"><?=$invoice['contact_number']?></span></p>
					<p><span class="text-lg text-gray-400 mr-2"><span class="text-lg text-gray-400 mr-2">Email:</span><span class="text-lg font-semibold text-gray-700"><?=$invoice['customer_email']?></p>
						<p><span class="text-lg text-gray-400 mr-2">Invoice Number:</span><span class="text-lg font-semibold text-gray-700"><?=$invoice['invoice_number']?></span></p>
					</div>
					<div class="col-span-1">

						<p><span class="text-lg text-gray-400 mr-2">Date: </span><span class="text-lg font-semibold text-gray-700"><?=$invoice['creation_date']?></span></p>

					</div>

				</div>


				<div class="proudcts-table w-10/12">
					<table class="w-10/12 border-2  table-fixed">
						<thead class="border-2">
							<tr>
								<th class="border-2 p-1 text-lg font-semibold text-gray-700">S NO.</th>
								<th class="border-2 p-1 text-lg font-semibold text-gray-700">Product Name</th>
								<th class="border-2 p-1 text-lg font-semibold text-gray-700">Amount</th>
							</tr>

						</thead>

						<tbody class="border-2">

							<?php foreach($products as $key => $value): ?>

								<tr class="border-2 border-black p-4 ">
									<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=$key?></td>
									<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=$value['product_name']?></td>
									<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=($value['quantity']*$value['price'])?></td>
								</tr>


							<?php endforeach; ?>

							<tr>
								<td></td>

								<td class="border-2 p-1 text-lg font-semibold text-gray-700">Discount
								</td>
								<td class="border-2 p-1 text-lg font-semibold text-gray-700">
									<?=$invoice['discount']?>
								</td>
							</tr>
							<?php if($invoice['currency']): ?>
								<?php if($invoice['state']): ?>
									<tr>
										<td></td>
										<td class="border-2 p-1 text-lg font-semibold text-gray-700">IGST</td>
										<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=$invoice['igst'];?></td>
									</tr>

								<?php else: ?>
									<tr>
										<td></td>
										<td class="border-2 p-1 text-lg font-semibold text-gray-700">CGST</td>
										<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=$invoice['cgst'];?></td>
									</tr>
									<tr>
										<td></td>
										<td class="border-2 p-1 text-lg font-semibold text-gray-700">SGST</td>
										<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=$invoice['sgst'];?></td>
									</tr>

								<?php endif; ?>

							<?php endif; ?>



							<tr>
								<td></td>
								<td class="border-2 p-1 text-lg font-semibold text-gray-700">Total</td>
								<td class="border-2 p-1 text-lg font-semibold text-gray-700"><?=$invoice['total']?></td>
							</tr>
						</tbody>
					</table>


				</div>









				<p><span class="text-lg text-gray-400 mr-2">PAN No:</span><span class="text-lg font-semibold text-gray-700"><?=$details['pan.no']?></span></p>
				<p><span class="text-lg text-gray-400 mr-2">GST No:</span><span class="text-lg font-semibold text-gray-700"><?=$details['gst.no']?></span></p>
				<p><span class="text-lg text-gray-400 mr-2">LUT No:</span><span class="text-lg font-semibold text-gray-700"><?=$details['lut.no']?></span></p>


				<h2>Bank Details:</h2>

				<p><span class="text-lg text-gray-400 mr-2">Account Name:</span><span class="text-lg font-semibold text-gray-700"><?=$details['account.name']?></span></p>
				<p><span class="text-lg text-gray-400 mr-2">Account No:</span><span class="text-lg font-semibold text-gray-700"><?=$details['account.no']?></span></p>
				<p><span class="text-lg text-gray-400 mr-2">Bank:</span><span class="text-lg font-semibold text-gray-700"><?=$details['bank.name']?></span></p>
				<p><span class="text-lg text-gray-400 mr-2">Account Address:</span><span class="text-lg font-semibold text-gray-700"><?=$details['account.address']?></span></p>
				<p><span class="text-lg text-gray-400 mr-2">IFSC Code:</span><span class="text-lg font-semibold text-gray-700"><?=$details['ifsc.code']?></span></p>



			</div>
		</span>
		




	</div>

	<button id="print"  > Print</button>


<!-- 	<script type="text/javascript">

		function printDiv() {
			var printContents = document.getElementById('main_content').innerHTML;
			var originalContents = document.body.innerHTML;

			document.body.innerHTML = printContents;

			window.print();

			document.body.innerHTML = originalContents;
		}

	</script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
	<script type="text/javascript">
		var doc = new jsPDF();
		document.getElementById('print').addEventListener('click',function () {
			doc.fromHTML(document.getElementById('main_content').innerHTML, 6, 6, {
				'width': 170,
				
			});
			doc.save('sample-file.pdf');
		});
	</script>