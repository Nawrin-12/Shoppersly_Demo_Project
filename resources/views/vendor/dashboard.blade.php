<!DOCTYPE html>
<html>
<head>
    <title>Vendor Dashboard</title>
</head>
<body>
    <h1>Vendor Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}!</p>
    <p>Role: {{ Auth::user()->role }}</p>
    <p>This is the VENDOR dashboard.</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>