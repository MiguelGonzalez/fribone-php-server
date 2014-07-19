var router;
$(function(){
    +function(){
        page.init();

        var routes = {
            '/tablon': tablon.draw,
            '/fridge/:id': {
                '/productos': fridge.draw_productos,
                '/compras': fridge.draw_compras,
                before: fridge.reset,
                on: fridge.draw
            },
            '/estadisticas' : estadisticas.draw
        };
        router = new Router(routes).configure({
            html5history: true,
            recurse: 'forward'
        });
        router.init();
    }();
});

var page = {
    init: function() {
        page.draw_menu();
        page.initEvents();
        page.initActionModals();
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
        $('#menu-left .list-group').on('click', 'a', function(event) {
            event.preventDefault();
            router.setRoute($(this).attr('data-to'));
        });
    },
    initActionModals: function() {
        $('#anadir-producto-modal').on('click', 'button.aceptar', function() {
            fridge.anadir_producto();
        });
    }
}

var tablon = {
    draw: function() {
    }
}

var fridge = {
    fridgeActive: null,
    reset: function() {
        fridge.fridgeActive = null;
    },
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
    draw_productos: function(id) {
        $.ajax({
            url: '/fridge/get_items_fridge/' + id,
            async:false,
            dataType: 'json'
        }).done(function(data) {
            var source   = $('#fridge-item-template').html();
            var template = Handlebars.compile(source);
            var html_template = template(data);

            $('#productos').html(html_template);

            fridge.initEvents();
        }).fail(function(jqXHR) {
            alert('Error obtener productos del frigorífico');
        });
    },
    draw_compras: function() {
    },
    initEvents: function() {
        $('#fridge .nav-tabs').on('click', 'a', function(event) {
            event.preventDefault();
            router.setRoute($(this).attr('data-to'));
        });
        $('#anadir-producto').on('click', function() {

        });
    },
    anadir_producto: function() {
        var codigo_barras = $('#anadir-producto-modal .codigo-barras').val();

        if(isInteger(codigo_barras)) {
            $.ajax({
                url: '/item/get_item/' + codigo_barras,
                dataType: 'json'
            }).done(function(data) {
                if(data.ok) {
                    alert('Producto agregado');
                    $('#anadir-producto-modal .form-group').removeClass('has-error');
                    $('#anadir-producto-modal').modal('hide');
                } else {
                    alert('No existe el producto');
                }
            }).fail(function(jqXHR) {
                alert('Error obtener productos del frigorífico');
            });
        } else {
            $('#anadir-producto-modal .form-group').addClass('has-error');
        }
    }
}

var estadisticas = {
    draw: function() {
        var source   = $('#estadisticas').html();
        var template = Handlebars.compile(source)();

        $('#main').html(template);
    }
}

// Funciones globales
function isInteger(str) {
    var n = ~~Number(str);
    return String(n) === str && n >= 0;
}