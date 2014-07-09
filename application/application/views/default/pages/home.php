<h1 class="cover-heading">
	Cover your page.
</h1>
<p class="lead">
	<?php if($logged):?>
		Estás logueado, <a href="<?php echo base_url(array('home','logout'));?>">Salir</a>
	<?php else:?>
		No estás logueado
		<br>
		<form id="login_form" method="POST" action="<?php echo base_url(array('home','login'));?>">
			<input name="email" type="text" placeholder="login@email.com" >
			<input name="password" type="password" placeholder="12345678" >
			<input type="submit" name="SUBMIT">
		</form>
		<br>
		<a href="<?php echo base_url(array('home','register'));?>">Registrarse</a>
		<a href="<?php echo base_url(array('home','register'));?>">Recuperar contraseña</a>
	<?php endif;?>
</p>
<p class="lead">
    <a href="#" class="btn btn-lg btn-default">Learn more</a>
</p>