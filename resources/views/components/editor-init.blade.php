<script src="https://cdn.ckeditor.com/ckeditor5/29.0.0/classic/ckeditor.js"></script>
<script>
    document.querySelectorAll('.editor').forEach(editorElement => {
        ClassicEditor
            .create(editorElement, {
                extraPlugins: [CustomUploadAdapterPlugin],
                toolbar: ['heading', '|', 'bold', 'italic', '|', 'link', 'imageUpload', '|', 'undo',
                    'redo', '|', 'bulletedList', 'numberedList', '|',
                    'blockQuote',
                ],
            })
            .then(editor => {
                const initialValue = editorElement.value;
                editor.setData(initialValue);
                editor.ui.view.element.classList.add('editor-container');
                trackImageDeletion(editor);
            })
            .catch(error => {
                console.error(error);
            });
    });

    function CustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new UploadAdapter(loader);
        };
    }

    class UploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('file', file);

                    fetch('/image-upload', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.location) {
                                resolve({
                                    default: data.location
                                });
                            } else {
                                reject(data);
                            }
                        })
                        .catch(error => {
                            reject(error);
                        });
                }));
        }
    }

    function trackImageDeletion(editor) {
        let previousData = editor.getData();

        editor.model.document.on('change:data', () => {
            const newData = editor.getData();
            const deletedImages = getDeletedImages(previousData, newData);

            if (deletedImages.length > 0) {
                deletedImages.forEach(imageSrc => {
                    if (imageSrc) {
                        deleteImageFromServer(imageSrc);
                    }
                });
            }

            previousData = newData;
        });
    }

    function getDeletedImages(previousData, newData) {
        const extractImageSources = (data) => {
            const div = document.createElement('div');
            div.innerHTML = data;
            const images = div.querySelectorAll('img');
            const srcs = Array.from(images).map(img => img.src);
            return srcs;
        };

        const previousImages = extractImageSources(previousData);
        const newImages = extractImageSources(newData);

        return previousImages.filter(src => !newImages.includes(src) && src !== null && src !== '');
    }

    function deleteImageFromServer(imageSrc) {
        const baseUrl = '{{ URL::to('/') . Storage::url('') }}';
        const relativePath = imageSrc.replace(baseUrl, '');
        console.log(relativePath);
        fetch('/image-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({
                    imagePath: relativePath
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Image deleted:', data);
            })
            .catch(error => {
                console.error('Error deleting image:', error);
            });
    }
</script>
<style>
    .ck-editor .ck-toolbar {
        background: #1e1e1e;
    }

    .ck-editor .ck-toolbar button:hover {
        background: #303030 !important;
        cursor: pointer;
    }

    .ck.ck-button.ck-on,
    a.ck.ck-button.ck-on {
        background: #303030 !important;
    }

    .ck-editor .ck-toolbar button {
        color: #dedede;
        background: #1e1e1e;
    }

    .ck-editor .ck-content {
        color: #dedede;
        background-color: #191919 !important;
    }

    .ck-editor .ck-content {
        color: #dedede;
        background-color: #191919 !important;
    }

    .ck-editor .ck-content,
    .ck-toolbar {
        border: none !important;
    }

    .ck-editor .ck-content a {
        color: #ff3b3c;
        text-decoration-line: underline;
    }

    .ck-editor .ck-content h2 {
        font-size: 1.5rem;
    }

    .ck-editor .ck-content h3 {
        font-size: 1.25rem;
    }

    .ck-editor .ck-content h4 {
        font-size: 1.125rem;
    }

    ol li {
        list-style-type: decimal;
        margin-left: 20px;
    }

    ul li {
        list-style-type: disc;
        margin-left: 20px;
    }
</style>
