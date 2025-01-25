<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
  
    
    <table align = "center" border ="1">
    <?php 

        require 'establisDBconnection';

        // info about all employees 

        if (isset($_POST['submit']) && $_POST['submit'] == 'info4allEmployees') {

        $employees = $conn->prepare('SELECT ID , firstName , lastName , userName , managerID ,  dateOfEntry  , dateOfRetirement 
                                            FROM  employees' ); // im so dumb

        $employees->execute();

        $employees_res = $employees->get_result();

        $num = $employees_res->num_rows;

        while ($num){
            $num--;
            echo "<tr>";
            while ($project_row = $employees_res->fetch_assoc()) {
                echo "<td>" . $project_row['ID'] . "</td>";
                echo "<td>" . $project_row['firstName'] . "</td>";
                echo "<td>" . $project_row['lastName'] . "</td>";
                echo "<td>" . $project_row['userName'] . "</td>";
                echo "<td>" . $project_row['managerID'] . "</td>";
                echo "<td>" . $project_row['dateOfEntry'] . "</td>";
                echo "<td>" . $project_row['dateOfRetirement'] . "</td>";
            }
            echo "</td>";
        }

    }else if (isset($_POST['submit']) && $_POST['submit'] == 'info4allManagers') {
        $managers  = $conn->prepare('SELECT *
                                            FROM manager');     

        $managers->execute();

        $managers_res = $managers->get_result();

        $num = $managers_res->num_rows;

        while ($num){
            $num--;
            echo "<tr>";
            while ($project_row = $managers_res->fetch_assoc()) {
                echo "<td>" . $project_row['ID'] . "</td>";
                echo "<td>" . $project_row['firstName'] . "</td>";
                echo "<td>" . $project_row['lastName'] . "</td>";
                echo "<td>" . $project_row['userName'] . "</td>";
                echo "<td>" . $project_row['projectID'] . "</td>";
                echo "<td>" . $project_row['dateOfEntry'] . "</td>";
                echo "<td>" . $project_row['dateOfRetirement'] . "</td>";
            }
            echo "</td>";
        }
        
    }else if (isset($_POST["submit"]) && $_POST["submit"] == "info4Projects") {
        $projects = $conn->prepare("SELECT *
                                           FROM project");

        $projects->execute();
        $projects_res = $projects->get_result();
        $num = $projects_res->num_rows;
      
        while ($num){
            $num--;
            echo "<tr>";
            while ($project_row = $projects_res->fetch_assoc()) {
                echo "<td>" . $project_row['ID'] . "</td>";
                echo "<td>" . $project_row['projectName'] . "</td>";
                echo "<td>" . $project_row['dateCreated'] . "</td>";
                echo "<td>" . $project_row['deadline'] . "</td>";
                echo "<td>" . $project_row['projectDone'] . "</td>";
            }
            echo "</tr>";
        }

    }else if (isset($_POST["submit"]) && $_POST["submit"] == "inof4recourses") {
            $recourses = $conn->prepare("SELECT *
                                                FROM resources");
            $recourses->execute();  
            $recourses_res = $recourses->get_result();  
            $num = $recourses_res->num_rows;
            while ($numm){
                $num--;
                echo"<tr>";
                while ($recourse = $recourses_res->fetch_assoc()){
                    echo "<td>". $recourse["ID"] . "</td>";
                    echo "<td>". $recourse["type"] . "</td>";
                    echo "<td>". $recourse["quantity"] . "</td>";
                }
                echo "</tr>";
            }
    }else if (isset($_POST["submit"]) && $_POST["submit"] == "whereRecourseUsed") {
        $queuery = "SELECT *
                    FROM usedfor U , project P , resourcec R
                    where U.projectID = P.ID and 
                          U.resourceID = R.ID";
        $used= $conn->prepare($queuery);
        $used->execute();
        $used_res = $used->get_result();
        $num = $used_res->num_rows;

        while($num){
            $num--;
            echo "<tr>";
            while( $used_row = $used_res->fetch_assoc()){
                echo "<td>". $used_row["projectID"] . "</td>";
                echo "<td>". $used_row["projectName"] . "</td>";
                echo "<td>". $used_row["dateCreated"] . "</td>";
                echo "<td>". $used_row["deadline"] . "</td>";
                echo "<td>". $used_row["resourceID"] . "</td>";
                echo "<td>". $used_row["type"] . "</td>";
                echo "<td>". $used_row["quantityUsed"] . "</td>";
            }
            echo"</tr>";
        }

    }else if (isset($_POST["submit"]) && $_POST["submit"] == "info") {
// some other shit idk  mega gayy this shit is maga gayy 
    }
    ?>
    </table>
</body>
</html>