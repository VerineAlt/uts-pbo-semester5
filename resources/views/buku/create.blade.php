@extends('layouts.app')

@section('judul', 'Tambah Buku')
@section('subjudul', 'Isi data buku baru untuk dimasukkan ke koleksi.')

@section('konten')
    <div class="panel">
        <form method="POST" action="{{ route('buku.store') }}">
            @csrf
            @include('buku._form', ['sedangEdit' => false])
        </form>
    </div>
@endsection
