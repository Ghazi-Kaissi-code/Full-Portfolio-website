<?php
//CONNECTION TO DATABASE
require_once('db.php');


//REGISTER NEW USER
function registerUser($pdo, $username, $email, $password, $role = 'user') {
    //CHECK EMAIL
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->rowCount() > 0) {
        return "Email already exists";
    } else {
        //NEW USER
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");

        // ANOTHER EXECUTION TECHNIQUE USING BIND PARAMETERS
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);

          if ($stmt->execute()) {
            echo "Registration successful!";
             // Redirect to login page after successful registration
             header("Location: login.php");
        } else {
            echo "Error in registration!";
        }
            }
        }
     //TO CHECK USER if exist in the databse
     function checkUser($pdo, $email, $password) {
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user && password_verify($password, $user['password']) ? $user : false;
    }
  
//INSERTING DATA TO NAVBAR
function InsertNav($pdo,$title,$url){
$sql="INSERT INTO navbar (title,url) VALUES (:title,:url)";
$stmt=$pdo->prepare($sql);
$stmt->execute([
    'title'=>$title,
    'url'=>$url
]);
}

//FETCHING DATA FROM NAVBAR

function FetchNav($pdo){
$sql="SELECT * FROM navbar";
$stmt=$pdo->query($sql);
return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
function insertNavbarItems($pdo) {
    // Check if there are already items in the navbar_links table
    $stmt = $pdo->query("SELECT COUNT(*) FROM navbar");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insert items only if the table is empty
        $navbarItems = [
            ['title' => 'Home', 'url' => 'Home.php'],
            ['title' => 'About', 'url' => 'About.php'],
            ['title' => 'Number', 'url' => 'Number.php'],
            ['title' => 'Portfolio', 'url' => 'Portfolio.php'],
            ['title' => 'Address', 'url' => 'Address.php'],
            ['title' => 'Contact', 'url' => 'Contact.php']

        ];

        foreach ($navbarItems as $item) {
            InsertNav($pdo, $item['title'], $item['url']);
        }

        //echo "Navbar items inserted successfully!";
    }
}



// Function to Insert Hero Section Data
function insertHeroSection($pdo, $title, $subtitle, $image_url) {
    // Check for existing entry
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM hero_section WHERE title = :title AND subtitle = :subtitle");
    $stmt->execute(['title' => $title, 'subtitle' => $subtitle]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // Insert only if no existing entry
        $sql = "INSERT INTO hero_section (title, subtitle, image_url) VALUES (:title, :subtitle, :image_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'title' => $title,
            'subtitle' => $subtitle,
            'image_url' => $image_url
        ]);
        //return "Hero section data inserted successfully!";
    } else {
        //return "Hero section entry already exists.";
    }
}

// Function to Fetch Hero Section Data
function fetchHeroSection($pdo) {
    $sql = "SELECT * FROM hero_section LIMIT 1"; // Assuming you only need the first entry
    $stmt = $pdo->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}




// Function to insert data into the services section
function insertService($pdo, $service_name, $description, $image_url) {
    // Check if the service already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM services WHERE service_name = :service_name AND description = :description");
    $stmt->execute([
        'service_name' => $service_name,
        'description' => $description
    ]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $sql = "INSERT INTO services (service_name, description, image_url) VALUES (:service_name, :description, :image_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'service_name' => $service_name,
            'description' => $description,
            'image_url' => $image_url
        ]);
        //return "Service data inserted successfully!";
    } else {
       // return "Service entry already exists.";
    }
}

// Function to fetch services data
function fetchServices($pdo) {
    $sql = "SELECT * FROM services";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Function to edit user
function editUser($pdo, $id, $username, $email, $role) {
    $query = "UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':role', $role, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

// Function to delete user
function deleteUser($pdo, $id) {
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}


// Fetch user details by ID
function fetchUserById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// Function to add new user
function addUser($pdo, $username, $email, $password, $role) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$username, $email, $hashedPassword, $role]);
}


//send messages

function sendMessage($pdo, $userId, $name, $email, $message) {
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$userId, $name, $email, $message]);
}
//fetch messages
function fetchMessages($pdo) {
    $stmt = $pdo->query("SELECT * FROM messages");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
//reply the message by the admin
function replyMessage($pdo, $messageId, $reply) {
    $stmt = $pdo->prepare("UPDATE messages SET reply = ? WHERE id = ?");
    return $stmt->execute([$reply, $messageId]);
}

?>







