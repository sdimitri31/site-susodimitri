const Link = Quill.import('formats/link');

class CustomLink extends Link {
    static create(value) {
        let node = super.create(value);
        if (typeof value === 'object') {
            node.setAttribute('href', value.href);
            node.setAttribute('target', value.target || '_self'); // default target
        } else {
            node.setAttribute('href', value);
            node.setAttribute('target', '_self');
        }
        return node;
    }

    static formats(domNode) {
        return {
            href: domNode.getAttribute('href'),
            target: domNode.getAttribute('target')
        };
    }

    format(name, value) {
        if (name === 'target') {
            if (value) {
                this.domNode.setAttribute(name, value);
            } else {
                this.domNode.removeAttribute(name);
            }
        } else {
            super.format(name, value);
        }
    }
}

Quill.register(CustomLink, true);
