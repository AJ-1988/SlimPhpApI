<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

// Enable Cores
$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


// Get All Users
$app->get('/api/users', function (Request $request) {
  try {
    $db = new db();
    $db = $db->connect();

    $query = $db->query("SELECT * FROM users");
    $users = $query->fetchAll(PDO::FETCH_OBJ);

    echo json_encode($users);
    $db = null;
  } catch (PDOException $e) {
    echo 'Opps Something went wrong' . $e->getMessage();
  }
});

// Get Single User
$app->get('/api/user/{id}', function (Request $request) {
  try {
    $db = new db();
    $db = $db->connect();

    $id = $request->getAttribute('id');
    $query = $db->query("SELECT * FROM users WHERE id = $id");
    $user = $query->Fetch(PDO::FETCH_OBJ);

    echo json_encode($user);
    $db = null;
  } catch (PDOException $e) {
    echo 'Opps something went wrong' . $e->getMessage();
  }
});

// Add User
$app->post('/api/user/add', function (Request $request) {
try {
  $db = new db();
  $db = $db->connect();

  $name = $request->getParam('name');
  $number = $request->getParam('number');
  $location = $request->getParam('location');

  $stmt = $db->prepare("INSERT INTO users (name, number, location) VALUES (:name, :number, :location)");

  $stmt->bindParam(':name', $name);
  $stmt->bindParam(':number', $number);
  $stmt->bindParam(':location', $location);

  $stmt->execute();
} catch (PDOException $e) {
  echo 'Opps something went wrong' . $e->getMessage();
}
});

// Update Users
$app->put('/api/user/update/{id}', function (Request $request) {
  try {
    $db = new db();
    $db = $db->connect();

    $id = $request->getAttribute('id');
    $name = $request->getParam('name');
    $number = $request->getParam('number');
    $location = $request->getParam('location');

    $stmt = $db->prepare("UPDATE users SET name = :name, number = :number, location = :location WHERE id = $id");

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':number', $number);
    $stmt->bindParam(':location', $location);

    $stmt->execute();
  } catch (PDOException $e) {

  }
});

// Delete Users
$app->delete('/api/user/delete/{id}', function (Request $request) {
  try {
    $db = new db();
    $db = $db->connect();

    $id = $request->getAttribute('id');
    $stmt = $db->prepare("DELETE FROM users WHERE id = $id");

    $stmt->execute();
  } catch (PDOException $e) {
    echo 'Opps something went wrong' . $e->getMessage();
  }
});


$app->run();
