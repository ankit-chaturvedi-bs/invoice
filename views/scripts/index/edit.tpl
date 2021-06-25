<style type="text/css">
	/*
	.product-item > input {
		width: 100px;
		}*/

		.product-item > input,
		.product-item > h4 {
			height: 40px;
			margin: 0px 4px;
		}

		.product{
			width: 240px;
		}

		.quantity{
			width: 80px;
		}

		.amount{
			width: 100px;
		}

		.product-item{
			margin: 8px 0px;
		}

	</style>
	<?php

	$this->headScript()
	->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/scripts/editinit.js');

	$this->headScript()
	->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/scripts/calculation.js');

	$this->headScript()
	->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/scripts/fieldsvalidation.js');
	?>


	<?php
	echo $this->form->render($this);
	?>



	<script type="text/javascript">
		let elem = $('products-wrapper');
		const cgst = +'8';
		const sgst = +'8';
		const igst = +'8';

		const maxCnt = 5;
		let cnt = 1;
		let  priceArray = [];
		let quantityArray = [];


	/**
	 * @set pricarr|quantityarr
	 */

	 for(let i=0;i<=maxCnt;i++) {
	 	priceArray[i] = 0;
	 	quantityArray[i] = 0;
	 }

	 let productElem = new Element('div',{
	 	class:"product-items"		
	 });

	/**
	 * @return An input element for product name field
	 */

	 function createProductInput(){
	 	const prodName = 'p'+cnt;
	 	let elem = document.createElement('input');
	 	elem.setAttribute('class','product');
	 	elem.setAttribute('id',prodName);
	 	elem.setAttribute('name',prodName);
	 	elem.setAttribute('placeholder','Product Name');
	 	return elem;
	 }

	/**
	 * @return An input element for quanitity field
	 */

	 function createQuantityInput(){
	 	const prodName = 'q'+cnt;
	 	let elem = document.createElement('input');
	 	elem.setAttribute('value',0);
	 	elem.setAttribute('type','number');
	 	elem.setAttribute('onblur','quantityChange(this)');
	 	elem.setAttribute('class','quantity');
	 	elem.setAttribute('id',prodName);
	 	elem.setAttribute('name',prodName);
	 	elem.setAttribute('placeholder','Quantity');
	 	return elem;
	 }

	/**
	 * @return An input element for price field
	 */
	 function createPriceInput(){
	 	const prodName = 'pr'+cnt;
	 	let elem = document.createElement('input');
	 	elem.setAttribute('type','number');
	 	elem.setAttribute('value',0);
	 	elem.setAttribute('onblur','priceChange(this)');
	 	elem.setAttribute('class','amount');
	 	elem.setAttribute('id',prodName);
	 	elem.setAttribute('name',prodName);
	 	elem.setAttribute('placeholder','Price');

	 	return elem;
	 }

	/**
	 * @return An input element for amount field
	 */
	 function createAmountInput(){
	 	const prodName = 'a'+cnt;
	 	let elem = document.createElement('input');
	 	elem.setAttribute('type','number');
	 	elem.setAttribute('value',0);
	 	elem.setAttribute('readonly',true);
	 	elem.setAttribute('class','amount');
	 	elem.setAttribute('id',prodName);
	 	elem.setAttribute('name',prodName);
	 	elem.setAttribute('placeholder','Amount');

	 	return elem;
	 }



	/**
	 * @do creates a new div
	 * @return div with input fileds
	 * 
	 */
	 function addElements(){
	 	let productItemElem = new Element('div',{
	 		class:"product-item"
	 	});
	 	createAmountInput().inject(productItemElem,'top');
	 	createPriceInput().inject(productItemElem,'top');
	 	createQuantityInput().inject(productItemElem,'top');
	 	createProductInput().inject(productItemElem,'top');
	 	return productItemElem
	 }

	/**
	 * @do check max count if allowed add a new div to top div
	 * @return 
	 */
	 function addProduct(){
	 	if(cnt > maxCnt ){
	 		alert("Products Can't Be More Then 5");
	 		return;
	 	}
	 	addElements().inject(productElem);
	 	++cnt;
	 }

	// initial product
	addProduct();
	
	// add the div after elem
	productElem.inject(elem,'after');
</script>


<!-- //  add more product script -->
<script type="text/javascript">
	const addMoreProdElem = document.getElementById('add_product');

	addMoreProdElem.addEventListener('click',function(){
		addProduct();	
	})

</script>


<!-- // currency based gst toggle script -->


<script type="text/javascript">
	const selectedCurr = document.getElementById('currency').value;

	if(selectedCurr == "1")
		toggleGstFields("0")
	else
		toggleGstFields("none")
</script>







<script type="text/javascript">
	
	// add database values to product fileds
	let productsArr = <?php echo json_encode($this->products);?>;

	/**
	 * @path externals/editinit.js
	 * @do add database products to table
	 * 
	 */
	 addInitialProducts(productsArr);


	 /**
	  * @path externals/editinit.js
	  * @do add the subtotal
	  * 
	  */
	  changeSubtotal(priceArray,quantityArray);



	</script>
































<!--  
<script type="text/javascript">
	let productsArr = <?php echo json_encode($this->products);?>;
	console.log(productsArr)
	let elem = $('products-wrapper');
	let productElem = new Element('div',{
		class:"product-items"
	});
	console.log(productsArr);
	productElem.inject(elem,'after');
</script>
<script type="text/javascript">

function addItem(id,arr){
	// console.log(arr.tostring());
	const price = arr['quantity'] * arr['price'];


	let stmt = `
	<div class="product-item" style="margin:5px 0px">
	<input type="text" id="p${id}" name="product" value="${arr['product_name']}" style="width:240px" " />

	<input type="number" id="q${id}" name="quantity" value="${arr['quantity']}" style="width:80px;height:40px" onblur="quantityChange(this)"/>

	<input type="number" id="pr${id}" name="price" value="${arr['price']}" 
	style="width:100px;height:40px" onblur="priceChange(this)" />

	<input type="number" id="a${id}" name="amount" value="${price}" style="width:100px;height:40px" readonly/>

	</div>
	` 

	return stmt;

}

</script>

<script type="text/javascript">
	let proTableDiv = document.querySelector('.product-items');

	let cnt = productsArr.length;
	var priceArray = [];
	var quantityArray = [];
	const maxCnt = 5;

	/*
	* set pricarray and quantity array values default;
	*/

	for(let i=0;i<=maxCnt;i++) {
		priceArray[i] = 0;
		quantityArray[i] = 0;
	}



	

	for(let i = 0;i<cnt;i++){
		let index = i+1;
		// set the price array and quantity array
		let product = productsArr[i];
		priceArray[index] = product['price'];
		quantityArray[index] = product['quantity']
		proTableDiv.innerHTML += addItem(index,productsArr[i]);
	}

	// increase quantity value by one 
	++cnt;

</script>


<script type="text/javascript">
		const addMoreProdElem = document.getElementById('add_product');

		addMoreProdElem.addEventListener('click',function(){
			
			/**
			 * @path defined in externals/scripts/editaction.js
			 * @param pass the cnt as an id
			 */

			if(!addProductRow(cnt)){ 
				alert("Can't add more then 5"); 	
				// for calculation purpose
				cnt = 6;
			}else{
				++cnt;
			}
		})

</script>

-->




