@extends('adminlte::page')

@section('title', 'Peminjaman Buku')
@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Peminjaman Buku</h1>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-tambah">
            Ajukan Peminjaman
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
                            <th scope="col">No - Nama Anggota</th>
                            <th scope="col">Judul Buku</th>
                            <th scope="col">Peminjaman Tanggal</th>
                            <th scope="col">Lama Pinjaman</th>
                            <th scope="col">Status Pinjaman</th>
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
        "title"     => 'Anggota',
        "id"        => 'modal-tambah',
        "action"    => route('pinjaman.save'),
        ])
        @if(auth()->user()->role == "anggota")
            <input type="hidden" name="anggota_id" class="form-control" value="{{ auth()->user()->id }}" placeholder="Nama Lengkap">
        @else
        <div class="row">
            <div class="form-group col-lg-12">
                <label>No. Anggota</label>
                <select name="anggota_id" class="no_anggota form-control" required>
                </select>
            </div>
        </div>
        <hr>
        @endif
        <div class="form-group">
            <label>Buku  Yang Akan diPinjam</label>
        </div>
        <div class="bukus">
            <ol></ol>
        </div>
        <button type="button" onclick="addBooks()" class="btn btn-primary btn-sm btn-block">++++++++++  Tambah Buku  ++++++++++ </button>
    @endcomponent
    
    @component('dashboard.component.modal-tambah',[
        "title"     => 'Status Upprovel',
        "id"        => 'modal-upprovel',
        "action"    => route('pinjaman.approvel'),
        ])
        <span id="texts"></span>
        <input type="hidden" name="id" class="form-control" >
        <input type="hidden" name="status" class="form-control" >
    @endcomponent
    
    @component('dashboard.component.modal-tambah',[
        "title"     => 'Pengembalian Buku',
        "id"        => 'modal-pengembalian',
        "action"    => route('pinjaman.pengembalian'),
        ])
        <span id="text_kems"></span>
        <input type="hidden" name="id" class="form-control" >
    @endcomponent

@endsection

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        .bukus > ol {
            padding-inline-start: 16px;
        }
    </style>
@stop

@section('js')
    <script>
        let form_filed_buook = `
            <li>
                <div class="the_books row">
                    <div class="form-group col-lg-5">
                        <label>Cari Buku</label>
                        <select name="book_id[]" class="book_fi form-control" required></select>
                    </div>
                    
                    <div class="form-group col-lg-4">
                        <label>Tanggal Pinjam</label>
                        <input name="tgl_pinjam[]" type="date" class="form-control" required>
                    </div>
                    
                    <div class="form-group col-lg-2">
                        <label>Lama Pinjam</label>
                        <input type="number" name="lama_peminjaman[]" class="form-control" required>
                    </div>
                    
                    <div class="form-group col-lg-1">
                        <label>.</label>
                        <button type="button" class="btn btn-danger form-control" onclick="remover(this)">-</button>
                    </div>
                </div>
            </li>
        `;

        $(document).ready(function() {
            table = $('#main-table').DataTable({
				autoWidth: false,
                serverSide: true,
                processing: true,
				ajax: `${BASE_API_URL}/data`,
				columns: [
					{data: "id" , orderable: false},
					{data: "anggota.name"},
					{data: "buku.title"},
					{data: "tgl_pinjam"},
					{data: "lama_peminjaman"},
					{data: "status"},
					{data: "action"},
                ],
                @if(auth()->user()->role == "anggota")
                "columnDefs": [
                    {
                        "targets": [1,6],
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

            $('.no_anggota').select2({
                dropdownParent: $("#modal-tambah"),
                width: '100%',
                ajax: {
                    url: `${BASE_API_URL}/get-anggota`,
                    dataType: 'json',
                    delay: 250,
                }
            });
        }); 

        const addBooks = () => {
            let selection = $(".bukus > ol");
            if (selection.children('li').length == 0 ) {
                selection.prepend(form_filed_buook);
                initSelectBoook()
                return
            }
            selection.append(form_filed_buook);
            initSelectBoook()

            return
        };

        const remover = (e) => {
            $(e).closest("li").remove();
        };

        const initSelectBoook = () => {
            $('select.book_fi').select2({
                dropdownParent: $("#modal-tambah"),
                width: '100%',
                ajax: {
                    url: `${BASE_API_URL}/get-book`,
                    dataType: 'json',
                    delay: 250,
                }
            });
        }

        $("#modal-upprovel").on('show.bs.modal', (e) => {
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let status = button.data('status');
            if(!id) return;
            if (status == "reject") {
                texts.innerHTML = "Reject Pinjaman Ini.";
            } else {
                texts.innerHTML = "Oke. Setujui Pinjaman ini.";
            }
            $("[name='id']").val(id);
            $("[name='status']").val((status == 'reject')?0:1 );
        });
        
        $("#modal-pengembalian").on('show.bs.modal', (e) => {
            let button = $(e.relatedTarget);
            let id = button.data('id');
            let status = button.data('status');
            if(!id) return;
            
            text_kems.innerHTML = "Selesai.";
            $("[name='id']").val(id);
        });
    </script>
@endsection