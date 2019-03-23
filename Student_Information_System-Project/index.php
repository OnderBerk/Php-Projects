<?php
require 'db_tables.php';
require 'util.php';

if (isset($_POST['submit_BT'])) {
    extract($_POST);
    $id_TF = isset($_POST['id_TF']) ? filter_var($_POST['id_TF'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "";
    $cgpa_TF = isset($_POST['cgpa_TF']) ? filter_var($_POST['cgpa_TF'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "";
    $id_error = "";
    $cgpa_error = "";

    if (!empty($id_TF)) {
        if (!preg_match('/^2\d{7}$/', $id_TF)) {
            $id_error = "PHP: Invalid Student ID";
        } else {
            $result = searchById($id_TF);
            if (empty($result)) {
                $id_error = "Student not found !";
            }
        }
    }

    if ( $cgpa_TF > 4.0 || $cgpa_TF < 1.8 || filter_var($cgpa_TF, FILTER_VALIDATE_FLOAT, ["options" => ['decimal' => 2]]) === false) {
        $cgpa_error = "PHP: Invalid Cgpa";
    }

    if (empty($id_error) && empty($cgpa_error)) {
        if (!empty($id_TF)) {
            $result = searchById($id_TF);
        } else {
            $result = searchByCgpa($cgpa_TF);
        }
    }
}

if (isset($_POST['done_BT'])) {
    header("Location: index.php");
    exit;
}

$size = 5;
$totalPage = ceil(count($students) / $size);
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 0;
$page = filter_var($page, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => $totalPage]]);

$start = ($page - 1) * $size;
$end = $start + $size;

$key = ["name", "lastname", "cgpa"];
$sort = filter_var($sort, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0, "max_range" => count($key)]]);
$sortAttr = $key[$sort];
uasort($students, function($k, $v) {
    global $sortAttr;
    return $k[$sortAttr] < $v[$sortAttr] ? -1 : 1;
});
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link href="custom.css" rel="stylesheet" type="text/css"/>
        <style>
            .searchPart { margin-top: 40px; margin-bottom: 20px ; background-color: #ffe5fc; border:none; }
            .aa { border: none; width: 150px; }
            .searchPart td:nth-child(2) { margin-bottom:20px;}
            #error { color: red; text-align: center; font-size: 12px; font-weight: lighter; font-style: italic; }
        </style>
    </head>
    <body>
        <h1>Student List</h1>

        <form action="" method="post">
            <table class='searchPart'>
                <tr>
                    <td class="aa">Student Id:</td>
                    <td class="aa">
                        <input type="text" name="id_TF" placeholder="Student Id" value="<?= $id_TF ?>" size='20' ><br>
                        <?php
                        if (isset($id_error)) {
                            echo "<span id='error'>" . $id_error . "</span>";
                        }
                        ?>
                    </td>
                    <td class="aa">Student Cgpa:</td>
                    <td class="aa">
                        <input type="text" name="cgpa_TF" placeholder="Cgpa" value="<?= $cgpa_TF ?>">
                        <?php
                        if (isset($cgpa_error)) {
                            echo "<span id='error'>" . $cgpa_error . "</span>";
                        }
                        ?>
                    </td>
                    <td class="aa"><input type="submit" value="&#x1F50D" name="submit_BT" ></td>
                    <td class="aa"><input type="submit" value="Done" name="done_BT" ></td>
                </tr>
            </table>
        </form>

        <?php if (isset($result)) { ?>
            <table>
                <?php if (empty($cgpa_error) || empty($id_error)) { ?>
                    <tr>
                        <td>No</td>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Lastname</td>
                        <td>Cgpa</td>
                        <td>Birthday</td>
                        <td>Transcript</td>
                    </tr>
                <?php } ?>

                <?php
                $i = 0;
                foreach ($result as $stu) {
                    echo "<tr><td>" . ($i + 1) . "</td>";
                    echo "<td>" . $stu . "</td>";
                    echo "<td>" . $students[$stu]['name'] . "</td>";
                    echo "<td>" . $students[$stu]['lastname'] . "</td>";
                    echo "<td>" . number_format($students[$stu]['cgpa'], 2) . "</td>";
                    echo "<td>" . $students[$stu]['birthday'] . "</td>";
                    echo "<td><a href='transcript.php?id=$stu'><img src='report.png'></a></td>";
                    $i++;
                }
                ?>
            </table>   

        <?php } else if (empty($cgpa_error) && empty($id_error)) { ?>

            <table>
                <tr>
                    <td>No</td>
                    <td>ID</td>
                    <td class="sorted"><a href='?sort=0'><span><?= $sort === 0 ? "&#x25bc" : ""; ?></span>Name</a></td>
                    <td class="sorted"><a href='?sort=1'><span><?= $sort === 1 ? "&#x25bc" : ""; ?></span>Lastname</a></td>
                    <td class="sorted"><a href='?sort=2'><span><?= $sort === 2 ? "&#x25bc" : ""; ?></span>Cgpa</a></td>
                    <td>Birthday</td>
                    <td>Transcript</td>
                </tr>

                <?php
                $i = 0;
                foreach ($students as $id => $std) {
                    if ($i >= $start && $i < $end) {
                        echo "<tr><td>" . ($i + 1) . "</td>";
                        echo "<td>" . $id . "</td>";
                        echo "<td>" . $std['name'] . "</td>";
                        echo "<td>" . $std['lastname'] . "</td>";
                        echo "<td>" . number_format($std['cgpa'], 2) . "</td>";
                        echo "<td>" . $std['birthday'] . "</td>";
                        echo "<td><a href='transcript.php?id=$id&page=$page&sort=$sort'><img src='report.png'></a></td>";
                    }
                    $i++;
                }
                ?>

            </table>
            <div id="pageDiv">
                [
                <?php
                for ($i = 1; $i <= $totalPage; $i++) {
                    if ($i === $page) {
                        echo "<span class='active'><a href='?page=$i'>$i</a></span>";
                    } else {
                        echo "<span><a href='?page=$i&sort=$sort'>$i</a></span>";
                    }
                }
                ?>
                ]
            </div>

        <?php } ?>
    </body>
</html>
