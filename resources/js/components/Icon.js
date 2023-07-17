import { h } from 'vue'

class Icon {
    constructor(name) {
        let div = document.createElement('div');
        div.innerHTML = require('../../icons/' + name + '.svg'); // change to wherever your svg files are

        let fragment = document.createDocumentFragment();
        fragment.appendChild(div);

        this.svg = fragment.querySelector('svg');
    }

    class(classes) {
        if (classes) {
            this.svg.classList = '';

            classes.split(' ').forEach(className => {
                this.svg.classList.add(className);
            });
        }

        return this;
    }

    width(width) {
        if (width) {
            this.svg.setAttribute('width', width);
        }

        return this;
    }

    height(height) {
        if (height) {
            this.svg.setAttribute('height', height);
        }

        return this;
    }

    toString() {
        return this.svg.outerHTML;
    }
}

export default {
    props: {
        name: {
            type: String,
            required: true
        },
        class: {
            type: String,
            default: ''
        },
        width: {
            type: String,
        },
        height: {
            type: String,
        },
    },
    setup(props) {
        return () => h('div', {
            classList: 'flex items-center',
            innerHTML: new Icon(props.name)
                .class(props.class)
                .width(props.width)
                .height(props.height)
        })
    }
}
