<script type="text/x-handlebars" id="menu-template">
    {{#each menu}}
        <a href="#" data-to="{{to}}" class="list-group-item" {{#if id}}id="{{id}}"{{/if}}>
            {{title}}
        </a>
    {{/each}}
</script>

<script type="text/x-handlebars" id="fridge-template">
    <div id="fridge">

        <div class="page-header">
            <h1>
                {{titulo}}
            </h1>
        </div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li {{#compare tab '===' 'productos'}}class="active"{{/compare}}>
                <a href="#productos" role="tab" data-toggle="tab" data-to="/fridge/{{id}}/productos">
                    Productos
                </a>
            </li>
            <li {{#compare tab '===' 'compras'}}class="active"{{/compare}}>
                <a href="#compras" role="tab" data-toggle="tab" data-to="/fridge/{{id}}/compras">
                    Compras
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane clearfix{{#compare tab '===' 'productos'}} active{{/compare}}" id="productos">
            </div>
            <div class="tab-pane clearfix{{#compare tab '===' 'compras'}} active{{/compare}}" id="compras">
            </div>
        </div>

    </div>
</script>

<script type="text/x-handlebars" id="fridge-productos-template">
    <div class="clearfix">
        {{#each producto}}
            <div class="col-md-3 caja-item">
                <div id="producto-{{id}}" class="item producto wrapper" data-to="/supermercados/{{id_supermercado}}/producto/{{id}}">
                    <span class="titulo">
                        {{titulo}}
                        <span class="badge top-right-badge">{{unidades}}</span>
                    </span>
                    <div class="info">
                        <p>
                            Precio:
                            <br>
                            {{#compare precio '==' '1'}}
                                <strong>{{precio}} euro</strong>
                            {{else}}
                                <strong>{{precio}} euros</strong>
                            {{/compare}}
                        </p>
                        <p>
                            Unidades:
                            <br>
                            {{#compare unidades '==' '1'}}
                                <strong>{{unidades}} unidad</strong>
                            {{else}}
                                <strong>{{unidades}} unidades</strong>
                            {{/compare}}
                        </p>
                        <p>
                            Código de barras:
                            <br>
                            <strong>{{codigo_barras}}</strong>
                        </p>
                        <p>
                            Código rfid:
                            <br>
                            {{#if codigo_rfid}}
                                <strong>{{codigo_rfid}}</strong>
                            {{else}}
                                <strong>No tiene</strong>
                            {{/if}}
                        </p>
                    </div>
                </div>
            </div>
        {{/each}}
        {{#unless producto}}
            <p class="well">
                El supermercado <strong>{{titulo}}</strong> no dispone de productos
            </p>
        {{/unless}}
    </div>

    <button id="anadir-producto" type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#anadir-producto-modal">
        Añadir producto
    </button>
</script>

<script type="text/x-handlebars" id="fridge-compras-template">
    <div class="clearfix">
        {{#each compra}}
            <div class="col-md-3 caja-item">
                <div id="compra-{{id}}" class="item compra wrapper" data-to="/fridge/{{id_frigorifico}}/compras/{{id}}" data-back="/fridge/{{id_frigorifico}}/compras">
                    <span class="titulo">
                        {{fecha_compra}} <br>
                        Gastado: <strong>{{total}}€</strong>
                        <span class="badge top-right-badge">{{num_productos}}</span>
                    </span>
                </div>
            </div>
        {{/each}}

    </div>
    <div id="compra"></div>
    {{#unless compra}}
        <p class="well">
            No has realizado ninguna compra
        </p>
    {{/unless}}
</script>

<script type="text/x-handlebars" id="fridge-compra-template">
    <h2>
        {{fecha_compra}}
    </h2>

    <div class="clearfix">
        {{#if producto}}
            <table class="table table-hover">
                <tr>
                    <td>
                        Producto
                    </td>
                    <td>
                        Unidades
                    </td>
                    <td>
                        Precio
                    </td>
                    <td>
                        Total
                    </td>
                </tr>
                {{#each producto}}
                    <tr>
                        <td>
                            {{titulo}}
                        </td>
                        <td>
                            {{num_productos}}
                        </td>
                        <td>
                            {{precio}}€
                        </td>
                        <td>
                            {{precio_total}}
                        </td>
                    </tr>
                {{/each}}
                <tr class="total">
                    <td colspan="4">
                        Total: <strong>{{total}}€</strong>
                    </td>
                </tr>
            </table>
        {{/if}}
        {{#unless producto}}
            <p class="well">
                El supermercado <strong>{{titulo}}</strong> no dispone de productos
            </p>
        {{/unless}}
    </div>
</script>

<script type="text/x-handlebars" id="supermercados-template">
    <div id="supermercados">
        <div class="page-header">
            <h1>
                Supermercados
            </h1>
        </div>
        <div id="listado-supermercados" class="clearfix">
            {{#each supermercado}}
                <div class="col-md-3 caja-item">
                    <div id="supermercado-{{id}}" class="item supermercado wrapper" data-to="/supermercados/{{id}}">
                        <span class="titulo">
                            {{titulo}}
                        </span>
                        <span class="badge top-right-badge">{{num_productos}}</span>
                    </div>
                </div>
            {{/each}}
            {{#unless supermercado}}
                <p class="well">
                    No hay supermercados
                </p>
            {{/unless}}
        </div>

        <button id="crear-supermercado" type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#crear-supermercado-modal">
            Crear supermercado
        </button>
    </div>
</script>

<script type="text/x-handlebars" id="supermercado-template">
    <div id="supermercado">
        <div class="page-header">
            <h1>
                {{titulo}}
                <button type="button" class="volver btn btn-default pull-right"  data-to="/supermercados">Volver</button>
            </h1>
        </div>
        <div id="productos" class="clearfix">
            {{#each producto}}
                <div class="col-md-3 caja-item">
                    <div id="producto-{{id}}" class="item producto wrapper" data-to="/supermercados/{{id_supermercado}}/producto/{{id}}">
                        <span class="titulo">
                            {{titulo}}
                        </span>
                        <div class="info">
                            <p>
                                Precio:
                                <br>
                                {{#compare precio '==' '1'}}
                                    <strong>{{precio}} euro</strong>
                                {{else}}
                                    <strong>{{precio}} euros</strong>
                                {{/compare}}
                            </p>
                            <p>
                                Unidades:
                                <br>
                                {{#compare unidades '==' '1'}}
                                    <strong>{{unidades}} unidad</strong>
                                {{else}}
                                    <strong>{{unidades}} unidades</strong>
                                {{/compare}}
                            </p>
                            <p>
                                Código de barras:
                                <br>
                                <strong>{{codigo_barras}}</strong>
                            </p>
                            <p>
                                Código rfid:
                                <br>
                                {{#if codigo_rfid}}
                                    <strong>{{codigo_rfid}}</strong>
                                {{else}}
                                    <strong>No tiene</strong>
                                {{/if}}
                            </p>
                        </div>
                    </div>
                </div>
            {{/each}}
            {{#unless producto}}
                <p class="well">
                    El supermercado <strong>{{titulo}}</strong> no dispone de productos
                </p>
            {{/unless}}
        </div>

        <button id="anadir-producto-supermercado" type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#anadir-producto-supermercado-modal">
            Añadir producto
        </button>
    </div>
</script>

<script type="text/x-handlebars" id="anadir-producto-search-template">
    {{#each producto}}
        <table data-id="{{id}}" class="busqueda table table-condensed">
            <tr>
                <td class="left">
                    <img src="<?php echo IMAGES.'tablon/producto.png'?>" alt="Producto">
                </td>
                <td>
                    <p>
                        <strong>{{titulo}}</strong>
                    </p>
                    <p>
                        Supermercado:
                        <strong>{{titulo_supermercado}}</strong>
                    </p>
                    <p>
                        Precio:
                        {{#compare precio '==' '1'}}
                            <strong>{{precio}} euro</strong>
                        {{else}}
                            <strong>{{precio}} euros</strong>
                        {{/compare}}
                    </p>
                    <p>
                        Unidades:
                        {{#compare unidades '==' '1'}}
                            <strong>{{unidades}} unidad</strong>
                        {{else}}
                            <strong>{{unidades}} unidades</strong>
                        {{/compare}}
                    </p>
                </td>
            </tr>
        </table>
    {{/each}}
    {{#unless producto}}
        <p class="well">
            No se han encontrado productos
        </p>
    {{/unless}}
</script>

<!-- Ventanas modales -->

<div class="modal fade" id="crear-frigorifico-modal" tabindex="-1" role="dialog" aria-labelledby="crear-frigorifico" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="crear-frigorifico">Crear frigorífico</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control nombre" placeholder="Nombre del frigorífico" >
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="aceptar btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="anadir-producto-modal" tabindex="-1" role="dialog" aria-labelledby="anadir-producto" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="anadir-producto">Añadir producto</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control codigo-barras" placeholder="Código de barras" >
                </div>
                <div id="anadir-producto-search">
                    <p class="well">
                        Introduce el código de barras del producto
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crear-supermercado-modal" tabindex="-1" role="dialog" aria-labelledby="crear-supermercado" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="crear-supermercado">Crear supermercado</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control nombre" placeholder="Nombre del supermercado" >
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="aceptar btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="anadir-producto-supermercado-modal" tabindex="-1" role="dialog" aria-labelledby="anadir-producto-supermercado" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span>
                </button>
                <h4 class="modal-title" id="anadir-producto-supermercado">Añadir producto</h4>
            </div>
                <div class="modal-body">
                <form name="form-anadir-producto-supermercado">
                    <div class="form-group">
                        <input name="titulo" type="text" class="form-control titulo" placeholder="Nombre del producto" >
                    </div>
                    <div class="form-group">
                        <input name="precio" type="text" class="form-control precio" placeholder="Precio en euros" >
                    </div>
                    <div class="form-group">
                        <input name="unidades" type="text" class="form-control unidades" placeholder="Unidades del paquete" >
                    </div>
                    <div class="form-group">
                        <textarea name="descripcion" class="form-control descripcion" placeholder="Descripción del producto"></textarea>
                    </div>
                    <div class="form-group">
                        <input name="codigo_barras" type="text" class="form-control codigo-barras" placeholder="Código de barras" >
                    </div>
                    <div class="form-group">
                        <input name="codigo_rfid" type="text" class="form-control codigo-rfid" placeholder="Código rfid" >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <button type="button" class="aceptar btn btn-primary">Aceptar</button>
            </div>
        </div>
    </div>
</div>