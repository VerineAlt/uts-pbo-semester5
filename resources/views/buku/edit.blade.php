@extends('layouts.app')

@section('judul', 'Edit Buku')
@section('subjudul', 'Perbarui data buku ' . $buku['kode'] . '.')

@section('konten')
    <div class="panel">
        <form method="POST" action="{{ route('buku.update', $buku['kode']) }}">
            @csrf
            @method('PUT')
            @include('buku._form', ['sedangEdit' => true])
        </form>
    </div>
@endsection
