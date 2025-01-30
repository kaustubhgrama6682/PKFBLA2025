<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

require_once 'db_connection.php';

// Handle actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['answer_question'])) {
        $question_id = $_POST['question_id'];
        $answer = $_POST['answer'];
        $is_faq = isset($_POST['is_faq']) ? 1 : 0;
        
        $stmt = $pdo->prepare("UPDATE questions SET answer = ?, is_faq = ?, status = 'answered' WHERE id = ?");
        $stmt->execute([$answer, $is_faq, $question_id]);
    }
    elseif (isset($_POST['delete_question'])) {
        $question_id = $_POST['question_id'];
        $stmt = $pdo->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$question_id]);
    }
    elseif (isset($_POST['delete_review'])) {
        $review_id = $_POST['review_id'];
        $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = ?");
        $stmt->execute([$review_id]);
    }
}

// Fetch questions
$stmt = $pdo->query("
    SELECT q.*, s.name as student_name 
    FROM questions q 
    LEFT JOIN students s ON q.student_id = s.id 
    ORDER BY q.created_at DESC
");
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch reviews
$stmt = $pdo->query("
    SELECT r.*, s.name as student_name 
    FROM reviews r 
    JOIN students s ON r.student_id = s.id 
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Include your standard head content -->
    <title>Manage Reviews & FAQ</title>
    <!-- bootstrap core css -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <!-- fonts style -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- font awesome style -->
    <link href="css/font-awesome.min.css" rel="stylesheet" />
    <!-- Custom styles -->
    <link href="css/style.css" rel="stylesheet" />

    <style>
        body {
  background: url('images/background.jpg') no-repeat center center fixed;
  background-size: cover;
}
        .question-card, .review-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .review-rating {
            color: #4D47C3;
            font-size: 20px;
        }
        
        .nav-pills .nav-link.active {
            background-color: #4D47C3;
        }
        
        .nav-pills .nav-link {
            color: white;
        }
        
        .btn-primary {
            background-color: #4D47C3;
            border-color: #4D47C3;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        .marquee-container {
    width: 100%;
    overflow: hidden;
    background: #4D47C3;
    padding: 10px 0;
    margin-bottom: 20px;
    position: relative;
    white-space: nowrap;
}

.marquee-track {
    display: inline-block;
    white-space: nowrap;
    animation: marquee 10s linear infinite;
}

.marquee-text {
    color: white;
    font-size: 18px;
    font-weight: bold;
    display: inline-block;
    padding: 0 40px;
}

@keyframes marquee {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(100%);
    }
}
    </style>
</head>

<body class="sub_page">
    <div class="hero_area">
        <!-- Include your header -->
         <!-- header section strats -->
   <header class="header_section">
      <div class="container-fluid">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              Cougar Connect
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  ">
              <li class="nav-item">
                <a class="nav-link" href="index.html">Home</a>
              </li>
              <li class="nav-item active">
                <a class="nav-link" href="manage_review_faq.php">Manage Reviews & FAQ  <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="manage_postings.php">Manage Postings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="add_admin.php">Add Admin</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="logout.php"> <i class="fa fa-user" aria-hidden="true"></i> Logout</a>
              </li>
              <form class="form-inline">
                <button class="btn  my-2 my-sm-0 nav_search-btn" type="submit">
                  <i class="fa fa-search" aria-hidden="true"></i>
                </button>
              </form>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <!-- end header section -->
  </div>
  <div class="marquee-container">
            <div class="marquee-track">
                <span class="marquee-text">Admin Mode</span>
                <span class="marquee-text">Admin Mode</span>
                <span class="marquee-text">Admin Mode</span>
                <span class="marquee-text">Admin Mode</span>
            </div>
        </div>

    <section class="layout_padding">
        <div class="container">
            <div class="heading_container heading_center mb-5">
                <h2 style = "color:white">Manage Reviews & FAQ</h2>
            </div>

            <!-- Navigation tabs -->
            <ul class="nav nav-pills mb-4 justify-content-center" id="manageTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="questions-tab" data-toggle="pill" href="#questions" role="tab">
                        Questions & FAQ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="reviews-tab" data-toggle="pill" href="#reviews" role="tab">
                        Reviews
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="manageTabsContent">
                <!-- Questions Tab -->
                <div class="tab-pane fade show active" id="questions" role="tabpanel">
                    <?php foreach ($questions as $question): ?>
                        <div class="card question-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">
                                        <?php echo htmlspecialchars($question['student_name'] ?? 'Anonymous'); ?>
                                    </h5>
                                    <span class="status-badge bg-<?php echo $question['status'] === 'answered' ? 'success' : 'warning'; ?>">
                                        <?php echo ucfirst($question['status']); ?>
                                    </span>
                                </div>
                                
                                <p class="mb-3"><?php echo htmlspecialchars($question['question']); ?></p>
                                
                                <form method="POST" class="mt-3">
                                    <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                                    
                                    <div class="form-group">
                                        <label>Answer</label>
                                        <textarea class="form-control" name="answer" rows="3"><?php echo htmlspecialchars($question['answer'] ?? ''); ?></textarea>
                                    </div>
                                    
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" name="is_faq" id="faq_<?php echo $question['id']; ?>" 
                                               <?php echo $question['is_faq'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="faq_<?php echo $question['id']; ?>">
                                            Add to FAQ
                                        </label>
                                    </div>
                                    
                                    <button type="submit" name="answer_question" class="btn btn-primary btn-sm">
                                        Save Answer
                                    </button>
                                    <button type="submit" name="delete_question" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('Are you sure you want to delete this question?');">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <?php foreach ($reviews as $review): ?>
                        <div class="card review-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0"><?php echo htmlspecialchars($review['student_name']); ?></h5>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa fa-star<?php echo $i <= $review['rating'] ? '' : '-o'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <p class="mb-3"><?php echo htmlspecialchars($review['review_text']); ?></p>
                                <small class="text-muted">Posted on <?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                                
                                <form method="POST" class="mt-3">
                                    <input type="hidden" name="review_id" value="<?php echo $review['id']; ?>">
                                    <button type="submit" name="delete_review" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this review?');">
                                        Delete Review
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Include your footer -->

    <!-- JavaScript -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/custom.js"></script>
</body>
</html>