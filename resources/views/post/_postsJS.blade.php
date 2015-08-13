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

      $.ajax({
        type: 'POST',
        url: "/post/search",
        data: {PageID: ID, Since: dateFrom, Until: dateUntil, Qty: result},
        dataType: 'json',
        success: function(data) {
          var content = data;
          var content = '';
          content += "<table id='table-result' class='table table-hover header-fixed'>";
          content += "<thead>";
          content += "<tr>";
          content += "<th class='col-sm-1'>#</th>";
          content += "<th class='col-sm-3'>ID</th>";
          content += "<th class='col-sm-6'>Post</th>";
          content += "<th class='col-sm-2'>Created time</th>";
          content += "</tr>";
          content += "</thead>";
          content += "<tbody class='result-body'>";
          $.each(data, function(index,val){
            content += "<tr>";
            content += "<td class='col-sm-1'>"+(index+1)+"</td>";
            content += "<td class='col-sm-3'><a href='post/"+ID+"/"+val['id']+"'>"+val['id']+"</a></td>";
            content += "<td class='col-sm-6'>"+val['message']+"</td>";
            content += "<td class='col-sm-2'>"+val['created_time']+"</td>";
            content += "</tr>";
          });
          content += "</tbody>";
          content += "</table>";
          $('#page-result').html(content);
        },
        error: function(error){
          // alert("Comments retrieval request failed");
          $('#page-result').html('<i class="fa fa-exclamation-circle fa-5x"></i> Error: Page not found, make sure the page id entered is valid.');
          }
      });
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
