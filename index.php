<?php

// Modules
require __DIR__ . '/vendor/autoload.php';
require_once "./src/server/RepositoryManager.php";

// Constants
$TEMPLATE_DIR = __DIR__ . "/views";
$CACHE_DIR = __DIR__ . "/views_cache";
$PUBLIC_DIR = "/public/";

// Router
$router = new \Bramus\Router\Router();

// Twig
$loader = new \Twig\Loader\FilesystemLoader($TEMPLATE_DIR);
$twig = new \Twig\Environment($loader, [
    // "cache" => $CACHE_DIR
]);

// Twig - filters

// getAssets($file)
$twig->addFunction(
    new \Twig\TwigFunction('getAssets', function ($file_path = "") use ($PUBLIC_DIR) {
        return $PUBLIC_DIR . $file_path;
    })
);

// RepositoryManager
$repositoryManager = new App\RepositoryManager();


/**
 * ####
 * ####
 * #### ROUTES
 * ####
 * ####
 */

$router->get("/", function() use($twig){
    echo $twig->render("pages/lab.twig", []);
});
$repo = "https://github.com/KenyZ/webgl-slider-image.git";
$router->get("/test", function() use($repo, $repositoryManager){
    $pullingNewRepo = $repositoryManager->addRepository($repo);
    echo $pullingNewRepo;
});

$router->mount("/api", function() use ($router, $repositoryManager) {

    $router->before("GET|PUT|POST|PATCH|DELETE", "/.*", function(){

        // We will return JSON
        header('Content-Type: application/json');
    });

    $router->post("/repository", function() use($repositoryManager) {

        // Fetch body request
        $body = json_decode(file_get_contents("php://input"));
        $body_url = $body->url;

        if($body_url){
            
            $pulling_new_repo = $repositoryManager->addRepository($body_url);

            // pulliong new repo has succeed
            if($pulling_new_repo == true){

                // Status - CREATED
                http_response_code(201);
                echo json_encode("CREATED");
            } else {

                // something has failed
                http_response_code(400);
                echo json_encode([
                    "error" => [
                        "message" => "operation has failed"
                    ]
                ]);
            }

        } else {
            
            http_response_code(400);
            echo json_encode([
                "error" => [
                    "message" => "url body paremeter is missing"
                ]
            ]);
        }
    });

});


$router->run();