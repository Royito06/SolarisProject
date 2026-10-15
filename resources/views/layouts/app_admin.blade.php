<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SICREFyM - Admin</title>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/css/admin/all.min.css">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="/css/admin/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
    
		@include('includes.admin.navbar')
    
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			@yield('content')
		</div>
	<!-- /.content-wrapper -->
    
      <!-- Main Footer -->
		<footer class="main-footer">
		<!-- To the right -->
			{{--<div class="float-right d-none d-sm-inline">
				Anything you want
			</div>
		<!-- Default to the left -->
			<strong>Copyright &copy; 2014-2019 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved. --}}
		</footer>
    </div>
    <!-- ./wrapper -->
    
    <script src="/js/jquery-3.5.1.js"></script>
    <script src="/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/js/admin/adminlte.min.js"></script>
    <script>
      /// Url actual
      let pathname = new String(window.location.pathname);
      let url = pathname.replace("/revisores/", "");

      /// Elementos de li
      const tabs = ["lista-espera", "lista-asistentes", "lista-inscritos"];

      tabs.forEach(e => {
          /// Compara el url actual con el arreglo
          if ( url === e) {
              /// Agregar tab- para hacer que coincida la Id
              setActive("tab-" + e);
          }
          if (url === "lista-espera" || url === "lista-asistentes" || url === "lista-inscritos"){
            setMenuOpen("dd")
          }
      });

      /// Funcion que asigna la clase active
      function setActive(id) {
          document.getElementById(id).setAttribute("class", "nav-link active");
      }
      /// Funcion que asigna la clase menu-open
      function setMenuOpen(id){
        document.getElementById(id).setAttribute("class", "nav-item has-treeview menu-open");
      }
  </script>
</body>

</html>