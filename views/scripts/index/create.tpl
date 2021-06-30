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
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/scripts/fieldsvalidation.js');
$this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/scripts/calculation.js');

$this->headScript()
      ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Invoice/externals/scripts/products_field_edit.js');

?>

<div class="global-form">
<?php
echo $this->form->render($this);
?>



<script type="text/javascript">
	let elem = $('products-wrapper');
	const cgst = +'<?php echo $this->cgst; ?>';
	const sgst = +'<?php echo $this->sgst; ?>';
	const igst = +'<?php echo $this->igst; ?>';

	const maxCnt = 5;
	let cnt = 1;
	let  priceArray = [];
	let quantityArray = [];


	/*
	* set pricarray and quantity array values default;
	*/

	for(let i=0;i<=maxCnt;i++) {
		priceArray[i] = 0;
		quantityArray[i] = 0;
	}

	let productElem = new Element('div',{
		class:"product-items"
		
	});


	function createDeleteButton(){
		const prodName = 'btn_'+cnt;
		let elem = document.createElement('button');
		elem.setAttribute('class','delete');
		elem.setAttribute('id',prodName);
		elem.setAttribute('name',prodName);
		elem.setAttribute('type','button');
		elem.setAttribute('onclick','deleteProduct(this)');
		elem.innerHTML = "X"
		return elem;
	}

	function createProductInput(){
		const prodName = 'p'+cnt;
		let elem = document.createElement('input');
		elem.setAttribute('class','product');
		elem.setAttribute('id',prodName);
		elem.setAttribute('name',prodName);
		elem.setAttribute('placeholder','Product Name');
		return elem;
	}

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
	/*
	* add elemnets to the product div
	*/
	function addElements(){
	let productItemElem = new Element('div',{
		class:"product-item",
		id:cnt,
	});
	createDeleteButton().inject(productItemElem,'top');
	createAmountInput().inject(productItemElem,'top');
	createPriceInput().inject(productItemElem,'top');
	createQuantityInput().inject(productItemElem,'top');
	createProductInput().inject(productItemElem,'top');
	return productItemElem
	}


	// will get called on click of add product button
	function addProduct(){
		if(cnt > maxCnt ){
			alert("Products Can't Be More Then 5");
			return;
		}
		addElements().inject(productElem);
		++cnt;
	}

	// add one field by default
	addProduct();
	//add the product div to @elem
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


		document.getElementById('type').setAttribute('disabled',"disabled");


		


	</script>


	// change total based on values





</div>