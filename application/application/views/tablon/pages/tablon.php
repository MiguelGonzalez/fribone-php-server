<script id="fridge" type="text/x-handlebars">
    <div class="page-header">
        <h1>Frigorífico</h1>
    </div>
    {{#if productos}}
    	{{#each productos}}
	    	<ul>
		    	<li>
		    		{{titulo}}
		    	</li>
	    	</ul>
    	{{/each}}
    {{else}}
    	<p class="well">
    		No dispones de productos en tu frigorífico
    	</p>
    {{/if}}

    {{bs-button title="Agregar producto" clicked="show" class="btn btn-default pull-right"}}
    {{#bs-modal name="myModal" fade=true footerButtonsBinding="myModalButtons" title="My Modal"}}
        <p>Modal content!</p>
    {{/bs-modal}}
    {{bs-notifications style="z-index: 1000; position: fixed; width: 50%; left: 0; right: 0; margin-left: auto; margin-right: auto; margin-top: 50px;"}}
</script>

<script id="statistics" type="text/x-handlebars">
    <h1>Estadísticas</h1>
</script>