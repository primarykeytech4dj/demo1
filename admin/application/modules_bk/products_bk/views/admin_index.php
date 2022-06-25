
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Products
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Products</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Products</h3>
          <?php echo anchor(custom_constants::new_product_url, 'New Product', 'class="btn btn-primary pull-right"'); ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <!-- <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Product Category</th>
              <th>Product Type</th>
              <th>Product</th>
              <th>Product Code</th>
              <th>Slug</th>
              <th>Base Price</th>
              <th>Description</th>
              <th>Meta Title</th>
              <th>Meta Description</th>
              <th>Meta Keyword</th>
              <th>Is Sale|Is Gift|Is New</th>
              <th>Is Active</th>
              <th>Action</th>
              
            </tr>
            </thead>
            <tbody>
                <?php foreach($product_category as $key=> $v) {
                	//print_r($v);?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $v['category_name'];?></td>
              <td>
                <?php 
                if($v['product_type']==1){ 
                  echo 'Product';
                }elseif ($v['product_type']==2) {
                  echo 'Service';
                }elseif ($v['product_type']==3) {
                  echo 'Product & Service';
                }
                ?>
              </td>
              <td><?php echo $v['product'] ;?></td>
              <td><?php echo $v['product_code'] ;?></td>
              <td><?php echo $v['slug'] ;?></td>
              <td><?php echo $v['base_price'] ;?></td>
              <td><?php echo $v['description'] ;?></td>
              <td><?php echo $v['meta_title'] ;?></td>
              <td><?php echo $v['meta_description'] ;?></td>
              <td><?php echo $v['meta_keyword'] ;?></td>
             <td><?php echo $v['product'] ;?></td>
              <td><i class ="<?php echo ($v['is_sale']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';?>"></i><?php echo "|";?>
              	<i class="<?php echo ($v['is_gift']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';?>"></i><?php echo "|";?>
              	<i class="<?php echo ($v['is_new']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove';?>"></i>
              </td>

              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                  <li><?php echo anchor(custom_constants::admin_product_view.'/'.$v['id'], 'View', ['class'=>'']);  ?></li>
                   <li><?php echo anchor(custom_constants::edit_product_url."/".$v['id'], 'Edit', ['class'=>'']); ?></li>
                  
                  </ul>
                </div>
              </td>  
              
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
              	
	              <th>Sr No</th>
	              <th>Product Category</th>
	              <th>Product Type</th>
	              <th>Product</th>
	              <th>Product Code</th>
	              <th>Slug</th>
	              <th>Base Price</th>
	              <th>Description</th>
	              <th>Meta Title</th>
	              <th>Meta Description</th>
	              <th>Meta Keyword</th>
	              <th>Is Sale|Is Gift|Is New</th>
	              <th>Is Active</th>
	              <th>Action</th>
	             
              </tr>
            </tfoot>
          </table> -->
          <table id="ajaxLoader" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Sr No</th>
                <th>ID</th>
                <th>Product Category</th>
                <!-- <th>Product Type</th> -->
                <th>Image</th>
                <th>Product</th>
                <th>Product Code</th>
                <th>Slug</th>
                <th>Base Price</th>
                <th>Base UOM</th>
                <th>Variant Count</th>
                <th>Description</th>
                <th>Meta Title</th>
                <th>Meta Description</th>
                <th>Meta Keyword</th>
                <th>Is Sale|Is Gift|Is New</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
                  
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>ID</th>
                <th>Product Category</th>
                <!-- <th>Product Type</th> -->
                <th>Image</th>
                <th>Product</th>
                <th>Product Code</th>
                <th>Slug</th>
                <th>Base Price</th>
                <th>Base UOM</th>
                <th>Variant Count</th>
                <th>Description</th>
                <th>Meta Title</th>
                <th>Meta Description</th>
                <th>Meta Keyword</th>
                <th>Is Sale|Is Gift|Is New</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

<script type="text/javascript">
  $(document).ready(function(){
    $('#ajaxLoader').DataTable({
      'processing': true,
      'serverSide': true,
      'serverMethod': 'post',
      "pageLength": 25,
      "order": [[2, "desc"]],
      "lengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "All"]],
      'ajax': {
         'url':'<?=base_url()?>products/adminindex'
      },
      'columns': [
         { data: 'sr_no' },
         { data: 'id' },
         { data: 'category_name' },
         { data: 'image_name_1' },
         { data: 'product' },
         { data: 'product_code' },
         { data: 'slug' },
         { data: 'base_price' },
         { data: 'base_uom' },
         { data: 'variant_count' },
         { data: 'description' },
         { data: 'meta_title' },
         { data: 'meta_description' },
         { data: 'meta_keyword' },
         { data: 'is_sale' },
         { data: 'is_active' },
         { data: 'action' },
         
      ],
      "columnDefs": [
        {
            "targets": [ 1 ],
            "visible": false,
            "searchable": false
        }
    ]
    });
  });
</script>