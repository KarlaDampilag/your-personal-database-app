function addAttField(value) {
	let formSection = document.getElementById('object-att-form');
	let label = document.createElement('label');
	label.innerHTML = 'Attribute name:';

	let input = document.createElement('input');
	input.type = 'text';
	input.className = 'attribute-input';
	input.placeholder = 'E.g. Author';
	input.required = true;

	if(value !== undefined) {
		input.value = value;
	}

	let deleteBtn = document.createElement('button');
	deleteBtn.innerHTML = 'Remove';
	deleteBtn.className = 'btn';
	deleteBtn.type = 'button';
	deleteBtn.onclick = deleteField;

	label.appendChild(input);
	label.appendChild(deleteBtn);
	formSection.appendChild(label);
}

function deleteField(e) {
	e.target.parentNode.remove();
}

let designObjForm = document.getElementById('design-obj-form');
if (designObjForm != null) {
	designObjForm.addEventListener('submit', function(e) {
		e.preventDefault();

		let objectName = document.getElementById('object-name').value;
		sessionStorage.setItem('objectName', objectName);

		let attInputs = document.getElementsByClassName('attribute-input');

		let attValues = [];

		for (let i = 0; i < attInputs.length; i++) {
			attValues.push(attInputs[i].value);
		}

		sessionStorage.setItem('attributes', JSON.stringify(attValues));

		let privacyOptions = document.getElementsByName('privacy');
		let privacy = '';

		for (let i = 0; i < privacyOptions.length; i++) {
			if(privacyOptions[i].checked) {
				privacy = privacyOptions[i].value;
			}
		}

		sessionStorage.setItem('privacy', privacy);

		window.location.href = 'preview-object-design.php';
	});
}

(function() {
	let headingObjectName = document.getElementById('object-name-preview');

	if (headingObjectName !== null) {
		let objectName = sessionStorage.getItem('objectName');
		let span = document.createElement('span');
		span.innerHTML = objectName;
		
		headingObjectName.appendChild(span);

		let privacy = sessionStorage.getItem('privacy');
		let span2 = document.createElement('span');
		span2.innerHTML = privacy;
		let headingPrivacy = document.getElementById('privacy-preview');
		headingPrivacy.appendChild(span2);

		let thead = document.getElementById('preview-head');
		let storedAtts = JSON.parse(sessionStorage.getItem('attributes'));
		let tr = document.createElement('tr');

		for (let i = 0; i < storedAtts.length; i++) {
			let th = document.createElement('th');
			th.innerHTML = storedAtts[i];
			tr.appendChild(th);
		}

		thead.appendChild(tr);

		let tbody = document.getElementById('preview-body');

		for (let i = 0; i < 4; i++) {
			let trSample = document.createElement('tr');

			for(let i = 0; i < storedAtts.length; i++) {
				let tdSample = document.createElement('td');
				tdSample.innerHTML = "Sample value";
				trSample.appendChild(tdSample);
			}

			tbody.appendChild(trSample);
		}
	}
	
})();

(function() {
 	let storedObjectName = sessionStorage.getItem('objectName');
 	let inputObjectName = document.getElementById('object-name');

 	if (storedObjectName !== null && inputObjectName !== null) {
 		inputObjectName.value = storedObjectName;

 		let storedAtts = JSON.parse(sessionStorage.getItem('attributes'));
 		let attInput1 = document.getElementById('att-1');
 		attInput1.value = storedAtts[0];
 		console.log(storedAtts[0]);

 		for(let i = 1; i < storedAtts.length; i++) {
 			addAttField(storedAtts[i]);
 		}

 		let storedPrivacy = sessionStorage.getItem('privacy');
 		if (storedPrivacy === 'private') {
 			let privateRadioBtn = document.getElementById('private-radio-btn');
 			privateRadioBtn.checked = true;
 		}
 	}
})();

function saveDesignToDb() {
	let form = document.createElement('form');
	form.method = 'POST';
	form.action = 'save-object.php';

	let objectName = document.createElement('input');
	objectName.type = 'hidden';
	objectName.name = 'object-name';
	objectName.value = sessionStorage.getItem('objectName');

	let attributes = document.createElement('input');
	attributes.type = 'hidden';
	attributes.name = 'attributes';
	attributes.value = sessionStorage.getItem('attributes');

	let privacy = document.createElement('input');
	privacy.type = 'hidden';
	privacy.name = 'privacy';
	privacy.value = sessionStorage.getItem('privacy');

	form.appendChild(objectName);
	form.appendChild(attributes);
	form.appendChild(privacy);

	document.body.appendChild(form);

	form.submit();
}

function sendForm(name, url, designId, privacy, add) {
	let form = document.createElement('form');
	form.method = 'POST';

	form.action = url;

	let objectName = document.createElement('input');
	objectName.type = 'hidden';
	objectName.name = 'object-name';
	objectName.value = name;

	let objectDesignId = document.createElement('input');
	objectDesignId.type = 'hidden';
	objectDesignId.name = 'object-design-id';
	objectDesignId.value = designId;

	let objectPrivacy = document.createElement('input');
	objectPrivacy.type = 'hidden';
	objectPrivacy.name = 'object-privacy';
	objectPrivacy.value = privacy;

	let addEntry = document.createElement('input');
	addEntry.type = 'hidden';
	addEntry.name = 'add';
	addEntry.value = add;

	form.appendChild(objectName);
	form.appendChild(objectDesignId);
	form.appendChild(objectPrivacy);
	form.appendChild(addEntry);

	document.body.appendChild(form);

	form.submit();
}

let addEntryValue = document.getElementById('add-entry-value');
if (addEntryValue != null && addEntryValue.innerHTML === 'add') {
	addEntryValue.innerHTML = 'not add';
	showAddItemsForm();
}

let indexTable = document.getElementById('index-table');
if (indexTable != null) {
	let viewBtn = document.getElementsByClassName('table-view-btn');

	for (let i = 0; i < viewBtn.length; i++) {
		let objectId = viewBtn[i].parentNode.parentNode.id;
		let objectDesignId = viewBtn[i].parentNode.parentNode.children[0].value;
		let objectPrivacy = viewBtn[i].parentNode.parentNode.children[1].value;
		viewBtn[i].addEventListener('click', function() {
			sendForm(objectId, 'view-records.php', objectDesignId, objectPrivacy, 'not add');
		});
	}
}

let publicIndexTable = document.getElementById('public-records-table');
if (publicIndexTable != null) {
	let viewBtn = document.getElementsByClassName('table-view-btn');

	for (let i = 0; i < viewBtn.length; i++) {
		let objectId = viewBtn[i].parentNode.parentNode.id;
		let objectDesignId = viewBtn[i].parentNode.parentNode.children[0].value;
		let objectPrivacy = viewBtn[i].parentNode.parentNode.children[1].value;
		viewBtn[i].addEventListener('click', function() {
			sendForm(objectId, 'view-public-object.php', objectDesignId, objectPrivacy, 'not add');
		});
	}
}

function showAddItemsForm() {
	let theadRow = document.getElementById('view-head-row');
	let thCollectiion = theadRow.children;
	let form = document.getElementById('add-items-form');

	if (form.children.length === 2 ) {
		for (let i = 0; i < thCollectiion.length-2; i++) {
			let label = document.createElement('label');
			label.innerHTML = thCollectiion[i].textContent + ': ';

			let input = document.createElement('input');
			input.type = 'text';
			input.name = thCollectiion[i].textContent.replace(' ', '_').toLowerCase();
			input.required = true;

			form.appendChild(label);
			form.appendChild(input);
		}

		let addBtn = document.createElement('button');
		addBtn.type = 'submit';
		addBtn.innerHTML = 'Add';
		addBtn.className = 'btn popup-btn';

		let cancelBtn = document.createElement('button');
		cancelBtn.type = 'button';
		cancelBtn.innerHTML = 'Cancel';
		cancelBtn.className = 'btn popup-btn';
		cancelBtn.onclick = closePopUpForm;

		let btnGroup = document.createElement('div');
		btnGroup.className = 'btn-group';
		btnGroup.appendChild(addBtn);
		btnGroup.appendChild(cancelBtn);

		form.appendChild(btnGroup);
	}

	let formContainer = document.getElementById('add-items-form-parent');
	formContainer.style.display = 'block';
}

function addEntryFromIndex(e) {
	let objectId = e.parentNode.parentNode.id;
	let objectDesignId = e.parentNode.parentNode.children[0].value;
	let objectPrivacy = e.parentNode.parentNode.children[1].value;

	sendForm(objectId, 'view-records.php', objectDesignId, objectPrivacy, 'add');
}

function closePopUpForm() {
	this.parentNode.parentNode.parentNode.style.display = 'none';
}

let deleteItemBtns = document.getElementsByClassName('delete-item-btn');
if (deleteItemBtns != null) {
	for (let i = 0; i < deleteItemBtns.length; i ++) {
		let btn = deleteItemBtns[i];
		btn.addEventListener('click', function() {
			let form = document.createElement('form');
			form.method = 'POST';
			form.action = 'delete-from-object.php';
			
			let objectName = document.createElement('input');
			objectName.type = 'hidden';
			objectName.name = 'object-name';
			objectName.value = btn.parentNode.parentNode.parentNode.id;

			let objectId = document.createElement('input');
			objectId.type = 'hidden';
			objectId.name = 'object-id';
			objectId.value = btn.parentNode.parentNode.id;

			form.appendChild(objectName);
			form.appendChild(objectId);
			document.body.appendChild(form);
			form.submit();
		});
	}
}

function confirmDeleteObject(e) {
	let form = '';
	let formIndex = document.getElementById('delete-object-form-index');
	let formRecords = document.getElementById('delete-object-form-records');

	if (formIndex == null) {
		form = formRecords;
	} else {
		form = formIndex;
		
		let objectName = document.createElement('input');
		objectName.type = 'hidden';
		objectName.name = 'object-name';
		objectName.value = e.parentNode.parentNode.id;

		let objectId = document.createElement('input');
		objectId.type = 'hidden';
		objectId.name = 'object-design-id';
		objectId.value = e.parentNode.parentNode.firstChild.value;

		form.appendChild(objectName);
		form.appendChild(objectId);
	}

	if (form.children.length === 4) {
		let deleteBtn = document.createElement('button');
		deleteBtn.type = 'submit';
		deleteBtn.innerHTML = 'Delete';
		deleteBtn.className = 'btn popup-btn';

		let cancelBtn = document.createElement('button');
		cancelBtn.type = 'button';
		cancelBtn.innerHTML = 'Cancel';
		cancelBtn.className = 'btn popup-btn';
		cancelBtn.onclick = closePopUpForm;

		let btnGroup = document.createElement('div');
		btnGroup.className = 'btn-group';
		btnGroup.appendChild(deleteBtn);
		btnGroup.appendChild(cancelBtn);


		form.appendChild(btnGroup);
	}

	let confirmDelete = document.getElementById('delete-object-form-parent');
	confirmDelete.style.display = 'block';
}

let deleteObjectBtns = document.getElementsByClassName('delete-object-btn');
if (deleteObjectBtns != null) {
	for (let i = 0; i < deleteObjectBtns.length; i ++) {
		let btn = deleteObjectBtns[i];
		btn.addEventListener('click', function() {
			confirmDeleteObject();
		});
	}
}

let editItemBtns = document.getElementsByClassName('edit-item-btn');
if (editItemBtns != null) {
	for (let i = 0; i < editItemBtns.length; i ++) {
		let btn = editItemBtns[i];
		btn.addEventListener('click', function(e) {
			showEditItemForm(btn);
		});
	}
}

function showEditItemForm(btn) {
	let theadRow = document.getElementById('view-head-row');
	let thCollection = theadRow.children;
	let form = document.getElementById('edit-item-form');
	let currentValues = btn.parentNode.parentNode.children;

	while (form.children.length > 1) {
		form.removeChild(form.lastChild);
	}

	let itemId = document.createElement('input');
	itemId.type = 'hidden';
	itemId.name = 'item-id';
	itemId.value = btn.parentNode.parentNode.id;
	form.appendChild(itemId);

	for (let i = 0; i < thCollection.length-2; i++) {
		let label = document.createElement('label');
		label.innerHTML = thCollection[i].textContent + ': ';

		let input = document.createElement('input');
		input.type = 'text';
		input.name = thCollection[i].textContent.replace(' ', '_').toLowerCase();
		input.value = currentValues[i].textContent;
		input.required = true;

		form.appendChild(label);
		form.appendChild(input);
	}

	let addBtn = document.createElement('button');
	addBtn.type = 'submit';
	addBtn.innerHTML = 'Save';
	addBtn.className = 'btn popup-btn';

	let cancelBtn = document.createElement('button');
	cancelBtn.type = 'button';
	cancelBtn.innerHTML = 'Cancel';
	cancelBtn.className = 'btn popup-btn';
	cancelBtn.onclick = closePopUpForm;

	let btnGroup = document.createElement('div');
	btnGroup.className = 'btn-group';
	btnGroup.appendChild(addBtn);
	btnGroup.appendChild(cancelBtn);

	form.appendChild(btnGroup);

	let formContainer = document.getElementById('edit-item-form-parent');
	formContainer.style.display = 'block';
}

function editObjectDesign(privacy) {
	let theadRow = document.getElementById('view-head-row');
	let thCollection = theadRow.children;
	let form = document.getElementById('edit-object-form');

	if(form.children.length === 3) {
		for (let i = 0; i < thCollection.length-2; i++) {
			let label = document.createElement('label');
			label.innerHTML = 'Column ' + (i + 1) + ': ';

			let input = document.createElement('input');
			input.type = 'text';
			input.name = thCollection[i].innerHTML.replace(' ', '_').toLowerCase();
			input.value = thCollection[i].innerHTML;
			input.required = true;

			form.appendChild(label);
			form.appendChild(input);

			if (i > 0) {
				let deleteColBtn = document.createElement('button');
				deleteColBtn.type = 'button';
				deleteColBtn.className = 'btn';
				deleteColBtn.innerHTML = 'Delete';
				form.appendChild(deleteColBtn);
			}
		}

		let addCol = document.createElement('button');
		addCol.type = 'button';
		addCol.innerHTML = 'Add Column';
		addCol.className = 'btn';
		addCol.id = 'add-col-btn'
		addCol.style.margin = '10px 0 0 0';
		addCol.onclick = editObjectAddColumn;
		form.appendChild(addCol);

		let label1 = document.createElement('label');
		let label2 = document.createElement('span');
		let label3 = document.createElement('span');
		label1.style.margin = '30px 0 0 0';
		label1.innerHTML = 'Privacy: ';
		label2.innerHTML = 'Public';
		label3.innerHTML = 'Private';

		let radio1 = document.createElement('input');
		radio1.type = 'radio';
		radio1.name = 'privacy';
		radio1.value = 'public';
		let radio2 = document.createElement('input');
		radio2.type = 'radio';
		radio2.name = 'privacy';
		radio2.value = 'private';

		if (privacy === 'public') {
			radio1.checked = true;
		} else {
			radio2.checked = true;
		}

		form.appendChild(label1);
		form.appendChild(radio1);
		form.appendChild(label2);
		form.appendChild(radio2);
		form.appendChild(label3);

		let addBtn = document.createElement('button');
		addBtn.type = 'submit';
		addBtn.innerHTML = 'Save';
		addBtn.className = 'btn popup-btn';

		let cancelBtn = document.createElement('button');
		cancelBtn.type = 'button';
		cancelBtn.innerHTML = 'Cancel';
		cancelBtn.className = 'btn popup-btn';
		cancelBtn.onclick = closePopUpForm;

		let btnGroup = document.createElement('div');
		btnGroup.className = 'btn-group';
		btnGroup.style.margin = '30px 0 0 0';
		btnGroup.appendChild(addBtn);
		btnGroup.appendChild(cancelBtn);

		form.appendChild(btnGroup);
	}

	let formContainer = document.getElementById('edit-object-form-parent');
	formContainer.style.display = 'block';
}

var editObjectAddColumnClicks = 0;
function editObjectAddColumn() {
	editObjectAddColumnClicks++;

	let form = document.getElementById('edit-object-form');

	let newCols = document.createElement('input');
	newCols.type = 'hidden';
	newCols.name = 'new-cols';
	newCols.value = editObjectAddColumnClicks;
	form.appendChild(newCols);

	let label = document.createElement('label');
	label.innerHTML = 'New Column:'

	let input = document.createElement('input');
	input.type = 'text';
	input.name = 'new-col-' + editObjectAddColumnClicks;
	input.required = true;

	let removeBtn = document.createElement('button');
	removeBtn.innerHTML = 'Delete';
	removeBtn.className = 'btn';
	removeBtn.onclick = deleteField;

	let group = document.createElement('div');
	group.appendChild(label);
	group.appendChild(input);
	group.appendChild(removeBtn);

	let addColBtn = document.getElementById('add-col-btn');

	form.insertBefore(group, addColBtn);
}

function showTabOption(selected) {
	let options = document.getElementsByClassName('tab-options');
	let tabs = document.getElementsByClassName('tab');

	for(let i = 0; i < options.length; i++) {
		options[i].style.display = 'none';
		tabs[i].classList.remove('selected')
	}

	switch (selected) {
		case 1:
			document.getElementById('rename-div').style.display = 'block';
			tabs[0].className = 'selected tab';
			break;
		case 2:
			document.getElementById('add-div').style.display = 'block';
			tabs[1].className = 'selected tab';
			break;
		case 3:
			document.getElementById('delete-div').style.display = 'block';
			tabs[2].className = 'selected tab';
			break;
		case 4:
			document.getElementById('privacy-div').style.display = 'block';
			tabs[3].className = 'selected tab';
			break;
		default:
			break;
	}
}

function sendObjectData(e) {
	let form = document.getElementById('edit-object-form');

	if (form.children.length == 0) {
		let objectName = document.createElement('input');
		objectName.type = 'hidden';
		objectName.name = 'object-name';
		objectName.value = e.parentNode.parentNode.id;

		let objectDesignId = document.createElement('input');
		objectDesignId.type = 'hidden';
		objectDesignId.name = 'object-design-id';
		objectDesignId.value = e.parentNode.parentNode.firstChild.value;

		let objectPrivacy = document.createElement('input');
		objectPrivacy.type = 'hidden';
		objectPrivacy.name = 'object-privacy';
		objectPrivacy.value = e.parentNode.parentNode.children[1].value;

		form.appendChild(objectName);
		form.appendChild(objectDesignId);
		form.appendChild(objectPrivacy);
	}

	form.submit();
}

function clearForm(formId) {
	let form = document.getElementById(formId);
	let inputFields = form.getElementsByTagName('input');

	for (let i = 0; i < inputFields.length; i++) {
		inputFields[i].value = '';
	}

}

function confirmAction() {
	document.getElementById('confirm-action-div').style.display = 'block';
}

function editObjectColumnNames() {
	let form = document.getElementById('rename-object-columns-form');
	form.submit();
}

function confirmDeleteObjectColumn(colName) {
	let form = document.getElementById('confirm-delete-object-column-form');

	if (form.children.length === 3) {
		let input = document.createElement('input')
		input.type = 'hidden';
		input.name = 'col-to-delete';
		input.value = colName;

		form.appendChild(input);

		let deleteBtn = document.createElement('button');
		deleteBtn.type = 'button';
		deleteBtn.innerHTML = 'Delete';
		deleteBtn.className = 'btn popup-btn';
		deleteBtn.onclick = deleteObjectColumn;

		let cancelBtn = document.createElement('button');
		cancelBtn.type = 'button';
		cancelBtn.innerHTML = 'Cancel';
		cancelBtn.className = 'btn popup-btn';
		cancelBtn.onclick = closePopUpForm;

		let btnGroup = document.createElement('div');
		btnGroup.className = 'btn-group';
		btnGroup.appendChild(deleteBtn);
		btnGroup.appendChild(cancelBtn);


		form.appendChild(btnGroup);
	}

	document.getElementById('confirm-delete-object-column-parent').style.display = 'block';
}

function deleteObjectColumn() {
	let form = document.getElementById('confirm-delete-object-column-form');
	form.submit();
}

function deleteParentField(e) {
	addColCount--;
	e.parentNode.remove();
}

var addColCount = 1;
function addColumnField() {
	addColCount++;
	let holder = document.getElementById('new-column-inputs');

	let label = document.createElement('label');
	label.innerHTML = "New column name: "

	let input = document.createElement('input');
	input.type = 'text';
	input.name = 'new-col-' + addColCount;
	input.className = 'new-col';
	input.placeholder = 'E.g. Author';
	input.required = true;

	let button = document.createElement('button');
	button.type = 'button';
	button.className = 'btn';
	button.innerHTML = 'Remove';
	button.onclick = deleteField;

	label.appendChild(input);
	label.appendChild(button);
	holder.appendChild(label);
}

/*function addNewColumns() {
	let colInputs = document.getElementsByClassName('new-col');

	let colValues = [];

	for (let i = 0; i < colInputs.length; i++) {
		if (colInputs[i].value == null or colInputs[i].value == ''){
			colValues.push(colInputs[i].value);
		} else {
			alert()
		}
		
	}

	let newCols = document.createElement('input');
	newCols.type = 'hidden';
	newCols.name = 'new-columns';
	newCols.value = JSON.stringify(colValues);

	let form = document.getElementById('add-object-column-form');
	form.appendChild(newCols);
	form.submit();
}*/

function sortTable(tableId, column, order) {
	var tableElement = document.getElementById(tableId);

	if (order === 'desc') {
		[].slice.call(tableElement.tBodies[0].rows).sort(function(a, b) {
			return (
				a.cells[column-1].textContent < b.cells[column-1].textContent ? -1 :
				a.cells[column-1].textContent > b.cells[column-1].textContent ? 1 :
				0);
		}).forEach(function(val, index) {
			tableElement.tBodies[0].appendChild(val);
		});
	} else {
		[].slice.call(tableElement.tBodies[0].rows).sort(function(a, b) {
			return (
				a.cells[column-1].textContent > b.cells[column-1].textContent ? -1 :
				a.cells[column-1].textContent < b.cells[column-1].textContent ? 1 :
				0);
		}).forEach(function(val) {
			tableElement.tBodies[0].appendChild(val);
		});
	}
}

function filterByPrivacy() {
	let form = document.getElementById('table-filters-form');
	form.submit();
}

let tableFilterDropDown = document.getElementById('search-by-option');
if (tableFilterDropDown != null) {
	let columns = document.getElementById('view-head-row').cells;
	
	let publicTable = document.getElementById('public-object-home-table');
	let ceiling = 0;

	if (publicTable == null) {
		ceiling = columns.length-2;
	} else {
		ceiling = columns.length;
	}
	for (let i = 0; i < ceiling; i++) {
		let option = document.createElement('option');
		option.text = columns[i].textContent;
		option.value = columns[i].textContent;
		tableFilterDropDown.appendChild(option);
	}
}

let textboxes = document.getElementsByTagName('input');
if (textboxes.length > 0) {
	for (let i = 0; i < textboxes.length; i ++) {
		if (textboxes[i].type === 'text' || textboxes[i].type === 'password') {
			textboxes[i].onclick = function() {
				this.value = '';
			};
		}
	}
}

function menuToggle() {
	let menu = document.getElementById('menu');
	if (menu.className === 'menu') {
	    menu.className += ' responsive';
	} else {
	    menu.className = 'menu';
	}
}