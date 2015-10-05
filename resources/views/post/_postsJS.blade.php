<script>
$(function(){
  $("#result-qty").keyup(function () {
      this.value = this.value.replace(/[^0-9\.]/g,'');
      if(this.value > 99){
        this.value = 99;
      }
      // if(this.value < 1){
      // 	this.value = 1;
      // }
  });


  $("#datepicker-from").datepicker({
    maxDate: '0',
        numberOfMonths: 2,
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#datepicker-until").datepicker("option", "minDate", dt);
        }
  });

  $("#datepicker-until").datepicker({
    maxDate: '0',
        numberOfMonths: 2,
        onSelect: function(selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#datepicker-from").datepicker("option", "maxDate", dt);
        }
  });

  $("#datepicker-from").blur(function(){
        val = $(this).val();
        val1 = Date.parse(val);
        if (isNaN(val1)==true && val!==''){
           alert("Invalid date!")
           currentText: "Now"
        }
    });

  $("#btnSearch").click(function(){
    if ($("input#txtID").val() && $("input#datepicker-from").val() && $("input#datepicker-until").val()) {
      var ID = $("input#txtID").val();
      var dateFrom = $("input#datepicker-from").val();
      var dateUntil = $("input#datepicker-until").val();
      var result = $("input#result-qty").val();

      $.post("/post/search", {PageID: ID, Since: dateFrom, Until: dateUntil, Qty: result}, function(data, status){
        if(status == "success"){
          // alert(data);
          if(data == 'Not Found'){
            $('#page-result').html('<i class="fa fa-exclamation-circle fa-5x"></i> Error: Result not found.');
          }
          else{
            var content = '';
            content += "<table class='display' cellspacing='0' width='100%' id='post_table'>";
            content += "<thead>";
            content += "<tr>";
            content += "<th>#</th>";
            content += "<th>ID</th>";
            content += "<th>Post</th>";
            content += "<th>Created time</th>";
            content += "</tr>";
            content += "</thead>";
            content += "<tbody class='result-body'>";
            $.each(data, function(index,val){
              content += "<tr>";
              content += "<td>"+(index+1)+"</td>";
              content += "<td><a href='post/"+ID+"/"+val['id']+"' class='post_id'>"+val['id']+"</a></td>";
              content += "<td>"+val['message']+"</td>";
              content += "<td>"+val['created_time']+"</td>";
              content += "</tr>";
            });
            content += "</tbody>";
            content += "</table>";
            $('#page-result').html(content);

            var table = $('#post_table').DataTable({
              "scrollY"   : "500px",
              "scrollX"   : true,
                  "scrollCollapse": true,
                  "paging"    : false
            });
          }
        }
        else{
          $('#page-result').html('<i class="fa fa-exclamation-circle fa-5x"></i> Error: Page not found, make sure the page id entered is valid.');
        }
      }, "json");
    }
    else{
      alert('All field cannot be blanked...');
    }

  });

  $.ajaxSetup({
   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
  });

  $( document ).ajaxStart(function() {
      $( "#page-result" ).html( "<i class='fa fa-cog fa-5x fa-spin'></i> Retrieving post from page..." );
  });
  
});


</script>
