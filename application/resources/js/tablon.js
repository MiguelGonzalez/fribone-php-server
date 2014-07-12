window.App = Ember.Application.create();

App.Router.map(function() {
  this.route("fridge", { path: "/fridge" });
  this.route("statistics", { path: "/estadisticas" });
});

App.Frige = Ember.Route.extend({
	renderTemplate: function() {
		this.render(
			'fridge');
	},
	model: function() {
		return {};
	}
})

App.Statistics = Ember.Route.extend({
	renderTemplate: function() {
		this.render(
			'statistics');
	},
	model: function() {
		return {};
	}
})