var AccionesCuenta = {
	init: function() {
		AccionesCuenta.inicializarValidadorLogin();
		AccionesCuenta.inicializarValidadorRegistro();
		AccionesCuenta.inicializarValidadorRecordarPassoword();


		if($('#modal-reset-password').length) {
			$('#modal-reset-password').modal('show');
			$('#modal-reset-password').on('click', '#submit_reset', function() {
				$('form[name="form-reset-password"]').submit();
			});
			AccionesCuenta.inicializarValidadorResetPassoword();
		}

		$('#darse-alta').on('click','a', AccionesCuenta.darseAlta);
		$('#recuperar-password').on('click', AccionesCuenta.recordarPassword);
		$('#modal-remember-password').on('click', '#submit_remember', function() {
			$('form[name="form-remember-password"]').submit();
		});
		$('#modal-remember-password').on('show.bs.modal', AccionesCuenta.resetearModalRemember);
	},
	darseAlta: function(event) {
		event.preventDefault();
		$('#darse-alta').off('click');
		$('#darse-alta > p').slideUp();
		$('#form-registro').slideDown();
	},
	recordarPassword: function(event) {
		event.preventDefault();
	},
	inicializarValidadorLogin: function() {
		var validator = new FormValidator('form-login', [{
		    name: 'email',
		    display: 'required',
		    rules: 'required|valid_email'
		}, {
		    name: 'password',
		    display: 'required',
		    rules: 'required'
		}], function(errors, evt) {
			$('form[name="form-login"] .form-group').removeClass('has-error');
		    if (errors.length > 0) {
		    	for(var i=0; i<errors.length; i++) {
		    		$('form[name="form-login"] input[name="' + errors[i].name + '"]').
		    				parents('.form-group').
		    				addClass('has-error');
		    	}

				if (evt && evt.preventDefault) {
					evt.preventDefault();
				} else if (event) {
					event.returnValue = false;
				}
		    }
		});
	},
	inicializarValidadorRegistro: function() {
		var validator = new FormValidator('form-registro', [{
		    name: 'email',
		    display: 'required',
		    rules: 'required|valid_email'
		}, {
		    name: 'name',
		    display: 'required',
		    rules: 'required'
		}, {
		    name: 'password',
		    display: 'required',
		    rules: 'required'
		}], function(errors, evt) {
			$('form[name="form-registro"] .form-group').removeClass('has-error');
		    if (errors.length > 0) {
		    	for(var i=0; i<errors.length; i++) {
		    		$('form[name="form-registro"] input[name="' + errors[i].name + '"]').
		    				parents('.form-group').
		    				addClass('has-error');
		    	}
		    } else {
		    	AccionesCuenta.registrar();
		    }
		    if (evt && evt.preventDefault) {
				evt.preventDefault();
			} else if (event) {
				event.returnValue = false;
			}
		});
	},
	inicializarValidadorRecordarPassoword: function() {
		var validator = new FormValidator('form-remember-password', [{
		    name: 'email',
		    display: 'required',
		    rules: 'required|valid_email'
		}, {
		    name: 'password',
		    display: 'required',
		    rules: 'required'
		}], function(errors, evt) {
			$('form[name="form-remember-password"] .form-group').removeClass('has-error');
		    if (errors.length > 0) {
		    	for(var i=0; i<errors.length; i++) {
		    		$('form[name="form-remember-password"] input[name="' + errors[i].name + '"]').
		    				parents('.form-group').
		    				addClass('has-error');
		    	}
		    } else {
		    	AccionesCuenta.recordarPasswordSubmit();
		    }
		    if (evt && evt.preventDefault) {
				evt.preventDefault();
			} else if (event) {
				event.returnValue = false;
			}
		});
	},
	inicializarValidadorResetPassoword: function() {
		var validator = new FormValidator('form-reset-password', [{
		    name: 'token',
		    display: 'required',
		    rules: 'required'
		}, {
		    name: 'email',
		    display: 'required',
		    rules: 'required|valid_email'
		}, {
		    name: 'password',
		    display: 'required',
		    rules: 'required'
		}], function(errors, evt) {
			$('form[name="form-reset-password"] .form-group').removeClass('has-error');
		    if (errors.length > 0) {
		    	for(var i=0; i<errors.length; i++) {
		    		$('form[name="form-reset-password"] input[name="' + errors[i].name + '"]').
		    				parents('.form-group').
		    				addClass('has-error');
		    	}
		    } else {
		    	AccionesCuenta.resetPasswordSubmit();
		    }
		    if (evt && evt.preventDefault) {
				evt.preventDefault();
			} else if (event) {
				event.returnValue = false;
			}
		});
	},
	registrar: function() {
		$('#form-registro').hide();
		$('#registrando').show();

		var formData = $('form[name="form-registro"]').serializeArray();
		$.ajax({
			type: "POST",
			url: "/home/register",
			data: formData,
			dataType: "json"
		})
		.done(function( msg ) {
			$('#registrando-ajax').hide();

			if(msg.result) {
				$('#registrando-ok').show();
			} else {
				$('#registrando-error').show();
			}
		})
		.fail(function(jqXHR, textStatus) {
			$('#registrando-ajax').hide();
			$('#registrando-error').show();
		});
	},
	recordarPasswordSubmit: function() {
		$('#modal-remember-password').modal('lock');
		$('#modal-remember-password .modal-footer').hide();

		$('form[name="form-remember-password"]').hide();
		$('#recordando').show();

		var formData = $('form[name="form-remember-password"]').serializeArray();
		$.ajax({
			type: "POST",
			url: "/home/remember",
			data: formData,
			dataType: "json"
		})
		.done(function( msg ) {
			$('#recordando-ajax').hide();

			if(msg.result) {
				$('#recordando-ok').show();
				$('#modal-remember-password').modal('unlock');
			} else {
				$('#recordando-error').show();
				$('#modal-remember-password').modal('unlock');
			}
		})
		.fail(function(jqXHR, textStatus) {
			$('#recordando-ajax').hide();
			$('#recordando-error').show();
			$('#modal-remember-password').modal('unlock');
		});
	},
	resetPasswordSubmit: function() {
		$('#modal-reset-password').modal('lock');
		$('#modal-reset-password .modal-footer').hide();

		$('form[name="form-reset-password"]').hide();
		$('#reset').show();

		var formData = $('form[name="form-reset-password"]').serializeArray();
		$.ajax({
			type: "POST",
			url: "/home/reset_password",
			data: formData,
			dataType: "json"
		})
		.done(function( msg ) {
			$('#reset-ajax').hide();

			if(msg.result) {
				$('#reset-ok').show();
				$('#modal-reset-password').modal('unlock');
			} else {
				$('#reset-error').show();
				$('#modal-reset-password').modal('unlock');
			}
		})
		.fail(function(jqXHR, textStatus) {
			$('#reset-ajax').hide();
			$('#reset-error').show();
			$('#modal-reset-password').modal('unlock');
		});
	},
	resetearModalRemember:function(event) {
		$('form[name="form-remember-password"]').show();
		$('#modal-remember-password .modal-footer').show();
		$('#recordando, #recordando-ok, #recordando-error').hide();
		$('#recordando-ajax').show();
	}
}

$(document).ready(function(){
	AccionesCuenta.init();
});