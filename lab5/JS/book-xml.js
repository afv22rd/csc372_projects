function selectImages(){
    // Select images from the document and change opacity to 0.5
    var images = document.getElementsByTagName("img");
    for(var i = 0; i < images.length; i++){
        images[i].style.opacity = 0.5;
    }
}

function loadXMLData(filePath, index) {
    // Create an XMLHttpRequest object
    var xhr = new XMLHttpRequest();

    // Select div with the id called "details" and overwrite its content to be empty
    var detailsDiv = document.getElementById("details");
    detailsDiv.innerHTML = "";

    // Prepare the request
    xhr.open("GET", filePath, true);

    // When the response has loaded
    xhr.onload = function() {
        // Check if the server status was okay
        if (xhr.status >= 200 && xhr.status < 300) {
            // Get XML from the server
            var xmlDoc = xhr.responseXML;

            // Find your XML book
            var book = xmlDoc.getElementsByTagName("book")[index];

            // Add your XML book to the DOM Tree of your web page using DOM manipulation methods
            if (book) {
                // Get the data from the XML
                var title = book.getElementsByTagName("title")[0].textContent;
                var author = book.getElementsByTagName("author")[0].textContent;
                var sold = book.getElementsByTagName("sold")[0].textContent;
                var description = book.getElementsByTagName("description")[0].textContent.trim();

                // Create HTML elements and add the data
                var titleElement = document.createElement("h3");
                titleElement.textContent = title;
                
                var authorElement = document.createElement("p");
                var authorStrong = document.createElement("strong");
                authorStrong.textContent = "Author: ";
                authorElement.appendChild(authorStrong);
                authorElement.appendChild(document.createTextNode(author));
                
                var soldElement = document.createElement("p");
                var soldStrong = document.createElement("strong");
                soldStrong.textContent = "Copies sold: ";
                soldElement.appendChild(soldStrong);
                soldElement.appendChild(document.createTextNode(sold));
                
                var descElement = document.createElement("p");
                descElement.textContent = description;
                
                // Append all elements to the details div
                detailsDiv.appendChild(titleElement);
                detailsDiv.appendChild(authorElement);
                detailsDiv.appendChild(soldElement);
                detailsDiv.appendChild(descElement);
            } else {
                detailsDiv.innerHTML = "Element not found.";
            }
        } else {
            detailsDiv.innerHTML = "Failed to load XML.";
        }
    };

    // Send the request
    xhr.send();
}

// Don Quixote
var donQuixote = document.getElementById("don-quixote-img");
donQuixote.addEventListener("click", function() {
    // Call your function that triggers your Ajax request to load the data about this book from the XML file you created in the previous part.
    loadXMLData("../data/book-data.xml", 0);

    // Call the function that changes the opacity of the images
    selectImages();

    // Change the opacity of the image to 1
    donQuixote.style.opacity = 1;
});

// A Tale of Two Cities
var twoCities = document.getElementById("two-cities-img");
twoCities.addEventListener("click", function() {
    // Call your function that triggers your Ajax request to load the data about this book from the XML file you created in the previous part.
    loadXMLData("../data/book-data.xml", 1);

    // Call the function that changes the opacity of the images
    selectImages();

    // Change the opacity of the image to 1
    twoCities.style.opacity = 1;
});

// The Lord of the Rings
var lordOfTheRings = document.getElementById("lotr-img");
lordOfTheRings.addEventListener("click", function() {
    // Call your function that triggers your Ajax request to load the data about this book from the XML file you created in the previous part.
    loadXMLData("../data/book-data.xml", 2);

    // Call the function that changes the opacity of the images
    selectImages();

    // Change the opacity of the image to 1
    lordOfTheRings.style.opacity = 1;
});