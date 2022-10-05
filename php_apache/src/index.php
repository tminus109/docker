<?php

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER['REQUEST_URI']);
$path = $parsed['path'];

$routes = [
    "POST" => [
        "/termekek" => "createProductHandler",
        "/delete-product" => "deleteProductHandler",
        "/update-product" => "updateProductHandler"
    ],
    "GET" => [
        "/termekek" => "productListHandler",
        "/" => "homeHandler",
    ],
];

$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$safeHandlerFunction = function_exists($handlerFunction) ? $handlerFunction : "notFoundHandler";

$safeHandlerFunction();

function updateProductHandler()
{ 
    $updatedProductId = $_GET["id"] ?? "";
    $products = json_decode(file_get_contents("./products.json"), true); 

    $foundProductIndex = -1; 
    foreach ($products as $index => $product) {
        if ($product["id"] === $updatedProductId) {
            $foundProductIndex = $index;
            break;
        }
    }

    if ($foundProductIndex === -1) {
        header("Location: /termekek");
        return; 
    }

    $updatedProduct = [ 
        "id" => $updatedProductId,
        "name" => filter_var($_POST["name"], FILTER_SANITIZE_STRING),
        "price" => (int)$_POST["price"],
    ];

    $products[$foundProductIndex] = $updatedProduct;

    file_put_contents('./products.json', json_encode($products));
    header("Location: /termekek");
}

function deleteProductHandler()
{
    $deletedProductId = $_GET["id"] ?? "";
    $products = json_decode(file_get_contents("./products.json"), true); 

    $foundProductIndex = -1; 

    foreach ($products as $index => $product) {
        if ($product["id"] === $deletedProductId) {
            $foundProductIndex = $index;
            break;
        }
    }

    if ($foundProductIndex === -1) {
        header("Location: /termekek");
        return;
    }

    array_splice($products, $foundProductIndex, 1);

    file_put_contents("./products.json", json_encode($products));
    header("Location: /termekek");
}

function homeHandler()
{
    $homeTemplate = compileTemplate('./views/home.php');

    echo compileTemplate('./views/wrapper.php', [
        'innerTemplate' => $homeTemplate,
        'activeLink' => '/',
    ]);
}

function productListHandler()
{
    $contents = file_get_contents("./products.json");
    $products = json_decode($contents, true);
    $isSuccess = isset($_GET["siker"]);

    $productListTemplate = compileTemplate("./views/product-list.php", [
        "products" => $products,
        "isSuccess" => $isSuccess,
        "editedProductId" => $_GET["szerkesztes"] ?? ""
    ]);

    echo compileTemplate('./views/wrapper.php', [
        'innerTemplate' => $productListTemplate,
        'activeLink' => '/termekek',
    ]);
}

function createProductHandler()
{
    $newProduct = [ 
        "id" => uniqid(),
        "name" => filter_var($_POST["name"], FILTER_SANITIZE_STRING),
        "price" => (int)$_POST["price"],
    ];

    $content = file_get_contents("./products.json");
    $products = json_decode($content, true);

    array_push($products, $newProduct);

    $json = json_encode($products);
    file_put_contents('./products.json', $json);

    header("Location: /termekek?siker=1");
}

function notFoundHandler()
{
    echo "Oldal nem található";
}

function compileTemplate($filePath, $params = []): string
{
    ob_start();
    require $filePath;
    return ob_get_clean();
}
