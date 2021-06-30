


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

}






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
	


function changeSubtotal(priceArr,quantityArr){
	let total = 0;
	for(let i=0;i<=5;i++){
		total += (priceArr[i])*(quantityArr[i]);
	}

	$('sub_total').value = total;
	const igst = +$('igst').value;
	const cgst = +$('cgst').value;
	const sgst = +$('sgst').value;
	$('igst').value = (total*igst)/100;
	$('cgst').value = (total*cgst)/100;
	$('sgst').value = (total*sgst)/100;

}





