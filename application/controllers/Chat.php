<?php
    class Chat extends CI_Controller{
        private $user_id;
        private $status;

        public function __construct(){
            parent::__construct();
            $this->user_id = $this->session->userdata("user_id");
            $this->status = $this->session->userdata("status");
            if(!$this->status){
                redirect('login');
            }
            date_default_timezone_set("Asia/Jakarta");
        }
        
        public function index(){
            $data['user_id'] = $this->user_id;
            $data['user'] = $this->db->get_where("user", ["user_id" => $this->user_id])->row_array();
            $data['all_user'] = $this->db->query("SELECT * FROM user WHERE user_id<>'$this->user_id'")->result();
            $this->load->view("chat/index", $data);
        }

        public function get_all_user(){
            $data['all_user'] = $this->db->query("SELECT * FROM user WHERE user_id<>'$this->user_id'")->result();
            echo json_encode($data);
        }

        public function get_news(){
            $room_id = $this->input->get("room_id");
            $user_id = $this->user_id;
            // $data['totals'] = $this->db->get_where("chat_readed", [
            //     "room_chat_id" => $room_id,
            //     "user_id" => $user_id,
            //     "readed" => 0
            // ])->num_rows();
            $total = $this->db->query("SELECT COUNT(*) as total_new_message, room_chat_id FROM chat_readed WHERE user_id='$user_id' AND room_chat_id='$room_id' AND readed='0' GROUP BY user_id");
            if($total->num_rows() > 0){
                $data['result'] = true;
                $data['totals'] = $total->row_array();
            }
            else{
                $data['result'] = false;
                $data['totals'] = [
                    "total_new_message" => 0
                ];
            }

            echo json_encode($data);
        }

        public function new_chat(){
            $room_chat_id = $this->uuid->v4(true);
            $chat_type = $this->input->post_get("chat_type");
            $user = $this->input->post_get("user");

            $date_now = date("Y-m-d H:i:s");
            //For Group
            if($chat_type == "Group"){
                //Add Group Chat Room First
                $title = $this->input->post("title");

                $data = [
                    "room_chat_id" => $room_chat_id,
                    "chat_title" => $title,
                    "created_at" => $date_now,
                    "updated_chat_at" => $date_now,
                    "created_by" => $this->user_id,
                    "room_type" => $chat_type
                ];
                $this->db->insert("chat_list", $data);

                //Add Himself as Admin
                $admin_user_id = $this->uuid->v4(true);
                $data_user = [
                    "chat_room_member_id" => $admin_user_id,
                    "room_chat_id" => $room_chat_id,
                    "user_id" => $this->user_id,
                    "user_type" => "Admin"
                ];
                $this->db->insert("chat_member", $data_user);

                //Add Other Members
                for($i = 0; $i < sizeof($user); $i++){
                    $chat_room_member_id = $this->uuid->v4(true);
                    $user_id_member = $user[$i];

                    $this->db->insert("chat_member", [
                        "chat_room_member_id" => $chat_room_member_id,
                        "room_chat_id" => $room_chat_id,
                        "user_id" => $user_id_member,
                        "user_type" => "Member"
                    ]);
                }

                $data['result'] = true;
            }
            //For Private
            else{
                //Only private chat will check her target.
                //Action will refuse if has target and himself already have a chat
                $allow = true;
                $himself_id = $this->user_id;
                $check = $this->db->query("SELECT * FROM chat_member JOIN chat_list ON chat_member.room_chat_id=chat_list.room_chat_id WHERE chat_member.user_id='$himself_id' AND chat_list.room_type='Private'");
                if($check->num_rows() > 0){
                    foreach($check->result() as $row){
                        $room_id = $row->room_chat_id;

                        $data_member = $this->db->query("SELECT * FROM chat_member WHERE room_chat_id='$room_id'")->result();
                        foreach($data_member as $row){
                            if($row->user_id == $user){
                                $allow = false;
                            }   
                        }
                    }
                }

                if($allow){
                    $data['result'] = true;
    
                    $this->db->insert("chat_list", [
                        "room_chat_id" => $room_chat_id,
                        "chat_title" => "Private Chat",
                        "created_at" => $date_now,
                        "updated_chat_at" => $date_now,
                        "created_by" => $this->user_id,
                        "room_type" => $chat_type
                    ]);
                    
                    //Himself
                    $this->db->insert("chat_member", [
                        "chat_room_member_id" => $this->uuid->v4(true),
                        "room_chat_id" => $room_chat_id,
                        "user_id" => $this->user_id,
                        "user_type" => "Member"
                    ]);
    
                    //Add His Chatting Member
                    $this->db->insert("chat_member", [
                        "chat_room_member_id" => $this->uuid->v4(true),
                        "room_chat_id" => $room_chat_id,
                        "user_id" => $user,
                        "user_type" => "Member"
                    ]);
                }
                else{
                    $data['result'] = false;
                    $data['msg'] = "You already have a chat with this user";
                }
            }

            echo json_encode($data);
        }

        public function group_chat(){
            //Load
            $user_id = $this->user_id;
            $data['chat'] = $this->db->query("SELECT * FROM chat_member JOIN chat_list ON chat_list.room_chat_id=chat_member.room_chat_id WHERE chat_member.user_id='$user_id' AND chat_list.room_type='Group' ORDER BY chat_list.updated_chat_at DESC")->result();

            echo json_encode($data);
        }

        public function private_chat(){
            //Load
            $user_id = $this->user_id;
            
            $array["chat"] = array(); 
            $all_members_id = $this->get_all_members_user_id();
            // print_r($all_members_id);
            for($i = 0; $i < sizeof($all_members_id); $i++){
                $member_user_id = $all_members_id[$i]['user_id'];
                $room_chat_id = $all_members_id[$i]['room_chat_id'];

                $data_users = $this->db->get_where("user", ["user_id" => $member_user_id])->row_array();

                $data = [
                    "chat_title" =>  $data_users['nama'],
                    "room_chat_id" => $room_chat_id
                ];

                array_push($array["chat"], $data);
            }

            echo json_encode($array);
        }

        //For private Chat
        public function get_all_members_user_id(){
            $user_id = $this->user_id;
            $data_chat_array = array();

            $data_chat = $this->db->query("SELECT * FROM chat_member JOIN chat_list ON chat_list.room_chat_id=chat_member.room_chat_id WHERE chat_member.user_id='$user_id' AND chat_list.room_type='Private' ORDER BY chat_list.updated_chat_at DESC")->result();

            foreach($data_chat as $row){
                $room_chat_id = $row->room_chat_id;

                //Get All Member Excep His
                $data_member = $this->db->query("SELECT * FROM chat_member WHERE room_chat_id='$room_chat_id' AND user_id<>'$user_id'")->row_array();
                $member_id = $data_member['user_id'];

                $data_room = [
                    "user_id" => $member_id,
                    "room_chat_id" => $room_chat_id
                ];

                array_push($data_chat_array, $data_room);
            }

            return $data_chat_array;
        }

        public function show_chat(){
            $room_id = $this->input->get_post("room_id");
            $check = $this->db->get_where("chat_list", ["room_chat_id" => $room_id]);
            if($check->num_rows() > 0){
                $data_chat_list = $check->row_array();
                $data_member = $this->db->get_where("chat_member", ["room_chat_id" => $room_id])->result();

                if($data_chat_list['room_type'] == "Group"){
                    $data['chat_title'] = $data_chat_list['chat_title']." (Group Chat)";
                }
                else{
                    foreach($data_member as $row){
                        if($row->user_id != $this->user_id){
                            $data_user = $this->db->get_where("user", ["user_id" => $row->user_id])->row_array();

                            $data['chat_title'] = $data_user['nama']." (Private Chat)";
                        }
                        else{
                            continue;
                        }
                    }
                }

                //Update that chat list is readed
                $this->db->update("chat_readed", ["readed" => 1], ["room_chat_id" => $room_id, "user_id" => $this->user_id]);

                $data['result'] = true;
                $data['all_member'] = $this->db->query("SELECT * FROM chat_member JOIN user ON chat_member.user_id=user.user_id WHERE chat_member.room_chat_id='$room_id' ORDER BY user.nama ASC")->result();
                $data['all_chat'] = $this->db->query("SELECT * FROM chat_message JOIN user ON chat_message.send_by=user.user_id WHERE chat_message.room_chat_id='$room_id' ORDER BY chat_message.send_at ASC")->result();
            }
            else{
                $data['result'] = false;
                $data['msg'] = "Your chat room does not exist!";
            }
            echo json_encode($data);
        }

        public function new_message(){
            $room_id = $this->input->post("room_id");
            $message = $this->input->post("chat_message");
            $send_at = date("Y-m-d H:i:s");
            $user_id = $this->user_id;

            //Checking Room
            $check = $this->db->get_where("chat_list", ["room_chat_id" => $room_id])->num_rows();
            if($check > 0){
                //Add Message First
                $this->db->insert("chat_message", [
                    "message_id" => $this->uuid->v4(true),
                    "room_chat_id" => $room_id,
                    "send_by" => $user_id,
                    "message" => $message,
                    "send_at" => $send_at
                ]);

                $member = $this->db->get_where("chat_member", ["room_chat_id" => $room_id])->result();
                foreach($member as $row){
                    $member_user_id = $row->user_id;
                    if($member_user_id != $user_id){
                        $this->db->insert("chat_readed", [
                            "chat_readed_id" => $this->uuid->v4(true),
                            "room_chat_id" => $room_id,
                            "user_id" => $member_user_id,
                            "readed" => 0
                        ]);
                    }
                }

                //Update lasted send message
                $this->db->update("chat_list", ["updated_chat_at" => $send_at], ["room_chat_id" => $room_id]);

                $data['result'] = true;
            }
            else{
                $data['result'] = false;
                $data['msg'] = "Your room does not exist";
            }

            echo json_encode($data);
        }
    }
?>