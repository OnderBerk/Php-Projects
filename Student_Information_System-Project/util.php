<?php

function searchById($id) {
    global $students;
    foreach ($students as $stu_id => $stu) {
        if ($stu_id == $id) {
            return [$stu_id];
        }
    }
    return [];
}

function searchByCgpa($cgpa) {
    global $students;
    $found = [];
    
    uasort($students, function($k, $v) {
        return $k['cgpa'] < $v['cgpa'] ? -1 : 1;
    });
    
    foreach ($students as $stu_id => $stu) {

        if ($stu['cgpa'] <= $cgpa) {

            $found[] = $stu_id;
        }
    }

    return $found;
}
