

// /**
//  * @param id 
//  * @return function executed successfully
//  *
//  */ 

// function addProductRow(id){
// 	if(id>5)return false;
// 	let proTableDiv = document.querySelector('.product-items');
// 	let stmt = `
// 	<div class="product-item" style="margin:5px 0px">
// 	<input type="text" id="p${id}" name="product" value="" style="width:240px" " />

// 	<input type="number" id="q${id}" name="quantity" value="" style="width:80px;height:40px" onblur="quantityChange(this)"/>

// 	<input type="number" id="pr${id}" name="price" value="" 
// 	style="width:100px;height:40px" onblur="priceChange(this)" />

// 	<input type="number" id="a${id}" name="amount" value="" style="width:100px;height:40px" readonly/>

// 	</div>`;
// 	proTableDiv.innerHTML += stmt;
// 	return true;
// }



function populateInput(value,id){

	const elem = document.getElementById(id);
	elem.value = value;
}



/** 
 * @param products array
 * @do add db products to table
 * 
 */

function populateFields(productsArr){
	console.log(productsArr);

	let populateName = (value,id) => {
		const elemId = `p${id}`;
		populateInput(value,elemId);
	}

	let populateQuatity = (value,id) => {
		const elemId = `q${id}`;
		quantityArray[id] = +value;
		populateInput(value,elemId);
	}

	let populatePrice = (value,id) => {
		const elemId = `pr${id}`;
		priceArray[id] = +value;
		populateInput(value,elemId);
	}

	let populateAmount = (value,id) => {
		const elemId = `a${id}`;
		populateInput(value,elemId);
	}




	for(let i=0;i<productsArr.length;i++){
		const product = productsArr[i];

		const price = product['price'];
		const quantity = product['quantity'];
		const prodName = product['product_name'];

		populateName(prodName,i+1);
		populateQuatity(quantity,i+1);
		populatePrice(price,i+1);
		populateAmount((+price)*(+quantity),i+1);
	}

	console.log('price array');
	console.log(priceArray);
	console.log('product array');
	console.log(quantityArray);

}





/** 
 * @param products array
 * 
 */
function addInitialProducts(productsArr){

	/**
	 * @logic add one less product field
	 * 
	 */ 
	let counts = productsArr.length-1;


	for(let i=1;i<=counts;i++){
		/**
		 * @path defined in edit.tpl
		 * @do add a product row
		 * 
		 */
		 addProduct();
	}

	populateFields(productsArr);

}