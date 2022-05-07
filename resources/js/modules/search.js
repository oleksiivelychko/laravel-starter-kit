import axios from "axios";

const searchElement = document.getElementById('search');
searchElement.addEventListener('input', buildResults);

function buildResults(e) {
    if (e.target.value.length < 4) return;

    axios.post(`/${document.documentElement.lang}/ajax/search`, {
        querySearch: e.target.value
    })
        .then(function (response) {
            const searchResults = document.getElementById('searchResults');
            if (searchResults.classList.contains('d-none')) {
                searchResults.classList.remove('d-none');
            }

            if (Object.keys(response.data).length > 0) {
                let html = '';
                for (let text in response.data) {
                    if (response.data.hasOwnProperty(text)) {
                        html += `<a href="${response.data[text]}">${text}</a><br>`;
                    }
                }

                searchResults.innerHTML = html;
            } else {
                searchResults.innerHTML = "<b>Nothing not found :(</b>";
            }
        })
        .catch(function (error) {
            console.log(error);
        })
}

searchElement.addEventListener("search", function(event) {
    const searchResults = document.getElementById('searchResults');
    if (!searchResults.classList.contains('d-none')) {
        searchResults.classList.add('d-none');
    }
});
