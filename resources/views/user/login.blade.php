<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Login website</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf
        <label for="username">Email:</label><br>
        <input type="email" id="email" name="email" placeholder="Nhập email" required><br><br>
        @error('email')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="password" required><br><br>
        @error('password')
            <div class="text-danger small">{{ $message }}</div>
        @enderror
        <button type="submit">Login</button>
    </form>
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
</body>
</html>