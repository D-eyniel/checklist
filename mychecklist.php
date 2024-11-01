<?php
$servername = "localhost"; 
$username = "root"; 
$password = "melvin_13"; 
$database = "midterm"; 
$prof = "professors"; 
$subject = "subjects"; 
$student_info = "student_info"; 
$common = "Course_Code"; 
$column1 = "Year_Taken"; 
$column2 = "Semester_Taken"; 
$midyear = "midyear";

$conn = new mysqli(hostname: $servername, username: $username, password: $password, database: $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT " . str_replace(search: '_', replace: ' ', subject: $prof) . ".*, " . str_replace(search: '_', replace: ' ', subject: $subject) . ".* 
        FROM " . $prof . " 
        INNER JOIN " . $subject . " 
        ON " . $prof . "." . $common . " = " . $subject . "." . $common . " 
        ORDER BY " . $column1 . ", " . $column2;


$midyear_query = "SELECT * 
                  FROM $prof
                  INNER JOIN $midyear
                  ON $prof.$common = $midyear.$common";

$midyear_result = $conn->query(query: $midyear_query);

$result = $conn->query(query: $sql);

$rows = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}

$student_query = "SELECT * FROM " . $student_info;
$student_result = $conn->query($student_query);
$student_row = $student_result->fetch_assoc();

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checklist</title>
<style>
  body {
    position: absolute;
    font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
    padding: 0;
  }

  h2 {
    text-align: center;
    margin-top: 20px;
    color: #333; 
    font-family: 'Courier New', Courier, monospace;
  }

  h4 {
    margin-left: 20px;
    color: #555; 
  }

  #searchForm {
    margin-left: 65px;
    margin-bottom: 20px;
  }

  #searchInput {
    padding: 8px;
    border: 1px solid #ccc; 
    border-radius: 4px;
    margin-right: 5px;
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
  }

  #searchInput:focus {
    outline: none;
    border-color: grey; 
  }

  #searchBtn {
    padding: 8px 12px;
    border: 1px solid #555; 
    background-color: #555; 
    color: #fff; 
    border-radius: 4px;
    cursor: pointer;
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
  }

  #searchBtn:hover {
    background-color: gray; 
  }

  select {
    width: 150px; 
    height: 30px; 
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
  }

  select::-ms-expand {
    display: none;
  }

  option {
    padding: 5px;
    font-size: 14px;
    font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
  }

  .data-table, .midyear-table {
    border-collapse: collapse;
    border: 1px solid #ddd; 
    width: 90%;
    margin: 20px auto;
    background-color: #fff; 
  }

  .data-table th, .data-table td, .midyear-table th, .midyear-table td {
    border: 1px solid #ddd; 
    padding: 8px;
    text-align: left;
  }

  .data-table th, .midyear-table th {
    background-color: #555; 
    color: #fff; 
    font-family: 'Times New Roman', Times, serif;
  }

  .semester-row {
    background-color: #f2f2f2; 
    font-weight: bold;
    margin: 20px auto;
    height: 30px; 
    line-height: 45px;
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  }

  .student-info {
    border-collapse: collapse;
    border: 1px solid #ddd; 
    width: 90%;
    margin: 20px auto;
    background-color: #fff; 
  }

  .student-info th, .student-info td {
    border: 1px solid #ddd; 
    padding: 8px;
    text-align: left;
  }

  .student-info th {
    background-color: #555; 
    color: #fff; 
    font-family: 'Times New Roman', Times, serif;
  }

  .top-left-image {
    position: absolute;
    top: 40px;
    left: 70px;
    margin: 10px;
    width: 140px; 
    height: 120px; 
  }

  .top-right-image {
    position: absolute;
    top: 40px;
    right: 70px;
    margin: 10px;
    width: 115px; 
    height: 120px; 
  }
</style>
</head>
<body>
<img src="cvsu.png" class="top-left-image">
<img src="idpic.jpg" class="top-right-image">
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<h2>CHECKLIST OF COURSES</h2>
<br>
<br>
<form id="searchForm">
  <input type="text" id="searchInput" placeholder="Search...">
  <select id="columnSelect">
    <option value="">Checklist</option>
    <option value="Student_Name">Student Name</option>
    <option value="Student_ID">Student ID</option>
    <?php
    $columns = array_keys($rows[0]);
    $columnsToExclude = ['Credit_Unit_Lec', 'Credit_Unit_Lab', 'Contact_Hours_Lec', 'Contact_Hours_Lab', 'Pre_Requisite', 'Final_Grade'];
    foreach ($columns as $column) {
        if (!in_array($column, $columnsToExclude)) {
            echo "<option value='" . $column . "'>" . str_replace('_', ' ', $column) . "</option>";
          }
      }
      ?>
    </select>
    <button type="button" id="searchBtn">Search</button> 
  </form>
  
  <table class="student-info">
    <tr>
      <th>Name of Student</th>
      <th>Student ID</th>
      <th>Contact Number</th>
      <th>Address</th>
    </tr>
    <tr>
      <td><?php echo $student_row['Name_of_Student']; ?></td>
      <td><?php echo $student_row['Student_ID']; ?></td>
      <td><?php echo $student_row['Contact_Number']; ?></td>
      <td><?php echo $student_row['Address']; ?></td>
    </tr>
  </table>
  
  <table class="data-table" id="dataTable">
    <tr>
      <?php
      if (!empty($rows)) {
          foreach ($rows[0] as $key => $value) {
              echo "<th>" . str_replace('_', ' ', $key) . "</th>";
          }
      } else {
          echo "<th>No data found</th>";
      } 
      ?>
    </tr>
    <?php
    if (!empty($rows)) {
        $rowIndex = 0;
        $firstSemesterCount = 0;
        $totalRows = count($rows); 
        foreach ($rows as $row) {
            if ($rowIndex == $totalRows - 1) {
                break;
            }
            if ($rowIndex == 47) {
                echo '<tr class="semester-row"><td colspan="' . count($row) . '">Mid Year Courses</td></tr>';
                
                if ($midyear_result->num_rows > 0) {
                    while ($midyear_row = $midyear_result->fetch_assoc()) {
                        echo "<tr>";
                        foreach ($midyear_row as $midyear_value) {
                            echo "<td>" . $midyear_value . "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='" . count($row) . "'>No data found</td></tr>";
                }
            }
            if ($rowIndex == 0 || $rowIndex == 10 || $rowIndex == 17 || $rowIndex == 25 || $rowIndex == 33 || $rowIndex == 40 || $rowIndex == 47) {
              $semester = $firstSemesterCount % 2 == 0 ? "First Semester" : "Second Semester";
              echo '<tr class="semester-row"><td colspan="' . count($row) . '">' . $semester . '</td></tr>';
              $firstSemesterCount++;
          }
            echo "<tr>";
            foreach ($row as $key => $value) {
                echo "<td data-column='" . $key . "'>" . $value . "</td>";
            }
            echo "</tr>";
            $rowIndex++;
        }
    }
    ?>
  </table>
  
  <script>
  document.getElementById("searchBtn").addEventListener("click", function(event) {
    event.preventDefault();
    var input = document.getElementById("searchInput").value.trim().toUpperCase();
    var table = document.getElementById("dataTable");
    var rows = table.getElementsByTagName("tr");
  
    var select = document.getElementById("columnSelect");
    var selectedColumn = select.value;
  
    for (var i = 1; i < rows.length; i++) {
      var row = rows[i];
      var cells = row.getElementsByTagName("td");
      var found = false;  
  
      for (var j = 0; j < cells.length; j++) {
        var cell = cells[j];
        if (selectedColumn && cell.getAttribute("data-column") === selectedColumn && cell.textContent.toUpperCase().indexOf(input) > -1) {
          found = true;
          break;
        } else if (!selectedColumn && cell.textContent.toUpperCase().indexOf(input) > -1) {
          found = true;
          break;
        }
      }
  
      if (found) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    }
  });
  </script>
  
  </body>
  </html>
  
