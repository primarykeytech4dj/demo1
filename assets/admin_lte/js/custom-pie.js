  // <!--  pie chart  -->
   $(document).ready(function(){
        $(".piechart").each(function(index, e){
            
            var college_id = this.id;
            //console.log(college_id);
            $.ajax({
                url: "<?php echo base_url(); ?>colleges/setpie",
                type: 'POST',
                data: { 'college_id' : college_id },
                success: function(res) {
                    if(res!=false){
                        var ctx = $("#"+college_id).get(0).getContext("2d");
                        console.log(value);
                        var value = $.parseJSON(res);
                        var piechart = new Chart(ctx).Pie(value);
                        /*var myPieChart = new Chart(ctx,{
                            type: 'pie',
                            data: value
                        });*/
                    }
                }
            });
        });
   });