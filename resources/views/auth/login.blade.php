@extends('layouts.app')

@section('content')
    <login-component toke_csrf="{{ @csrf_token() }}"></login-component>
@endsection
