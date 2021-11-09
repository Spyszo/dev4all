<head>
    <link rel="stylesheet" href="../css/addPost.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
</head>

<form id="add-post-form" method="POST" enctype="multipart/form-data">
    <div class="post-thumbnail">
        <div class="post-image">
            <input type="file" accept="image/x-png,image/jpeg" name="post-image" id="post-image" required/>
            <span class='material-icons'>image<span>
        </div>
        <div class="post-basicInfo">
            <label class="post-title">
                Tytuł
                <input type="text" name="post-title" required />
            </label>
            <label class="post-description">
                Krótki opis
                <textarea name="post-description" required ></textarea>
            </label>
        </div>
    </div>
    <label class="post-body" for="post-body">
        <input type="hidden" name="post-body">
        <div id="editor">
            <p>Hello World!</p>
            <p>Some initial <strong>bold</strong> text</p>
            <p><br></p>
        </div>
    </label>
    <input class="post-submit" type="submit" value="Wyślij post">
</form>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
    var toolbarOptions = ['bold', 'italic', 'underline', 'strike'];
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }, { size: [ 'small', false, 'large', 'huge' ]}, { 'font': [] }],
            [{ 'align': [] }, { 'list': 'ordered'}, { 'list': 'bullet' }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],  
            ['image', 'code-block', 'link']

        ]
    },
    });

    let thumbnail = {};
    let compressedImage = {};

    $('#add-post-form').on('submit', (e) => {
        e.preventDefault()

        const formData = new FormData($('#add-post-form')[0]);
        formData.append('post-thumbnail', thumbnail, thumbnail.name)
        formData.set('post-image', compressedImage, compressedImage.name)
        formData.set('post-body', quill.root.innerHTML)

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: "../helpers/addPost.php",
            processData: false,
            contentType: false,
            data: formData,
            success: (res) => {
                if (res == 'success') {
                    Snackbar.show({text: "Dodano nowy post.", customClass: 'snackbar'})
                    document.location = _IPADDR;
                } else {
                    Snackbar.show({text: res, customClass: 'snackbar'})
                }
            }
        })
    })

    $('#post-image').change(function() {
        const file = this.files[0];
        new Compressor(file, {
            maxWidth: 880,
            quality: 0.3,
            success(result){
                let reader = new FileReader();
                reader.onloadend = () => {
                    $('.post-image span').remove()
                    $('#post-image').css('background-image', 'url("' + reader.result + '")');
                    thumbnail = result
                }
                if (file) {
                    reader.readAsDataURL(result);
                }
            }
        })

        new Compressor(file, {
            maxWidth: 1420,
            quality: 0.7,
            success(result){
                let reader = new FileReader();
                reader.onloadend = () => {
                    compressedImage = result
                }
                if (file) {
                    reader.readAsDataURL(result);
                }
            }
        })
    })

    $('.post-image span').click(() => {
        $('#post-image').trigger('click');
    })

</script>