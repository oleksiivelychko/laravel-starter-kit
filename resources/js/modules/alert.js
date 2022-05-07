import Alert from 'bootstrap/js/dist/alert';
import {fadeOut} from "./fade";

const myAlert = document.getElementById('alert')
if (typeof(myAlert) != 'undefined' && myAlert != null) {
    fadeOut(myAlert)
}
