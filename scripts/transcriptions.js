function search() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchbar");
    filter = input.value.toUpperCase();
    table = document.getElementById("table");
    tr = table.getElementsByTagName("tr");
  
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
}

$('body').addClass('waiting');
$('body').removeClass('waiting');

// let button = document.getElementById('download') 

// button.addEventListener('click', function(e){
//   let downloadVal = document.getElementById('textarea').value

//   let filename = "output.pdf"

//   download (downloadVal, filename)
// })

// function download(downloadVal, filename){
//   let element = createElement('a')

//   element.setAttribute('href', 'data:test/plan;charset=utf-8' + encodeURIComponent(Blob))

//   element.setAttribute('downlaod', filename)
//   element.style.display = none
//   document.body.appendChild(element)
//   element.click()
//   document.body.removeChild(element)
// }


