<!DOCTYPE html>
<html>
<head>
  <?php $this->load->view("templates/header"); ?>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>Chat</b>Kampus</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="#" method="post" enctype="multipart/form-data">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <div class="col-xs-7">
          <!-- <div class="checkbox icheck">
            <label>
              <input type="checkbox"> Remember Me
            </label>
          </div> -->
        </div>
        <!-- /.col -->
        <div class="col-xs-5">
          <button type="submit" class="btn btn-primary btn-block btn-flat" id="submitBtn">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

    <!-- <div class="social-auth-links text-center">
      <p>- OR -</p>
      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Sign in using
        Facebook</a>
      <a href="#" class="btn btn-block btn-social btn-google btn-flat"><i class="fa fa-google-plus"></i> Sign in using
        Google+</a>
    </div>
    
    <a href="#">I forgot my password</a><br>
    <a href="register.html" class="text-center">Register a new membership</a> -->

  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<?php $this->load->view("templates/js"); ?>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });

    $("form").submit(function (e) { 
        e.preventDefault();
        
        var form_data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url("login_act"); ?>",
            data: form_data,
            dataType: "JSON",
            beforeSend : function(){
                $("#submitBtn").text("Prosessing...");
            },
            success: function (response) {
                if(response.result){
                    swal({
                        title : "Success!",
                        text : response.msg,
                        type : "success",
                        timer : 2000,
                        showConfirmButton : false
                    }, function(){
                        location.reload();
                    });
                }
                else{
                    swal({
                        title : "Oops!",
                        text : response.msg,
                        type : "error"
                    });
                }
                console.log(response.pass_desc);
                $("form")[0].reset();    
                $("#submitBtn").text("Sign In");
            },
            error : function(){
                swal({
                    title : "Oops!",
                    text : "Can't proses your login session",
                    type : "error"
                });
                $("form")[0].reset();
                $("#submitBtn").text("Sign In");
            }
        });
    });
  });
</script>
</body>
</html>
