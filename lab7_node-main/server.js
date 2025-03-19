// load http module
var http = require('http');
var fs = require('fs');

var port = 1337;

// Create a function to serve static files
function serveStaticFile(filePath, response, contentType, statusCode = 200) {
    // Try to read the file at the given path
    fs.readFile(filePath, (error, data) => {
        if (error) {
            // If error, tell browser there was an internal error
            response.writeHead(500, { 'Content-Type': 'text/plain' });
            response.end('500 - Internal Server Error');
        } else {
            // If successful, provide response code, content type and data
            response.writeHead(statusCode, { 'Content-Type': contentType });
            response.end(data);
        }
    });
}

// Create HTTP server
var server = http.createServer(function (request, response) {
    // Normalize URL: remove querystring, trailing slash, and convert to lowercase
    var path = request.url.replace(/\/?(?:\?.*)?$/, '').toLowerCase();
    
    // Route handling based on the path
    switch(path) {
        case '':
        case '/':
            serveStaticFile('./public/index.html', response, 'text/html');
            break;
        case '/posts':
            serveStaticFile('./public/posts.html', response, 'text/html');
            break;
        case '/contact':
            serveStaticFile('./public/contact.html', response, 'text/html');
            break;
        case '/under-construction':
            serveStaticFile('./public/under-construction.html', response, 'text/html');
            break;
        // CSS files
        case '/css/style.css':
            serveStaticFile('./public/css/style.css', response, 'text/css');
            break;
        // Image files - handle common image formats
        case '/images/logo.png':
            serveStaticFile('./public/images/logo.png', response, 'image/png');
            break;
        case '/images/x.png':
            serveStaticFile('./public/images/x.png', response, 'image/jpeg');
            break;
        case '/images/merch.png':
            serveStaticFile('./public/images/merch.png', response, 'image/jpeg');
            break;
        case '/images/construction.png':
            serveStaticFile('./public/images/construction.png', response, 'image/jpeg');
            break;
        case '/images/computer-typing.jpeg':
            serveStaticFile('./public/images/computer-typing.jpeg', response, 'image/jpeg');
            break;
        case '/images/blogging.png':
            serveStaticFile('./public/images/blogging.png', response, 'image/jpeg');
            break;
        case '/images/404top_w.jpg':
            serveStaticFile('./public/images/404top_w.jpg', response, 'image/jpeg');
            break;
        case '/images/404bottom.gif':
            serveStaticFile('./public/images/404bottom.gif', response, 'image/gif');
            break
        case '/images/404mid.gif':
            serveStaticFile('./public/images/404mid.gif', response, 'image/gif');
            break;
        
        // Add more image cases as needed
        default:
            serveStaticFile('./public/404.html', response, 'text/html', 404);
            break;
    }
});

// Start the server
server.listen(port, function() {
    console.log(`Server running at http://localhost:${port}`);
});



