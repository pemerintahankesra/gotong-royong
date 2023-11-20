@extends('template')

@section('content')
<div class="container mt-3">
  <div class="row justify-content-center">
    @if(session()->has('success'))
    <div class="row">
      <div class="alert alert-info col-12">
          {{ session()->get('success') }}
      </div>
    </div>
    @endif
    <div class="col-md-7">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <h1 class="display-6">Ubah Password</h1>
          <form action="{{route('auth.change_password_action')}}" method="post">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label for="old_password" class="form-label">Password Lama</label>
              <input type="password" name="old_password" id="old_password" class="form-control @error('old_password')is-invalid @enderror">
              @error('old_password')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="new_password" class="form-label">Password Baru</label>
              <input type="password" name="new_password" id="new_password" class="form-control @error('new_password')is-invalid @enderror">
              @error('new_password')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="mb-3">
              <label for="new_password_confirmation" class="form-label">Konfirmasi Password</label>
              <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control @error('new_password_confirmation')is-invalid @enderror">
              @error('new_password_confirmation')<div class="invalid-feedback">{{$message}}</div>@enderror
            </div>
            <div class="d-grid gutter-2">
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection