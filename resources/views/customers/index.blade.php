@extends('layouts.master')
@section('title', 'Pelanggan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Master Pelanggan</h1>            
        </div>
        <div class="section-body">                        
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Pelanggan</h4>
                        </div>
                        <div class="card-body">                            
                            <button class="btn btn-primary" @click="tambah"><i class="fa fa-plus"></i> Tambah</button>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="table">
                                    <thead>
                                        <th width="10%">No</th>
                                        <th>Nama</th>                                                                                                                     
                                        <th>Email</th>                                                                                                                     
                                        <th>Telepon</th>                                                                                                                     
                                        <th>Alamat</th>                                                                                                                     
                                        <th>Aksi</th>     
                                    </thead>   
                                    <tbody>
                                        <tr v-for="(data, index) in tableData" :key="data.id">
                                            <td>@{{++index}}</td>
                                            <td>@{{data.name}}</td>
                                            <td>@{{data.email}}</td>   
                                            <td>@{{data.phone}}</td>                                            
                                            <td>@{{data.address}}</td>                                            
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

    <div class="modal fade" tabindex="-1" role="dialog" id="modalTambah">
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
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Nama</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" v-model="formAdd.name" id="name" name="name" placeholder="Nama" autocomplete="off" >
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Email</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" v-model="formAdd.email" id="email" name="email" placeholder="Email" autocomplete="off" >
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Telepon</label>
                        <div class="col-sm-12 col-md-9">
                            <input type="text" class="form-control" v-model="formAdd.phone" id="phone" name="phone" placeholder="Telepon" autocomplete="off" >
                        </div>
                    </div>   
                    <div class="form-group row">
                        <label class="col-form-label col-12 col-md-3 col-lg-3">Alamat</label>
                        <div class="col-sm-12 col-md-9">
                            <textarea name="address" v-model="formAdd.address" id="" cols="30" rows="10" class="form-control" ></textarea>
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
                <form id="formEdit" method="post" @submit.prevent="updateData">                
                    <div class="modal-body">   
                        <div class="alert alert-danger print-error-msg" style="display:none">

                            <ul></ul>
                    
                        </div>                                                                          
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Nama</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" class="form-control" v-model="formEdit.name" id="name" name="name" placeholder="Nama" autocomplete="off" >
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Email</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" class="form-control" v-model="formEdit.email" id="email" name="email" placeholder="Email" autocomplete="off" >
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Telepon</label>
                            <div class="col-sm-12 col-md-9">
                                <input type="text" class="form-control" v-model="formEdit.phone" id="phone" name="phone" placeholder="Telepon" autocomplete="off" >
                            </div>
                        </div>   
                        <div class="form-group row">
                            <label class="col-form-label col-12 col-md-3 col-lg-3">Alamat</label>
                            <div class="col-sm-12 col-md-9">
                                <textarea name="address" v-model="formEdit.address" id="" cols="30" rows="10" class="form-control" ></textarea>
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
                    email:'',
                    phone:'',
                    address:'',
                },
                formEdit:{
                    id:'',
                    name:'',
                    email:'',
                    phone:'',
                    address:'',
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
                    this.formAdd.email = '';
                    this.formAdd.phone = '';
                    this.formAdd.address = '';
                },

                loadData(){

                    axios.get('{{ route("customers.data") }}', {                                
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
                        url: '{{ route("customers.store") }}',
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

                            axios.delete(`/customers/${id}`)
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
                    this.formEdit.email = currData.email;
                    this.formEdit.phone = currData.phone;
                    this.formEdit.address = currData.address;

                    

                }, 

                updateData(){
                    axios({
                        method: 'POST',
                        url: `/customers/${this.formEdit.id}`,
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