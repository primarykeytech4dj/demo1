<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

		<!-- <div id="footer">
			<p>login module for CodeIgniter with <a href="https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc" taget="_blank">MX extensions</a> by Wiredesignz | module by <a href="http://lewnelson.com">lewis nelson</a> | project on <a href="https://github.com/lewnelson/CI-hmvc-login-module">GitHub</a></p>
		</div>
	</body>
</html> -->
		
<!-- Content Wrapper. Contains page content -->
<!--    <div class="content-wrapper">
 <?php //$this->load->view($content); ?>
  </div>  -->
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> <?php echo custom_constants::current_version; ?>
    </div>
    <strong>Copyright &copy; <?php echo (date('m')>3)?date('Y').' - '.date('Y',strtotime('+1 Year')):date('Y',strtotime('-1 Year')).' - '.date('Y'); ?> <a href="http://primarykey.in" target="_new">Primary Key Technologies</a>.</strong> All rights
    reserved.
  </footer>
  
<!-- Modal -->
  <div id="modal-handler"></div>
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
            <!-- <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal-default">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->
</div>

<!-- ./wrapper -->

<!-- Page script -->
<script src="<?php echo assets_url(); ?>js/core.js"></script>

<script src="<?php echo assets_url(); ?>js/ajs.js"></script>
<script>
$(document).on('ready', function(){
  init();
  //$(".select2-container").hide();
});
function exportToExcel(table, name){
   
     var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
        //creating a temporary HTML link element (they support setting file names)
        var a = document.createElement("a");
        //getting data from our div that contains the HTML table
        var data_type = "data:application/vnd.ms-excel";
        var table_div = document.getElementById("dvData");
        var table_html = table_div.outerHTML.replace(/ /g, "%20");
        //table_html.types.date.assert = function(v){return false;};
        a.href = data_type + ", " + table_html;
        //setting the file name
        a.download = name + ".xls";
        //triggering the function
        a.click();
        //just in case, prevent default behaviour
        //e.preventDefault();
}
</script>
<script type="text/javascript">
        $(document).ready(function() {
            $('#columnsearch tfoot th').each( function () {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search" class="form-control" />');
            });

            // DataTable
            var table = $('#columnsearch').DataTable();

            // Apply the search
            table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if (that.search() !== this.value) {
                        that
                        .search(this.value)
                        .draw();
                    }
                });
            });
        });
        
        $(document).on('click', '.checkAll', function(event){

            var dataId = $(this).attr('data-id');
            var id=this.id;
            console.log(dataId);
            //console.log(this.id);
            if($(this).is(":checked")){

              $(".checkSite_"+dataId).prop('checked', true);
            }else{

              $(".checkSite_"+dataId).prop('checked', false);
            }
          /*$("checkSite_"+dataId).change(function (event) {
            event.preventDefault();
          });*/
          });
        
    </script>
    <script type="text/javascript">
      

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
</body>
</html>