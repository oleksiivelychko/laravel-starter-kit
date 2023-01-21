import 'slim-select'
import axios from "axios";

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let liveSearch = document.querySelectorAll('.live-search');
for (let i = 0; i < liveSearch.length; i++) {
    let slimSelect = new SlimSelect({
        select: liveSearch[i],
        settings: {
            allowDeselect: true,
            placeholderText: 'Select value',
            searchingText: 'Searching...',
            searchPlaceholder: 'Searching...',
            searchText: 'No results 😢',
        },
        events: {
            search: function (searchValue, currentData) {
                return new Promise((resolve, reject) => {
                    if (searchValue.length < 3) {
                        return reject('Search value must be at least 3 characters.')
                    }

                    axios.post(`/${document.documentElement.lang}/ajax/live-search-${liveSearch[i].dataset.entityName}`, {
                        search: searchValue
                    })
                        .then(function (json) {
                            // take the data and create an array of options excluding any that are already selected in currentData
                            const options = json.data
                                .filter((user) => {
                                    return !currentData.some((optionData) => {
                                        return optionData.value === `${user.value}`
                                    })
                                })
                                .map((user) => {
                                    return {
                                        text: `${user.text}`,
                                        value: `${user.value}`,
                                    }
                                })

                            resolve(options)
                        })
                })
            },
            error: function (error) {
                console.log(error)
            }
        }
    });

    if (liveSearch[i].dataset.entityValue && liveSearch[i].dataset.entityText) {
        let data = [(
            {
                text: liveSearch[i].dataset.entityText,
                value: liveSearch[i].dataset.entityValue
            }
        )];
        slimSelect.setData(data);
    }
}
