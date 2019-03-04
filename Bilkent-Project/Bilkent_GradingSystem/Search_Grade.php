<?php
require 'StuDB.php';
require 'util.php';
const MAX=100;
if(isset($_POST["btnSearch"])){
    extract($_POST,EXTR_PREFIX_ALL,"g"); 
    //var_dump($g_name);
    $g_maxgrade=!empty($g_maxgrade) ? $g_maxgrade:MAX;
    if(!empty($g_StudentID)){
        $result=searchById($g_StudentID);
        //var_dump($result);
       
    }
    else{
        $result= searchBy($g_name,$g_maxgrade);
        //var_dump($result);
    }
}
//var_dump(allName());

$names= allName();
$g_name= isset($g_name) ? $g_name :"";
$g_maxgrade= isset($g_maxgrade) && $g_maxgrade!==MAX ? $g_maxgrade :"";
$g_StudentID= isset($g_StudentID) ? $g_StudentID :"";
?>

<html>
    <head>
        <meta charset="UTF-8">
    <title>Bilkent Grading Scale</title>
    <style>
            body { font-family: arial;  background: url(1.jpg) no-repeat center center fixed; 
              -webkit-background-size: cover;
              -moz-background-size: cover;
              -o-background-size: cover;
              background-size: cover;}
            h1 { text-align: center; color:#fde0dc;text-shadow: 2px 2px 5px black;}
            table { margin:30px auto;}
            table [type="text"] { width: 200px;}
            td { padding: 5px 10px; text-align: center;}
            #resultTable { border-collapse: collapse;}
            #resultTable th {
                border-top: 1px solid black;
                border-bottom: 1px solid black;
                padding: 5px 20px;
                background:#cfd8dc;
            }
            [colspan='6'] { text-align: right; font-size: 0.8em;}
            #resultTable tr{ background:#fde0dc;}
            #resultTable tr:nth-child(2n+1) { background: #d0d9ff;}
     </style>
</head>
<body>
    <h1>Bilkent Grading Scale</h1>
    <form action="" method="post">
        <table>
            <tr>
                <td>
                    <input type="text" name="StudentID" placeholder="Student ID" value="<?=$g_StudentID?>">
                </td>
                <td>
                    <select name="name">
                        <?php
                        foreach ($names as $name){
                            if($name===$g_name){
                            echo "<option selected>",$name,"</option>";
                            }
                            else{
                                echo "<option>",$name,"</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="maxgrade" placeholder="Grade" value="<?=$g_maxgrade?>">
                </td>
                <td>
                    <button type="submit" name="btnSearch">&#8987;</button>
                </td>
            </tr>
        </table>
    </form>
    <?php if(isset($result)){ ?>
    <table id="resultTable">
            <tr>
                <th>NO</th>
                <th>ID Number</th>
                <th>Student Name</th>
                <th>Course Name</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
            <?php
            $i=0;
            foreach ($result as $student){
                echo "<tr>";
                echo '<td>'. ++$i . "</td>";
                echo '<td>'. $student["stuId"] . "</td>";
                echo '<td>'. strtoupper($student["name"]) . "</td>";
                echo '<td>'. $student["lesson"] . "</td>";
                echo '<td>'. $student["grade"] . "</td>";
                echo '<td>'. ($student["status"]===0 ? "&#10060" : "&#x2705") . "</td>";
                echo "</tr>";
            }
            
            ?>
        </table>
    
    <?php } ?>
</body>
</html>
