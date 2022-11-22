


// This function sorts a non-paginated table that is named "MyTable"
// This function is no longer being used, since it is not compatible with pagination
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("myTable");
  switching = true;

  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      if (x.childElementCount !== 0) {
        x = x.firstElementChild;
        y = y.firstElementChild;
      }
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
    switchSort(n);
}

// Switches the sorting order for a non-paginated table that is named "MyTable"
// This is a helper function for the above function sortTable(n)
function switchSort(n) {
  var table = document.getElementById("myTable");
  var switchCol = "Col"+n;
    for (let i = 0; i < table.rows[0].cells.length; i++) {
    if(table.rows[0].cells[i].id === switchCol) {
        if(document.getElementById(switchCol).classList.contains("sorted-asc")) {
          document.getElementById(switchCol).className = "sorted-desc";
        }
        else {
          document.getElementById(switchCol).className = "sorted-asc";
        }
      }
      else if(table.rows[0].cells[i].id != "checkcolumn") {
        document.getElementById(table.rows[0].cells[i].id).className = "unsorted";
      }
    }
}

// Toggels all of the checkboxes in a given table
// This function is called by the master checkbox in the header of various lists
// (used in: site/tmpl/catalogc/default.php, site/tmpl/setc/default.php, site/tmpl/editproblem/default.php)
function toggleAll(tableName="myTable", toggleName="toggle") {
    var table = document.getElementById(tableName);
    var toggle = document.getElementsByClassName(toggleName)[0].checked;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length); i++) {
      // Start by saying there should be no switching:
      rows[i].getElementsByTagName("TD")[0].getElementsByTagName("input")[0].checked = toggle;
    }
 }