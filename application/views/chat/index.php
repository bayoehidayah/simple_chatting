<!DOCTYPE html>
<html>
<head>
  <?php $this->load->view("templates/header"); ?>
  <style>
      .overflow{ overflow-y:scroll;}
      .height{ height:auto; }
      .direct-chat-msg{width:100%;}
      .direct-chat-text{width:auto !important;}
      .chat-time{margin-left:10px;margin-right:10px;}
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <?php 
    $this->load->view("templates/topbar");
    $this->load->view("templates/sidebar");
  ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Chat
        <!-- <small>13 new messages</small> -->
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-comments"></i> Home</a></li>
        <li class="active">Chat</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <button type="button" class="btn btn-success btn-block margin-bottom" data-toggle="modal" data-target="#modalChat">New Chat</button>

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Group Chat</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding" style="max-height:350px;" id="group_chat_list">
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Private Chat</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding" style="max-height:350px;" id="private_chat_list">
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <!-- DIRECT CHAT PRIMARY -->
          <div class="box box-primary direct-chat direct-chat-primary">
            <div class="box-header with-border">
              <h3 class="box-title" id="chatTitle">Private Chat</h3>

              <div class="box-tools pull-right">
                <!-- <span data-toggle="tooltip" title="3 New Messages" class="badge bg-light-blue">3</span> -->
                <!-- <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button> -->
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Member for this chat" data-widget="chat-pane-toggle">
                  <i class="fa fa-comments"></i></button>
                <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Conversations are loaded here -->
              <div class="direct-chat-messages" style="max-height:710px;" id="chatResult">
              </div>
              <!--/.direct-chat-messages-->

              <!-- Contacts are loaded here -->
              <div class="direct-chat-contacts" style="height:auto;max-height:710px;">
                <ul class="contacts-list" id="chatMember">
                </ul>
                <!-- /.contatcts-list -->
              </div>
              <!-- /.direct-chat-pane -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="alert alert-info">Harap menggunakan kata dan kalimat yang sopan. Setelah mengirim pesan anda tidak dapat mengedit atau menghapusnya</div>
              <form action="#" method="post" id="sendChat" enctype="multipart/form-data">
              <input type="hidden" name="room_id" id="sendChatRoomId">
                <div class="input-group">
                    <input type="text" placeholder="Type some message here..."  name="chat_message" id="sendChatMessage" class="form-control">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-primary btn-flat">Send</button>
                    </span>
                </div>
              </form>
            </div>
            <!-- /.box-footer-->
          </div>
          <!--/.direct-chat -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

  <?php $this->load->view("templates/footer"); ?>
</div>
<!-- ./wrapper -->

<form action="#" method="post" enctype="multipart/form-data" id="newChat">
    <div class="modal fade" id="modalChat">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">New Chat</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Chat Type</label>
                        <select name="chat_type" id="chat_type" class="form-control">
                            <option value="Private">Private</option>
                            <option value="Group">Group</option>
                        </select>
                    </div>
                    <div class="form-group" id="chat_title" style="display:none;">
                        <label>Chat Title</label>
                        <input type="text" name="title" id="chat_title_form" maxlength="30" class="form-control" placeholder="Create your group chat title here (Max : 30 Character)">
                    </div>
                    <div class="form-group">
                        <label>Add User</label>
                        <select name="user" id="user" class="form-control select2" style="width:100%;">
                            <?php 
                                foreach($all_user as $row) { 
                                    echo '<option value="'.$row->user_id.'">'.$row->nama.'</option>';
                                } 
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Chat</button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $this->load->view("templates/js"); ?>
<!-- Page Script -->
<script>
  $(function () {  
    chat_core();
    $(".select2").select2();
    //Enable iCheck plugin for checkboxes
    //iCheck for checkbox and radio inputs
    $('.mailbox-messages input[type="checkbox"]').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
      radioClass: 'iradio_flat-blue'
    });

    //Enable check and uncheck all functionality
    $(".checkbox-toggle").click(function () {
      var clicks = $(this).data('clicks');
      if (clicks) {
        //Uncheck all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
        $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
      } else {
        //Check all checkboxes
        $(".mailbox-messages input[type='checkbox']").iCheck("check");
        $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
      }
      $(this).data("clicks", !clicks);
    });

    //Handle starring for glyphicon and font awesome
    $(".mailbox-star").click(function (e) {
      e.preventDefault();
      //detect type
      var $this = $(this).find("a > i");
      var glyph = $this.hasClass("glyphicon");
      var fa = $this.hasClass("fa");

      //Switch states
      if (glyph) {
        $this.toggleClass("glyphicon-star");
        $this.toggleClass("glyphicon-star-empty");
      }

      if (fa) {
        $this.toggleClass("fa-star");
        $this.toggleClass("fa-star-o");
      }
    });

    $("#chat_type").on("change", function(){
        var value = $(this).val();
        var user = $("#user");
        if(value == "Private"){
            user.attr("name", "user");
            user.select2("destroy");
            user.select2({
                multiple : false
            });
            $("#chat_title").hide();
            $("#chat_title_form").removeAttr("required");
        }
        else{
            user.attr("name", "user[]");
            user.select2("destroy");
            user.select2({
                multiple : true,
                placeholder : "Select User"
            });
            $("#chat_title").show();
            $("#chat_title_form").attr("required", "required");
        }
    });
    
    $("#newChat").submit(function (e) { 
        e.preventDefault();
        
        var data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("chat_add"); ?>",
            data: data,
            dataType: "JSON",
            success: function (response) {
                if(response.result){
                    swal({
                        title : "Success!",
                        text : "New chat has been created. This will take a moment",
                        type : "success",
                        timer : 2000,
                        showConfirmButton : false
                    }, function(){
                        $("#newChat")[0].reset();
                        $("#modalChat").modal("toggle");

                        swal.close(); 
                    });
                }
                else{
                    swal({
                        title : "Oops!",
                        text : response.msg,
                        type : "error"
                    });
                }
            },
            erorr : function(){
                swal({
                    title : "Oops!",
                    text : "Can't add new chat",
                    type : "error"
                }, function(){
                    location.reload();
                })
            }
        });
    });

    $("#sendChat").submit(function(e){
        e.preventDefault();

        var form_data = $(this).serialize();
        var room_id = $("#sendChatRoomId").val();
        if(room_id == ""){
            swal({
                title : "Oops!",
                text : "Please select chat room first",
                type : "warning"
            });
        }
        else{
            $.ajax({
                type: "POST",
                url: "<?php echo base_url("send_message"); ?>",
                data: form_data,
                dataType: "JSON",
                success: function (response) {
                    if(response.result){
                        $("#sendChat")[0].reset();
                    }
                    else{
                        swal({
                            title : "Oops!",
                            text : response.msg,
                            type : "error"    
                        });
                    }
                },
                error : function(errorThrown, textStatus, jqXHR){
                    swal({
                        title : "Oops!",
                        text : "Can't send your message",
                        type : "error"
                    });
                }
            });
        }
    })
  });

// Chat JS
function chat_core(){
    setInterval(load_group_chat, 1500);
    setInterval(load_private_chat, 1500);
    setInterval(() => {
       var room_id = $("#sendChatRoomId").val(); 
       if(room_id != ""){
           load_chat_message(room_id);
       }
    }, 1500);
}

function load_group_chat(){
    $.ajax({
        type: "GET",
        url: "<?php echo base_url("group_chat_load"); ?>",
        dataType: "JSON",
        success: function (response) {
            var list = '<ul class="nav nav-pills nav-stacked">';
            var room_id, news, chat_title;
            var news_message = 0;

            if(response.chat.length > 8){
                $("#group_chat_list").addClass("overflow");
            }
            else{
                $("#group_chat_list").removeClass("overflow");
            }

            for(var i = 0; i < response.chat.length; i++){
                room_id = response.chat[i].room_chat_id;
                chat_title = response.chat[i].chat_title;

                news_message = get_news_message(room_id);
                if(news_message > 0){
                    news = '<span class="label label-primary pull-right">'+news_message+'</span>';
                }
                else{
                    news = '';
                }

                list += '<li><a href="#" onclick="load_chat_message(\''+room_id+'\')">'+chat_title+news+'</a></li>';
            }

            list += '</ul>';
            $("#group_chat_list").html(list);
        },
        error : function(errorThrown, textStatus, jqXHR){
            var errors = '<div class="alert alert-danger">Error while getting group chat</div>';
            $("#group_chat_list").html(errors);
        }
    });
}

function load_private_chat(){
    $.ajax({
        type: "GET",
        url: "<?php echo base_url("private_chat_load"); ?>",
        dataType: "JSON",
        success: function (response) {
            var list = '<ul class="nav nav-pills nav-stacked">';
            var room_id, news_message, news, chat_title;

            if(response.chat.length > 8){
                $("#private_chat_list").addClass("overflow");
            }
            else{
                $("#private_chat_list").removeClass("overflow");
            }

            for(var i = 0; i < response.chat.length; i++){
                room_id = response.chat[i].room_chat_id;
                chat_title = response.chat[i].chat_title;

                news_message = get_news_message(room_id);
                if(news_message > 0){
                    news = '<span class="label label-primary pull-right" id="new_message_'+room_id+'">'+news_message+'</span>';
                }
                else{
                    news = '';
                }

                list += '<li><a href="#" onclick="load_chat_message(\''+room_id+'\')">'+chat_title+news+'</a></li>';
            }

            list += '</ul>';
            $("#private_chat_list").html(list);
        },
        error : function(errorThrown, textStatus, jqXHR){
            var errors = '<div class="alert alert-danger">Error while getting private chat</div>';
            $("#private_chat_list").html(errors);
        }
    });
}

function load_chat_message(room_id){
    $.ajax({
        type: "POST",
        url: "<?php echo base_url("show_chatting"); ?>",
        data: {room_id : room_id},
        dataType: "JSON",
        success: function (response) {
            if(response.result){
                var user_id = "<?php echo $user_id; ?>";
                var chat_result = "", userName = "", dateTime = "", message = "";  
                var chat_member = "";
                $("#chatTitle").text(response.chat_title);
                $("#sendChatRoomId").val(room_id);
                //Chat Message
                if(response.all_chat.length > 5){
                    $("#chatResult").addClass("height");
                }
                else{
                    $("#chatResult").removeClass("height");
                }

                for(var i = 0; i < response.all_chat.length; i++){
                    userName = response.all_chat[i].nama;
                    dateTime = response.all_chat[i].send_at;
                    message = response.all_chat[i].message;

                    //Left Chat
                    if(user_id != response.all_chat[i].send_by){
                        chat_result += '<div class="direct-chat-msg left-text"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-left">'+userName+'</span><span class="direct-chat-timestamp pull-left chat-time">'+dateTime+'</span></div><img class="direct-chat-img" src="<?php echo base_url("assets/dist/img/user1-128x128.jpg"); ?>" alt="Message User Image"><div class="direct-chat-text">'+message+'</div></div>';
                    }
                    //Right Chat
                    else{
                        chat_result += '<div class="direct-chat-msg right pull-right right-text"><div class="direct-chat-info clearfix"><span class="direct-chat-name pull-right">'+userName+'</span><span class="direct-chat-timestamp pull-right chat-time">'+dateTime+'</span></div><img class="direct-chat-img" src="<?php echo base_url("assets/dist/img/user3-128x128.jpg"); ?>" alt="Message User Image"><div class="direct-chat-text">'+message+'</div></div>';
                    }
                }
                $("#chatResult").html(chat_result);

                //Chat Member
                var member_userName = "", member_joined = "", memberType = "";
                for(var j = 0; j < response.all_member.length; j++){
                    member_userName = response.all_member[j].nama;
                    member_joined = response.all_member[j].joined_at;
                    memberType = response.all_member[j].user_type;

                    chat_member += '<li><a href="#"><img class="contacts-list-img" src="<?php echo base_url("assets/dist/img/user1-128x128.jpg"); ?>" alt="User Image"><div class="contacts-list-info"><span class="contacts-list-name">'+member_userName+'<small class="contacts-list-date pull-right">'+memberType+'</small></span><span class="contacts-list-msg">Has joined since '+member_joined+'</span></div></a></li>';
                }

                $("#chatMember").html(chat_member);
            }
            else{
                swal({
                    title : "Oops!",
                    text : response.msg,
                    type : "error"
                }, function(){
                    location.reload();
                });
            }
        },
        error : function(errorThrown, jqXHR, textStatus){
            swal({
                title : "Oops!",
                text : "Can't process your chatting",
                type : "error"
            });
        }
    });
}

function get_news_message(room_id){
    var totals = 0;
    $.ajax({
        type: "GET",
        url: "<?php echo base_url("news_message"); ?>",
        data: {room_id : room_id},
        dataType: "JSON",
        async:false,
        success: function (response) {
            totals = response.totals.total_new_message;
        }, 
        error : function(){
            totals = 0;
        }
    });
    // console.log(totals);
    return totals;
    // console.log(totals);
}
</script>
</body>
</html>
