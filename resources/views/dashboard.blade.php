<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ Auth::user()->name }}!</h1>
    <p>Role: {{ Auth::user()->role }}</p>
    <p>This is the USER dashboard.</p>
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>