function init_table() {

  const table = document.querySelector(".biblio-table");
  if (table.classList.contains("init")){
    return
  }
  table.querySelector("thead")?.addEventListener('click', function (e) {
    function findElementRecursive(element, tag) {
      return element.nodeName === tag ? element :
        findElementRecursive(element.parentNode, tag)
    }
    var descending_th_class = 'dir-d'
    var ascending_th_class = 'dir-u'
    var element = findElementRecursive(e.target, 'TH')
    var tr = findElementRecursive(element, 'TR')
    var table = findElementRecursive(tr, 'TABLE')

    function reClassify(element, dir) {
      console.log(dir)
      // element.className = element.className.replace(regex_dir, '') + dir
      element.classList.remove(descending_th_class)
      element.classList.remove(ascending_th_class)
      if (dir !== '') {
        element.classList.add(dir)
      }
    }
    function getValue(element) {
      return element.getAttribute('data-sort') || element.innerText;
    }

    var column_index
    var nodes = tr.cells
    for (var i = 0; i < nodes.length; i++) {
      if (nodes[i] === element) {
        column_index = element.getAttribute('data-sort-col') || i
        console.log("ici")
      } else {
        console.log("là ?")
        
        reClassify(nodes[i], '')
      }
    }
    var dir = descending_th_class
    if (element.classList.contains(descending_th_class)) {
      dir = ascending_th_class
    }
    reClassify(element, dir)
    var org_tbody = table.tBodies[0]
    var rows = [].slice.call(org_tbody.rows, 0)
    var reverse = dir === ascending_th_class
    rows.sort(function (a, b) {
      var x = getValue((reverse ? a : b).cells[column_index])
      var y = getValue((reverse ? b : a).cells[column_index])
      return isNaN(x - y) ? x.localeCompare(y) : x - y
    })
    var clone_tbody = org_tbody.cloneNode()
    while (rows.length) {
      clone_tbody.appendChild(rows.splice(0, 1)[0])
    }
    table.replaceChild(clone_tbody, org_tbody)
  });

  // table.querySelectorAll("button").forEach((e)=>{
  //   e.addEventListener("click",(ev)=>{

  //   })
  // })

  document.querySelector(".table-search")?.addEventListener("keyup", (e) => {
    const filter = e.currentTarget.value.toUpperCase();
    const tr = table.querySelectorAll("tbody tr");

    // Loop through all table rows, and hide those who don't match the search query
    for (var i = 0; i < tr.length; i++) {
      const txtValue = tr[i].textContent || tr[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  })

  table.classList.add("init")
}

init_table();
