@extends('layouts.app')

@section('content')
  <div class="container py-5">
    <div class="card text-center">
      <div class="card-body">
        <h3 class="text-danger">Pembayaran Gagal</h3>
        <p>Maaf, terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.</p>
        <a href="/" class="btn btn-secondary">Kembali</a>
      </div>
    </div>
  </div>
@endsection
