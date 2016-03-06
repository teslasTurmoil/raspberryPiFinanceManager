//  sortTable(f,n)
//  f : 1 ascending order, -1 descending order
//  n : n-th child(<td>) of <tr>
function sortTable(tableid, sortOrder ,columnNumber){
  var rows = $('#' + tableid +' tbody  tr').get();
  rows.sort(function(a, b) {

    // get the text of n-th <td> of <tr> 
    var A = $(a).children('td').eq(columnNumber).text().toUpperCase();
    var B = $(b).children('td').eq(columnNumber).text().toUpperCase();
    if(A < B) {
     return -1*sortOrder;
    }
    if(A > B) {
     return 1*sortOrder;
    }
    return 0;
  });

  $.each(rows, function(index, row) {
    $('#'+tableid).children('tbody').append(row);
  });
}

//goes on the main page
var f_sl = 1; // flag to toggle the sorting order
var f_nm = 1; // flag to toggle the sorting order
$("#sl").click(function(){
    f_sl *= -1; // toggle the sorting order
    var n = $(this).prevAll().length;
    sortTable(f_sl,n);
});
$("#nm").click(function(){
    f_nm *= -1; // toggle the sorting order
    var n = $(this).prevAll().length;
    sortTable(f_nm,n);
});


