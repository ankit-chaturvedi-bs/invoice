

//trigger on blur of discount

function discountChange(element){
	const value = +(element.value);
	let finalValue = 0;

	if(value > 0 && value){
		finalValue = value;
	}

	element.value = finalValue;

	calcTotalAmount();
}


function getDiscountPrice(){
	const element = $('discount').value;
	if(!element) return 0;
	return +(element);
}



function changeAmount(){

	for(let i=1;i<=cnt-1;i++){
		const id = 'a'+i;
		$(id).value = (quantityArray[i]*priceArray[i])
	}

}

// trigger on blur of price
function priceChange(element){
	const value = +(element.value);
	const maxPrice = 100000;
	
	/*
		* get the int values from id
		* id will represent pricearray index
	*/
	const id = +(element.id.substring(2)); 

	let finalValue = 0;
	if(value && value >= 0 && value<=maxPrice){
		finalValue = value;
	}
	priceArray[id] = finalValue;

	element.value = finalValue;	

	changeAmount();

	// global function can call any where
	calcTotalAmount();	
}




// trigger on blur of quantity
function quantityChange(element){

	const value = +(element.value);
	const maxQuantity = 1000;
	
	/*
		* get the int values from id
		* id will represent pricearray index
	*/
	const id = +(element.id.substring(1)); 

	let finalValue = 0;
	if(value && value >= 0 && value<=maxQuantity){
		finalValue = value;
	}
	quantityArray[id] = finalValue;
	element.value = finalValue;

	changeAmount();
	// global funtion can access any where
	calcTotalAmount();
}



/*

	* calculate gst 
*/

function calcGst(){
	let obj = {
		igst:0,
		cgst:0,
		sgst:0,
	};

	const curr = $('currency').value;
	const state = $('state').value;

	// if currency is dollar 
	if(curr == "0"){
		return obj;
	}


	// if state is harayana
	if(state == "0"){
		obj['sgst'] = sgst;
		obj['cgst'] = cgst;
		return obj;
	}


	// if state is outside harayana
	if(state == "1"){
		obj['igst'] = igst;
		return obj;
	}


	return obj;


}


//calculate total amount

function calcTotalAmount(){

	let subTotal = 0;
	let maxElements = quantityArray.length-1;


	/*
		*quantity array will give the no of quantity of each product
		*price array will give the price of each quantity
		Todo's
		*change the maxElements and take it from global value
		*also add discount price
	*/
	for(let i=1;i<=maxElements;i++) {
		subTotal += (quantityArray[i]*priceArray[i]);
	}

	let discount = getDiscountPrice();

	$('sub_total').value = subTotal;

	let gstObj = calcGst();
	const igstTax = +(subTotal*(gstObj['igst']))/100;
	const cgstTax = +(subTotal*(gstObj['cgst']))/100;
	const sgstTax = +(subTotal*(gstObj['sgst']))/100;

	$('igst').value= igstTax;
	$('cgst').value = cgstTax;
	$('sgst').value = sgstTax;



	let total = subTotal + cgstTax + sgstTax + igstTax-discount;


	$('total').value = total;




}