@extends('adminlte::page')

@section('title', 'Data Buku')
@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Data Buku</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
            Tambah Buku
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
                            <th scope="col">Jenis Buku</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @component('dashboard.component.modal-tambah',[
        "title"     => 'Buku',
        "id"        => 'modal-tambah',
        "action"    => route('book.type.save'),
    ])
        <div class="form-group">
            <label>Jenis Buku</label>
            <input type="text" class="form-control" name="name" placeholder="Jenis Buku">
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
					{data: "name"},
					{data: "action", className: "text-center"},
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