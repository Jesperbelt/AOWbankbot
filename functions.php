<?php
include 'Connection.php';
function person($query, $id)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $query);
    mysqli_stmt_bind_param($statement, 's', $id);
    mysqli_stmt_execute($statement);
    $data = mysqli_stmt_get_result($statement);
    $data = mysqli_fetch_all($data, MYSQLI_ASSOC);
    return $data;
}

function addperson($query, $id, $user)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $query);
    mysqli_stmt_bind_param($statement, 'ss', $id, $user);
    mysqli_stmt_execute($statement);
}

function total($update, $id)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $update);
    mysqli_stmt_bind_param($statement, 'sssssss', $id, $id, $id, $id, $id, $id, $id);
    mysqli_stmt_execute($statement);
}

function whatweek($queryweek)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $queryweek);
    mysqli_stmt_execute($statement);
    $week = mysqli_stmt_get_result($statement);
    $week = mysqli_fetch_all($week, MYSQLI_ASSOC);
    return $week;
}

function whatweek2($queryweek2, $week)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $queryweek2);
    mysqli_stmt_bind_param($statement, 's', $week);
    mysqli_stmt_execute($statement);
    $valid = mysqli_stmt_get_result($statement);
    $valid = mysqli_fetch_all($valid, MYSQLI_ASSOC);
    return $valid;
}

function participants($wtaparticipantsquery, $week)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $wtaparticipantsquery);
    mysqli_stmt_bind_param($statement, 's', $week);
    mysqli_stmt_execute($statement);
    $names = mysqli_stmt_get_result($statement);
    $names = mysqli_fetch_all($names, MYSQLI_ASSOC);
    return $names;
}

function totals($totalsquery, $week, $name)
{
    $Connection = Connection();
    $statement = mysqli_prepare($Connection, $totalsquery);
    mysqli_stmt_bind_param($statement, 'ss', $week, $name);
    mysqli_stmt_execute($statement);
    $totals = mysqli_stmt_get_result($statement);
    $totals = mysqli_fetch_all($totals, MYSQLI_ASSOC);
    return $totals;
}
?>