@extends('layouts.app')

@section('title','Register')

@section('content')

<div class="flex justify-center items-center min-h-screen">

<div class="bg-white p-8 rounded shadow w-[400px]">

<h2 class="text-2xl font-bold mb-6 text-center">
Buat Akun
</h2>

<form method="POST" action="/register">

@csrf

<input type="text"
name="name"
placeholder="Nama"
class="w-full border p-2 mb-4">

<input type="email"
name="email"
placeholder="Email"
class="w-full border p-2 mb-4">

<input type="password"
name="password"
placeholder="Password"
class="w-full border p-2 mb-4">

<button class="w-full bg-[#8B0000] text-white p-2 rounded">
Daftar
</button>

</form>

<p class="text-center mt-4">

Sudah punya akun?

<a href="/login" class="text-red-600">
Login
</a>

</p>

</div>

</div>

@endsection