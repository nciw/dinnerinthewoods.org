/**
 3/15-3/31 : VIP rate for Current, Alumni  Families : $55/ticket , $350/table of 8
 4/1-4/30 :  Early Bird rate open to all : $65/ticket, $450/table

 5/1 - 5/30 : $75/ticket, $500/table

 2 bottles of wine at table, dinner, dessert, appetizers

 Ticket enhancers : $2/ticket and sold in packs of 10

 Drinks - 4 tickets
 Egg Pull - 2 tickets
 Wine pull - 3 tickets
 */

// Init variables for use below
ticketPrice = 55;
tablePrice = 350;
drinkPrice = 20;
childcarePrice = 25;
cabanaPrice = 250;


// When the DOM is loaded and ready add our handlers
addEventHandler(document, 'DOMContentLoaded', function () {
    // Fire update ticket prices on DOM Loaded
    updateTicketPricesOnDOM();

});

function updateTicketPricesOnDOM() {
    document.getElementById('eventTicketPrice').innerHTML = ticketPrice;
    document.getElementById('eventTicketPrice2').innerHTML = ticketPrice;
    document.getElementById('ticketEnhancerPrice').innerHTML = drinkPrice;
    document.getElementById('ticketEnhancerPrice2').innerHTML = drinkPrice;
    document.getElementById('tablePrice').innerHTML = tablePrice;
    document.getElementById('tablePrice2').innerHTML = tablePrice;
    document.getElementById('childcarePrice').innerHTML = childcarePrice;
    document.getElementById('cabanaPrice').innerHTML = cabanaPrice;
}


/**
 * Add event handler to DOM event
 * @param element
 * @param eventType
 * @param handler
 */
function addEventHandler(element, eventType, handler) {
    if (element.addEventListener) {
        element.addEventListener(eventType, handler, false);
    } else if (element.attachEvent) {
        element.attachEvent('on' + eventType, handler);
    }
}