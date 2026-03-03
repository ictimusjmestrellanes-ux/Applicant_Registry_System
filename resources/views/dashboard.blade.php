@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Welcome to your Dashboard</h1>
    <p>Hello <strong>{{ Auth::user()->name }}</strong>, You are logged in!</p>
@endsection