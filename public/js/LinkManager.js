class LinkManager {
    constructor(quill, options = {}) {
        this.quill = quill;
        this.linkDialog = document.getElementById('link-dialog');
        this.linkUrlInput = document.getElementById('link-url');
        this.linkTextInput = document.getElementById('link-text');
        this.linkTargetInput = document.getElementById('link-target');
        this.applyLinkButton = document.getElementById('apply-link');
        this.removeLinkButton = document.getElementById('remove-link');
        this.currentRange = null;
        this.currentLink = null;
        this.defaultTarget = options.defaultTarget || '_self';

        this.quill.root.addEventListener('click', this.handleClick.bind(this));
        this.applyLinkButton.addEventListener('click', this.applyLink.bind(this));
        this.removeLinkButton.addEventListener('click', this.removeLink.bind(this));
    }

    handleClick(event) {
        if (event.target.tagName === 'A') {
            this.currentRange = this.quill.getSelection();
            this.currentLink = event.target;
            this.selectLink(this.currentLink);
            this.showLinkDialog(this.currentLink.href, event.target.innerText, this.currentLink.target === '_blank', event.target);
        } else {
            this.hideLinkDialog();
        }
    }

    selectLink(link) {
        const blot = Quill.find(link);
        if (blot) {
            const index = blot.offset(this.quill.scroll);
            this.quill.setSelection(index, blot.length(), Quill.sources.USER);
        }
    }

    showLinkDialog(url = '', text = '', isBlank = false, link) {
        this.linkUrlInput.value = url;
        this.linkTextInput.value = text;
        this.linkTargetInput.checked = isBlank;
        this.linkDialog.style.display = 'block';

        this.currentRange = this.quill.getSelection();
        if (this.currentRange) {
            const linkRect = link.getBoundingClientRect();
            const linkTop = window.scrollY + linkRect.top;
            const linkLeft = window.scrollX + linkRect.left;
            const linkWidth = linkRect.width;
            const linkHeight = linkRect.height;

            const dialogWidth = this.linkDialog.offsetWidth;
            const dialogHeight = this.linkDialog.offsetHeight;

            let dialogTop = linkTop + linkHeight / 2 - dialogHeight / 2;
            let dialogLeft = linkLeft + linkWidth + 10;

            if (dialogLeft + dialogWidth > window.innerWidth) {
                dialogLeft = linkLeft - dialogWidth - 10;
            }

            this.linkDialog.style.top = `${dialogTop}px`;
            this.linkDialog.style.left = `${dialogLeft}px`;
        }
    }

    hideLinkDialog() {
        this.linkDialog.style.display = 'none';
        this.currentLink = null;
        this.currentRange = null;
    }

    applyLink() {
        let url = this.linkUrlInput.value;
        const text = this.linkTextInput.value;
        const target = this.linkTargetInput.checked ? '_blank' : this.defaultTarget;

        if (!/^https?:\/\//i.test(url)) {
            if (url.startsWith('/')) {
                url = window.location.origin + url;
            } else {
                url = 'https://' + url;
            }
        }

        if (this.currentLink) {
            this.currentLink.href = url;
            this.currentLink.innerText = text;
            this.currentLink.target = target;
        } else if (this.currentRange) {
            this.quill.clipboard.dangerouslyPasteHTML(this.currentRange.index, `<a href="${url}" target="${target}">${text}</a>`);
        }

        this.hideLinkDialog();
    }

    removeLink() {
        this.quill.removeFormat(this.currentRange.index, this.currentRange.length);
        this.hideLinkDialog();
    }
}
