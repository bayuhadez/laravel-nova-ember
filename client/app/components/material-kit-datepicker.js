import Component from '@ember/component';
import $ from 'jquery';
import { isBlank } from '@ember/utils';

export default Component.extend({

	defaultIcons: {
		time: "fa fa-clock-o",
		date: "fa fa-calendar",
		up: "fa fa-chevron-up",
		down: "fa fa-chevron-down",
		previous: 'fa fa-chevron-left',
		next: 'fa fa-chevron-right',
		today: 'fa fa-screenshot',
		clear: 'fa fa-trash',
		close: 'fa fa-remove'
	},
	datePicker: null,
	maxLength: 10,
	name: 'date',
	value: null,

	init() {
		this._super(...arguments);

		var icons = this.get('icons');
		var defaultIcons = this.get('defaultIcons');

		this.setProperties({
			icons: {defaultIcons, icons},
			maxLength: this.get('maxLength'),
			name: this.get('name'),
			placeholder: this.get('placeholder'),
			value: this.get('value'),
		});
	},

	didRender() {
		this._super(...arguments);

		// estimation selector value:
		// '#selector input.datepicker'
		var selector = '#' + this.get('elementId') + ' input.datepicker';

		var datePicker = $(selector).first().datetimepicker({
			format: 'L',
			format: 'DD/MM/YYYY',
			icons: this.get('icons'),
		});
		this.set('datePicker', datePicker);
	},

	willDestroyElement() {

		// destroy datepicker
		if (!isBlank(this.get('datePicker'))) {
			this.get('datePicker').datetimepicker('destroy');
		}

		this._super(...arguments);
	}
});
