/*global Ember, App */
(function () {
	'use strict';

	App.Router.map(function () {
		this.resource('tablon', { path: '/' }, function () {
			this.route('active');
			this.route('completed');
		});
	});

	App.TablonRoute = Ember.Route.extend({
		model: function () {
			return this.store.find('todo');
		}
	});

	App.TablonIndexRoute = App.TablonRoute.extend({
		templateName: 'menu-list',
		controllerName: 'menu-list'
	});

	App.TodosActiveRoute = Todos.TodosIndexRoute.extend({
		model: function () {
			return this.store.filter('todo', function (todo) {
				return !todo.get('isCompleted');
			});
		}
	});

	App.TodosCompletedRoute = Todos.TodosIndexRoute.extend({
		model: function () {
			return this.store.filter('todo', function (todo) {
				return todo.get('isCompleted');
			});
		}
	});
})();
