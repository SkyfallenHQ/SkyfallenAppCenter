<?php
if($_POST["action"]=="login"){
    // Initialize the session
    session_name("AppCenterSession");
    session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        http_response_code(200);
        die();
    }

// Include config file
    require_once "../configuration.php";

// Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";
        // Check if username is empty
        if(empty(trim($_POST["username"]))){
            http_response_code(403);
            die();
        } else{
            $username = trim($_POST["username"]);
        }

        // Check if password is empty
        if(empty(trim($_POST["password"]))){
            http_response_code(403);
            die();
        } else{
            $password = trim($_POST["password"]);
        }

        // Validate credentials
        if(empty($username_err) && empty($password_err)){
            // Prepare a select statement
            $sql = "SELECT id, username, password FROM users WHERE username = ?";

            if($stmt = mysqli_prepare($link, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                // Set parameters
                $param_username = $username;

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Store result
                    mysqli_stmt_store_result($stmt);

                    // Check if username exists, if yes then verify password
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        // Bind result variables
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                // Password is correct, so start a new session
                                session_start();

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;

                                // Redirect user to welcome page
                                http_response_code(200);
                                die();
                            } else{
                                // Display an error message if password is not valid
                                http_response_code(403);
                                die();
                            }
                        }
                    } else{
                        // Display an error message if username doesn't exist
                        http_response_code(403);
                        die();;
                    }
                } else{
                    http_response_code(500);
                    die();
                }

                // Close statement
                mysqli_stmt_close($stmt);
            } else {
                http_response_code(503);
                die(mysqli_stmt_error($stmt)." ".mysqli_error($link));
            }
        }

        // Close connection
        mysqli_close($link);
}
if($_POST["action"]=="logout"){
    session_name("AppCenterSession");
    session_start();
    $_SESSION = array();
    session_destroy();
    http_response_code(200);
    die();
}

if($_POST["action"]=="listprivateapps"){
    // Initialize the session
    session_name("AppCenterSession");
    session_start();
// Include config file
    require_once "../configuration.php";

// Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        http_response_code(403);
        die();
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        http_response_code(403);
        die();
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) > 0){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            http_response_code(200);
                            $resparr = array();
                            $sql = "SELECT * FROM apps WHERE creator='".$username."' and ispublic='PRIVATE'";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    while($row = mysqli_fetch_array($result)){
                                        $resparr[$row["appname"]] = $row["appid"];
                                    }
                                    mysqli_free_result($result);
                                    echo json_encode($resparr);
                                    die();
                                } else{
                                    echo "No apps found.";
                                }
                            } else{
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                            }
                            die();
                        } else{
                            // Display an error message if password is not valid
                            http_response_code(403);
                            die();
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    http_response_code(403);
                    die();;
                }
            } else{
                http_response_code(500);
                die();
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            http_response_code(503);
            die(mysqli_stmt_error($stmt)." ".mysqli_error($link));
        }
    }

    // Close connection
    mysqli_close($link);
}

if($_POST["action"]=="listpublicapps"){
include_once "../configuration.php";
http_response_code(200);
$resparr = array();
$sql = "SELECT * FROM apps WHERE creator='".$_POST["username"]."' and ispublic='PUBLIC'";
if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_array($result)){
            $resparr[$row["appname"]] = $row["appid"];
        }
        mysqli_free_result($result);
        echo json_encode($resparr);
        die();
    } else{
        echo "No apps found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
die();
    mysqli_close($link);
}
if($_POST["action"]=="verifysecret"){
    include_once "../configuration.php";
    $resparr = array();
    $sql = "SELECT * FROM apps WHERE creator='".$_POST["username"]."' and appid='".$_POST["appid"]."' and appsecret='".$_POST["appsecret"]."'";
    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) == 1){
            while($row = mysqli_fetch_array($result)){
                http_response_code(200);
            }
            mysqli_free_result($result);
            die();
        } else{
            http_response_code(403);
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    die();
    mysqli_close($link);
}
if($_POST["action"]=="getsecret"){
    // Initialize the session
    session_name("AppCenterSession");
    session_start();
// Include config file
    require_once "../configuration.php";

// Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = "";
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        http_response_code(403);
        die();
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        http_response_code(403);
        die();
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) > 0){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            http_response_code(200);
                            $resparr = array();
                            $sql = "SELECT * FROM apps WHERE creator='".$username."' and appid='".$_POST["appid"]."'";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) == 1){
                                    while($row = mysqli_fetch_array($result)){
                                        $resparr["appname"] = $row["appname"];
                                        $resparr["appid"] = $row["appid"];
                                        $resparr["appsecret"] = $row["appsecret"];
                                    }
                                    mysqli_free_result($result);
                                    echo json_encode($resparr);
                                    die();
                                } else{
                                    echo "No apps found.";
                                }
                            } else{
                                echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                            }
                            die();
                        } else{
                            // Display an error message if password is not valid
                            http_response_code(403);
                            die();
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    http_response_code(403);
                    die();;
                }
            } else{
                http_response_code(500);
                die();
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            http_response_code(503);
            die(mysqli_stmt_error($stmt)." ".mysqli_error($link));
        }
    }

    // Close connection
    mysqli_close($link);
}
http_response_code(404);
die();
