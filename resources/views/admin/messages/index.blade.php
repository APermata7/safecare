@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manajemen Pesan</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pesan</h6>
        </div>
        <div class="card-body">
            @if($messages->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Pengirim</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $message->subject }}</td>
                                    <td>{{ $message->user->name }}</td>
                                    <td>{{ $message->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if($message->replied_at)
                                            <span class="badge badge-success">Telah dibalas</span>
                                        @else
                                            <span class="badge badge-warning">Belum dibalas</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $message->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $messages->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    Tidak ada pesan yang masuk.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection