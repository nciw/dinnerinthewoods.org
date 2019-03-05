
// When the DOM is loaded and ready add our handlers
addEventHandler(document, 'DOMContentLoaded', function () {

    addEventHandler(document.getElementById('credit'), 'click', function() {
       document.getElementById('checkDetails').style.display = 'none';
       document.getElementById('creditDetails').style.display = '';
       document.getElementById('cc-number').required = true;
       document.getElementById('cc-name').required = true;
       document.getElementById('cc-expiration').required = true;
       document.getElementById('cc-cvv').required = true;
    });
    addEventHandler(document.getElementById('check'), 'click', function() {
       document.getElementById('checkDetails').style.display = '';
       document.getElementById('creditDetails').style.display = 'none';
        document.getElementById('cc-number').required = false;
        document.getElementById('cc-name').required = false;
        document.getElementById('cc-expiration').required = false;
        document.getElementById('cc-cvv').required = false;
    });

});

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