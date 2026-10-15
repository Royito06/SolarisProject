php artisan tinker 
$user = new Revisores(); 
$user->nombre = "Adrian"; 
$user->apellido_paterno = "Vazquez"; 
$user->apellido_materno = "Osorio"; 
$user->correo = "adrianvo@gmail.com"; 
$user->contrasena = Hash::make("Ch3ng1d4s"); 
$user->save(); 
exit 
