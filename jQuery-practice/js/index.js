// update h1 text
$("h1").text("My jQuery Example")

$("h1").after("<p>jQuery is a JavaScript Library. jQuery greatly simplifies JavaScript programming. jQuery is easy to learn.</p>");

// Insert unordered list after paragraph and append items

$("p").after("<ul></ul>");
$("ul").append("<li>HTML/DOM manipulation</li>", "<li>CSS manipulation</li>",
    "<li>HTML event methods</li>", "<li>Effects and animations</li>");

$("ul").before("<p> The jQuery library contains the following features: </p>");

// Update css
$("body").css("background-color", "lightgrey");
$("h1").css({
    "color": "red",
    "text-align": "center"
});

$("li").css({
    "color": "blue",
    "border": "1px solid red",
    "background-color": "lightblue"
})