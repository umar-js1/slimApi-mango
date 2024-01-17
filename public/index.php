<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

//require __DIR__ . '/../vendor/autoload.php';
include  'config.php';
$app = AppFactory::create();



$app->get('/', function (Request $request, Response $response, $args) {
    $collection = $GLOBALS['collection'];

    $cursor = $collection->find();

// Convert the cursor to an array
$documents = iterator_to_array($cursor);
header('Content-Type: application/json');
$response->getBody()->write(json_encode($documents));
return $response
        ->withHeader('content-type', 'application/json');
});
//get by id
$app->get('/user/{id}', function(Request $request, Response $response, array $args) {
    $collection = $GLOBALS['collection'];
    $id=intval($args['id']);
    $user = $collection->findOne(['id' => $id]);
    $response->getBody()->write(json_encode($user));
    return $response
            ->withHeader('content-type', 'application/json');

});
//post a data 
$app->post('/user', function(Request $request, Response $response) {
    $collection = $GLOBALS['collection'];

    // Get JSON data from the request body
    $jsonBody = $request->getBody();
    $data = json_decode($jsonBody, true);

    // Validate and extract necessary fields (id, name, mobile)
    $id = isset($data['id']) ? intval($data['id']) : null;
    $name = isset($data['name']) ? $data['name'] : null;
    $mobile = isset($data['mobile']) ? $data['mobile'] : null;

    // Validate if necessary fields are present
    if ($id !== null && $name !== null && $mobile !== null) {
        // Prepare the document to be inserted
        $document = [
            'id' => $id,
            'name' => $name,
            'mobile' => $mobile,
        ];

        // Insert the document into the MongoDB collection
        $collection->insertOne($document);

        // Return a success response
        $response->getBody()->write(json_encode(['success' => true]));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        // Return an error response if necessary fields are not provided
        $response->getBody()->write(json_encode(['error' => 'Invalid input data']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});
// PUT
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
$app->put('/user/{id}', function(Request $request, Response $response, array $args) {
    $collection = $GLOBALS['collection'];
    $id = intval($args['id']);

    // Get JSON data from the request body
    $jsonBody = $request->getBody();
    $data = json_decode($jsonBody, true);

    // Validate and extract necessary fields (name, mobile)
    $name = isset($data['name']) ? $data['name'] : null;
    $mobile = isset($data['mobile']) ? $data['mobile'] : null;

    // Validate if necessary fields are present
    if ($name !== null && $mobile !== null) {
        // Update the document in the MongoDB collection
        $collection->updateOne(['id' => $id], ['$set' => ['name' => $name, 'mobile' => $mobile]]);

        // Return a success response
        $response->getBody()->write(json_encode(['success' => true]));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        // Return an error response if necessary fields are not provided
        $response->getBody()->write(json_encode(['error' => 'Invalid input data']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});
//DEL
$app->delete('/user/{id}', function(Request $request, Response $response, array $args) {
    $collection = $GLOBALS['collection'];
    $id = intval($args['id']);

    // Delete the document from the MongoDB collection
    $result = $collection->deleteOne(['id' => $id]);

    if ($result->getDeletedCount() > 0) {
        // Return a success response
        $response->getBody()->write(json_encode(['success' => true]));
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        // Return an error response if the document is not found
        $response->getBody()->write(json_encode(['error' => 'User not found']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }
});
$app->run();
