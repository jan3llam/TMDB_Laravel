<!DOCTYPE html>
<html lang="en">
  <head>
  </head>
  <body>
  	<h1>Email Verification Mail</h1>
  
	Please verify your email on moviesnshows.rf.gd with below link so you can start rating: 
	<a href="{{ route('users.verify', $token) }}">Verify Email</a>
  </body>
</html>
