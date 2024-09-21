<!DOCTYPE html>
<html>
<head>
    <title>Upload Images</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .upload-form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        label {
            font-size: 16px;
            color: #333;
            margin-top: 10px;
            display: block;
        }
        input[type="text"], textarea {
            width: 96%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        textarea {
            resize: vertical;
        }
        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .drop-zone {
            border: 2px dashed #007bff;
            border-radius: 4px;
            padding: 30px;
            text-align: center;
            color: #007bff;
            cursor: pointer;
            margin-top: 20px;
        }
        .drop-zone.dragging {
            background-color: #e0e7ff;
        }
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        .preview-image {
            width: 100px;
            height: 100px;
            margin-right: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            position: relative;
            display: flex; 
            justify-content: center; 
            align-items: center;
        }
        .preview-image img {
            max-width: 100%; 
            max-height: 100%; 
            object-fit: cover; 
        }
        .remove-mark {
            position: absolute;
            top: 1px; 
            right: 2px; 
            color: red; 
            font-size: 18px; 
            cursor: pointer;
            transition: background 0.2s; 
        }
        .remove-mark:hover {
            background: #f8d7da; 
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #007bff;
        }
        .back-link:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Upload New Images</h1>
    <div class="upload-form-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('galleries.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>

            <div class="drop-zone" id="dropZone">
                Drag & drop images here or click to upload
            </div>
            <input type="file" name="images[]" id="images" multiple accept="image/*" style="display:none;" required>
            <div class="preview-container" id="previewContainer"></div>
            <button type="submit">Upload</button>
        </form>
        <a href="{{ route('galleries.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Back to Gallery</a>
    </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('images');
        const previewContainer = document.getElementById('previewContainer');

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragging');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('dragging');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragging');

            const files = e.dataTransfer.files;

            if (files.length) {
                fileInput.files = files;
                updatePreview(files);
            }
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                updatePreview(fileInput.files);
            }
        });

        function updatePreview(files) {
            previewContainer.innerHTML = ''; 

            for (const file of files) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const div = document.createElement('div');
                    div.classList.add('preview-image');
                    div.appendChild(img);

                    const removeMark = document.createElement('span');
                    removeMark.innerText = '✖';
                    removeMark.classList.add('remove-mark');
                    removeMark.onclick = () => {
                        div.remove(); 
                        removeFileFromInput(file); 
                    };
                    div.appendChild(removeMark); 

                    previewContainer.appendChild(div);
                }
                reader.readAsDataURL(file);
            }
        }

        const uploadForm = document.getElementById('uploadForm');
        uploadForm.addEventListener('submit', function (e) {
            if (!fileInput.files.length) {
                e.preventDefault();
                alert('Please select at least one image to upload.');
            }
        });
    </script>
</body>
</html>
