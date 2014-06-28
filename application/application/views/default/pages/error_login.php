<h1 class="cover-heading">
	Cover your page.
</h1>
<p class="lead">
	<?php if($logged):?>
		Estás logueado
	<?php else:?>
		No estás logueado (ERROR AL LOGUEAR)
		<br>
		<form id="login_form" method="POST" action="<?php echo base_url(array('home','login'));?>">
			<input name="email" type="text" placeholder="login@email.com" >
			<input name="password" type="password" placeholder="12345678" >
			<input type="submit" name="SUBMIT">
		</form>
		<br>
		<a href="<?php echo base_url(array('home','register'));?>">Registrarse</a>
	<?php endif;?>
</p>
<p class="lead">
    <a href="#" class="btn btn-lg btn-default">Learn more</a>
</p>