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
require 'functions.php'; // Ensure this file exists and contains the necessary functions

// Fetch all videos initially
$videos = getAllVideos($conn);

// Check if a search query is submitted
$search_query = isset($_GET['search_query']) ? $_GET['search_query'] : '';

if (!empty($search_query)) {
    // Perform search
    $videos = searchVideos($conn, $search_query);
}

// Number of videos per page
$videosPerPage = 8;

// Calculate total number of videos
$totalVideos = count($videos);

// Determine current page number
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting video index for the current page
$start = ($page - 1) * $videosPerPage;

// Fetch videos for the current page
$videos = array_slice($videos, $start, $videosPerPage);

// Function to search videos based on title or other criteria
function searchVideos($conn, $search_query) {
    $sql = "SELECT * FROM videos WHERE title LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%$search_query%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $result = $stmt->get_result();
    $videos = [];
    while ($row = $result->fetch_assoc()) {
        $videos[] = $row;
    }
    return $videos;
}

// Function to generate pagination links
function pagination($totalVideos, $videosPerPage, $page) {
    $totalPages = ceil($totalVideos / $videosPerPage);
    $previous = max($page - 1, 1);
    $next = min($page + 1, $totalPages);

    $html = '<nav aria-label="Page navigation example"><ul class="pagination">';
    $html .= '<li class="page-item"><a class="page-link" href="videos.php?page=' . $previous . '">Previous</a></li>';

    for ($i = 1; $i <= $totalPages; $i++) {
        $active = $i == $page ? 'active' : '';
        $html .= '<li class="page-item ' . $active . '"><a class="page-link" href="videos.php?page=' . $i . '">' . $i . '</a></li>';
    }

    $html .= '<li class="page-item"><a class="page-link" href="videos.php?page=' . $next . '">Next</a></li>';
    $html .= '</ul></nav>';

    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Videos - PUIHAHA Videos</title>
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
    <h1>Explore <span class="auto-type typed-text"></span></h1>
    <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
    <script>
        var typed = new Typed(".auto-type", {
            strings: ["Videos"],
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
                            <form class="form-inline d-flex justify-content-start" method="GET" action="videos.php">
                                <input class="form-control mr-2" type="search" name="search_query" placeholder="Enter title, director, etc..." aria-label="Search">
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
                                    <li><a class="dropdown-item" href="videos.php?sort_by=video_type">DVD</a></li>
                                    <li><a class="dropdown-item" href="videos.php?sort_by=video_type">Blu-Ray</a></li>
                                    <li><a class="dropdown-item" href="videos.php?sort_by=video_type">Digital</a></li>
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
                                <li><a class="dropdown-item" href="videos.php?sort_by=release_date">Newest First</a></li>
                                <li><a class="dropdown-item" href="videos.php?sort_by=release_date&order=asc">Oldest First</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <?php foreach ($videos as $video): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100">
                                <img src="uploads/<?php echo $video['image']; ?>" class="card-img-top" alt="<?php echo $video['title']; ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $video['title']; ?></h5>
                                    <p class="card-text"><?php echo $video['description']; ?></p>
                                    <a href="#" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pagination section -->
<div class="row mt-4">
    <div class="col-lg-12">
        <?php echo pagination($totalVideos, $videosPerPage, $page); ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="collab">
                    <img src="https://i.postimg.cc/CxLnK8q1/PUIHAHA-VIDEOS.png" class="collab-img img-fluid">
                    <i class="nav-icon fab fa-facebook"></i> <!-- Facebook icon using FontAwesome classes -->
                </div>
            </div>
            <div class="col-md-4">
                <div class="footerBottom text-center text-md-end">
                    <h3>Application Development and Emerging Technologies - Final Project</h3>
                    <p></p>
                    <p>This website is for educational purposes only and no copyright infringement is intended.</p>
                    <p>Copyright &copy;2024; All images used in this website are from the Internet.</p>
                    <p>Designed by PIPOPIP, June 2024.</p>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.12.1/dist/umd/popper.min.js" integrity="sha384-NBce/N3RzFpeMv/HaPbTT5S5D/qorTrGwIlYa4bbKCE1B4+EwXAZ/bS2K2C+JM7+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-djs/8nC1nOqh1kG5ceSM+jPvY0/WxyX4v3ud8v6aY0wL5PRG6Fk+JyLvMHi4MLYhg" crossorigin="anonymous"></script>
</body>
</html>
