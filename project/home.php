<?php
 session_start();
 require_once('db.php');
 require_once('functions.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
} else {
    require_once('db.php');
    require_once('functions.php');
    
    // Insert items first
    insertNavbarItems($pdo);
    // Fetch them afterwards 
    $navbarLinks = FetchNav($pdo); 
}
 // Insert items first
 insertNavbarItems($pdo);
 // Fetch them afterwards 
 $navbarLinks = FetchNav($pdo);
// Insert Hero Section Data (Run once or control with a condition)
if (!isset($_SESSION['hero_inserted'])) {
  $insertMessage = insertHeroSection($pdo, 'Taste the Creativity', 'We make perfect web designs and websites.', 'hero.jpg');
  $_SESSION['hero_inserted'] = true; // Mark as inserted to avoid duplication
  echo $insertMessage;
}
// Fetch Hero Section Data
$heroSection = fetchHeroSection($pdo);

//for services
// Insert Service Data only if the table is empty or under certain conditions
if (!isset($_SESSION['service_inserted'])) {
  try {
      $insertMessage1 = insertService($pdo, 'Graphic Design', 'Description for Graphic Design', 'graph.jpg');
      echo $insertMessage1 . "<br>"; // Print the insertion message for debug
  } catch (PDOException $e) {
      echo "Error inserting Graphic Design service: " . $e->getMessage() . "<br>";
  }
  
  try {
      $insertMessage2 = insertService($pdo, 'Web Design', 'Description for Web Design', 'web.jpg');
      echo $insertMessage2 . "<br>"; // Print the insertion message for debug
  } catch (PDOException $e) {
      echo "Error inserting Web Design service: " . $e->getMessage() . "<br>";
  }
  
  $_SESSION['service_inserted'] = true; // Mark as inserted to avoid duplication
}

// Fetch Services Data
$services = fetchServices($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Web Design</title>

  <!-- Bootstrap 5.3.0 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons CDN (for icon usage) -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css" rel="stylesheet">

  <!-- Custom Stylesheet -->
  <link rel="stylesheet" type="text/css" href="style.css">

   <!-- Owl Carousel CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg sticky-top  navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand text-light" href="#">
        <img src="logo.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top"> M:G
      </a>

      <!-- Navbar Toggler for Mobile View -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    <!-- Navbar Links -->
<div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
        <!-- Navbar php pdo -->
        <?php
            foreach ($navbarLinks as $link) {
                echo "<li class='nav-item'><a class='nav-link text-light' href='" . htmlspecialchars($link['url']) . "'>" . htmlspecialchars($link['title']) . "</a></li>";
            }
        ?>
        <li class='nav-item'><a class='nav-link text-light' href="logout.php">Logout</a></li>
    </ul>
</div>


      <!-- Search Form -->
      <form class="d-flex" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <a href="" class="btn btn-dark rounded-pill">Search</a>
        
      </form>

      <!-- User Icon -->
      <div class="ms-3">
        <a href="#">
          <img src="user.png" width="50" alt="icon"  class="rounded-circle">
        </a>
      </div>
    </div>
  </nav>
  <!-- Notification Message -->
  <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
  <!-- Landing Section -->
   <?php
    // Insert Hero Section Data (only call this once or under specific conditions)
    $insertMessage = insertHeroSection($pdo, 'Taste the Creativity', 'We make perfect web designs and websites.', 'hero.jpg');
    echo $insertMessage;

    //Fetch Hero Section Data
    $heroSection = fetchHeroSection($pdo);  
   ?>
   <section class="landing d-flex justify-content-center align-items-center text-center text-light py-5" style="height: 70vh; background-image: url('<?php echo htmlspecialchars($heroSection['image_url']); ?>'); background-size: cover; background-position: center;">
        <div class="container">
            <h2 class="fs-1"><?php echo htmlspecialchars($heroSection['title']); ?></h2>
            <p class="fs-5 text-white-50 mb-5"><?php echo htmlspecialchars($heroSection['subtitle']); ?></p>
            <a class="btn btn-danger rounded-pill main-btn" href="#">Get started</a>
        </div>
    </section>
 




  <!-- Features Section -->
  <section class="features text-center pt-5 pb-5">
    <div class="container">
      <div class="main-title mt-6 mb-6">
        <img src="title1.png" alt="Logo" class="logo-img">
        <h3>We are good at</h3>
        <p class="text-uppercase ">Some of these stuff under:</p>
        <hr class="line"> <!-- Line under the paragraph -->
      </div>

        <!-- Services Section -->
    <section class="services-section">
        <div class="container">
            <div class="row">
                <?php foreach ($services as $service): ?>
                    <div class="col-md-4 mb-4">
                        <img src="<?php echo htmlspecialchars($service['image_url']); ?>" alt="Service Image" class="display-1">
                        <h4><?php echo htmlspecialchars($service['service_name']); ?></h4>
                        <p><?php echo htmlspecialchars($service['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
  <!-- new section -->
  <section class="our-work text-center pt-5 pb-5">
    <div class="container">
      <div class="main-title mt-6 mb-6">
        <img src="title1.png" alt="logo" class="logo-img">
        <h3>we make this</h3>
        <p class="text-uppercase">Prepared to be amazed</p>
        <hr class="line">
      </div>
      <ul class="nav nav-pills justify-content-center mt-5 mb-5" id="work-tabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active rounded-pill text-light bg-danger" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link rounded-pill text-light bg-danger" id="design-tab" data-bs-toggle="pill" data-bs-target="#design" type="button" role="tab" aria-controls="design" aria-selected="false">Design</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link rounded-pill text-light bg-danger" id="code-tab" data-bs-toggle="pill" data-bs-target="#code" type="button" role="tab" aria-controls="code" aria-selected="false">Code</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link rounded-pill text-light bg-danger" id="photo-tab" data-bs-toggle="pill" data-bs-target="#photo" type="button" role="tab" aria-controls="photo" aria-selected="false">Photo</button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link rounded-pill text-light bg-danger" id="app-tab" data-bs-toggle="pill" data-bs-target="#app" type="button" role="tab" aria-controls="app" aria-selected="false">App</button>
        </li>
      </ul>
      <div class="tab-content" id="work-tabs-content">
        <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white position-relative">
                <img class="img-fluid" src="work-01.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-02.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-07.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-08.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="design" role="tabpanel" aria-labelledby="design-tab">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-05.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-06.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-07.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-08.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="code" role="tabpanel" aria-labelledby="code-tab">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-01.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-02.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-03.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-04.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="photo" role="tabpanel" aria-labelledby="photo-tab">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-01.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-02.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-03.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-04.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="app" role="tabpanel" aria-labelledby="app-tab">
          <div class="row">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-01.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-02.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-03.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <div class="box mb-3 bg-white">
                <img class="img-fluid" src="work-04.jpg" alt="" />
                <span class="overlay-text">Application</span>
              </div>
            </div>
          </section>
          
  


 <section class="about text-center pt-5 pb-5">
    <div class="container">
      <div class="main-title ">
        <img src="title1.png" alt="Logo" class="logo-img">
        <h3>Some stuff about us</h3>
        <p class="text-uppercase ">The great team</p>
        <hr class="line"> <!-- Line under the paragraph -->
      </div>
      <p class="text-center text-black-50 description m-auto mb-5">
        Donec rutrum congue leo eget malesuada. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus. Donec sollicitudin
        molestie malesuada
      </p>
      <div class="row">
        <div class="col-lg-4 mb-4 d-flex align-items-center">
          <div class="text">
            <h4>Retina Design</h4>
            <p class="text-black-50">Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a.</p>
            <p class="text-black-50">
              Donec rutrum congue leo eget malesuada. Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Pellentesque in ipsum id orci porta dapibus. Proin eget tortor risus. Donec
              sollicitudin molestie malesuada.
            </p>
            <a href="" class="btn main-btn rounded-pill">Order Me One</a>
          </div>
        </div>
        <div class="col-lg-8">
          <!-- <div class="image"> -->
          <img class="img-fluid" src="laptop.jpg" alt="">
          <!-- </div> -->
        </div>
      </div>
    </div>
  </section>
  <!-- Carousel -->
<div id="demo" class="carousel slide" data-bs-ride="carousel">

  <!-- Indicators/dots -->
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
    <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
  </div>

  <!-- The slideshow/carousel -->
  <div class="carousel-inner">
    <div class="carousel-item active">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <img src="team1.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
          <div class="col-lg-4">
            <img src="team2.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
          <div class="col-lg-4">
            <img src="team3.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <img src="team1.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
          <div class="col-lg-4">
            <img src="team2.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
          <div class="col-lg-4">
            <img src="team3.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
        </div>
      </div>
    </div>
    <div class="carousel-item">
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <img src="team42.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
          <div class="col-lg-4">
            <img src="team42.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with these troops. I thought we were dead."</blockquote>
          </div>
          <div class="col-lg-4">
            <img src="team42.jpeg" class="d-block" alt="Bill Gates">
            <h5>Bill Gates</h5>
            <blockquote>"I don't know how we got out with thesess troops. I thought we were dead."</blockquote>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Left and right controls/icons -->
  <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>




  
  <section class="start justify-content-center text-center text-white">
  <h2 class="pt-5">Start your project now</h2>
  <p>leave your description and we start the engine.don't worry you can cancel anytime</p>
  <a href="" class="btn main-btn rounded-pill text-uppercase mt-5 mb-5">Start project</a>
</section>

<section class="read mt-5">
  <div class="container justify-content-center">
    <div class="main-title mt-6 mb-6 text-center">
      <img src="title1.png" alt="logo" class="logo-img mt-5">
      <h3>read our blog</h3>
      <p class="text-uppercase">new stories</p>
      <hr class="line">
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4 d-flex justify-content-center mb-2">
        <div class="box bg-white text-center">
          <img class="img-fluid" src="read1.jpeg" alt="">
          <blockquote class="text-black-50">25 june 2020</blockquote>
          <p>some awesome blog title here</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 d-flex justify-content-center mb-2">
        <div class="box bg-white text-center">
          <img class="img-fluid" src="read2.jpeg" alt="">
          <blockquote class="text-black-50">25 june 2020</blockquote>
          <p>some awesome blog title here</p>
        </div>
      </div>
      <div class="col-md-6 col-lg-4 d-flex justify-content-center mb-2">
        <div class="box bg-white text-center">
          <img class="img-fluid" src="read3.jpeg" alt="">
          <blockquote class="text-black-50">25 june 2020</blockquote>
          <p>some awesome blog title here</p>
        </div>
      </div>
    </div>
    <div class="d-flex justify-content-center mt-5 mb-5">
      <a href="" class="btn main-btn rounded-pill text-uppercase">Start project</a>
    </div>
  </div>
</section>

<div class="subscribe-section">
  <h1 class="text-dark fw-bold">Subscribe to our Newsletter:</h1>
  <div class="d-flex justify-content-center align-items-center">
    <input type="email" placeholder="Enter Email Address" class="form-control me-2">
    <a class="btn btn-danger rounded-pill main-btn" href="#">Subscribe</a>
  </div>
</div>


  <!-- Bootstrap JS (for navbar toggling) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
