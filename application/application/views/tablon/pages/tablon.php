<script type="text/x-handlebars" data-template-name="_menu">
    {{#each fridge in fridges}}
        {{#link-to 'fridge' fridge.id class="list-group-item"}}{{fridge.titulo}}{{/link-to}}
    {{/each}}
    
    {{#link-to 'statistics' class="list-group-item"}}Estadísticas{{/link-to}}
</script>

<script type="text/x-handlebars" data-template-name="home">
<h2>Home</h2>
</script>

<script type="text/x-handlebars" data-template-name="fridge">
    <div class="page-header">
        <h1>{{titulo}}</h1>
    </div>
    
    {{#if controller.productos.isSettled}}
        {{#each producto in productos}}
            {{#if producto.isLoading}}

            {{else}}
                <ul>
                    <li>
                        {{producto.title}}
                    </li>
                </ul>
            {{/if}}
        {{/each}}
        {{#unless productos}}
            <p class="well">
                No dispones de productos en tu frigorífico
            </p>
        {{/unless}}
    {{else}}
        <p class="well">
        Cargando productos del frigorífico
        </p>
    {{/if}}
    
    

    {{bs-button title="Agregar producto" clicked="show" class="btn btn-default pull-right"}}
    {{#bs-modal name="myModal" fade=true footerButtonsBinding="myModalButtons" title="My Modal"}}
        <p>Modal content!</p>
    {{/bs-modal}}
    {{bs-notifications style="z-index: 1000; position: fixed; width: 50%; left: 0; right: 0; margin-left: auto; margin-right: auto; margin-top: 50px;"}}
</script>

<script type="text/x-handlebars" data-template-name="stadistics">
    <h1>Estadísticas</h1>
</script>