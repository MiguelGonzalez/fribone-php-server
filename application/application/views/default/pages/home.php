<div class="col-lg-9 col-md-8 col-sm-7"></div>
<div class="col-lg-3 col-md-4 col-sm-5">
	<div id="form-login">
		<form name="form-login" class="form-horizontal" role="form" method="POST" action="<?php echo base_url(array('home','login'));?>">
			<div class="form-group">
				<label for="email" class="col-lg-4 control-label">Email</label>
				<div class="col-lg-8">
					<input type="email" class="form-control" name="email" placeholder="Email">
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-lg-4 control-label">Contraseña</label>
				<div class="col-lg-8">
					<input type="password" class="form-control" name="password" placeholder="********">
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-offset-4 col-lg-8">
					<div class="checkbox">
						<label>
							<input type="checkbox" name="remember-me"> Recordarme
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-offset-1 col-lg-11">
					<button type="submit" class="btn btn-default">Acceder</button>
					<a class="pull-right" id="recuperar-password" href="#"
							data-toggle="modal" data-target="#modal-remember-password">¿Has olvidado tu contraseña?</a>
				</div>
			</div>
		</form>
	</div>
	<div id="darse-alta">
		<p>
			¿No estas registrado? <a href="<?php echo base_url(array('home','register'));?>">Date de alta aquí</a>.
		</p>
		<div id="form-registro">
			<form name="form-registro" class="form-horizontal" role="form">
				<div class="form-group">
					<label for="email" class="col-lg-4 control-label">Email</label>
					<div class="col-lg-8">
						<input type="email" class="form-control" name="email" placeholder="Email">
					</div>
				</div>
				<div class="form-group">
					<label for="username" class="col-lg-4 control-label">Nombre</label>
					<div class="col-lg-8">
						<input type="text" class="form-control" name="username" placeholder="Nombre">
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-lg-4 control-label">Password</label>
					<div class="col-lg-8">
						<input type="password" class="form-control" name="password" placeholder="Password">
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-offset-1 col-lg-11">
						<button type="submit" class="btn btn-default">Registrarse</button>
					</div>
				</div>
			</form>
		</div>
		<div id="registrando">
			<p id="registrando-ajax">
				<i class="icon ion-loading-c"></i> Enviando registro
			</p>
			<p id="registrando-ok">
				<i class="ion-checkmark-circled"></i> ¡Bienvenido a Fribone!
			</p>
			<p id="registrando-error">
				<i class="icon ion-close-circled"></i> Se ha producido un error
			</p>
		</div>
	</div>
</div>

<!-- Ventana modal para recordatorio de contraseña -->
<div id="modal-remember-password" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-body">
				<form name="form-remember-password" class="form-horizontal" role="form">
					<div class="form-group">
						<label for="email" class="col-lg-3 control-label">Email</label>
						<div class="col-lg-9">
							<input type="email" class="form-control" name="email" placeholder="Email">
						</div>
					</div>
				</form>
			</div>
			<div id="recordando">
				<p id="recordando-ajax">
					<i class="icon ion-loading-c"></i> Enviando recordatorio
				</p>
				<p id="recordando-ok">
					<i class="ion-checkmark-circled"></i> Revisa tu correo
				</p>
				<p id="recordando-error">
					<i class="icon ion-close-circled"></i> Se ha producido un error
				</p>
			</div>
			<div class="modal-footer">
                 <button id="submit_remember" type="button" class="btn btn-default">Recuperar contraseña</button>
            </div>
		</div>
	</div>
</div>

<!-- Ventana modal para resetear la contraseña -->
<?php if($reset):?>
	<div id="modal-reset-password" class="modal fade" tabindex="-1" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body">
					<form name="form-reset-password" class="form-horizontal" role="form">
						<input type="hidden" name="token" value="<?php echo $token;?>">
						<div class="form-group">
							<label for="email" class="col-lg-3 control-label">Email</label>
							<div class="col-lg-9">
								<input type="email" class="form-control" name="email" placeholder="Email">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-lg-4 control-label">Contraseña</label>
							<div class="col-lg-8">
								<input type="password" class="form-control" name="password" placeholder="********">
							</div>
						</div>
					</form>
				</div>
				<div id="reset">
					<p id="reset-ajax">
						<i class="icon ion-loading-c"></i> Reseteando contraseña
					</p>
					<p id="reset-ok">
						<i class="ion-checkmark-circled"></i> ¡Contraseña cambiada"
					</p>
					<p id="reset-error">
						<i class="icon ion-close-circled"></i> Se ha producido un error
					</p>
				</div>
				<div class="modal-footer">
	                 <button id="submit_reset" type="button" class="btn btn-default">Cambiar contraseña</button>
	            </div>
			</div>
		</div>
	</div>
<?php endif;?>