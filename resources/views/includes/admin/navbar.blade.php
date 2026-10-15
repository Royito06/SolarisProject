		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					

					<a class="btn btn-outline-dark btn-sm lo-btn" style="float: right; position: relative; top: 0.5px; margin-left: 2em; font-size: 1em;"  href="{{ route('logout') }}"
						onclick="event.preventDefault();
										document.getElementById('logout-form').submit();">
						{{ __('Salir') }}
					</a>
		
					<form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
						@csrf
					</form>
		
					
				</li>
			</ul>
		</nav>
      <!-- /.navbar -->
    
      <!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="{{route('revisores.index')}}" class="brand-link">
				<span class="brand-text font-weight-light" >SICREFyM Admin</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<!-- Add icons to the links using the .nav-icon class
						with font-awesome or any other icon font library -->
						<li class="nav-item has-treeview" id="dd">
							<a href="#" class="nav-link">
								<i class="nav-icon fas fa-user"></i>
								<p>
									Asistentes
									<i class="right fas fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="{{route('revisores.lista-asistentes')}}" id="tab-lista-asistentes" class="nav-link" id="tab-">
										<i class="nav-icon fas fa-users"></i>
										<p>
											Todos los asistentes.
										</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="{{route('revisores.lista-espera')}}" id="tab-lista-espera" class="nav-link">
										<i class="nav-icon fa fa-user-plus"></i>
										<p>
											Asistentes en espera.
										</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="{{route('revisores.lista-inscritos')}}" id="tab-lista-inscritos" class="nav-link">
										<i class="nav-icon fas fa-address-book"></i>
										<p>
											Asistentes inscritos.
										</p>
									</a>
								</li>
							</ul>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
		<!-- /.sidebar -->
		</aside>