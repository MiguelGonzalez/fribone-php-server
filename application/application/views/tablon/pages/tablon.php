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

<script type="text/x-handlebars" id="fridge-item-template">
    {{#if item}}
        {{#each item}}
            {{titulo}}
        {{/each}}
    {{else}}
        <p class="well">
            El frigorífico está vacío
        </p>
    {{/if}}

    <button id="anadir-producto" type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#anadir-producto-modal">
        Añadir producto
    </button>
</script>

<script type="text/x-handlebars" id="estadisticas">
    <h1>Estadísticas</h1>
</script>

<!-- Ventanas modales -->

<!-- Modal -->
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

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="aceptar btn btn-primary">Aceptar</button>
        </div>
    </div>
</div>
</div>