<?php
session_start();
require_once('db.php');
require_once('functions.php');

if ($_SESSION['role'] !== 'admin') {
    $_SESSION['message'] = "You do not have admin rights to access this page.";
    header("Location:  admin_messages.php");
    exit();
    }
    

if (isset($_POST['reply_message'])) {
    $messageId = htmlspecialchars($_POST['message_id']);
    $reply = htmlspecialchars($_POST['reply']);
    if (replyMessage($pdo, $messageId, $reply)) {
        $_SESSION['message'] = "Reply sent successfully.";
    } else {
        $_SESSION['message'] = "Failed to send reply.";
    }
    header("Location: admin_messages.php");
    exit();
}

$messages = fetchMessages($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin - View Messages</h2>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['id']); ?></td>
                        <td><?php echo htmlspecialchars($message['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($message['name']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo htmlspecialchars($message['message']); ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="showReplyModal('<?php echo $message['id']; ?>')">Reply</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a class="btn btn-secondary" href="home.php">Back to Dashboard</a>
        </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="admin_messages.php">
                    <div class="modal-header">
                        <h5 class="modal-title" id="replyModalLabel">Reply to Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="message_id" id="replyMessageId">
                        <div class="mb-3">
                            <label for="reply" class="form-label">Reply</label>
                            <textarea class="form-control" id="reply" name="reply" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="reply_message" class="btn btn-primary">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showReplyModal(id) {
            document.getElementById('replyMessageId').value = id;
            var myModal = new bootstrap.Modal(document.getElementById('replyModal'));
            myModal.show();
        }
    </script>
</body>
</html>
