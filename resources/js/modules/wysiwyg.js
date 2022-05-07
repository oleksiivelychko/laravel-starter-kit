import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

let allEditors = document.querySelectorAll('.wysiwyg');
for (let i = 0; i < allEditors.length; ++i) {
    ClassicEditor
        .create(allEditors[i], {
            language: document.documentElement.lang,
            toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                ]
            }
        })
        .catch( error => {
            console.log( error );
        } );
}
