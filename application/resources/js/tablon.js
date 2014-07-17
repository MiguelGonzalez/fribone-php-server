window.App = Ember.Application.createWithMixins(Bootstrap);

App.ApplicationAdapter = DS.RESTAdapter.extend({
  namespace: 'api_v1'
});

/*
 * Rutas
*/

App.Router.map(function() {
	this.route('index', {path : "/"});
	this.route('home', {path : "home"});
	this.route("fridge", { path: "/fridge/:fridge_id" });
	this.route("statistics", { path: "/estadisticas" });
});

App.IndexRoute = Ember.Route.extend({
  redirect: function() {
   this.transitionTo('home'); 
  }
});

App.ApplicationRoute = Ember.Route.extend({
	model: function() {
		return this.store.find('fridge');
	},
	setupControllers: function(controller, model) {
    	this.set('controller.fridges', model);
	}
});

App.HomeRoute = Ember.Route.extend({
	model: function() {
		return {};
	},
	renderTemplate: function() {
		this.render('home');
	},
});


App.FridgeRoute = Ember.Route.extend({
	model: function(params) {
		console.log("Fridge route start");
		console.log(this.store.find('fridge', params.fridge_id));
		console.log("Fridge route end");
		return this.store.find('fridge', params.fridge_id);
	}
});

App.StatisticsRoute = Ember.Route.extend({
	renderTemplate: function() {
		this.render(
			'statistics');
	},
	model: function() {
		return {};
	}
});


/*
 * Controladores
*/


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
 * Modelos
*/
var attr = DS.attr;

App.Fridge = DS.Model.extend({
	titulo: attr('string'),
	fecha_alta: attr('date'),
	fecha_modificacion: attr('date'),
	productos: DS.hasMany('producto',  {
	  async: true
	})
});

App.Producto = DS.Model.extend({
	title: attr('string')
});