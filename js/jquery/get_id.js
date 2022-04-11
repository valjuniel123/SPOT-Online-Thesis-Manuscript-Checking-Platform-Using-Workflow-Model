var deleteData = function(id){
    $.ajax({    
        type: "GET",
        url: "controller_accounts.php", 
        data:{deleteId:id},            
        dataType: "html",                  
        success: function(data){   
            $('#msg').html(data);
            $('#dataTables').load('fetch-data.php');
            }
    });
};

$(".testing").click(function() {
var $row = $(this).closest("tr");    // Find the row
var $text = $row.find(".nr").text(); // Find the text

alert($text);
});
