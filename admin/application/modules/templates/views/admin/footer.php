<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> <?php echo custom_constants::current_version; ?>
    </div>
    <strong>Copyright &copy; <?php echo (date('m')>3)?date('Y').' - '.date('Y',strtotime('+1 Year')):date('Y',strtotime('-1 Year')).' - '.date('Y'); ?> <a href="http://primarykey.in" target="_new">Primary Key Technologies</a>.</strong> All rights
    reserved.
    <?php //print_r($_COOKIE); ?>
    <div id="result"></div>
  </footer>
  <!-- Modal -->
  <div id="modal-handler">

  </div>
      <div class="modal" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal-default" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Default Modal</h4>
            </div>
            <div class="modal-body" style="overflow-y: scroll;">
              <p>One fine body&hellip;</p>
            </div>
            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
</div>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>
      <!-- /.tab-pane -->
      <!-- Stats tab content -->
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- Page script -->
<script src="<?php echo assets_url(); ?>js/core.js"></script>
<script src="<?php echo assets_url(); ?>js/ajs.js"></script>
<script type="text/javascript">
        //$("input[type='number']").inputSpinner();
        $(document).ready(function(){
            
            checkCookie();
           
        });
        
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function checkCookie() {
            //console.log("reached in check cookie");
            var user=getCookie("username");
            var pass=getCookie("password");
            console.log("user-"+user);
            if (user != "") {
                console.log("Welcome again " + user);
                $("#username").val(user);
                $("#password").val(pass);
                $("#submit").trigger('click')
            } /*else {
             user = prompt("Please enter your name:","");
                if (user != "" && user != null) {
                    setCookie("username", user, 30);
                }
            }*/
        }
      </script>
<script type="text/javascript">
    
  (function($){

    init();

  })(jQuery);
/*$(document).ready(function(){
  //alert("reached in footer");
  init();
  //$(".select2-container").hide();
});*/

</script>

<?php 
  if(isset($js)){
      //print_r($js);
      foreach ($js as $key => $jq) {
          # code...
          echo $jq;
      }
  }
   ?>
    <script>
      function showPosition(position) {
        lat = position.coords.latitude;
        lng = position.coords.longitude;
        // alert("hii");
        $.ajax({
           type: 'POST',
            url: base_url+'customers/post_geolocation',
            dataType: 'json',
            data: {'lat':lat, 'lng':lng},
            success:function(response) {
              console.log(response);
              if(response.status == 'success')
                $(this).html('<span class="glyphicon glyphicon-ok alert-success"></span>');
              else
                return false;
            } 
        });
       
        //alert("called after lat lng is set lat & lng = "+lat+" "+lng);
       

      }

      function getLocation(){
        if (navigator.geolocation) {
        //alert("hii");
          navigator.geolocation.getCurrentPosition(showPosition);

          //alert(lat+" "+lng);
        } else { 
        //x.innerHTML = "Geolocation is not supported by this browser.";
        }
      }

      
        const worker = new Worker("<?php echo assets_url(); ?>js/persistsession.js");

        worker.onmessage = e => {
          const message = e.data;
          console.log(`[From Worker]: ${message}`);
          getLocation();
          const reply = setTimeout(() => worker.postMessage(base_url+'login/persistSession'), 60000);
        };
        
        worker.postMessage(base_url+'login/persistSession');
    </script>
</body>
</html>