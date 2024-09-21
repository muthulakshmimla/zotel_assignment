<!DOCTYPE html>
<html>
<head>
    <title>Edit Gallery</title>
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
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Edit Gallery</h1>
    <div class="form-container">

        @if (session('success'))
            <div class="alert alert-success">
                <strong>{{ session('success') }}</strong> 
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

        <div class="image-container">
            <img src="{{ asset('storage/' . $gallery->image_url) }}" alt="{{ $gallery->title }}">
        </div>
        <form action="{{ route('galleries.update', $gallery->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" value="{{ $gallery->title }}"><br>
            
            <label for="tags">Tags:</label>
            <input type="text" name="tags" id="tags" value="{{ $gallery->tags }}"><br>
            
            <button type="submit">Update</button>
        </form>
    </div>
    <a href="{{ route('galleries.index') }}" class="back-link">Back to Gallery</a>
</body>
</html>
