<?php

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class kelas extends REST_Controller {

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
        $jurusan = $this->input->get('jurusan') ? ' AND jurusan='.$this->db->escape($this->input->get('jurusan')) : '';
        $level = $this->input->get('level') ? ' AND level='.$this->db->escape($this->input->get('level')) : '';
        $class = $this->input->get('class') ? ' AND class='.$this->db->escape($this->input->get('class')) : '';
        $db = $this->db->select('level,name,jurusan,class')->where("(code=$code OR code='ALL')$jurusan$level$class")->get('class');
        if ($db->num_rows()>0) {
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