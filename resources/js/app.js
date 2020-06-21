
import Quill from 'quill';
import 'quill/dist/quill.snow.css';
import imageUpload from 'quill-plugin-image-upload';

// register quill-plugin-image-upload
Quill.register('modules/imageUpload', imageUpload);

 const toolbarOptions = [
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],

        [{'header': 1}, {'header': 2}],
        [{'list': 'ordered'}, {'list': 'bullet'}],
        [{'script': 'sub'}, {'script': 'super'}],
        [{'indent': '-1'}, {'indent': '+1'}],
        [{'direction': 'rtl'}],

        [{'size': ['small', false, 'large', 'huge']}],
        [{'header': [1, 2, 3, 4, 5, 6, false]}],

        [{'color': []}, {'background': []}],
        [{'font': []}],
        [{'align': []}],
        ['link', 'image', 'video'],
        ['clean'],
    ];

    console.log('sss');