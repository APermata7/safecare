@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Donatur</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Donatur</h6>
        </div>
        <div class="card-body">
            @if($donaturs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($donaturs as $donatur)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $donatur->name }}</td>
                                    <td>{{ $donatur->email }}</td>
                                    <td>
                                        @if($donatur->banned_at)
                                            <span class="badge badge-danger">Banned</span>
                                        @else
                                            <span class="badge badge-success">Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($donatur->banned_at)
                                            <form action="{{ route('admin.users.unban', $donatur->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Unban
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.ban', $donatur->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-ban"></i> Ban
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.users.destroy', $donatur->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $donaturs->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Tidak ada donatur.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection