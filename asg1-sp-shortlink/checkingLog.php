<?php
session_start();

require_once('./db_con.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error_msg = false;
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        // User wants to login
        $username = sha1($_POST['username']);
        $password = sha1($_POST['password']);
        $av_char = "/[^a-zA-Z0-9@._]/"; // Remove the "i" from regex to match all characters.

        if (preg_match($av_char, $username) === 1) {
            $_SESSION['error'] = "Use the right input!";
            $error_msg = true;
            }

        if ($error_msg) {
            header("Location: index.php");
            die;
        }

        $sql = "SELECT userID, userName FROM username WHERE userName = ? AND userPass = ?";
        $query = $mysqli->prepare($sql);
        $query->bind_param("ss", $username, $password);
        $query->execute();
        $query->store_result();

        if ($query->num_rows > 0) {
            // Username and password are valid
            $query->bind_result($userid, $username);
            $query->fetch();

            $_SESSION['is_login'] = true;
            $_SESSION['userID'] = $userid;
            $_SESSION['userName'] = $username;
            $query->close();
            $mysqli->close();
            header("Location: ./mainPage.php");
            die;
        } else {
            // Username or password invalid
            $message = "Username atau password salah!";
            $_SESSION['error'] = $message;
            header("Location: ./index.php");
            $query->close();
            $mysqli->close();
            die;
        }
    } else {
        $_SESSION['error'] = "Username dan password tidak boleh kosong!";
        header("Location: ./index.php");
        die;
    }
}
?>
