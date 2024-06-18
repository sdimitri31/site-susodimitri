document.addEventListener('DOMContentLoaded', (event) => {
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{ 'font': [] }],
                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'script': 'sub' }, { 'script': 'super' }],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'indent': '-1' }, { 'indent': '+1' }],
                    [{ 'direction': 'rtl' }],
                    [{ 'align': [] }],
                    ['link', 'image', 'video'],
                    ['clean']
                ],
                handlers: {
                    'link': function () {
                        linkManager.showLinkDialog();
                    }
                }
            }
        }
    });

    const imageManager = new ImageManager(quill, { destinationFolder: 'temp/' });

    const linkManager = new LinkManager(quill, { defaultTarget: '_self' });
    
    // Update form fields on Submit
    document.querySelector('form').onsubmit = function () {
        document.querySelector('#content').value = quill.root.innerHTML;
        imageManager.populateNewImagesList();
    };
});
