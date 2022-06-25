
function init(){
  //console.log("hii");
  if ( $.fn.dataTable.isDataTable( '.example1' ) ) {
    $(".example1").DataTable();
  }

  if ( $.fn.dataTable.isDataTable( '.example2' ) ) {

  }
  
  if($(".example2").dataTable.isDataTable()==false){
    $('.example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "columnDefs": [{
      "defaultContent": "-",
      "targets": "_all"
      }]
    });
  }else{
    console.log("not a data table");
  }
  if($(".exporttoexcel").dataTable.isDataTable()==false){
    $('.exporttoexcel').DataTable({
      "paging": false,
      //"lengthChange": false,
      "searching": true,
      "ordering": false,
      "info": false,
      //"autoWidth": false,
      "columnDefs": [{
      "defaultContent": "-",
      "targets": "_all"
      }]
    });
  }else{
    console.log("not a data table");
  }
  //Initialize Select2 Elements
  $(".select2").select2();


  //Datemask dd/mm/yyyy
  $(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
  //Datemask2 mm/dd/yyyy
  $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
  //Money Euro
  $("[data-mask]").inputmask();

  //Date range picker
  $('#reservation').daterangepicker({format: 'DD/MM/YYYY'});
  //Date range picker with time picker
  $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'DD/MM/YYYY h:mm A'});
  /*$('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })*/
  //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );
    //console.log("reached here");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,
      format: 'dd/mm/yyyy'
    });

    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });

    //Colorpicker
    $(".my-colorpicker1").colorpicker();
    //color picker with addon
    $(".my-colorpicker2").colorpicker();

    //Timepicker
    $(".timepicker").timepicker({
      showInputs: false
    });
  
    $(".textarea").each(function() {
      var id = this.id;
      var html = $("#"+id).val();
      CKEDITOR.replace(id);
      CKEDITOR.instances[id].setData(html);
    });
  $('.hideIt').addClass('hide');
  $('.close').on('click', function(e){
    var closeId = this.id;
    var id = $("#"+closeId).closest('div .modal').attr('id');
    $("#"+id).modal('hide');
  });

  $(document).on('ready', function(){
    $(".select2-container").hide();
  });
// Add more for colleges aluminis
    var  count = 0;
    var val1;
    var removeselectclass;
    $('.AddMoreRow').on('click', function(){
        addMoreId = this.id;
        //console.log(addMoreId);
        var appendTo = $("#"+addMoreId).closest('tfoot').closest('table').find('tbody');
        var tr = $("#"+addMoreId).closest('tfoot').closest('table').find('tbody tr:last');
        //console.log(tr);
        if(tr.find('select').hasClass('select2')){
          tr.find('select').select2('destroy');
        }
        //get id of last TR before appending new row
        var lastTrId = tr.attr('id');
        //console.log(lastTrId);
        newId = ++lastTrId;
        //console.log(newId);
        var trlast = tr.clone().appendTo(appendTo);
       
        trlast.attr('id', newId);
        trlast.find('input, select, textarea').each(function() {
          //var idLength = (newId.toString().length)+1;
          //console.log(idLength);
          if($(this).hasClass('filter')){
            var datatarget = $(this).attr('data-target');
            //console.log(datatarget);
            datatarget = datatarget.substring(0, datatarget.length-1) + newId;
            //console.log(datatarget);
            $(this).attr('data-target', datatarget);
          }

          if($(this).hasClass('viewInput')){
            var datatarget = $(this).attr('data-target');
            //console.log(datatarget);
            datatarget = datatarget.substring(0, datatarget.length-1) + newId;
            //console.log(datatarget);
            $(this).attr('data-target', datatarget);
          }

          if($(this).hasClass('datepicker')){
            $('.datepicker').datepicker({
              autoclose: true,
              format: 'dd/mm/yyyy'
            });
          }
          if($(this).hasClass('datemask')){
            $(".datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
          }
          //Datemask2 mm/dd/yyyy
          if($(this).hasClass('datemask2'))
            $(".datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
          
          var id = $(this).attr('id');
          //console.log("new id="+id);
          id = id.substring(0, id.length-1) + newId;
          $(this).attr('id', id);
          oldName = $(this).attr('name');
          var name = oldName.split('[');
          newName = '';
          $(name).each(function(key, val) {
            //console.log(key+" "+val);
            if(key==0){
              newName = newName+val;
            }
            else if(key==1){
              newName = newName+"["+newId+"]";
            }else{
              newName = newName+"["+val;
            }
          });
          $(".select2-container").hide();
          $(this).attr('name', newName);
          if($(this).hasClass('nochange')){
            //console.log(newName);
            //console.log("has no change class");
          }else{
            $(this).val('');
          }
          
        });
        //console.log(trlast);
        //trlast.find('input, textarea, select').val('');//empty the value of new appended row
        trlast.attr('id', newId);//increament id of appended row
        trlast.find('td:last').html("<a href='#' data-tr='"+newId+"' class='removebutton'><span class='glyphicon glyphicon-remove'></span></a>");
        //console.log(trlast.find('last:td').html('hiii'));
        $(".select2").select2();
    });

    



  $(document).on('click', '.removebutton', function() {
    event.preventDefault();
        var trId = $(this).closest('tr').attr('id');
        //console.log(trId);
        //var counttr = $(this).closest('table').find('tr').length;
        //console.log(counttr);
       // var trlength = trId.length;
        var dataId = $(this).attr('data-id');
        if (typeof dataId !== typeof undefined && dataId !== false) {
          var datalink = $(this).attr('data-link');
          var datatable = $(this).attr('data-table');
          console.log(datalink);
          $.ajax({
            type: 'POST',
            url: base_url+datalink,
            dataType: 'json',
            data: {'id':dataId, 'is_active':false, 'table':datatable},
            success:function(response) {
              console.log(response);
              if(response.status == 'success')
                $(this).html('<span class="glyphicon glyphicon-ok alert-success"></span>');
              else
                return false;
            } 
          });
        }
        //$(this).closest('tr').fadeout();
        $(this).closest('tr').remove();
   });

  $(document).on('click', '.showdiv', function() {
    var elementId = $(this).attr('id');
    //console.log(elementId);
    var targetToShow = $('#'+elementId).attr('data-show');
    //console.log(targetToShow);
    if(($('#' + elementId).is(":checked"))){

      $('#'+targetToShow).removeClass("hide");
    }else{
      $('#'+targetToShow).addClass("hide");
    }
  });
  
  $(document).on('click', '.SingleCheck', function() {
    var elementId = $(this).attr('id');
    var className = $(this).attr('class');
    $(".SingleCheck").attr('checked', false);
    //console.log(elementId);
    $("#" + elementId).prop('checked', true);    
    //var className = elementId.attr('class');
    //console.log(className);
    
  });

  $(document).on('change', '.prodDetail', function(){
    //alert($(this).val());
    var productId = $(this).val();
    var trId = $(this).closest('tr').attr('id');
    $.ajax({
          type: 'POST',
          dataType: 'json',
          url : base_url+'products/get_product_detail_ajax/'+productId,
          data: 'productId = '+productId,
          success: function(response) {
            //console.log(response);
            $('#uom_'+trId).val('nos');
            $('#qty_'+trId).val('1');
            if(null!==response.base_price)
              $('#unit_price_'+trId).val(response.base_price);
            else
              $('#unit_price_'+trId).val('0.00');
            if(null!==response.gst)
              $('#tax_'+trId).val(response.gst);
            else
              $('#tax_'+trId).val('0');
            calculateTotal(trId);
            //$('#total_'+trId).val('1');
          }
        
        });
  });

  function calculateTotal(trId){
    var qty = $('#qty_'+trId).val();
    var price = $('#unit_price_'+trId).val();
    var tax = $('#tax_'+trId).val();
    var total = (qty*price)+((tax/100.00)*(qty*price));
    $('#total_'+trId).val(total);
    calculateGrandTotal();

  }

  $(document).on('onkeyup change', '.calculate', function(){
    //alert(this.id);
    var id = this.id;
    var trId = $("#"+id).closest('tr').attr('id');
    //alert(trId);
    calculateTotal(trId);
  });

  function calculateGrandTotal(){
    //var tr = $('.producttotal').;
    var grandTotal = 0;
    $(".producttotal").each(function() {
      var val = 0;

      if(''!=($(this).val()))
        val = parseInt($(this).val());
      else
        val = 0.00;

      grandTotal = parseFloat(grandTotal)+parseFloat(val);
    });
      //alert(grandTotal);

    $('.grandTotal').html(grandTotal);

  }

}

$('body').on('click', '.load-ajax', function(e){
    console.log("hii");
        
  e.preventDefault();
  var dataPath =  $(this).attr('data-path');
  var callId = $(this).attr('data-id');
  var dataRefillTarget = '';
  if($(this).attr('data-refill-target')!='undefined'){
    var formId = $(this).closest('form').attr('id');
    dataRefillTarget = formId+"|"+$(this).attr('data-refill-target');
  }
  var modelSize = '';
  if($(this).attr('data-model-size')!='undefined'){
    modelSize = $(this).attr('data-model-size');
  }
  var modalTitle = '';
  if($(this).attr('data-modal-title')!=='undefined'){
    modalTitle = $(this).attr('data-modal-title');
  }
  var modalId = "modal-"+new Date().getTime();
  console.log(modalId);
  console.log("reached in load-ajax");
  //console.log(modelSize);
  $.ajax({
    type: 'GET',
    dataType: 'html',
    url : base_url+dataPath,
    success: function(response) {
      //console.log(response);
      //$("#modal-handler").html('');
      $("#modal-handler").append('<div class="modal" id="'+modalId+'" data-refill-target="'+dataRefillTarget+'"><div class="modal-dialog '+modelSize+'"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal-default" aria-label="Close" id="close_'+modalId+'"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'+modalTitle+'</h4></div><div class="modal-body" style="overflow-y: auto;">'+response+'</div><div class="modal-footer" id="response_'+modalId+'"></div></div></div></div>');
      init();
      $("#"+modalId).modal();
    }
  });
  
})

      $('.content').on('click', '.delete-address', function(e){
        //alert('hiii');
        
        var id = $(this).attr('data-id');
        var dataPath = $(this).attr('data-path');
        var dataTable = $(this).attr('data-table');
        console.log(base_url);
        //console.log(addressId);
        $.ajax({
          type: 'GET',
          dataType: 'html',
          url : base_url+dataPath+'/'+id,
          success: function(response) {
            //console.log(response);
            window.location.replace(base_url);
            
            //$(".select2-container").hide();
          }
        });

      });

      $(document).on('click', '.checkAll', function(event){

        var dataId = $(this).attr('data-id');
        var id=this.id;
        console.log(dataId);
        //console.log(this.id);
        if($(this).is(":checked")){

          $(".check_"+dataId).prop('checked', true);
        }else{

          $(".check_"+dataId).prop('checked', false);
        }
      /*$("checkSite_"+dataId).change(function (event) {
        event.preventDefault();
      });*/
      });

  
$(document).on('submit','.submit-ajax', function(e){
  var formId = this.id;
  //console.log(formId);
  var action = $("#"+formId).attr('action');
  var formdata = new FormData($("#"+formId)[0]);
  //console.log(formdata);
      $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: action,
        data: formdata,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 600000,
        success: function (data) {

            console.log(data);
            var data = $.parseJSON(data);
            //console.log(data);
            //return false;
            if(data.status==0){
              $("#"+formId+" .response").addClass('alert alert-danger').html(data.msg);
                //$('#newpost').trigger("reset");
            }else{
              $("#"+formId+" .response").removeClass('alert alert-danger').addClass('alert alert-success').html(data.msg);
              var modalId = $("#"+formId).closest('.modal').attr('id');
              //console.log(modalId);
              if($("#"+modalId).attr('data-refill-target')!='undefined'){
                var targetInput = $("#"+modalId).attr('data-refill-target');
                var inp = targetInput.split('|');
                //console.log(targetInput);
                //var newOption = new Option(data.text, data.id, false, false);
                //console.log(data.value);
                $("#"+inp[0]+" #"+inp[1]).select2('destroy').empty().select2({data : data.value}).trigger('change');
                $("#"+modalId).modal('hide');
              }
            }
        }
    });

  return false;
});

/*$(document).ajaxSuccess(function( event, request, settings ) {
  init();
});*/

$(document).on('change click', '.filter', function(event) {
        event.preventDefault();
        var elementId = $(this).attr('id');
        console.log(elementId);
        var formId = $(this).closest("form").attr('id');
        console.log(formId);
        var datalink = $('#'+formId+' #'+elementId).attr('data-link');
        var datatarget = $('#'+formId+' #'+elementId).attr('data-target');
        var value = $('#'+formId+' #'+elementId).val();
        console.log(datatarget);
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url : base_url+datalink,
          data: 'params='+value,
          success: function(response) {
            console.log(response);
            $('#'+formId+' #'+datatarget).select2('destroy').empty().select2({data : response});
          }
        
        });
    });
$(document).on('change', '.viewInput', function(){
    //console.log(this.id);
    var formId = $(this).closest('form').attr('id');
    console.log(formId);
    var id = this.id;
    var val = $('#'+formId+' #'+id).val();
    //console.log(val);
    var targetInput = $('#'+formId+' #'+id).attr('input-data-target');
    //console.log(targetInput);
    if(val==0){
      $('#'+formId+' #'+targetInput).show("slow");
    }else{
      console.log(targetInput);
      $('#'+formId+' #'+targetInput).val("");
      $('#'+formId+' #'+targetInput).hide("slow");

    }


  });

$(document).on('click', '.ajax-request', function(){
  r = confirm("Do you really want to Appove this traveller??");
  if(r==true){

  }else{
    
  }
  var id = this.id;
  var msg = $("#"+id).attr('data-msg');
})

/*$("#btnExport").click(function (e) {
    window.open('data:application/vnd.ms-excel,' + $('.exporttoexcel').html());
    e.preventDefault();
});*/
$("#btnExport").click(function(e) {

    $('.hidefromdownload').hide();
    var a = document.createElement('a');
    //getting data from our div that contains the HTML table
    var data_type = 'data:application/vnd.ms-excel';
    var table_div = document.getElementById('exporttoexcel');
    var table_html = table_div.outerHTML.replace(/ /g, '%20');
    a.href = data_type + ', ' + table_html;
    //setting the file name
    a.download = 'download.xls';
    //triggering the function
    a.click();
    //just in case, prevent default behaviour
    e.preventDefault();
    $('.hidefromdownload').show();
});
