class ImageManager {
    constructor(quill, options = {}) {
        this.quill = quill;
        this.options = options;
        this.uploadedImages = [];
        this.selectedImage = null;

        this.quill.root.addEventListener('click', this.handleClick.bind(this));

        this.initDialog();
        this.initImageHandler();
    }

    populateNewImagesList() {
        const dataJsonField = document.getElementById('dataJson');
        dataJsonField.value = JSON.stringify(this.uploadedImages);
    }

    handleClick(event) {
        if (event.target && event.target.tagName === 'IMG') {
            this.show(event.target);
        } else {
            this.hide();
        }
    }

    show(image) {
        this.selectedImage = image;

        const dialog = document.getElementById('image-dialog');
        dialog.style.display = 'block';

        const imageRect = image.getBoundingClientRect();
        const imageTop = window.scrollY + imageRect.top;
        const imageLeft = window.scrollX + imageRect.left;
        const imageWidth = imageRect.width;
        const imageHeight = imageRect.height;
        
        const dialogWidth = dialog.offsetWidth;
        const dialogHeight = dialog.offsetHeight;
        
        let dialogTop = imageTop + imageHeight / 2 - dialogHeight / 2;
        let dialogLeft = imageLeft + imageWidth + 10; 
        
        if (dialogLeft + dialogWidth > window.innerWidth) {
            dialogLeft = imageLeft - dialogWidth - 10; 
        }
        
        dialog.style.top = `${dialogTop}px`;
        dialog.style.left = `${dialogLeft}px`;

        document.getElementById('image-alt').value = image.alt;
        document.getElementById('image-width').value = image.width;
        document.getElementById('image-height').value = image.height;
    }

    hide() {
        const dialog = document.getElementById('image-dialog');
        dialog.style.display = 'none';
    }

    initDialog() {
        const widthInput = document.getElementById('image-width');
        const heightInput = document.getElementById('image-height');
        const maintainAspectRatioCheckbox = document.getElementById('maintain-aspect-ratio');

        widthInput.addEventListener('input', () => {
            if (maintainAspectRatioCheckbox.checked && this.selectedImage) {
                const aspectRatio = this.selectedImage.naturalWidth / this.selectedImage.naturalHeight;
                heightInput.value = Math.round(widthInput.value / aspectRatio);
            }
        });

        heightInput.addEventListener('input', () => {
            if (maintainAspectRatioCheckbox.checked && this.selectedImage) {
                const aspectRatio = this.selectedImage.naturalWidth / this.selectedImage.naturalHeight;
                widthInput.value = Math.round(heightInput.value * aspectRatio);
            }
        });

        document.getElementById('apply-image-properties').onclick = () => {
            if (!this.selectedImage) return;

            const newAlt = document.getElementById('image-alt').value;
            const newWidth = document.getElementById('image-width').value;
            const newHeight = document.getElementById('image-height').value;

            this.selectedImage.alt = newAlt;
            this.selectedImage.width = newWidth;
            this.selectedImage.height = newHeight;

            this.hide();
        };
    }

    initImageHandler() {
        const toolbar = this.quill.getModule('toolbar');
        toolbar.addHandler('image', this.imageHandler.bind(this));
    }

    imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('image', file);

                const destinationFolder = this.options.destinationFolder || 'temp/';
                formData.append('destinationFolder', destinationFolder);

                fetch('/upload_image', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            const range = this.quill.getSelection();
                            this.quill.insertEmbed(range.index, 'image', result.file);
                            this.uploadedImages.push({ name: result.filename });
                        } else {
                            console.error('Error uploading image: ', result.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        };
    }
}