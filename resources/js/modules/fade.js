export function fadeOut(el) {
    el.style.opacity = 1;
    (function fade() {
        once( 4, function () {
            if ((el.style.opacity -= .1) < 0) {
                el.style.display = 'none';
            } else {
                requestAnimationFrame(fade);
            }
        });

    })();
}

export function fadeIn(el, display) {
    el.style.opacity = 0;
    el.style.display = display || 'block';
    (function fade() {
        let val = parseFloat(el.style.opacity);
        if (!((val += .1) > 1)) {
            once( 4, function () {
                el.style.opacity = val;
                requestAnimationFrame(fade);
            });
        }
    })();
}

/**
 * Call once after timeout
 * 1000 ms = 1 second.
 *
 * @param  {Number}   ms  Number of milliseconds to wait
 * @param  {Function} callback Callback function
 */
function once (ms, callback) {
    let counter = 0;
    let time = window.setInterval( function () {
        counter++;
        if ( counter >= ms ) {
            callback();
            window.clearInterval( time );
        }
    }, 50 );
}
