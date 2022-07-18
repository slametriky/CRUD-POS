@extends('layouts.master')
@section('title', 'Transaksi Penjualan')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Master Transaksi Penjualan</h1>            
        </div>
        <div class="section-body">                        
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h4>Data Transaksi Penjualan</h4>
                        </div>
                        <div class="card-body">                            
                            <button class="btn btn-primary" @click="tambah()"><i class="fa fa-plus"></i> Tambah</button>
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" id="table">
                                    <thead>
                                        <th width="10%">No</th>
                                        <th>Pelanggan</th> 
                                        <th>Produk</th>                                                                                                                     
                                        <th>Total</th>                                                                                                                                                                                                                                                                                
                                    </thead>   
                                    <tbody>
                                        <tr v-for="(data, index) in tableData" :key="data.id">
                                            <td>@{{++index}}</td>
                                            <td>@{{data.customer.name}}</td>
                                            <td class="text-right">                                                
                                                <div class="mb-3" v-for="(item) in data.items" :key="item.id">
                                                    <span style="font-weight: bold">@{{item.product.name}}</span><br>
                                                    <span>@{{item.price | rupiah}} x @{{item.quantity}} = @{{item.amount | rupiah}}</span>
                                                </div>
                                            </td>               
                                            <td>
                                                @{{ data.total | rupiah }}
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
        <div class="modal-dialog modal-lg" role="document">
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
                        <label class="col-form-label  col-2 col-2">Pelanggan</label>
                        <div class="col-sm-12 col-md-10">
                            <select name="customer" v-model="formAdd.customer_id" class="form-control">
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>         
                    <div class="form-group row">
                        <label class="col-form-label  col-2 col-2">Produk</label>
                        <div class="col-sm-12 col-md-10">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Quantity</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data, index) in formAdd.items" :key="data.id">
                                        <td width="30%">@{{ data.name }}</td>
                                        <td width="25%">@{{ data.price_formatted }}</td>  
                                        <td><input type="number" min="0" @change="updateItem(index)" placeholder="qty" v-model="formAdd.items[index].quantity" class="form-control"></td>                                          
                                        <td width="25%" class="text-right">
                                            @{{ data.amount | rupiah }}                                            
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td colspan="3" class="text-right" style="font-weight: bold">TOTAL</td>
                                        <td class="text-right">@{{ total | rupiah }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>         
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" :disabled="total == 0" class="btn btn-primary">Simpan</button>
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
                    customer_id:'',
                    total:'',
                    items:[],
                },                
                products:[],                
                tableData:[],
            },
            computed:{
                total(){
                    let total = this.formAdd.items.map(item => item.amount).reduce((prev, next) => prev + next);

                    return total;
                }       
            },
            created() {
                
                let products = {!!json_encode($products)!!}
                products = products.map(item => {
                    item.quantity = 0;
                    item.amount = 0;
                    return item;
                })

                this.formAdd.items = products;
            },
            mounted() {
                this.loadData();
            },
            methods:{

                tambah(){

                    $("#modalTambah").modal("show");                
                    $(".print-error-msg").css('display','none');

                    this.formAdd.customer_id = '';
                    this.formAdd.items = this.formAdd.items.map(item => {
                        item.quantity = 0;
                        item.amount = 0;
                        return item;
                    })
                    
                },

                loadData(){

                    axios.get('{{ route("sales-orders.data") }}', {                                
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
                    
                    this.formAdd.total = this.total;

                    axios({
                        method: 'post',
                        url: '{{ route("sales-orders.store") }}',
                        data: this.formAdd,                    
                    })
                    .then(response => {

                        // handle success                    
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

                        // handle error
                        $(".print-error-msg").find("ul").html('');
                        $(".print-error-msg").css('display','block');

                        $.each( error.response.data.errors, function( key, value ) {
                            $(".print-error-msg").find("ul").append('<li>'+value+'</li>');

                        });
                        
                        
                    });               
                },

                updateItem(index){
                    let qty = this.formAdd.items[index].quantity == '' ? 0 : this.formAdd.items[index].quantity;

                    this.formAdd.items[index].amount = qty * this.formAdd.items[index].price
                }
            }, 
            filters: {
                rupiah: (value) => {

                    const formatter = new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 0,
                    })

                    return `Rp ${formatter.format(value)}`
                },
            },
        });

    </script>
@endpush