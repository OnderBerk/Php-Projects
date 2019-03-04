<?php

function searchById($id){
    global $students;
    foreach($students as $student){
        if($student["stuId"]===$id){
            return [$student];
        }
    }
    return [];
}
function searchBy($names,$grade){
    global $students;
    $found=[];
    foreach ($students as $student){
        if(strtoupper($student["name"])===$names && $student["grade"]<=$grade){
            $found[]=$student;
            
        }
    }
    return $found;
}
function allName() {
    global $students;
    $name = [];
    foreach( $students as $student) {
        $upperCase = strtoupper($student["name"]);
        if ( !in_array( $upperCase, $name)) {
            $name[] = $upperCase;
        }
    }
    sort($name);
    return $name;
}