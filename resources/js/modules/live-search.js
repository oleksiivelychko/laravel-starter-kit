import SlimSelect from 'slim-select'
import axios from "axios";

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let liveSearch = document.querySelectorAll('.live-search');
for (let i = 0; i < liveSearch.length; i++) {
    let slimSelect = new SlimSelect({
        select: liveSearch[i],
        searchingText: 'Searching...',
        allowDeselect: true,
        placeholder: 'Searching...',
        ajax: function (search, callback) {
            // Check search value. If you don't like it then use callback(false) or callback('Message String')
            if (search.length < 3) {
                callback('Need 3 characters');
                return;
            }

            axios.post(`/${document.documentElement.lang}/ajax/live-search-${liveSearch[i].dataset.entityName}`, {
                search: search
            })
                .then(function (json) {
                    let data = []
                    for (let i = 0; i < json.data.length; i++) {
                        data.push({
                            value: json.data[i].value,
                            text: json.data[i].text
                        })
                    }

                    callback(data)
                })
                .catch(function (error) {
                    callback(false)
                })
        }
    });

    if (liveSearch[i].dataset.entityValue && liveSearch[i].dataset.entityText) {
        let data = [({
            value: liveSearch[i].dataset.entityValue,
            text: liveSearch[i].dataset.entityText
        })];
        slimSelect.setData(data);
        slimSelect.set([liveSearch[i].dataset.entityValue])
    }
}
