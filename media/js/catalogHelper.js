
// This function sorts a non-paginated table with the given name
// This function is used on the problemdetails and editproblem pages
function sortTable(n, tableName) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById(tableName);
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
      // This ensures we get the content of a link rather than the link itself
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
}

// Toggels all of the checkboxes in a given table
// This function is called by the master checkbox in the header of various lists
// (used in: site/tmpl/catalogc/default.php, site/tmpl/setc/default.php, site/tmpl/editproblem/default.php)
function toggleAll(tableName="myTable", toggleName="toggle") {
    var table = document.getElementById(tableName);
    // checks if we are checking or unchecking everything
    var toggle = document.getElementsByClassName(toggleName)[0].checked;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length); i++) {
      // set the state to match the checkAll box
      rows[i].getElementsByTagName("TD")[0].getElementsByTagName("input")[0].checked = toggle;
    }
 }

//This function activates a loading screen when a submit button is pressed
function onLoad() {
	document.getElementById("pageloader").style.display = "block";
}
//activates the operation panel
function operation() {
	var panel = document.getElementsByClassName("panel-box")[0];
	if (window.getComputedStyle(panel).display == "none") {
		document.getElementsByClassName("panel-box")[0].style.display = "block";
	}
	else {
		document.getElementsByClassName("panel-box")[0].style.display = "none";
	}
	
}

