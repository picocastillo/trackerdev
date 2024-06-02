<?php
 use \App\Task;
 use \App\Project;
function getNameState($number){
    switch ($number) {
        case 1: return "New";
        case 2: return "Assigned";
        case 3: return "Testing";
        case 4: return "Finished";
        case 5: return "Feedback";
    }
}
function getClassStateColor($number){
    switch ($number) {
        case 1: return "new";
        case 2: return "assigned";
        case 3: return "testing";
        case 4: return "finished";
        case 5: return "feedback";
    }
}
function getRisk($number){
    switch ($number) {
        case 1: return "Muy Bajo";
        case 2: return "Bajo";
        case 3: return "Medio";
        case 4: return "Alto";
        case 5: return "Muy Alto";
    }
}

function canShowTimes(){
    if (!\Auth::user()) return false;
    return \Auth::user()->canShowTimes();
}

function canChargeTime($task_id){
    if (!\Auth::user()) return false;
    return \Auth::user()->canChargeTime($task_id);
}

function isInTeam($task_id){
    $task = Task::findOrFail($task_id);
    if ($task->user_id == \Auth::user()->id)
        return true;
    return isInProject($task->project_id);
}


function isInProject($project_id,$user_id=null){
    $project = Project::findOrFail($project_id);
    if (!$user_id)
        $user_id = \Auth::user()->id;
    foreach ($project->users as  $value) {
        if ($value->id  == $user_id ){
            return true;
        }
    }
    return false;
}

function minutesToHours($total){
    return floor($total / 60).':'.$total%60;
}


function isClient(){
    if (!\Auth::user()) return false;
    return \Auth::user()->role->seniority=="stackeholder";
}
function isJunior(){
    if (!\Auth::user()) return false;
    return \Auth::user()->role->seniority=="junior";
}
function isSemiSenior(){
    if (!\Auth::user()) return false;
    return \Auth::user()->role->seniority=="semi-senior";
}

function isSenior(){
    if (!\Auth::user()) return false;
    return \Auth::user()->role->seniority=="senior";
}

function isDeveloper(){
    if (!\Auth::user()) return false;
    $role = \Auth::user()->role->seniority;
    return ($role=="senior" || $role=="semi-senior" || $role=="junior" );
}
 

function isManager(){
    if (!\Auth::user()) return false;
    $role = \Auth::user()->role->seniority;
    return ($role=="senior");
}
 