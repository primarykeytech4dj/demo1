$(function() {
    $(".example1").DataTable();
    $('.example2').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true
    });
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
    $('#reservationtime').daterangepicker({timePicker: true, timePickerIncrement: 30, format: 'MM/DD/YYYY h:mm A'});
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
    //alert($('textarea').hasClass('textarea'));
    //if($('textarea').hasClass('.textarea')){
      //alert("hello");
      $(".textarea").each(function() {
        var id = this.id;
        //CKEDITOR.replace(id);
        var html = $("#"+id).val();
        //console.log(html);
        CKEDITOR.replace(id);
        CKEDITOR.instances[id].setData(html);
       /* $(document).on("submit", "#product_categories", function(){
          $(".description").val(CKEDITOR.instances.description.getData());
          $(".description").show();
        });
        alert(id);*/
      });

      $('form').on('submit', function(){
        $(".textarea").each(function() {
          var id = this.id;
          $("#"+id).val(CKEDITOR.instances[id].getData());
          
        });
        
      });
    
  //core.init();
  $('.hideIt').addClass('hide');

  $(".slugify").on('onkeyup blur', function(){
  	/*
  	Added Date 14th April, 2018
	Last Updated on : 14th April, 2018 
  	*/
  	var slugId = this.id;
  	str = $('#'+slugId).val();
  	str = str.replace(/\s+/g, '-').toLowerCase();
  	
  	$('#'+slugId).val(str);
  });
});
$(document).on('ready', function(){
  //$(".select2-container").hide();
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
        //trlast.css.style('display', 'block');
        //console.log("reached here");
        //console.log($(this).attr('data-target'));
        //console.log(trlast);
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
            //console.log("reached in view input");
            if (typeof datatarget !== typeof undefined && datatarget !== false) {
            
              datatarget = datatarget.substring(0, datatarget.length-1) + newId;
              $(this).attr('data-target', datatarget);
            }
            //console.log(datatarget);
            var inputDataAttr = $(this).attr('input-data-target');

            if (typeof inputDataAttr !== typeof undefined && inputDataAttr !== false) {
              inputdatatarget = inputDataAttr.substring(0, inputDataAttr.length-1) + newId;
              $(this).attr('input-data-target', inputdatatarget);
            }

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
          $(this).attr('name', newName);
          if($(this).hasClass('nochange')){
            console.log(newName);
            console.log("has no change class");
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

    $(document).on('change click', '.filter', function(event) {
      
        event.preventDefault();
        //console.log("hii");
        var elementId = $(this).attr('id');
        var datalink = $('#'+elementId).attr('data-link');
        var datatarget = $('#'+elementId).attr('data-target');
        //console.log(datatarget);
        var value = $('#'+elementId).val();
        console.log(value);
        $.ajax({
          type: 'POST',
          dataType: 'json',
          url : base_url+datalink,
          data: 'params='+value,
          success: function(response) {
            console.log(response);
            $("#"+datatarget).select2('destroy').empty().select2({data : response});
            //$(".select2-container").hide();
          }
        });
    });



  $(document).on('click', '.removebutton', function(event) {
      //console.log("hii");
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

  $(document).on('change', '.viewInput', function(){
    //console.log(this.id);
    var id = this.id;
    var val = $('#'+id).val();
    console.log($('#'+id).val());
    var targetInput = $('#'+id).attr('input-data-target');
    console.log(targetInput);
    if(val==0){
      $('#'+targetInput).show("slow");
    }else{
      console.log(targetInput);
      $('#'+targetInput).val("");
      $('#'+targetInput).hide("slow");

    }


  });

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