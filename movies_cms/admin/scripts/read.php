<?php

function getAll($tbl){
    $pdo = Database::getInstance()->getConnection();
    $queryAll = 'SELECT * FROM '.$tbl;
    $results = $pdo->query($queryAll);

    if($results){
        return $results;
    }else{
        return 'There was a problem accessing this info';
    }
}

function getSingleMovie($tbl, $col, $id){
    //TODO: finish the function based on getAll, this time only return
    //one movie's fields

    $pdo = Database::getInstance()->getConnection();
    $query = 'SELECT * FROM '.$tbl.' WHERE '.$col.' = '.$id;
    // $querySingle = "SELECT * FROM $tbl WHERE $col = $id";
    $result = $pdo->query($query);

    if($result){
        return $result;
    }else{
        return 'There was a problem accessing this info';
    }
}

function getMoviesByFilter($args){
    $pdo = Database::getInstance()->getConnection();

    $filterQuery = 'SELECT * FROM '.$args['tbl'].' AS t, '.$args['tbl2'].' AS t2, '.$args['tbl3'].' AS t3';
    $filterQuery .= ' WHERE t.'.$args['col'].' = t3.'.$args['col'];
    $filterQuery .= ' AND t2.'.$args['col2'].' = t3.'.$args['col2'];
    $filterQuery .= ' AND t2.'.$args['col3'].' = "'.$args['filter'].'"';

    // echo $filterQuery;
    // exit;

    $results = $pdo->query($filterQuery);

    if($results){
        return $results;
    }else{
        return 'There was a problem accessing this info';
    }
}