<?php
require 'db_tables.php';
$stu_id = isset($_GET['id']) ? $_GET['id'] : "";
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$letter_mapping = ["F" => 0, "D" => "1", "D+" => "1.3", "C-" => 1.7, "C" => 2, "C+" => 2.3, "B-" => 2.7, "B" => 3, "B+" => 3.3, "A-" => 3.7, "A" => 4];
$sort = isset($_GET['sort']) ? $_GET['sort'] : 0;

if (!preg_match('/^2\d{7}$/', $stu_id) || !isset($students[$stu_id])) {
    header("Location: index.php?page=$page&sort=$sort");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            body { font-family: arial; }
            table { width: 300px; margin: 30px auto; border: 1px solid #c1c1c1; border-collapse: collapse;}
            table img { width: 150px; height: 150px;}
            td { padding: 10px; }
            #userPanel tr:nth-child(even) { background-color: #f4e3ff; }
            #userPanel { border: none;}
            #userPanel td:nth-child(2n+2) { font-weight: bold; }
            #courseTable { width: 700px; border-collapse: collapse; }
            #courseTable tr:nth-child(1) { font-weight: bold; text-align: center; }
            #courseTable td { border: 1px solid #c1c1c1;}
            #courseTable tr:nth-child(even) { background-color: #f4f2ff; } 
            #courseTable tr:last-child { background-color: #ffa3f5; font-weight: bold; font-style: italic;}
            p { text-align: center;}
        </style>
    </head>
    <body>
        <table>
            <tr>
                <td>
                    <img src="user.png">
                </td>
                <td>
                    <table id='userPanel'>
                        <tr><td>Name</td><td><?= $students[$stu_id]['name'] ?></td></tr>
                        <tr><td>Lastname</td><td> <?= $students[$stu_id]['lastname'] ?></td></tr>
                        <tr><td>Bilkent Id</td><td> <?= $stu_id ?></td></tr>
                        <tr><td>Cgpa</td><td> <?= $students[$stu_id]['cgpa'] ?></td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <table id='courseTable'>
            <tr>
                <td>Course Code</td>
                <td>Course Name</td>
                <td>Grade</td>
                <td>Credits</td>
                <td>Grade Points</td>
            </tr>

            <?php
            $sum_credit = 0;
            $sum_grade = 0;
            foreach ($transcript as $std_course) {
                if ($std_course['student_id'] == $stu_id) {
                    $cname = $courses[$std_course['course_code']]['name'];
                    $ccode = $std_course['course_code'];
                    $grade = $std_course['grade'];
                    $credit = $courses[$std_course['course_code']]['credit'];

                    echo "<tr><td>" . $ccode . "</td>";
                    echo "<td>" . $cname . "</td>";
                    echo "<td>" . $grade . "</td>";
                    echo "<td>" . $credit . "</td>";
                    echo "<td>" . $credit * $letter_mapping[$grade] . "</td></tr>";

                    $sum_credit += $credit;
                    $sum_grade += $letter_mapping[$grade] * $credit;
                }
            }
            ?>

            <tr><td>Gpa: </td><td colspan="4"><?= round($sum_grade / $sum_credit, 2) ?></td></tr>

        </table>
        <p><a href='index.php?page=<?= $page ?>&sort=<?= $sort ?>'>Go Back to List</p>
    </body>
</html>
