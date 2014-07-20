var router;
$(function(){
    +function(){
        page.init();

        var routes = {
            '/tablon': tablon.draw,
            '/fridge/:id': {
                '/productos': fridge.draw_productos,
                '/compras': fridge.draw_compras,
                on: fridge.draw
            },
            '/supermercados' : {
                '/:id': supermercado.draw,
                on: supermercados.draw
            }
        };
        router = new Router(routes).configure({
            html5history: true
        });
        router.init();
    }();
});

var page = {
    init: function() {
        page.draw_menu();
        page.initEvents();
        page.initActionModals();
        page.initValidators();
    },
    draw_menu: function() {
        $.ajax({
            url: '/tablon/menus',
            async:false,
            dataType: 'json'
        }).done(function(data) {
            var source   = $('#menu-template').html();
            var template = Handlebars.compile(source);

            var html_template = template(data);

            $('#menu-left .list-group').html(html_template);
        }).fail(function(jqXHR) {
            alert('Error obtener menus');
        });
    },
    initEvents: function() {
        $('#menu-left .list-group').on('click', 'a:not(#crear_frigorifico)', function(event) {
            event.preventDefault();
            router.setRoute($(this).attr('data-to'));
        });
        $('#menu-left .list-group').on('click', 'a#crear_frigorifico', function(event) {
            event.preventDefault();
            $('#crear-frigorifico-modal').modal('show');
        });
    },
    initActionModals: function() {
        $('#anadir-producto-modal').on('input', '.codigo-barras', function() {
            fridge.anadir_producto_change();
        });
        $('#anadir-producto-modal').on('click', '.busqueda', fridge.anadir_producto);
        $('#crear-frigorifico-modal').on('click', 'button.aceptar', function() {
            fridge.crear_frigorifico();
        });
        $('#crear-supermercado-modal').on('click', 'button.aceptar', function() {
            supermercados.crear_supermercado();
        });
        $('#anadir-producto-supermercado-modal').on('click', 'button.aceptar', function() {
            supermercado.anadir_producto();
        });
    },
    initValidators: function() {
        new FormValidator('form-anadir-producto-supermercado', [{
            name: 'titulo',
            display: 'required',
            rules: 'required'
        }, {
            name: 'precio',
            display: 'required',
            rules: 'required|decimal'
        }, {
            name: 'unidades',
            display: 'required',
            rules: 'required|is_natural_no_zero'
        }, {
            name: 'descripcion',
            display: 'required',
            rules: 'required'
        }, {
            name: 'codigo_barras',
            display: 'required',
            rules: 'required|is_natural'
        }, {
            name: 'codigo_rfid',
            rules: 'alpha_numeric'
        }], function(errors, evt) {
            $('form[name="form-anadir-producto-supermercado"] .form-group').removeClass('has-error');
            if (errors.length > 0) {
                for(var i=0; i<errors.length; i++) {
                    if(errors[i].name == 'descripcion') {
                        $('form[name="form-anadir-producto-supermercado"] textarea[name="' + errors[i].name + '"]').
                            parents('.form-group').
                            addClass('has-error');
                    }
                    $('form[name="form-anadir-producto-supermercado"] input[name="' + errors[i].name + '"]').
                            parents('.form-group').
                            addClass('has-error');
                }
            }

            if (evt && evt.preventDefault) {
                evt.preventDefault();
            } else if (event) {
                event.returnValue = false;
            }
        });
    }
}

var tablon = {
    draw: function() {
    }
}

var fridge = {
    fridgeActive: null,
    ajaxProductoChange: null,
    draw: function(id) {
        var tab = router.getRoute(2);
        if(fridge.fridgeActive === id) {
            return;
        } else if(tab === undefined) {
            router.setRoute(
                '/' + router.getRoute(0) +
                '/' + router.getRoute(1) +
                '/' + 'productos'
            );

            return;
        }

        fridge.fridgeActive = id;
        $.ajax({
            url: '/fridge/get_fridge/' + id,
            async:false,
            dataType: 'json'
        }).done(function(data) {
            var source   = $('#fridge-template').html();
            var template = Handlebars.compile(source);
            var html_template = template($.extend({}, data, {'tab':tab}));

            $('#main').html(html_template);

            fridge.initEvents();
        }).fail(function(jqXHR) {
            alert('Error obtener frigorífico');
        });
    },
    initEvents: function() {
        $('#fridge .nav-tabs').on('click', 'a', function(event) {
            event.preventDefault();
            router.setRoute($(this).attr('data-to'));
        });
        $('#anadir-producto').on('click', function() {

        });
    },
    draw_productos: function(id) {
        fridge.draw(id);

        $.ajax({
            url: '/fridge/get_items_fridge/' + id,
            async: false,
            dataType: 'json'
        }).done(function(data) {
            var source   = $('#fridge-item-template').html();
            var template = Handlebars.compile(source);
            var html_template = template(data);

            $('#productos').html(html_template);

            fridge.initEventsProductos();
        }).fail(function(jqXHR) {
            alert('Error obtener productos del frigorífico');
        });
    },
    initEventsProductos: function() {

    },
    draw_compras: function(id) {
        fridge.draw(id);

    },
    crear_frigorifico: function() {
        var nombre = $('#crear-frigorifico-modal .nombre').val();

        if(nombre.length !== 0) {
            $.ajax({
                url: '/fridge/create_fridge',
                type: 'POST',
                data: {'titulo' : nombre},
                dataType: 'json'
            }).done(function(data) {
                if(data.ok) {
                    page.draw_menu();

                    $('#crear-frigorifico-modal .form-group').removeClass('has-error');
                    $('#crear-frigorifico-modal').modal('hide');
                } else {
                    alert('No se pudo crear el frigorifico');
                }
            }).fail(function(jqXHR) {
                alert('Error crear frigorifico');
            });
        } else {
            $('#crear-frigorifico-modal .form-group').addClass('has-error');
        }
    },
    anadir_producto_change: function() {
        var codigo_barras = $('#anadir-producto-modal .codigo-barras').val();

        if(isInteger(codigo_barras)) {
            if(fridge.ajaxProductoChange !== null) {
                fridge.ajaxProductoChange.abort();
            }

            fridge.ajaxProductoChange = $.ajax({
                url: '/supermercado/search_productos_codigo_barras/' + codigo_barras,
                dataType: 'json'
            }).done(function(data) {
                fridge.ajaxProductoChange = null;

                var source   = $('#anadir-producto-search-template').html();
                var template = Handlebars.compile(source);
                var html_template = template(data);

                $('#anadir-producto-search').html(html_template);
            }).fail(function(jqXHR, text_status) {
                if(text_status === 'abort') {
                    return;
                }
                alert('Error obtener productos del frigorífico');
            });
        } else {
            $('#anadir-producto-modal .form-group').addClass('has-error');
        }
    },
    anadir_producto: function() {
        var id_producto = $(this).attr('data-id');

        fridge.ajaxProductoChange = $.ajax({
            url: '/fridge/anadir_producto/' + fridge.fridgeActive + '/' + id_producto,
            dataType: 'json'
        }).done(function(data) {
            if(data.ok) {
                $('#anadir-producto-modal').modal('hide');
                fridge.draw_productos(fridge.fridgeActive);
            } else {
                alert('No se pudo agregar el producto al frigorífico');
            }
        }).fail(function(jqXHR, text_status) {
            alert('Error al agregar el producto al frigorífico');
        });
    }
}

var supermercados = {
    draw: function() {
        $.ajax({
            url: '/supermercado/get_supermercados',
            async:false,
            dataType: 'json'
        }).done(function(data) {
            var source   = $('#supermercados-template').html();
            var template = Handlebars.compile(source);
            var html_template = template(data);

            $('#main').html(html_template);
            console.log('Dibujado template');

            supermercados.initEvents();
        }).fail(function(jqXHR) {
            alert('Error obtener supermercados');
        });
    },
    initEvents: function() {
        $('#listado-supermercados').on('click', '.supermercado', function() {
            router.setRoute($(this).attr('data-to'));
        });
    },
    crear_supermercado:function() {
        var nombre = $('#crear-supermercado-modal .nombre').val();

        if(nombre.length !== 0) {
            $.ajax({
                url: '/supermercado/crear_supermercado',
                type: 'POST',
                data: {'nombre' : nombre},
                dataType: 'json'
            }).done(function(data) {
                if(data.ok) {
                    supermercados.draw();

                    $('#crear-supermercado-modal .form-group').removeClass('has-error');
                    $('#crear-supermercado-modal').modal('hide');
                } else {
                    alert('No se pudo crear el supermercado');
                }
            }).fail(function(jqXHR) {
                alert('Error crear supermercado');
            });
        } else {
            $('#crear-supermercado-modal .form-group').addClass('has-error');
        }
    }
}

var supermercado = {
    id_supermercado : null,
    draw: function(id_supermercado) {
        supermercado.id_supermercado = id_supermercado;
        $.ajax({
            url: '/supermercado/get_supermercado/' + id_supermercado,
            dataType: 'json'
        }).done(function(data) {
            var source   = $('#supermercado-template').html();
            var template = Handlebars.compile(source);
            var html_template = template(data);

            $('#main').html(html_template);

            supermercado.initEvents();
        }).fail(function(jqXHR) {
            alert('Error obtener supermercados');
        });
    },
    initEvents: function() {
        $('#supermercado').on('click', '.volver', function() {
            router.setRoute($(this).attr('data-to'));
        });
        $('#supermercado').on('click', '.caja-item .item:not(.active)', function() {
            $(this).addClass('active').find('.info').slideDown();
        });
        $('#supermercado').on('click', '.caja-item .item.active', function() {
            $(this).removeClass('active').find('.info').slideUp();
        });
    },
    anadir_producto: function() {
        var form = $('#anadir-producto-supermercado-modal form');
        form.submit(); // Ejecutamos el evento para validar

        if(form.find('.has-error').length === 0) {
            var datos = form.serializeArray();

            $.ajax({
                url: '/supermercado/anadir_producto_supermercado/' + supermercado.id_supermercado,
                type: 'POST',
                data: datos,
                dataType: 'json'
            }).done(function(data) {
                if(data.ok) {
                    supermercado.draw(supermercado.id_supermercado);

                    $('#anadir-producto-supermercado-modal .form-group').removeClass('has-error');
                    $('#anadir-producto-supermercado-modal').modal('hide');
                } else {
                    alert('No se pudo añadir el producto al supermercado');
                }
            }).fail(function(jqXHR) {
                alert('Error al añadir el producto al supermercado');
            });
        }
    }
}

// Funciones globales
function isInteger(str) {
    var n = ~~Number(str);
    return String(n) === str && n >= 0;
}