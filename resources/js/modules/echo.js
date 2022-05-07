/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo'
window.Pusher = require('pusher-js');

const echoListener = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

let $el = document.getElementById('userId');
if ($el) {
    let userId = $el.value;
    if (userId) {
        echoListener.private(`import.${userId}`)
            .listen('ReloadImportPageEvent', e => {
                location.reload();
            });
    }
}
