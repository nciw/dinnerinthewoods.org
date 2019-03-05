
// When the DOM is loaded and ready add our handlers
addEventHandler(document, 'DOMContentLoaded', function () {

    addEventHandler(document.getElementById('credit'), 'click', function() {
       document.getElementById('checkDetails').style.display = 'none';
       document.getElementById('creditDetails').style.display = '';
    });
    addEventHandler(document.getElementById('check'), 'click', function() {
       document.getElementById('checkDetails').style.display = '';
       document.getElementById('creditDetails').style.display = 'none';
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