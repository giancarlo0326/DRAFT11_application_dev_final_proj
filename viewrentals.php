<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page
    header("Location: signin.php");
    exit(); // Make sure no further code executes after redirect
}

// Include database connection and functions
require 'database.php'; // Ensure this file exists and is correctly set up
require 'functions.php'; // Ensure this file exists and contains the getAllVideos function

// Fetch videos from the database
$videos = getAllVideos($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rentals - PUIHAHA Videos</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        h1 {
            color: #fff;
            font-size: 75px;
            text-align: center;
        }

        .typed-text {
            color: #82420f;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 4rem;
            }
        }

        body {
            position: relative; /* Ensure body is relative for absolute positioning inside */
        }

        .hero-content {
            position: relative; /* Use relative positioning instead of absolute */
            text-align: center; /* Center align content */
            margin: 48px, 107.5px, 0px;
        }

        .card {
            background-color: #82420f;
            color: white;
        }
    </style>
</head>
<body>
    <nav>
        <a class="home-link" href="index.php">
            <img src="https://i.postimg.cc/CxLnK8q1/PUIHAHA-VIDEOS.png" alt="Home">
        </a>
        <input type="checkbox" id="sidebar-active">
        <label for="sidebar-active" class="open-sidebar-button">
            <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
        </label>
        <label id="overlay" for="sidebar-active"></label>
        <div class="links-container">
            <label for="sidebar-active" class="close-sidebar-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
            </label>
            <a href="add.php">Add Videos</a>
            <a href="videos.php">Videos</a>
            <a href="viewrentals.php">Rentals</a>
            <a href="account.php">Account</a>
            <a href="aboutdevs.php">About Us</a>
            <a href="signin.php">Sign In</a>
            <a href="signup.php">Sign Up</a>
            <a href="logout.php">Log Out</a>
        </div>
    </nav>

    <div class="hero-content">
        <h1>View <span class="auto-type typed-text"></span></h1>
        <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
        <script>
            var typed = new Typed(".auto-type", {
                strings: ["Rentals"],
                typeSpeed: 100,
                backSpeed: 10,
            });
        </script>
    </div>

    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="centered-container">
                <div class="row mt-12">
                    <div class="col-lg-12 d-flex flex-column flex-lg-row justify-content-between align-items-center">
                        <div class="col-lg-6 mb-3 mb-lg-0">
                            <form class="form-inline d-flex justify-content-start">
                                <input class="form-control mr-2" type="search" placeholder="Enter title, director, etc..." aria-label="Search">
                                <button class="btn btn-success ml-2" type="submit">Search</button>
                            </form>
                        </div>
                        <div class="col-lg-6 d-flex flex-column flex-lg-row justify-content-end align-items-center">
                            <p class="mb-0 mb-lg-0 mr-lg-2">Sort by: </p>
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Video Type
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">DVD</a></li>
                                    <li><a class="dropdown-item" href="#">Blu-Ray</a></li>
                                    <li><a class="dropdown-item" href="#">Digital</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3"> <!-- New row for the second dropdown -->
                    <div class="col-lg-6 mb-3 mb-lg-0 offset-lg-6 d-flex flex-column flex-lg-row justify-content-end align-items-center">
                        <p class="mb-0 mb-lg-0 mr-lg-2"></p>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Release Date
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Newest First</a></li>
                                <li><a class="dropdown-item" href="#">Oldest First</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-3"> <!-- New row for the second dropdown -->
                    <div class="col-lg-6 mb-3 mb-lg-0 offset-lg-6 d-flex flex-column flex-lg-row justify-content-end align-items-center">
                        <p class="mb-0 mb-lg-0 mr-lg-2"></p>
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Genre
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Comedy</a></li>
                                <li><a class="dropdown-item" href="#">Drama</a></li>
                                <li><a class="dropdown-item" href="#">Horror</a></li>
                                <li><a class="dropdown-item" href="#">Science Fiction</a></li>
                                <li><a class="dropdown-item" href="#">Fantasy</a></li>
                                <li><a class="dropdown-item" href="#">Romance</a></li>
                                <li><a class="dropdown-item" href="#">Thriller</a></li>
                                <li><a class="dropdown-item" href="#">Animation</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <?php foreach ($videos as $video) : ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card">
                        <img src="https://i.pinimg.com/474x/4e/79/73/4e7973bf3a86dc66138d09db96b3cc9a.jpg" class="img-fluid">
                        <div class="card-body">
                            <h4 class="card-text"><?php echo htmlspecialchars($video['title']); ?></h4>
                            <p class="card-text">Genre: <?php echo htmlspecialchars($video['genre']); ?></p>
                            <p class="card-text">Director: <?php echo htmlspecialchars($video['director']); ?></p>
                            <p class="card-text">Release Date: <?php echo htmlspecialchars($video['release_date']); ?></p>
                            <p class="card-text">Available Copies: <?php echo htmlspecialchars($video['available_copies']); ?></p>
                            <p class="card-text">Type: <?php echo htmlspecialchars($video['video_type']); ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="edit_video.php?id=<?php echo $video['id']; ?>" class="btn btn-primary">Watch</a>
                                <a href="rent_video.php?id=<?php echo $video['id']; ?>" class="btn btn-success">Return Video</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="collab">
                        <img src="https://i.postimg.cc/CxLnK8q1/PUIHAHA-VIDEOS.png" class="collab-img img-fluid">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="footerBottom text-center text-md-end">
                        <h3>Application Development and Emerging Technologies - Final Project</h3>
                        <p>This website is for educational purposes only and no copyright infringement is intended.</p>
                        <p>Copyright &copy;2024; All images used in this website are from the Internet.</p>
                        <p>Designed by PIPOPIP, June 2024.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
