<?php
// kalau udah dipanggi gk dipanggil lagi
require_once BASE_PATH.'/user/model_rumahnegara.php';
class AsetController{
    private $model;
    public function __construct(){
        //buat obj model user
        $this->model = new ModelAset();
    }
    public function action(){
        //ternary operator
        // isset apakah isinya ada 
        $act = isset($_GET['act'])?$_GET['act']:'index';
        // $act = $_GET['act']??'index';
        // route dari aplikasi
        switch($act){
            case 'index':
                $this->index();
                break;
            case 'delete':
                $this->delete();
                break;
            case 'update':
                $this->update();
                break;
            default:
                $this->index();
            }
        }
    
    //action index
    public function index(){
        //baca parameter namaCari
        $paramCari = isset($_GET['paramCari'])?$_GET['paramCari']:"";
        $sortParam = isset($_GET['sortParam'])?$_GET['sortParam']:"";
        //get all data user
        // $users = $this->model->getAllUser();
        // $users = $this->model->findUser(array('field'=>'nama','searchvalue'=>"%$paramCari%"));
        $criteria = array(
            'field'=>array('id_aset','kode_barang','nama_barang','nup','kode_unit','status_validasi'),
            'searchvalue'=>"%$paramCari%",
            // 'sort' => 'nama DESC'
        );
        if($sortParam!=""){
            $criteria['sort'] = $sortParam;
        }
        $page = isset($_GET['page'])?$_GET['page']:1;
        //jumlah data per halaman
        $perpage = 2;
        //offset = page * perpage (1->0;2->2;3->4;4->6)
        $offset = ($page-1) * $perpage;

        $criteria['limit'] = $perpage;
        $criteria['offset'] = $offset;

        $asets = $this->model->findAset($criteria);

        //tampilkan data user dlm bentuk tabel
        $this->loadView('view/ui_rumahnegara',array('asets'=>$asets,'pesan'=>'helo','paramCari'=>$paramCari),'Tabel Aset');
    }

    //action delete
    public function delete(){
        //baca id yang mau di delete
        $id_delete = $_GET['id_aset'];
        $status = $this->model->deleteAset($id_delete);
        if($status){
            // header('location:user_controller.php');
            URL_Helper::redirect('user/aset_controller','index',null);
        }
    }

    //action update
    public function update(){
        //baca parameter id user yang akan diupdate
        $id_diupdate = $_GET['id_aset'];
        //get user berdasarkan id
        $aset = $this->model->updateAset($id_diupdate);
        // if($aset==null){
        //     echo 'aset not found';
        // }else{
        //      //cek apakah menampilkan form atau proses form
        //     if(isset($_POST['submit_update'])){
        //         //proses data
        //         $aset_baru = new Aset('',$_POST['kode_barang'],$_POST['nama_barang'],$_POST['nup'],$_POST['kode_unit']);
        //         //panggil fungsi updateuser
        //         $this->model->updateAset($id_diupdate,$aset_baru);
        //         // header('location:user_controller.php');
        //         URL_Helper::redirect('user/rumahnegara_controller','index',null);
        //     }else{
        //         $this->loadView('view/form_update_rumahnegara',array('aset'=>$aset),'Ubah Rumah Negara');
        //     }
        // }

    }

    //fungsi untuk load tampilan
    private function loadView($file,$data,$halaman){
        foreach($data as $key => $value){
            //membuat variable yang namanya adalah index dari elemen $data
            $$key = $value;
        }
        $namaModule = 'Aset';
        $linkModule = 'home';

        include BASE_PATH.'/user/view/header.php';
        // include BASE_PATH.'/user/view/ui_user.php';
        include BASE_PATH.'/user/'.$file.'.php';
        include BASE_PATH.'/user/view/footer.php';
    }
}

$asetController = new AsetController();
$asetController->action();

?>