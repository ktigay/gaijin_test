
class Request {
	static get(url) {
		return fetch(url, {
			credentials: 'same-origin',
			headers: {'X-Requested-With': 'XMLHttpRequest'}
		});
	}

	static post(url, params = {}) {
		return fetch(url, {
			method: 'POST',
			body: new URLSearchParams(params),
			credentials: 'same-origin',
			cache: 'no-cache',
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		});
	}
}