<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class packages extends REST_Controller {

    function __construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->conf = array(
            'key' => $this->uri->segment(1)
        );
        $this->res = array(
            'status'    => 200,
            'msg'       => 'success'
        );
        if (!$this->conf['key']) {
            $this->res['status'] = 203;
            $this->res['msg'] = 'unauthorize';
            $this->response($this->res,203);
            exit;
        } else {
            $checking_key = $this->db->get_where('server_list',['id'=>$this->conf['key']])->num_rows();
            if ($checking_key<1) {
                $this->res['status'] = 403;
                $this->res['msg'] = 'auth failure';
                $this->response($this->res,403);
                exit;
            }
        }
    }
    
    function index_get() {
        $code = $this->db->escape($this->conf['key']);
        $db = $this->db->select('jurusan,lesson,level,package,question_count,status,acak_soal,acak_jawaban')->where("(code=$code OR code='ALL')")->get('packages');
        if ($db) {
            $this->res['key'] = $this->conf['key'];
            $this->res['length'] = $db->num_rows();
            $this->res['data'] = $db->result();
            $this->response($this->res, 200);
        } else {
            $this->res['msg'] = 'Not found';
            $this->response($this->res,200);
        }
    }
}