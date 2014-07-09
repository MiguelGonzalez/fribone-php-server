<h1 class="cover-heading">
	Cover your page.
</h1>
<p class="lead">
	Registro<br>
	<br>
	<form id="login_form" method="POST" action="<?php echo base_url(array('home','register_submit'));?>">
		<input name="email" type="text" placeholder="login@email.com" ><br>
		<input name="username" type="text" placeholder="usuario" ><br>
		<input name="password" type="password" placeholder="******" ><br>
		<input type="submit" name="SUBMIT">
	</form>
</p>
<p class="lead">
    <a href="#" class="btn btn-lg btn-default">Learn more</a>
</p>