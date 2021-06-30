	const constructId = (elemId,intPart) => {
		let idx = 0;
		for(let char in elemId){
			if(!isNaN(elemId[char])){
				idx = char;
				break;
			}
		}
		const stringPart = elemId.substring(0,idx);
		return `${stringPart}${intPart}`;

	}


	function changeIds(id){
		/**
		 * id =3
		 * send id = 3,4,5
		 */
		 // console.log(`count is ${cnt}`);
		 if(id == cnt) return;
		let value = id;
		let elems = [];
		for(let i=id;id<6;++i){

			let elem = document.getElementById(i);
			if(!elem){
				console.log("noull");
				break;
			}
			elems.push(elem);
		}

		for(let i=0;i<elems.length;i++){
			let elem = elems[i].children;
			let elemId = elems[i].id;

			for(let j=0;j<elem.length;j++){
				let id = elem[j].id;
				
				// elem[j].id = changeId(elemId);
				let intPart = (+elemId)-1;
				elem[j].id = constructId(id,intPart);
				elem[j].name = constructId(elem[j].name,intPart);
			}
			
			// elemId
			elems[i].id = (+elemId)-1;
		}



	}


	function deleteProduct(elem){

		let id = elem.id.substring(4);
		let div = document.getElementById(id);


		changeIds((+id)+1);
		div.remove();
		--cnt;

	}