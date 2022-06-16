const request = window.location.search;
const iframe = document.querySelector('#iframe');
const index = new RegExp('.*index.php$');
const home = new RegExp('.*\/$');
const search = new RegExp('.*\?id\=');
let lastId;

const renderIframe = (width, height, id) => {
	const iframeLink = document.querySelector('#iframe-link');
	const intervalIframeLink = () => {
		iframeLink.innerText = `<iframe width="${width}" height="${height}" allowfullscreen referrerpolicy="no-referrer" frameborder="0" src="${window.location}timeline.php?id=${Number.parseInt(id, 10)}"></iframe>`;
	};

	setTimeout(intervalIframeLink, 1000);
	iframeLink.style.display = 'block';
	iframe.style.display = 'block';
	iframe.setAttribute('width', width);
	iframe.setAttribute('height', height);
};

const getLastId = () => {
	fetch('./last-id.php', {
		method: 'POST',
		body: 'request',
		headers: {
			'Content-type': 'application/text-plain; chartset=UTF-8',
		},
	})
		.then(response => response.text())
		.then(body => {
			document.querySelector('#last-id').innerText = body;
			lastId = body;
		});
};

const lastIdInterval = () => {
	document.querySelector('#last-id').innerText = lastId;
	const width = Number.parseInt(document.querySelector('#width').value, 10) || 400;
	const height = Number.parseInt(document.querySelector('#height').value, 10) || 800;
	renderIframe(width, height, lastId);
};

const formTextSubmit = textForm => {
	fetch('./timeline.php', {
		method: 'POST',
		body: JSON.stringify(textForm),
		headers: {
			'Content-type': 'application/json; chartset=UTF-8',
		},
	})
		.then(response => response.text())
		.then(body => {
			getLastId();
			renderIframe(textForm.width, textForm.height, lastId);
			iframe.contentWindow.document.body.innerHTML = body;
		});
};

const formSelectSubmit = selectForm => {
	fetch('./timeline.php', {
		method: 'POST',
		body: JSON.stringify(selectForm),
		headers: {
			'Content-type': 'application/json; chartset=UTF-8',
		},
	})
		.then(response => response.text())
		.then(body => {
			lastId = selectForm.id;
			const width = Number.parseInt(document.querySelector('#width').value, 10) || 400;
			const height = Number.parseInt(document.querySelector('#height').value, 10) || 800;
			renderIframe(width, height, selectForm.id);
			iframe.contentWindow.document.body.innerHTML = body;
		});
};

if (document.querySelector('#instance-text') && document.querySelector('#instance-select')) {
	const selectedInstance = document.querySelector('#selected-instance');
	let timelineChoice = false;
	let textFormValidation = false;

	document.querySelector('#yes-no').checked = false;
	document.querySelector('#yes-no').addEventListener('change', () => {
		document.querySelector('#yes-no').checked ? timelineChoice = true : timelineChoice = false;
	});

	document.querySelector('#form-text').addEventListener('click', () => {
		document.querySelector('#errors').innerText = '';
		document.querySelector('#validation-instance-name').innerText = '';
		document.querySelector('#validation-width').innerText = '';
		document.querySelector('#validation-height').innerText = '';

		const textForm = {
			instanceName: document.querySelector('#instance-name').value,
			width: Number.parseInt(document.querySelector('#width').value, 10),
			height: Number.parseInt(document.querySelector('#height').value, 10),
			timelineChoice,
		};

		if (textForm.instanceName === '') {
			document.querySelector('#validation-instance-name').innerText = 'Required';
		}

		if (isNaN(textForm.width)) {
			document.querySelector('#validation-width').innerText = 'Requires a number';
		} else if (textForm.width < 0) {
			document.querySelector('#validation-width').innerText = 'Requires a positive number';
		}

		if (isNaN(textForm.height)) {
			document.querySelector('#validation-height').innerText = 'Requires a number';
		} else if (textForm.height < 0) {
			document.querySelector('#validation-height').innerText = 'Requires a positive number';
		}

		textFormValidation = Boolean(textForm.instanceName !== '' && !isNaN(textForm.width) && !isNaN(textForm.height));

		if (textFormValidation) {
			formTextSubmit(textForm);
		} else {
			document.querySelector('#errors').innerText = 'There are some errors in your form.';
		}
	});

	document.querySelector('#form-select').addEventListener('click', () => {
		const selectedInstanceValue = selectedInstance.options[selectedInstance.selectedIndex].value;
		if (selectedInstanceValue !== '0') {
			const selectForm = {
				id: selectedInstanceValue,
				timelineChoice,
			};
			formSelectSubmit(selectForm);
		} else {
			alert('There is no instance');
		}
	});
}

if (document.querySelector('#last-id')) {
	getLastId();
	setInterval(lastIdInterval, 5000);
}

