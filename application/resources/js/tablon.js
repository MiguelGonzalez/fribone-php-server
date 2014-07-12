App = Ember.Application.createWithMixins(Bootstrap);

/*
 * Frigorífico
*/

App.Router.map(function() {
  this.route("fridge", { path: "/fridge" });
  this.route("statistics", { path: "/estadisticas" });
});

App.FridgeRoute = Ember.Route.extend({
	renderTemplate: function() {
		this.render('fridge');
	},
	model: function() {
		return Ember.$.getJSON('/fridge/productos');
	}
})

App.FridgeController = Ember.ObjectController.extend({
	myModalButtons: [
      Ember.Object.create({title: 'Añadir', clicked: 'submit'}),
      Ember.Object.create({title: 'Cancelar', clicked: 'cancel', dismiss: 'modal'})
	],
	actions: {
	    submit: function() {
			Bootstrap.GNM.push('Añadir', 'El producto ha sido añadido a tu frigorífico', 'info');
			return Bootstrap.ModalManager.hide('myModal');
	    },
	    cancel: function() {
			return Bootstrap.GNM.push('Cancelar', 'No has añadido ningún producto', 'warning');
	    },
	    show: function() {
	      return Bootstrap.ModalManager.show('myModal');
	    }
	}
});

/*
 * Estadísticas
*/
App.StatisticsRoute = Ember.Route.extend({
	renderTemplate: function() {
		this.render(
			'statistics');
	},
	model: function() {
		return {};
	}
})