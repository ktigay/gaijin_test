class App {

	_events = {};

	_registerGlobalEvents() {

		for(let eventName of Object.keys(this._events)) {
			document.body.addEventListener(eventName, (event) => {
				let target = event.target;

				for (let [selector, listener] of Object.entries(this._events[eventName])) {

					if (target.classList.contains(selector)) {
						if (listener.call(this, event) === false) {
							event.preventDefault();
							return false;
						}
					}
				}
			});
		}
	}
}