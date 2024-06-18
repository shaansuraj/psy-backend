<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <style>
        .img-thumbs {
            background: #eee;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            margin: 1.5rem 0;
            padding: 0.75rem;
        }

        .img-thumbs-hidden {
            display: none;
        }

        .wrapper-thumb {
            position: relative;
            display: inline-block;
            margin: 1rem 0;
            justify-content: space-around;
        }

        .img-preview-thumb {
            background: #fff;
            border: 1px solid none;
            border-radius: 0.25rem;
            box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
            margin-right: 1rem;
            max-width: 140px;
            padding: 0.25rem;
        }

        .remove-btn {
            position: absolute;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: .7rem;
            top: -5px;
            right: 10px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
        }

        .remove-btn:hover {
            box-shadow: 0px 0px 3px grey;
            transition: all .3s ease-in-out;
        }
    </style>
    <div class="container my-5">
        <h3 class="text-center">Multiple Upload Images and Remove Button </h3>
        <div class="row">
            <div class="col">
                <form action="{{route('upload')}}" method="post" enctype="multipart/form-data" id="form-upload">
                    @csrf
                    <div class="form-group mt-5">
                        <label for="">Choose Images</label>
                        <input type="file" class="form-control" name="images[]" multiple id="upload-img" />
                    </div>
                    <input type="text" name="content" id="">
                    <input type="text" name="user_id" id="">
                    <div class="img-thumbs img-thumbs-hidden" id="img-preview"></div>
                    <button type="submit" class="btn btn-dark">Upload</button>
                </form>
            </div>
        </div>

    </div>
    <script>
        var imgUpload = document.getElementById('upload-img'),
            imgPreview = document.getElementById('img-preview'),
            imgUploadForm = document.getElementById('form-upload'),
            totalFiles, previewTitle, previewTitleText, img;

        imgUpload.addEventListener('change', previewImgs, true);

        function previewImgs(event) {
            totalFiles = imgUpload.files.length;

            if (!!totalFiles) {
                imgPreview.classList.remove('img-thumbs-hidden');
            }

            for (var i = 0; i < totalFiles; i++) {
                wrapper = document.createElement('div');
                wrapper.classList.add('wrapper-thumb');
                removeBtn = document.createElement("span");
                nodeRemove = document.createTextNode('x');
                removeBtn.classList.add('remove-btn');
                removeBtn.appendChild(nodeRemove);
                img = document.createElement('img');
                img.src = URL.createObjectURL(event.target.files[i]);
                img.classList.add('img-preview-thumb');
                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                imgPreview.appendChild(wrapper);

                $('.remove-btn').click(function() {
                    $(this).parent('.wrapper-thumb').remove();
                });

            }


        }
    </script>
</body>

</html>
