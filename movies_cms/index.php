<?php
require_once 'load.php';

if(isset($_GET['filter'])){
    //filter
    $args = array(
        'tbl'=>'tbl_movies',
        'tbl2'=>'tbl_genre',
        'tbl3'=>'tbl_mov_genre',
        'col'=>'movies_id',
        'col2'=>'genre_id',
        'col3'=>'genre_name',
        'filter'=>$_GET['filter']
    );
    $getMovies = getMoviesByFilter($args);
}else{
    $movie_table = 'tbl_movies';
    $getMovies = getAll($movie_table);
}

// var_dump($getMovies);exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Welcome to the Movie CMS</title>
</head>
<body>
<?php include 'templates/header.php' ?>
    <?php while($row = $getMovies->fetch(PDO::FETCH_ASSOC)):?>
    <div class="movie-item">
        <img src="images/<?php echo $row['movies_cover']; ?>" alt="<?php echo $row['movies_title'];?>" />
        <h2><?php echo $row['movies_title'];?> </h2>
        <h4>Movie Released: <?php echo $row['movies_year'];?></h4>
        <a href="details.php?id=<?php echo $row['movies_id'];?>">Read More...</a>
    </div>
    <?php endwhile;?>
    <?php include 'templates/footer.php' ?>
</body>
</html>
