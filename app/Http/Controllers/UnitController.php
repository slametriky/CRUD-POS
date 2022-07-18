<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\RkaProgram;
use App\Models\RkaKegiatan;
use App\Models\RkaKro;
use App\Models\RkaRo;
use App\Models\RkaKomponen;
use App\Models\RkaSubKomponen;
use App\Models\RkaAkun;
use App\Models\RkaDetail;
use File;

use Illuminate\Support\Facades\DB;
use App\Repositories\Renstra\RenstraRepository;

class UnitController extends Controller
{

    protected $renstraRepo;
    //

    public function __construct(RenstraRepository $renstraRepo) {
        $this->renstraRepo = $renstraRepo;
    }
    
    public function index()
    {        
        $user = Auth::user();
        return view('unit.unit_home', compact('user'));
    }

    public function renstra()
    {
        $data['periodes'] = DB::table('renstra_periode')->get();
        $data['satuans'] = DB::table('satuans')->get();
        $data['jenis_pendanaans'] = DB::table('jenis_pendanaans')->get();
        $data['programs'] = Program::all();

        return view('unit.unit_renstra', $data);
    }

    public function buatRenstra()
    {   
        
        $periode = isset($_GET['periode']) ? $_GET['periode'] : '';

        if(empty($periode)){
            return redirect(url('/'));
        }

        $tahunPeriode = DB::table('renstra_periode')
        ->where('id', $periode)
        ->first();

        if($tahunPeriode){

            //cek apakah renstra sdh ada
            $cekRenstra = DB::table('renstra')
            ->where('renstra_periode_id', $periode)
            ->where('unit_id', $this->getUnit())
            ->first();

            if(!$cekRenstra){
                //input restra
                $insertRenstra = DB::table('renstra')->insertGetId([
                    'renstra_periode_id' => $periode,
                    'unit_id' => $this->getUnit()  
                ]);
                
                $data['renstra_id'] = $insertRenstra;

            } else {
                $data['renstra_id'] = $cekRenstra->id;
            }
            
            $tahun = [];
    
            for($i = $tahunPeriode->tahun_mulai; $i <= $tahunPeriode->tahun_selesai; $i++){
                array_push($tahun, $i);
            }
            
            $data['tahun'] = $tahun;
            $data['periode'] = $periode.'.'.$tahunPeriode->tahun_mulai.'-'.$tahunPeriode->tahun_selesai;

            $data['satuans'] = DB::table('satuans')->get();
            $data['jenis_pendanaans'] = DB::table('jenis_pendanaans')->get();
            $data['programs'] = Program::all();
            $data['status_renstra'] = $cekRenstra->status_renstra_id;

            return view('unit.unit_buat_renstra', $data);

        } else {

            return redirect(url('/'));

        }


    }

    public function dataRenstra(Request $request)
    {

        $data = $this->renstraRepo->dataRenstra($request);        

        return response()->json($data);

    }

    public function rka()
    {   
        $data['satuans'] = DB::table('satuans')->get();
        $data['jenis_pendanaans'] = DB::table('jenis_pendanaans')->get();
        $data['programs'] = Program::all();

        return view('unit.unit_rka', $data);
    }

    public function dataRka(Request $request)
    {

        $data = $this->renstraRepo->dataRka($request);
        
        return response()->json($data);

    }

    public function dataKegiatan(Request $request)
    {
        $kro = DB::table('kegiatans')
                ->where('program_id', '=', $request->id)
                ->get();

        return response()->json($kro);  

    }

    public function rekamProgram(Request $request)
    {

        $unit_id = $this->getUnit();

        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_program')
        ->where('tahun', '=', $request->tahun)
        ->where('program_id', '=', $request->program)
        ->where('unit_id', '=', $unit_id)
        ->first();
        
        if($cek_data){

            $data['respon'] = 'Program sudah ada!';

        } else {

            $rka_program_save = new RkaProgram;
            $rka_program_save->tahun = $request->tahun;
            $rka_program_save->program_id = $request->program;
            $rka_program_save->unit_id = $unit_id;
            $rka_program_save->renstra_id = $request->renstra_id;
            $rka_program_save->save();                      

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil menghapus data',
                
            ];     

        }
      
        return response()->json($data);
    }

    public function rekamKegiatan(Request $request)
    {        

        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_kegiatan as b')
        ->where('b.kegiatan_id', '=', $request->kegiatan)
        ->where('b.tahun', '=', $request->tahun)
        ->where('b.unit_id', '=', $unit_id)
        ->first();

        
        if($cek_data){

            $data['respon'] = 'Kegiatan sudah ada!';

        } else {

          
            $rka_kegiatan_save = new RkaKegiatan;
            $rka_kegiatan_save->kegiatan_id = $request->kegiatan;
            $rka_kegiatan_save->tahun = $request->tahun;
            $rka_kegiatan_save->rka_program_id = $request->rka_program_id;
            $rka_kegiatan_save->unit_id = $unit_id;
            $rka_kegiatan_save->save();


            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',
                
            ];     

        }
      
        return response()->json($data);
    }
    
    public function hapusProgram(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $hapus = DB::table('rka_program')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);

        
    }

    public function hapusKegiatan(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $hapus = DB::table('rka_kegiatan')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function hapusKro(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $hapus = DB::table('rka_kro')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function dataKro(Request $request)
    {
       
        $kro = DB::table('kro')
                ->where('kegiatan_id', '=', $request->id)
                ->orderBy('kode_kro') 
                ->get();

        return response()->json($kro);  
        
    }    

    public function rekamKro(Request $request)
    {

        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_kro as a')
        ->where('a.kro_id', '=', $request->kro)
        ->where('a.tahun', '=', $request->tahun)
        ->where('a.unit_id', '=', $unit_id)
        ->first();
        
        if($cek_data){

            $data['respon'] = 'KRO sudah ada!';

        } else {

            $rka_kro_save = new RkaKro;
            $rka_kro_save->tahun = $request->tahun;
            $rka_kro_save->kro_id = $request->kro;
            $rka_kro_save->rka_kegiatan_id = $request->rka_kegiatan_id;
            $rka_kro_save->unit_id = $unit_id;
            $rka_kro_save->save();
          
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',               
            ];     

        }

        return response()->json($data);
    }

    public function dataRo(Request $request)
    {
        
        $data = DB::table('ro')
                ->where('kro_id', '=', $request->id)
                ->orderBy('kode_ro') 
                ->get();

        return response()->json($data);  
    }
        
    public function rekamRo(Request $request)
    {

        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_ro as a')
        ->where('a.ro_id', '=', $request->ro)
        ->where('a.tahun', '=', $request->tahun)
        ->where('a.unit_id', '=', $unit_id)
        ->first();
        
        if($cek_data){

            $data['respon'] = 'RO sudah ada!';

        } else {

            $save = new RkaRo;
            $save->tahun = $request->tahun;
            $save->ro_id = $request->ro;
            $save->rka_kro_id = $request->rka_kro_id;
            $save->unit_id = $unit_id;
            $save->save();
          
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',               
            ];     

        }

        return response()->json($data);
    }

    public function hapusRo(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $hapus = DB::table('rka_ro')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function dataKomponen(Request $request)
    {
        
        $data = DB::table('komponens')
                ->where('ro_id', '=', $request->id)
                ->orderBy('kode_komponen') 
                ->get();

        return response()->json($data);  
    }

    public function rekamKomponen(Request $request)
    {
        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_komponen as a')
        ->where('a.komponen_id', '=', $request->komponen)
        ->where('a.tahun', '=', $request->tahun)
        ->where('a.unit_id', '=', $unit_id)
        ->first();
        
        if($cek_data){

            $data['respon'] = 'Komponen sudah ada!';

        } else {

            $save = new RkaKomponen;
            $save->tahun = $request->tahun;
            $save->komponen_id = $request->komponen;
            $save->rka_ro_id = $request->rka_ro_id;
            $save->unit_id = $unit_id;
            $save->save();
          
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',               
            ];     

        }

        return response()->json($data);
    }

    public function hapusKomponen(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $hapus = DB::table('rka_komponen')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function dataSubKomponen(Request $request)
    {
        
        $data = DB::table('komponens')
                ->where('ro_id', '=', $request->id)
                ->orderBy('kode_komponen') 
                ->get();

        return response()->json($data);  
    }

    public function rekamSubKomponen(Request $request)
    {
        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_sub_komponen as a')
        ->where('a.kode', '=', $request->sub_komponen)
        ->where('a.rka_komponen_id', '=', $request->rka_komponen_id)
        ->where('a.tahun', '=', $request->tahun)
        ->where('a.unit_id', '=', $unit_id)
        ->first();
        
        if($cek_data){

            $data['respon'] = 'Sub Komponen sudah ada!';

        } else {

            $save = new RkaSubKomponen;
            $save->tahun = $request->tahun;
            $save->kode = $request->sub_komponen;
            $save->uraian = $request->uraian;
            $save->rka_komponen_id = $request->rka_komponen_id;
            $save->unit_id = $unit_id;
            $save->save();
          
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',               
            ];     

        }

        return response()->json($data);
    }

    public function hapusSubKomponen(Request $request)
    {
        // dd($request);
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $fileSubKomponen = DB::table('rka_sub_komponen as a')
        ->Join('rka_sub_komponen_files as c', 'a.id', 'c.rka_sub_komponen_id')
        ->select(DB::raw('c.file'))
        ->where('a.id', '=', $request->id)->get()->toArray();

        if($fileSubKomponen) {
            $arrFileSub = array_map(function($dt){
                return public_path() . '/assets/file/'.$dt->file;
            }, $fileSubKomponen);

            File::delete($arrFileSub);             
        }
        
        $fileDetail = DB::table('rka_sub_komponen as a')
        ->join('rka_akun as b', 'a.id', 'b.rka_sub_komponen_id')
        ->join('rka_detail as c', 'b.id', 'c.rka_akun_id')
        ->join('rka_detail_files as d', 'c.id', 'd.rka_detail_id')
        ->select(DB::raw('d.file'))
        ->where('a.id', '=', $request->id)->get()->toArray();

        if($fileDetail){
            $arrFileDetail = array_map(function($dt){
                return public_path() . '/assets/file/'.$dt->file;
            }, $fileDetail);

            File::delete($arrFileDetail); 
        }        

        $hapus = DB::table('rka_sub_komponen')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function loadListFileSubKom(Request $request)
    {          

        $jenis = $this->getJenis($request->jenis);

        $data = DB::table('rka_sub_komponen_files as a')
        ->join('rka_sub_komponen as b', 'b.id', 'a.rka_sub_komponen_id')
        ->select("a.*")
        ->where('b.unit_id','=', $this->getUnit())
        ->where('a.rka_sub_komponen_id', $request->id)
        ->where('a.jenis_file', $jenis)
        ->get();

        return response()->json($data);

    }

    public function hapusFileSubKom(Request $request)
    {
        $data = DB::table('rka_sub_komponen_files')
        ->where('id', '=', $request->id);
        
        $file = $data->first()->file;

        $file_lama = public_path() . '/assets/file/'.$file;
        			
        if(File::exists($file_lama)){			
            File::delete($file_lama);
        } 

        if($data->delete()){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     

        } 

        return response()->json($data);

    }

    public function cariAkun(Request $request)
    {   
        $akun = DB::table('akuns')
                ->where('kode_akun', '=', $request->get('query'))
                ->orWhere('desk_akun', 'like', '%'.$request->get('query').'%' )
                ->get();
        
        return response()->json($akun);
    }

    public function rekamAkun(Request $request)
    {
        
        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $cek_data = DB::table('rka_akun as a')
        ->where('a.akun_id', '=', $request->akun)
        ->where('a.rka_sub_komponen_id', '=', $request->rka_sub_komponen_id)
        ->where('a.tahun', '=', $request->tahun)
        ->where('a.unit_id', '=', $unit_id)
        ->first();
        
        if($cek_data){

            $data['respon'] = 'Akun sudah ada!';

        } else {

            $save = new RkaAkun;
            $save->tahun = $request->tahun;
            $save->akun_id = $request->akun;
            $save->rka_sub_komponen_id = $request->rka_sub_komponen_id;
            $save->jenis_pendanaan_id = $request->jenis_pendanaan;
            $save->unit_id = $unit_id;
            $save->save();
          
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',               
            ];     

        }

        return response()->json($data);
    }

    public function hapusAkun(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $hapus = DB::table('rka_akun')
        ->where('id', '=', $request->id)
        ->delete();

        if($hapus){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function dataDetail(Request $request)
    {   
        $akun = DB::table('akuns')
                ->where('kode_akun', '=', $request->get('query'))
                ->orWhere('desk_akun', 'like', '%'.$request->get('query').'%' )
                ->get();
        
        return response()->json($akun);
    }

    public function rekamDetail(Request $request)
    {
    
        //cari record terakhir dan tambah urutan kode
        $kode = DB::table('rka_detail')
        ->where('rka_akun_id','=',$request->rka_akun_id)
        ->orderBy('kode', 'desc')
        ->limit(1)->get()->toArray();
        
        $kode = empty($kode) ? 1 : $kode[0]->kode+=1;
        
        $unit_id = $this->getUnit();
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];          
       
        $harga = $this->getAngka($request->harga);
        $jumlah = $this->getAngka($request->jumlah);

        $save = new RkaDetail;
        $save->tahun = $request->tahun;
        $save->kode = $kode;
        $save->uraian = $request->uraian;
        $save->rka_akun_id = $request->rka_akun_id;
        $save->satuan_id = $request->satuan;
        $save->volume = $request->vol;
        $save->harga = $harga;
        $save->jumlah = $jumlah;
        $save->unit_id = $unit_id;

        $save->save();
        
        $data = [
            'responCode' => 1,
            'respon'    => 'Berhasil',               
        ];     

        return response()->json($data);
    }

    public function updateDetail(Request $request)
    {

        $harga = $this->getAngka($request->harga);
        $jumlah = $this->getAngka($request->jumlah);

        $data = DB::table('rka_detail')
        ->where('id', '=', $request->id)
        ->update(
            [
                'uraian'    => $request->uraian,
                'volume'    => $request->vol,
                'satuan_id' => $request->satuan,
                'harga'     => $harga,
                'jumlah'    => $jumlah  
            ]
        );

        $data = [
            'responCode' => 0,
            'respon'    => '',            
        ];     

        if($data){
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } 

        return response()->json($data);
        
    }
    
    public function loadListFileDetail(Request $request)
    {
        $jenis = $this->getJenis($request->jenis);
        
        $data = DB::table('rka_detail_files as a')
        ->join('rka_detail as b', 'b.id', 'a.rka_detail_id')
        ->select("a.*")
        ->where('b.unit_id','=', $this->getUnit())
        ->where('a.rka_detail_id', $request->id)
        ->where('a.jenis_file', $jenis)
        ->get();

        return response()->json($data);

    }

    public function uploadFileDetail(Request $request)
    {   

        $jenis = $this->getJenis($request->jenis);

        $namaFile = rand().time().'.'.$request->file('file_pendukung')->extension();                         

        $insert = DB::table('rka_detail_files')->insert([
            'rka_detail_id' =>  $request->data_id,
            'jenis_file'    =>  $jenis,
            'file'          =>  $namaFile      
        ]);    

        if($insert){

            $request->file('file_pendukung')->move(public_path('assets/file/'), $namaFile);

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        }

        return response()->json($data);

    }

    public function uploadFileSubKom(Request $request)
    {
        $jenis = $this->getJenis($request->jenis);

        $namaFile = rand().time().'.'.$request->file('file_pendukung')->extension();     
        
        $insert = DB::table('rka_sub_komponen_files')->insert([
            'rka_sub_komponen_id' =>  $request->data_id,
            'jenis_file'    =>  $jenis,
            'file'          =>  $namaFile      
        ]);    

        if($insert){

            $request->file('file_pendukung')->move(public_path('assets/file/'), $namaFile);

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        }

        return response()->json($data);

    }

    public function hapusFileDetail(Request $request)
    {
        $data = DB::table('rka_detail_files')
        ->where('id', '=', $request->id);
        
        $file = $data->first()->file;

        $file_lama = public_path() . '/assets/file/'.$file;
        			
        if(File::exists($file_lama)){			
            File::delete($file_lama);
        } 

        if($data->delete()){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     

        } 

        return response()->json($data);

    }

    public function hapusDetail(Request $request)
    {
        
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $data = DB::table('rka_detail')
        ->where('id', '=', $request->id);

        $dataFile = DB::table('rka_detail_files as a')
        ->join('rka_detail as b', 'b.id', 'a.rka_detail_id')
        ->where('a.rka_detail_id', '=', $request->id)->get()->toArray();
        
        $namaFileHapus = array_map(function($data){
            return public_path() . '/assets/file/'.$data->file;
        }, $dataFile);
        
        File::delete($namaFileHapus);        

        if($data->delete()){

            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     

        } else {
            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function simpanRka(Request $request)
    {

        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        //cek ada detil atau tidak
        $cekDetail = DB::table('rka_detail')
        ->where('tahun', $request->tahun)
        ->where('unit_id', $this->getUnit())
        ->get();
        
        if($cekDetail->isEmpty()){
            $data = ['respon' => 'Gagal, detail belum diinput'];
        } else {

            $update = DB::table("rka_program")
            ->where('tahun', $request->tahun)
            ->where('unit_id', $this->getUnit())
            ->update(['status' => 1]);

            if($update){
                $data = [
                    'responCode' => 1,
                    'respon'    => 'Berhasil',            
                ];     
            } else {
                $data = ['respon' => 'Gagal'];
            }

        }
        

        return response()->json($data);
    }

    public function batalSimpanRka(Request $request)
    {

        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $update = DB::table("rka_program")
        ->where('tahun', $request->tahun)
        ->where('unit_id', $this->getUnit())
        ->update(['status' => 0]);

        if($update){
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {

            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function ajukanRenstra(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $update = DB::table("renstra")
        ->where('id', $request->id)
        ->where('unit_id', $this->getUnit())
        ->update(['status_renstra_id' => 2]);

        if($update){
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {

            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function batalAjukanRenstra(Request $request)
    {
        $data = [
            'responCode' => 0,
            'respon'    => ''
        ];  

        $update = DB::table("renstra")
        ->where('id', $request->id)
        ->where('unit_id', $this->getUnit())
        ->update(['status_renstra_id' => 1]);

        if($update){
            $data = [
                'responCode' => 1,
                'respon'    => 'Berhasil',            
            ];     
        } else {

            $data = ['respon' => 'Gagal'];
        }

        return response()->json($data);
    }

    public function loadListComment(Request $request)
    {
        
        $data = $this->renstraRepo->loadListComment($request);   

        return response()->json($data);
    }

    public function getAngka($angka){
        
        $angka = explode('.',$angka);

        if(count($angka) > 1){
            $hasil = '';
            foreach($angka as $data){
                $hasil .= $data;
            }
        } else {
            $hasil = $angka[0];
        }

        return $hasil;
    } 

    public function getUnit()
    {
        return DB::table('user_units')
        ->where('user_id', '=', Auth::user()->id)
        ->first()->unit_id;
    }

    public function getJenis($jenis_file)
    {
        if($jenis_file == "kak"){
            $jenis = 1;
        } else if($jenis_file == "rab"){
            $jenis = 2;
        } else if($jenis_file == "pendukung"){
            $jenis = 3;
        }

        return $jenis;
    }

}
