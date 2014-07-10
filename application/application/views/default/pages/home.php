<div class="col-md-7"></div>
<div class="col-md-5 ">
	<div id="form-login">
	<form class="form-horizontal" role="form" method="POST" action="<?php echo base_url(array('home','login'));?>">
		<div class="form-group">
			<label for="email" class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10">
				<input type="email" class="form-control" name="email" placeholder="Email">
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-sm-2 control-label">Password</label>
			<div class="col-sm-10">
				<input type="password" class="form-control" name="password" placeholder="Password">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="remember-me"> Remember me
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Sign in</button>
				<a class="pull-right recuperar-password" href="<?php echo base_url(array('home','register'));?>">Recuperar contraseña</a>
			</div>
		</div>
	</form>
	<br>
	<!--<a href="<?php echo base_url(array('home','register'));?>">Registrarse</a>-->
	
	</div>
</div>