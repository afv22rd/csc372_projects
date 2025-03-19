// Adding events with jQuery

// Highlight a list item with different color when mouse hovers

$("li").on({ // on is used to add event handlers
    mouseenter: function () {
        $(this).css("color", "red");
    },
    mouseleave: function () {
        $(this).css("color", "blue");
    }
})

// Adding effects

// Hide all list items after appending them to the <ul> element.

$("li").hide(1000);

// Select last paragraph and append a new button that says show jquery features

$("p:last").append("<button id='btn'>Show jQuery Features</button>");

// When this button is clicked, the button should fade out of appearance. Once the button has completely faded out, then all of the list items should slide down and appear.  
$("#btn").on({
    click: function () {
        $(this).fadeOut(1000, function () {
            $("li").slideDown(1000);
        });
    }
})


