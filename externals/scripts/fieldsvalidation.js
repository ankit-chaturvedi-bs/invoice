function toggleGstFields(selectedState){
	const igstElem = document.getElementById('igst-wrapper');
	const cgstElem = document.getElementById('cgst-wrapper');
	const sgstElem = document.getElementById('sgst-wrapper');
	switch(selectedState){
		case '0':{
		igstElem.style.display = "none";
		cgstElem.style.display = "block";
		sgstElem.style.display = "block";
		break;
		}
		case '1':{
		igstElem.style.display = "block";
		cgstElem.style.display = "none";
		sgstElem.style.display = "none";
		break;
		}
		default:{
		igstElem.style.display = "none";
		cgstElem.style.display = "none";
		sgstElem.style.display = "none";
		}
	}



}


function currencyChange(element){
	const selectedCurr = element.value;

	/*
		* if rupees is selected show gst fileds based on selected state
	*/

	if(selectedCurr == "1"){
		const selectedState = document.getElementById('state').value;

		toggleGstFields(selectedState);
	}else{
		toggleGstFields('none');
	}

	calcTotalAmount();
}



function stateChange(element){
	const selectedCurr = document.getElementById('currency').value;
	if(selectedCurr == "1")
	toggleGstFields(element.value);

    calcTotalAmount();
}


//add products count to total no of added elements;

function addProductCount(){
	console.log(cnt);
	$('products').value = cnt-1;
}



