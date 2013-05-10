(function($) {
	$.fn.sales = function(method) {

		var settings,
			table;
//		var source = $('#actions-template').html().replace(/_id_/, '{{id}}');
//		var template = Handlebars.compile(source);

		var methods = {
			init: function(options) {

				settings = $.extend(true, {}, this.sales.defaults, options);

				return this.each(function() {
					var $this = $(this);

					table = $('table.table', this).dataTable($.extend(true, {}, settings.dataTables, {
//						fnCreatedRow: function(row, data, index) {
//							$('td.actions', row).html(template(data));
//						}
					}));
				});
			}
		};

		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}
		else {
			$.error('Method ' +  method + ' does not exist in jQuery.sales.');
		}
	};

	$.fn.sales.defaults = {
		dataTables: {
			aoColumnDefs: [
				{ bSortable: false, aTargets: [0, 13] },
				{ bVisible: false, aTargets: [0] },
				{ sClass: 'number', aTargets: [5, 7, 8, 9] },
				{ sClass: 'actions', aTargets: [13] }
			],
			asStripeClasses: [],
			bAutoWidth: false,
			bPaginate: true,
			bProcessing: true,
			bServerSide: true,
			bSortable: true,
			oLanguage: {
				sUrl: '/bundles/uamdatatables/lang/' + porot.lang + '.txt'
			}
		}
	};
})(jQuery);

$(document).ready(function() {
	$('.dzangocart.sales').sales(dzangocart.sales);
});