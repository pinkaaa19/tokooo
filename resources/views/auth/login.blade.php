@extends('layouts.app')

@section('title','Login')

@section('content')

<div class="flex justify-center items-center min-h-screen">

<div class="bg-white p-8 rounded shadow w-[400px]">

<h2 class="text-2xl font-bold mb-6 text-center">
Login
</h2>
    @if(session('error'))
<div class="bg-red-100 text-red-700 p-3 rounded mb-4">
    {{ session('error') }}
</div>
@endif
<form method="POST" action="/login">

@csrf

<input type="email"
name="email"
placeholder="Email"
class="w-full border p-2 mb-4">

<input type="password"
name="password"
placeholder="Password"
class="w-full border p-2 mb-4">

<button class="w-full bg-[#8B0000] text-white p-2 rounded">
Login
</button>

</form>

<p class="text-center mt-4">

Belum punya akun?

<a href="/register" class="text-red-600">
Buat Akun
</a>

</p>

</div>

</div>

@endsection