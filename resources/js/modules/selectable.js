import SlimSelect from 'slim-select'

let multiSelects = document.querySelectorAll('.multiselect');
for (let i = 0; i < multiSelects.length; i++) {
    new SlimSelect({
        select: multiSelects[i],
    });
}
