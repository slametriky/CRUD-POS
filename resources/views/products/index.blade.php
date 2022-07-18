@extends('layouts.master')
@section('title', 'Produk')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Master Produk</h1>            
        </div>
        <div class="section-body">                        
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Produk</h4>
                        </div>
                        <div class="card-body">                            
                            <button class="btn btn-primary" @click="tambah()"><i class="fa fa-plus"></i> Tambah</button>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="table">
                                    <thead>
                                        <th width="10%">No</th>
                                        <th>Produk</th>                                                                                                                     
                                        <th>Deskripsi</th>                                                                                                                     
                                        <th>Harga</th>                                                                                                                                                                                                                                                                                
                                        <th>Aksi</th>     
                                    </thead>   
                                    <tbody>
                                        <tr v-for="(data, index) in tableData" :key="data.id">
                                            <td>@{{++index}}</td>
                                            <td>@{{data.name}}</td>
                                            <td>@{{data.description}}</td>   
                                            <td>@{{data.price_formatted}}</td>                                            
                                            <td>
                                                <button class="btn btn-warning btn-sm" type="button" @click="edit(data.id)"><i
                                                    class="fa fa-edit"></i></button>
                                                <button class="btn btn-danger btn-sm" type="button" @click="hapus(data.id)"><i
                                                    class="fa fa-trash"></i></button>
                                            </td>
                                        </tr>    
                                    </tbody>                         
                                </table>
                                
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>   
        </div>       
    </section>

    <div class="modal fade" role="dialog" id="modalTambah">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Data</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="formTambah" method="post" @submit.prevent="saveData">                
                <div class="modal-body">      
                    <div class="alert alert-danger print-error-msg" style="display:none">

                        <ul></ul>
                
                    </div>                        
                    <div class="form-group row">
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Nama Produk</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" v-model="formAdd.name" id="name" name="name" placeholder="Produk" autocomplete="off" >
                        </div>
                    </div>         
                    <div class="form-group row">
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Deskripsi</label>
                        <div class="col-sm-12 col-md-9">
                            <textarea name="description" v-model="formAdd.description" id="" cols="30" rows="10" class="form-control" ></textarea>
                        </div>
                    </div>         
                    <div class="form-group row">
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Harga</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="number" class="form-control" v-model="formAdd.price" id="price" name="price" placeholder="Harga" autocomplete="off" >
                        </div>
                    </div>                                                                                   
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
          </div>
        </div>
    </div>    
    
    <div class="modal fade" tabindex="-1" role="dialog" id="modalEdit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEdit" @submit.prevent="updateData">                
                    <div class="modal-body">      
                        <div class="alert alert-danger print-error-msg" style="display:none">

                            <ul></ul>
                    
                        </div>                                                                      
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Produk</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="hidden" id="idEdit" name="id" required>
                                <input type="text" v-model="formEdit.name" class="form-control" id="name_e" name="name" placeholder="Produk" autocomplete="off" >
                            </div>
                        </div>         
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Deskripsi</label>
                            <div class="col-sm-12 col-md-9">
                                <textarea name="description" v-model="formEdit.description" id="description_e" cols="30" rows="10" class="form-control" ></textarea>
                            </div>
                        </div>         
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Harga</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="number" class="form-control" v-model="formEdit.price" id="price_e" name="price" placeholder="Harga" autocomplete="off" >
                            </div>
                        </div>                        
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection
    
@push('page-script')
    <script>

        new Vue({
            el: '#app',
            data: {
                formAdd:{
                    name:'',
                    description:'',
                    price:'',
                },
                formEdit:{
                    id:'',
                    name:'',
                    description:'',
                    price:'',
                    _method:'PUT'
                },
                tableData:[],
            },
            mounted() {
                this.loadData();
            },
            methods:{

                tambah(){

                    $("#modalTambah").modal("show");                
                    $(".print-error-msg").css('display','none');
                    
                    this.formAdd.name = '';
                    this.formAdd.description = '';
                    this.formAdd.price = '';
                },

                loadData(){

                    axios.get('{{ route("products.data") }}', {                                
                    })
                    .then((res) => {                            
                                                
                        if(res.data.length > 0){
                            $('#table').DataTable().destroy();
                        }
                        
                        this.tableData = res.data;

                    }, (error) => {
                        console.log(error);
                    }).finally(() => {
                        $("#table").DataTable();
                    })
                },

                saveData(){
                    
                    axios({
                        method: 'post',
                        url: '{{ route("products.store") }}',
                        data: this.formAdd,                    
                    })
                    .then(response => {

                            //handle success                    
                        Swal.fire({            
                            icon: 'success',                   
                            title: 'Berhasil',                            
                            timer: 2000,                                
                            showConfirmButton: false
                        });
                        
                        $("#modalTambah").modal("hide");                                                               

                        this.loadData();
    
                    })
                    .catch(error => {

                        //handle error
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display','block');

                        $.each( error.response.data.errors, function( key, value ) {
                            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

                        });
                        
                        
                    });               
                },

                hapus(id){
                    Swal.fire({
                        title: "Yakin hapus data?",            
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        
                        if(result.value){

                            axios.delete(`/products/${id}`)
                            .then(response => {     
                                
                                Swal.fire({                                
                                    icon: 'success',                   
                                    title: 'Berhasil',                                
                                    timer: 2000,                                
                                    showConfirmButton: false
                                })

                                this.loadData();
                                                            
                            })
                            .catch(error => {
                                console.log(error);
                                //     Swal.fire({                                
                                //         icon: 'warning',                   
                                //         title: 'Gagal...',
                                //         text: response.data.respon,                            
                                //     })
                                // }
                            })

                        }            

                    });
                },

                edit(id){

                    $(".print-error-msg").css('display','none');
                    $("#modalEdit").modal("show");    

                    let currData =  this.tableData.filter(item => item.id == id)[0];
                    
                    this.formEdit.id = currData.id;
                    this.formEdit.name = currData.name;
                    this.formEdit.description = currData.description;
                    this.formEdit.price = currData.price;

                    

                }, 

                updateData(){
                    axios({
                        method: 'POST',
                        url: `/products/${this.formEdit.id}`,
                        data: this.formEdit,                    
                    })
                    .then((res) => {
                        // handle success                                        
                        Swal.fire({            
                            icon: 'success',                   
                            title: 'Berhasil',                            
                            timer: 2000,                                
                            showConfirmButton: false
                        })

                        this.loadData();
                        $("#modalEdit").modal("hide");                
                        
                    })
                    .catch((error) => {
                        //handle error
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display','block');

                        $.each( error.response.data.errors, function( key, value ) {
                            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

                        });

                    });
                }
            }, //end vue
        });

    </script>
@endpush