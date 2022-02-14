@extends('adminlte::page')

@section('title', 'Data Anggota')
@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Data Anggota</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
            Tambah Anggota
        </button>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="main-table" class="table">
                    <thead class="thead-primary">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">No. Anggota</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @component('dashboard.component.modal-tambah',[
        "title"     => 'Anggota',
        "id"        => 'modal-tambah',
        "action"    => route('anggota.save'),
    ])
        <div class="form-group">
            <label for="nama_lengkap">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" id="nama_lengkap" placeholder="Nama Lengkap">
        </div>
        <div class="form-group">
            <label for="emal">Email</label>
            <input type="email" name="email" class="form-control" id="emal" placeholder="Enter email">
        </div>
    @endcomponent
@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            table = $('#main-table').DataTable({
				autoWidth: false,
                serverSide: true,
                processing: true,
				ajax: `${BASE_API_URL}/data`,
				columns: [
					{data: "id"},
					{data: "no_anggota"},
					{data: "name"},
					{data: "email"},
                ],
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    var index = iDisplayIndex +1;
                    $('td:eq(0)',nRow).html(index);

                    return nRow;
                },
            });
        });
    </script>
@endsection