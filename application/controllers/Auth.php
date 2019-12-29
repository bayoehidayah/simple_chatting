<?php
    class Auth extends CI_Controller{
        private $status;
        
        public function __construct(){
            parent::__construct();
            $this->status = $this->session->userdata("status");
        }

        public function index(){
            if(!$this->status){
                $this->load->view("auth/login");
            }
            else{
                redirect("chat");
            }
        }

        public function login_act(){
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            
            $check_user = $this->db->get_where("user", ["username" => $username]);
            if($check_user->num_rows() > 0){
                $data_user = $check_user->row_array();
                $pass_dec = $this->encrypt->decode($data_user['password']);
                $data['pass_desc'] = $pass_dec;
                if($pass_dec == $password){
                    $this->session->set_userdata([
                        "user_id" => $data_user['user_id'],
                        "status" => true
                    ]);
                    $data['result'] = true;
                    $data['msg'] = "Your login session has been created. This will take a moment";
                }
                else{
                    $data['result'] = false;
                    $data['msg'] = "Username or password is false";
                }
            }
            else{
                $data['result'] = false;
                $data['msg'] = "Username or password is false";
            }

            echo json_encode($data);
        }

        public function logout(){
            if($this->status){
                $this->session->sess_destroy();
                redirect("login");
            }
        }
    }
?>