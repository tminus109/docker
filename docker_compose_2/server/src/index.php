<?php

require '../vendor/autoload.php';
require './Mailer.php';

$method = getenv("REQUEST_METHOD");
$path = parse_url(getenv('REQUEST_URI'), PHP_URL_PATH);

function home()
{
    echo render('home.phtml', [
        'isSuccess' => isset($_GET['kuldesSikeres'])
    ]);
}

function render($path, $params = [])
{
    ob_start();
    require __DIR__ . '/views/' . $path;
    return ob_get_clean();
}

function notFoundhandler()
{
    header('Location: /');
}
 
function submitMessageHandler()
{
    $pdo = getConnection();
    $statement = $pdo->prepare("INSERT INTO `messages` 
    (`email`, `subject`, `body`, `status`, `numberOfAttempts`, `createdAt`) 
    VALUES 
    (?, ?, ?, ?, ?, ?);");

    $body = render("email-template.phtml", [
        'name' =>  $_POST['name'] ?? '',
        'email' =>  $_POST['email'] ?? '',
        'content' =>  $_POST['content'],
    ]);

    $statement->execute([
        getenv('RECIPIENT_EMAIL'),
        "Új üzenet érkezett",
        $body,
        'notSent',
        0,
        time()
    ]);

    header('Location: /?kuldesSikeres=1#contactForm');   
}

function getConnection()
{
    return new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASSWORD')
    );
}

function sendMailsHandler()
{
    if(($_POST['key'] ?? '') !== getenv('WORKER_KEY')) {
        http_response_code(401);
        echo json_encode(['error' => 'unauthorized']);
        return;
    }
    
    $pdo = getConnection();
    $statement = $pdo->prepare(
        "SELECT * FROM messages 
        WHERE 
        status = 'notSent' AND 
        numberOfAttempts < 10 
        ORDER BY createdAt ASC"
    );

    $statement->execute();
    $messages = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($messages as $message) {
        $pdo = getConnection();
        $statement = $pdo->prepare(
            "UPDATE `messages` SET 
                status = 'sending', 
                numberOfAttempts = ? 
            WHERE id = ?;"
        );

        $statement->execute([
            (int)$message['numberOfAttempts'] + 1,
            $message['id']
        ]);

        $isSent = sendMail(
            $message['email'],
            $message['subject'],
            $message['body']
        );

        if ($isSent) {
            $statement = $pdo->prepare(
                "UPDATE `messages` SET status = 'sent', sentAt = ? WHERE id = ?;"
            );
            $statement->execute([
                time(),
                $message['id'],
            ]);
        } else {
            $statement = $pdo->prepare("UPDATE `messages` SET status = 'notSent' WHERE id = ?;");
            $statement->execute([
                $message['id']
            ]);
        }
    }
}

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'home');
    $r->addRoute('POST', '/submit-message', 'submitMessageHandler');
    $r->addRoute('POST', '/send-mails', 'sendMailsHandler');
});

$routeInfo = $dispatcher->dispatch($method, $path);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        notFoundHandler();
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        notFoundHandler();
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $handler($vars);
        break;
}