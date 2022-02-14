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
                            <th scope="col">Cover</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Jenis Buku</th>
                            <th scope="col">Pengarang</th>
                            <th scope="col">Tahun Terbit</th>
                            <th scope="col">Stok Tersedia</th>
                            <th scope="col">Status</th>
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
        "action"    => route('book.save'),
    ])
        <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" class="form-control" name="title" placeholder="Judul Buku" requireed>
        </div>
        <div class="form-group">
            <label>Jenis Buku</label>
            <select name="jenis_book_id" class="form-control" requireed>
            </select> 
        </div>
        <div class="form-group">
            <label>Pengarang</label>
            <input type="text" class="form-control" name="pengarang" requireed>
        </div>
        <div class="form-group">
            <label>Tahun Terbit</label>
            <select name="thn_terbit" class="form-control" requireed>
                <option selected disabled>-- Pilih Tahun Terbit-- </option>
                {{ $year = date('Y') }}
                @for ($years = $year-20; $years <= $year; $years++)
                    <option value="{{ $years }}" {{ ( Session::get('tahun-laporan'))? ((Session::get('tahun-laporan') == $years)?'selected':'') : '' }}>{{ $years }}</option>
                @endfor
            </select> 
        </div>
        <div class="form-group">
            <label>Stok Buku</label>
            <input type="text" class="form-control" name="stok" requireed>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control" requireed>
                <option value="" selected disabled> -- Pilih Status Ketersediaan -- </option>
                <option value="1">Tersedia</option>
                <option value="0">Tidak Tersedia</option>
            </select>
        </div>
        <div class="form-group">
            <label>Cover Buku</label>
            <input type="file" class="form-control" name="cover">
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
					{data: "id", orderable: false},
					{data: "cover", "render": function (data, type, row, meta) {
                        return (data)?'<img src="' + data + '" alt="' + data + '"width="75"/>':'-';
                    }},
					{data: "title"},
					{data: "jenis.name"},
					{data: "pengarang"},
					{data: "thn_terbit"},
					{data: "stok"},
					{data: "status"},
					{data: "action", className: "text-center"},
                ],
                @if(auth()->user()->role == "anggota")
                "columnDefs": [
                    {
                        "targets": [6,7,8],
                        "visible": false,
                        "searchable": false
                    },
                ],
                @endif
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    var index = iDisplayIndex +1;
                    $('td:eq(0)',nRow).html(index);

                    return nRow;
                },
            });
            getJenisBuku();
        });

        const getJenisBuku = (id, set = false) => {
            let list = ''; 
            $.getJSON(`${BASE_API_URL}/get-tipe-buku`)
            .done(function(res){
                console.log(res)
                $.each(res.data, function(index, data){
                    list += `<option value="${data.id}">${data.name}</option>`
                })
                $(`[name="jenis_book_id"]`).html(list);
                if(set) $(`[name="jenis_book_id"]`).val(set);
            });
        }
    </script>
@endsection